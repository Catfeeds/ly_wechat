<?php
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
class Operate extends Bn_Basic {
	protected $ModuleName;
	protected $Nav2Name;
	protected $Nav2Url;
	protected $Result;
	protected $Number;
	protected $ParentId;
	protected $ModuleId;
	protected $Text;
	protected $Item;
	protected $Info;
	protected $Receive;
	protected $AffixFileSize;
	public function __construct() {
		$this->Result = TRUE;
	}
	private function ReceiveSaveAffix($dir, $n_uid, $n_username, $n_receiveid) {
		//保存邮件附件
		$dh = opendir ( $dir );
		while ( $file = readdir ( $dh ) ) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (! is_dir ( $fullpath )) {
					$o_path = md5 ( $file . $n_uid . rand ( 0, 9999 ) . $this->GetTimeCut () );
					mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $n_username ) . '/affix/' . $o_path, 0700 );
					$o_to = RELATIVITY_PATH . 'userdata/' . md5 ( $n_username ) . '/affix/' . $o_path . '/' . $file;
					rename ( $fullpath, $o_to );
					$o_affix = new Email_Receive_Affix ();
					$o_affix->setPath ( 'userdata/' . md5 ( $n_username ) . '/affix/' . $o_path );
					$o_affix->setFilename ( iconv ( 'gb2312', 'UTF-8', $file ) );
					$o_affix->setFilesize ( ceil ( filesize ( $o_to ) / 1024 ) );
					$o_affix->setReceiveId ( $n_receiveid );
					$o_affix->Save ();
					$this->AffixFileSize = $this->AffixFileSize + ceil ( filesize ( $o_to ) / 1024 );
				} else {
					$this->ReceiveSaveAffix ( $fullpath, $n_uid, $n_username, $n_receiveid );
				}
			}
		}
		closedir ( $dh );
	}
	public function ReceiveEmail() {
		//检测有无重复邮件，如果重复，则不再保存
		if (! isset ( $_POST ['uid'] )) {
			return;
		}
		require_once RELATIVITY_PATH . 'sub/email/include/db_table.class.php';
		$o_save = new Email_Receive ();
		$o_save->PushWhere ( array ('&&', 'Date', '=', $_POST ['date'] ) );
		$o_save->PushWhere ( array ('&&', 'EmailFrom', '=', $_POST ['email_from'] ) );
		$o_save->PushWhere ( array ('&&', 'Uid', '=', $_POST ['uid'] ) );
		$n_count = $o_save->getAllCount ();
		if ($n_count == 0) {
			//开始保存邮件；
			$o_save = new Email_Receive ();
			$o_save->setUid ( $_POST ['uid'] );
			$o_save->setEmailFrom ( $_POST ['email_from'] );
			$o_save->setEmailTo ( $_POST ['email_to'] );
			$o_save->setDate ( $_POST ['date'] );
			$o_save->setReceiveDate ( $this->GetDateNow () );
			$s_size = ceil ( strlen ( $_POST ['content'] ) / 1024 ); //邮件大小
			$o_save->setSize ( $s_size );
			//$encode = mb_detect_encoding($_POST ['title'], array('ASCII','GB2312','GBK','UTF-8'));  
			if ($_POST ['title'] == '') {
				return;
			}
			$o_save->setTitle ( $_POST ['title'] );
			$o_save->setContent ( $_POST ['content'] );
			$o_save->Save ();
			//开始保存附件
			$this->AffixFileSize = 0;
			require_once RELATIVITY_PATH . 'include/bn_user.class.php';
			$o_user = new Single_User ( $_POST ['uid'] );
			$dir = RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/affix/mail_receive_temp/' . $_POST ['path'];
			$this->ReceiveSaveAffix ( $dir, $_POST ['uid'], $o_user->getUserName (), $o_save->getReceiveId () );
			$this->DeleteDir ( $dir ); //删除临时附件
			if ($this->AffixFileSize > 0) {
				$o_save->setHaveAffix ( 1 );
				$o_save->setSize ( $o_save->getSize () + $this->AffixFileSize );
				$o_save->Save ();
			}
			//发送消息弹出框提醒
			$this->AddSysmsg ( 1, 89, '未读邮件', '未读邮件', $_POST ['uid'] );
			//发送图标提醒
			$o_wait = new Email_Setup ( $_POST ['uid'] );
			$a = $o_wait->getWaitRead ();
			if ($o_wait->getWaitRead () == null) {
				$o_wait = new Email_Setup ();
				$o_wait->setUid ( $_POST ['uid'] );
				$o_wait->setWaitRead ( 1 );
			} else {
				$o_wait->setWaitRead ( $o_wait->getWaitRead () + 1 );
			}
			$o_wait->Save ();
		}
	}
	public function CheckUserOnline() {
		$o_login = new Base_User_Login ();
		$o_login->PushWhere ( array ('&&', 'Online', '=', 1 ) );
		$n_count = $o_login->getAllCount ();
		$n_timenow = $this->GetTimeCut ();
		for($i = 0; $i < $n_count; $i ++) {
			if (($n_timenow - $o_login->getLastTime ( $i )) > 180) {
				//当前时间-如果最后上线时间>3分钟，就认为是用户下线了。
				$o_login2 = new Base_User_Login ( $o_login->getUid ( $i ) );
				$o_login2->setOnline ( 0 );
				$o_login2->setSessionId ( '' );
				$o_login2->Save ();
			}
		}
	}
	public function CheckUserDiaryRem() {
		require_once RELATIVITY_PATH . 'sub/diary/include/db_table.class.php';
		$o_record = new Diary_Record ();
		$o_record->PushWhere ( array ('&&', 'Remind', '=', 1 ) );
		$o_record->PushWhere ( array ('&&', 'Date', '<=', $this->GetDateNow () ) );
		$n_count = $o_record->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$o_record2 = new Diary_Record ( $o_record->getRecordId ( $i ) );
			$o_record2->setRemind ( 0 );
			$o_record2->Save ();
			$this->SendRemind ( $o_record->getUid ( $i ), $o_record->getUid ( $i ), '《工作日程提醒》<br/>时间：' . $o_record->getDate ( $i ) . '<br/>内容：' . $o_record->getContent ( $i ) );
		}
	}
	public function DesktopSave($s_moduleid, $n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$s_moduleid = str_replace ( ",", "<1>", $s_moduleid );
		$o_desktop = new Base_User_Desktop ( $n_uid );
		if ($o_desktop->getModuleSort () == null) {
			$o_desktop = new Base_User_Desktop ();
			$o_desktop->setUid ( $n_uid );
			$o_desktop->setModuleSort ( $s_moduleid );
		} else {
			$o_desktop->setModuleSort ( $s_moduleid );
		}
		$o_desktop->Save ();
	}
	public function OnlineRefresh() {
		$o_user = new Base_User_Login ();
		$o_user->PushWhere ( array ('&&', 'Online', '=', 1 ) );
		return $o_user->getAllCount ();
	}
	public function AddTab($n_module_id, $n_sub, $n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$a_moduleid = array ();
		$o_module = new Base_Module ( $n_module_id );
		$this->ModuleName = $o_module->getName ();
		/*		if ($o_module->getPath () != '') {
			$this->Nav2Url = $o_module->getPath ();
			return;
		}*/
		$o_userModule = new View_User_Right ();
		$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $n_uid ) );
		$o_userModule->PushWhere ( array ('&&', 'ParentModuleId', '=', $n_module_id ) );
		$o_userModule->PushOrder ( array ('Module', 'A' ) );
		$n_count = $o_userModule->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			array_push ( $a_moduleid, $o_userModule->getModuleId ( $i ) );
			if ($o_userModule->getModuleId ( $i ) == $n_sub) {
				$this->Number = $i;
			}
			if (($i + 1) == $n_count) {
				$this->Nav2Name .= $o_userModule->getModuleName ( $i );
				$this->Nav2Url .= $o_userModule->getPath ( $i );
			} else {
				$this->Nav2Name .= $o_userModule->getModuleName ( $i ) . '<1>';
				$this->Nav2Url .= $o_userModule->getPath ( $i ) . '<1>';
			}
		}
		for($k = 1; $k <= 5; $k ++) {
			eval ( '$o_userModule = new View_User_Right_Sec' . $k . ' ();' );
			$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $n_uid ) );
			$o_userModule->PushWhere ( array ('&&', 'ParentModuleId', '=', $n_module_id ) );
			$o_userModule->PushOrder ( array ('Module', 'A' ) );
			$n_count = $o_userModule->getAllCount ();
			if ($this->Nav2Name != '' && $n_count > 0) {
				$this->Nav2Name .= '<1>';
				$this->Nav2Url .= '<1>';
			}
			for($i = 0; $i < $n_count; $i ++) {
				if (! (array_search ( $o_userModule->getModuleId ( $i ), $a_moduleid ) === FALSE)) {
					continue;
				}
				array_push ( $a_moduleid, $o_userModule->getModuleId ( $i ) );
				if ($o_userModule->getModuleId ( $i ) == $n_sub) {
					$this->Number = $i;
				}
				if (($i + 1) == $n_count) {
					$this->Nav2Name .= $o_userModule->getModuleName ( $i );
					$this->Nav2Url .= $o_userModule->getPath ( $i );
				} else {
					$this->Nav2Name .= $o_userModule->getModuleName ( $i ) . '<1>';
					$this->Nav2Url .= $o_userModule->getPath ( $i ) . '<1>';
				}
			}
		}
		if ($n_sub == 0) {
			$this->Number = 0;
		}
	}
	public function ButtonUnreadMsg($n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		require_once RELATIVITY_PATH . 'sub/sms/include/db_table.class.php';
		$o_unread = new Sms_Msg ();
		$o_unread->PushWhere ( array ('&&', 'ReceiverId', '=', $n_uid ) );
		$o_unread->PushWhere ( array ('&&', 'ReceiverDelete', '=', 0 ) );
		$o_unread->PushWhere ( array ('&&', 'Readed', '=', 0 ) );
		$n_count = $o_unread->getAllCount ();
		if ($n_count > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	public function ButtonGetUserInfo($n_other_uid, $n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$o_user = new Base_User ( $n_other_uid );
		$o_user_info = new Base_User_Info ( $n_other_uid );
		$o_user_info_custom = new Base_User_Info_Custom ( $n_other_uid );
		$o_user_role = new Base_User_Role ( $n_other_uid );
		$o_dept = new Single_User ( $n_other_uid );
		$o_role = new Base_Role ( $o_user_role->getRoleId () );
		$this->Item = '用户名<1>姓名<1>性别<1>邮箱<1>部门';
		$this->Info .= $o_user->getUserName () . '<1>';
		$this->Info .= $o_user_info->getName () . '<1>';
		$this->Info .= $o_user_info->getSex () . '<1>';
		$this->Info .= $o_user_info->getEmail () . '<1>';
		$this->Info .= $o_dept->getDeptNameForStr ();
	}
	public function ButtonUnreadRem($n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		require_once RELATIVITY_PATH . 'sub/sms/include/db_table.class.php';
		$o_unread = new Sms_Rem ();
		$o_unread->PushWhere ( array ('&&', 'ReceiverId', '=', $n_uid ) );
		$o_unread->PushWhere ( array ('&&', 'Delete', '=', 0 ) );
		$o_unread->PushWhere ( array ('&&', 'Readed', '=', 0 ) );
		$n_count = $o_unread->getAllCount ();
		if ($n_count > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	public function SysmsgRead($n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$o_msg = new View_System_Msg ();
		$o_msg->PushWhere ( array ('&&', 'Uid', '=', $n_uid ) );
		$o_msg->PushWhere ( array ('&&', 'Readed', '=', 0 ) );
		$o_msg->PushOrder ( array ('Type', 'A' ) );
		$n_count = $o_msg->getAllCount ();
		if ($n_count == 0) {
			return false;
		}
		$s_type = $o_msg->getType ( 0 );
		$a_parentid = array ($o_msg->getParentModuleId ( 0 ) );
		$a_moduleid = array ($o_msg->getModuleId ( 0 ) );
		$a_number = array (0 );
		$a_text = array ($o_msg->getText ( 0 ) );
		for($i = 0; $i < $n_count; $i ++) {
			if ($o_msg->getType ( $i ) == $s_type) {
				//同一个类别的就累加
				$a_number [count ( $a_number ) - 1] = $a_number [count ( $a_number ) - 1] + 1;
			} else {
				//不是同一个类别的就新建
				array_push ( $a_parentid, $o_msg->getParentModuleId ( $i ) );
				array_push ( $a_moduleid, $o_msg->getModuleId ( $i ) );
				array_push ( $a_number, 1 );
				array_push ( $a_text, $o_msg->getText ( $i ) );
				$s_type = $o_msg->getType ( $i );
			}
			$o_msg2 = new Base_System_Msg ( $o_msg->getId ( $i ) );
			if ($o_msg->getAmUsername ( $i ) == '' || $o_msg->getAmReaded ( $i ) == 1) { //如果没有即时消息用户名或AM已经发生提醒了，直接删除
				$o_msg2->Deletion ();
			} else {
				//如果有，则标记为已读，便于即时消息的程序读取
				$o_msg2->setReaded ( 1 );
				$o_msg2->Save ();
			}
		}
		//构建回调函数的参数
		for($i = 0; $i < count ( $a_parentid ); $i ++) {
			if (($i + 1) == count ( $a_parentid )) {
				$this->ParentId .= $a_parentid [$i];
				$this->ModuleId .= $a_moduleid [$i];
				$this->Text .= $a_text [$i];
				$this->Number .= $a_number [$i];
			} else {
				$this->ParentId .= $a_parentid [$i] . '<1>';
				$this->ModuleId .= $a_moduleid [$i] . '<1>';
				$this->Text .= $a_text [$i] . '<1>';
				$this->Number .= $a_number [$i] . '<1>';
			}
		}
	
	}
	public function GetOnlineUser($n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$o_user = new View_Online_User ();
		$o_user->PushWhere ( array ('&&', 'Online', '=', 1 ) );
		$n_count = $o_user->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$html .= '
								<div style="float:left;width:100px;text-align:center;padding:5px">
                                   <a href="javascript:;" hidefocus="hidefocus">
                                        <img src="images/org/U01.png" alt="">' . $o_user->getName ( $i ) . '
                                   </a>
                                </div>
			';
		}
		return $html;
	}
	public function getModuleName() {
		return $this->ModuleName;
	}
	public function getNav2Name() {
		return $this->Nav2Name;
	}
	public function getNumber() {
		return $this->Number;
	}
	public function getNav2Url() {
		return $this->Nav2Url;
	}
	public function getResult() {
		return $this->Result;
	}
	public function getParentId() {
		return $this->ParentId;
	}
	public function getModuleId() {
		return $this->ModuleId;
	}
	public function getText() {
		return $this->Text;
	}
	public function getItem() {
		return $this->Item;
	}
	public function getInfo() {
		return $this->Info;
	}
	public function UploadTempFile($n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$o_user = new Single_User ( $n_uid );
		$this->DeleteDir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/netdisk/netdisk_temp' ); //删除临时文件	
		mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/netdisk/netdisk_temp', 0700 );
		if ($_FILES ['Vcl_Upload'] ['size'] > 0) {
			move_uploaded_file ( $_FILES ["Vcl_Upload"] ["tmp_name"], RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/netdisk/netdisk_temp/' . iconv ( 'UTF-8', 'gb2312', $_FILES ['Vcl_Upload'] ['name'] ) );
		} else {
			return 3;
		}
	}
	public function UploadAffixFile($n_uid) {
		if (! ($n_uid > 0)) {
			//直接退出系统
			$this->Result = FALSE;
			return;
		}
		$o_user = new Single_User ( $n_uid );
		mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ), 0700 );
		mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/netdisk', 0700 );
		$dir = RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/netdisk/netdisk_temp';
		$dh = opendir ( $dir );
		while ( $file = readdir ( $dh ) ) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (! is_dir ( $fullpath )) {
					$o_path = md5 ( $file . $n_uid . rand ( 0, 9999 ) . $this->GetTimeCut () );
					mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/netdisk/' . $o_path, 0700 );
					//$filename = rawurlencode($file);
					$filename = iconv ( 'gb2312', 'UTF-8', $file );
					mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/affixfile', 0700 );
					mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/affixfile/' . $o_path, 0700 );
					$o_to = RELATIVITY_PATH . 'userdata/' . md5 ( $o_user->getUserName () ) . '/affixfile/' . $o_path . '/' . $file;
					rename ( $fullpath, $o_to );
					break;
				}
			}
		}
		closedir ( $dh );
		//$this->DeleteDir ( $dir ); //删除临时文件	
		return 'http://www.xcjyxxzx.cn/userdata/' . md5 ( $o_user->getUserName () ) . '/affixfile/' . $o_path . '/' . $filename;
	}
}
?>