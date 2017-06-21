<?php
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
class Single_User extends Bn_Basic //单独用户,把所有根用户有关的进行统一操作
{
	private $O_Role; //角色
	//private $O_Group;
	private $O_Info; //基本信息
	private $O_Login; //登录信息
	//private $O_Count;//统计
	private $O_Photo;//头像
	//private $O_Profile;//私人信息
	private $S_Session_Id;
	private $S_Ip;
	private $S_Agent;
	private $S_Picture_Url;
	private $N_Picture_Id;
	public function ValidModule($n_module_id) {
		$o_userModule = new View_User_Right ();
		$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->O_Info->getUid () ) );
		$o_userModule->PushWhere ( array ('&&', 'ModuleId', '=', $n_module_id ) );
		$n_count = $o_userModule->getAllCount ();
		if ($n_count > 0) {
			return true;
		}
		for($i = 1; $i <= 5; $i ++) {
			eval ( '$o_userModule = new View_User_Right_Sec' . $i . ' ();' );
			$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->O_Info->getUid () ) );
			$o_userModule->PushWhere ( array ('&&', 'ModuleId', '=', $n_module_id ) );
			$n_count = $o_userModule->getAllCount ();
			if ($n_count > 0) {
				return true;
			}
		}
		if($n_module_id==0)
		{
			return true;//查看系统消息模块
		}
		return false;
	}
	public function setSessionID($s_sessionid) {
		$this->S_Session_Id = $s_sessionid;
	}
	
	public function setUserName($s_username) {
		$this->O_Info->setUserName ( $s_username );
	}
	public function getDeptId() { //获取用户属于所有部门的Id的数组
		$a_deptid = array ();
		$n_deptid = $this->O_Role->getDeptId ();
		array_push ( $a_deptid, $n_deptid );
		$o_dept = new Base_Dept ( $n_deptid ); //检验有没有父部门
		while ( $o_dept->getParentId () != 0 ) {
			array_push ( $a_deptid, $o_dept->getParentId () );
			$o_dept = new Base_Dept ( $o_dept->getParentId () ); //检验有没有父部门
		}
		return $a_deptid;
	}
	public function getDeptName() { //获取用户属于所有部门的名称的数组
		$a_deptname = array ();
		$o_dept = new Base_Dept ( $this->O_Role->getDeptId () );
		array_push ( $a_deptname, $o_dept->getName () );
		while ( $o_dept->getParentId () != 0 ) { //检验有没有父部门			
			$o_dept = new Base_Dept ( $o_dept->getParentId () );
			array_push ( $a_deptname, $o_dept->getName () );
		}
		return $a_deptname;
	}
	public function getDeptNameForStr() { //获取用户属于所有部门的名称的数组
		$a_deptname = array ();
		$o_dept = new Base_Dept ( $this->O_Role->getDeptId () );
		array_push ( $a_deptname, $o_dept->getName () );
		$s_deptname = '';
		while ( $o_dept->getParentId () != 0 ) { //检验有没有父部门			
			$o_dept = new Base_Dept ( $o_dept->getParentId () );
			array_push ( $a_deptname, $o_dept->getName () );
		}
		for($i = 0; $i < count ( $a_deptname ); $i ++) {
			$s_deptname .= '' . $a_deptname [count ( $a_deptname ) - $i - 1] . ' >> ';
		}
		$s_deptname = substr ( $s_deptname, 0, strlen ( $s_deptname ) - 4 );
		return $s_deptname;
	}
	public function getPhoto() {
		return $this->O_Photo->getPath ();
	}
	public function getName() {
		return $this->O_BaseInfo->getName ();
	}
	public function getRoleId() {
		return $this->O_Role->getRoleId ();
	}
	public function getRoleName() {
		return $this->O_Role->getName ();
	}
	public function getActivityId() {
		return $this->O_Info->getActivityId ();
	}
	public function getUid() {
		return $this->O_Info->getUid ();
	}
	public function getUserName() {
		return $this->O_Info->getUserName ();
	}
	public function getPassword() {
		return $this->O_Info->getPassword ();
	}
	public function getEmailPassword() {
		return $this->O_BaseInfo->getEmailPassword ();
	}
	public function getSex() {
		return $this->O_BaseInfo->getSex ();
	}
	public function getEmail() {
		return $this->O_BaseInfo->getEmail ();
	}
	public function __construct($uid = null) {
		$this->S_Ip = $_SERVER ['REMOTE_ADDR'];
		$this->S_Agent = $_SERVER ['HTTP_USER_AGENT'];
		$this->S_Session_Id = $_COOKIE ['SESSIONID'];
		if (isset ( $uid )) {
			$this->O_Info = new Base_User ( $uid );
			$this->O_BaseInfo = new Base_User_Info ( $uid );
			$this->O_Login = new Base_User_Login ( $uid );
			$this->O_Role = new Base_User_Role ( $uid );
			$this->O_Photo = new Base_User_Photo ( $uid );
		} else {
		}
	}
	public function __call($s_function, $a_arguments) {
		$a_function = explode ( '_', $s_function );
		if (count ( $a_function ) != 3) {
			return false;
		}
		$s_methodtype = $a_function [0];
		$s_methodMember = $a_function [2];
		$s_object = 'O_' . $a_function [1];
		switch ($s_methodtype) {
			case 'set' :
				return ($this->SetAccessor ( $s_object, $s_methodMember, $a_arguments [0] ));
				break;
			case 'get' :
				return ($this->GetAccessor ( $s_object, $s_methodMember ));
				break;
		}
		return false;
	}
	
	private function SetAccessor($s_object, $s_member, $s_newValue) {
		if (property_exists ( $this, $s_object )) {
			if (is_numeric ( $s_newValue )) {
				eval ( '$this->' . $s_object . '->set' . $s_member . '(' . $s_newValue . ');' );
			} else {
				eval ( '$this->' . $s_object . '->set' . $s_member . '(\'' . str_replace ( '\'', '`', $s_newValue ) . '\');' );
			}
			$this->A_ModifiedRelations [$s_member] = '1';
		} else {
			return false;
		}
	}
	
	private function GetAccessor($s_object, $s_member) {
		if (property_exists ( $this, $s_object )) {
			$s_retVal = '';
			eval ( '$s_retVal=$this->' . $s_object . '->get' . $s_member . '();' );
			return $s_retVal;
		} else {
			return false;
		}
	}
	
	public function Save() //保存用户信息
{
		$uid = $this->O_Info->getS_Value ();
		if (isset ( $uid )) { //保存时不能修改权限和Gruop
			$this->O_Info->Save ();
			$this->O_Login->Save ();
			$this->O_Role->Save ();
		}
	}
	
	public function Deletion() //删除用户
{
		$n_uid = $this->O_Info->getS_Value ();
		if (isset ( $n_uid )) {
			$this->O_Info->Deletion ();
			$this->O_Login->Deletion ();
			$this->O_Role->Deletion ();
		}
	}
	public function LoginIn($s_username, $s_password, $type = '') {
		$n_uid = $this->FindUserName ( $s_username );
		if ($n_uid > 0) {
			$this->__construct ( $n_uid );
		} else {
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('NotFoundUserName').'\')");' );
		}
		$o_date = new DateTime ( 'Asia/Chongqing' );
		$n_nowTime = $o_date->format ( 'U' );
		if (($n_nowTime - $this->O_Login->getLockTime ()) < 60) {
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('PasswordOverLimit').'\')");' );
		}
		if (md5 ( 'welcome ' . $s_password . ' to 教育城域网综合管理信息系统 !' ) == $this->O_Info->getPassword ()) { //密码输入正确
			if ($this->O_Info->getState () == 0) {
				$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('UserBlock').'\')");' );
			}
			$this->O_Login->setPassError ( 0 );
			$this->O_Login->setLastIp ( $this->S_Ip );
			if ($this->O_Login->getOnline () == 1) {
				//计算上次在线时间
				$n_onlinetime = $this->O_Login->getOnlineTime (); //在线总时
				$n_logintime = $this->O_Login->getLoginTime (); //上线时间
				$n_lasttime = $this->O_Login->getLastTime (); //最后在线时
				$n_onlinetime = $n_onlinetime + ($n_lasttime - $n_logintime);
				$this->O_Login->setOnlineTime ( $n_onlinetime );
			}
			$this->O_Login->setOnline ( 1 );
			$this->O_Login->setLoginTime ( $n_nowTime );
			$this->O_Login->setLastTime ( $n_nowTime );
			$this->O_Login->setUserAgent ( $this->S_Agent );
			$this->O_Login->setSessionId ( $this->S_Session_Id );			
			$this->O_Login->Save ();
			$this->setReturn ( 'parent.location="'.$this->getPost('Url').'sub/meeting/meeting_list.php";' );
		} else { //密码输入错误
			if (($this->O_Login->getPassError () + 1) >= 3) {
				$this->O_Login->setPassError ( 0 ); //密码错误次数归零,超过一分钟后可以再输入
				$this->O_Login->setLockTime ( $n_nowTime ); //锁定账户
			} else {
				$this->O_Login->setPassError ( $this->O_Login->getPassError () + 1 ); //密码错误次数+1
			}
			$this->O_Login->Save ();
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('PasswordError').'\')");' );
		}
	}
	
	public function Logout($s_session_id) {
		$n_uid = $this->FindUserNameForSessionId ( $s_session_id );
		if ($n_uid > 0) {
			$this->__construct ( $n_uid );
		} else {
			return;
		}
		//计算在线时间
		$o_date = new DateTime ( 'Asia/Chongqing' );
		$n_nowTime = $o_date->format ( 'U' );
		$n_onlinetime = $this->O_Login->getOnlineTime (); //在线总时
		$n_logintime = $this->O_Login->getLoginTime (); //上线时间
		$n_lasttime = $n_nowTime; //最后在线时
		$n_onlinetime = $n_onlinetime + ($n_lasttime - $n_logintime);
		$this->O_Login->setOnlineTime ( $n_onlinetime );
		//
		$this->O_Login->setOnline ( 0 );
		$this->O_Login->setLastTime ( $n_nowTime );
		//$s_session_id = md5($n_nowTime . rand(0, 9999));
		$this->O_Login->setSessionId ( '0' );
		$this->O_Login->Save ();
	
		//$s_username = '游客' . rand(0, 9999);
	//setcookie('VISITER', $s_username, 0);
	//setcookie('VISITER', $s_username, 0);
	//setcookie('SESSIONID', $s_session_id, 0);
	}
	
	public function FindUserName($s_username) {
		$s_username = str_replace ( '\'', '`', $s_username );
		$o_user = new Base_User ();
		$o_user->setItem ( array ('Uid' ) );
		$o_user->PushWhere ( array ('&&', 'UserName', '=', $s_username ) );
		if ($o_user->getAllCount () > 0) {
			return $o_user->getUid ( 0 );
		} else {
			return 0;
		}
	}
	public function UpLoadPicture() {
		//验证用户是否登录
		//$o_user_picture = new Base_User_Picture ();
		//$o_user_picture->Save();
		if ($this->O_Info->getUid () == 0) {
			return false;
		}
		//验证文件是否存在
		if ($_FILES ['Filedata'] ['size'] == 0) {
			return false;
		}
		//验证后缀名
		$allowpictype = array ('jpg', 'jpeg', 'gif', 'png' );
		$fileext = strtolower ( trim ( substr ( strrchr ( $_FILES ['Filedata'] ['name'], '.' ), 1 ) ) );
		if (! in_array ( $fileext, $allowpictype )) {
			return false;
		}
		//验证上传图片的大小不能大于2MB
		if ($_FILES ['Filedata'] ['size'] > 1024 * 1024 * 2) {
			return false;
		}
		/*$n_photosplace = $this->O_Count->getPhotoSplace (); //读取图片的总空间
		$n_photosize = $this->O_Count->getUpLoadPhotoSize (); //读取已经占用的空间
		//验证剩余空间
		if ($_FILES ['Filedata'] ['size'] > ($this->getPhotoFreeSplace ())) {
			return false;
		}*/
		//$s_home_page = $o_system_info->getHomePage (); //获取网站的决定地址
		$date = new DateTime ( 'Asia/Chongqing' );
		$filename = $date->format ( 'U' ) . rand ( 0, 999 ) . '.' . $fileext; //生成随机的文件名 
		$path = '/userdata/' . md5 ( $this->O_Info->getUserName () ) . '/picture/' . $filename; //设置图片的绝对地址
		$o_user_picture = new Base_User_Picture ();
		$o_user_picture->setTimecut ( $date->format ( 'U' ) ); //设置时间截
		$o_user_picture->setPath ( $path ); //设置图片的绝对地址
		$o_user_picture->setUid ( $this->O_Info->getUid () ); //设置用户编号
		$o_user_picture->setFilesize ( $_FILES ['Filedata'] ['size'] ); //设置图片文件大小		
		$o_user_picture->Save (); //更新用户图片数据库
		//计算剩余空间和统计图片数量
		//$this->O_Count->setUpLoadPhotoSize ( $this->O_Count->getUpLoadPhotoSize () + $_FILES ['Filedata'] ['size'] ); //占用空间
		//$this->O_Count->setPictrueSum ( $this->O_Count->getPictrueSum () + 1 ); //图片数+1
		//$this->O_Count->Save ();
		$filename = RELATIVITY_PATH . 'userdata/' . md5 ( $this->O_Info->getUserName () ) . '/picture/' . $filename; //设置图片放置的位置
		mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $this->O_Info->getUserName () ), 0700 );
		mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $this->O_Info->getUserName () ) . '/photo', 0700 );
		mkdir ( RELATIVITY_PATH . 'userdata/' . md5 ( $this->O_Info->getUserName () ) . '/picture', 0700 );
		copy ( $_FILES ['Filedata'] ['tmp_name'], $filename ); //将图片拷贝到指定
	}
	//删除上传的文章图片,参数为图片的ID编号
	public function DelUpLoadPicture($n_id) {
		$o_user_picture = new Base_User_Picture (); //新建一个用户照片的对象
		$o_user_picture->PushWhere ( array ('&&', 'Uid', '=', $this->O_Info->getUid () ) ); //照片的用户ID等于当前登录的用户的ID
		$o_user_picture->PushWhere ( array ('&&', 'Id', '=', $n_id ) ); //照片的ID等于要删除照片的ID
		if ($o_user_picture->getAllCount () > 0) {
			//如果有信息则删除图像
			$o_user_picture = new Base_User_Picture ( $n_id ); //以当前要删除照片的ID作为构造参数
			//获取照片文件的文件名
			$s_file = $o_user_picture->getPath ();
			$s_file = explode ( '/', $s_file );
			$s_file = $s_file [(count ( $s_file ) - 1)];
			//删除在磁盘上的照片
			unlink ( RELATIVITY_PATH . 'userdata/' . md5 ( $this->O_Info->getUserName () ) . '/picture/' . $s_file );
			//计算剩余空间和统计图片数量
			//$n_filesize = $o_user_picture->getFileSize ();//读取删除照片的大小
			//$n_photosplace = $this->O_Count->getPhotoSplace (); //读取图片的总空间
			//$n_photosize = $this->O_Count->getUpLoadPhotoSize (); //读取已经占用的空间
			//$this->O_Count->setUpLoadPhotoSize ( $n_photosize - $n_filesize ); //占用空间
			//$this->O_Count->setPictrueSum ( $this->O_Count->getPictrueSum () - 1 ); //图片数+1
			//$this->O_Count->Save ();
			$o_user_picture->Deletion (); //删除数据库中的记录
		}
	}
	//获取用户的图片剩余空间
	public function getPhotoFreeSplace() {
		/*$n_photosplace = $this->O_Count->getPhotoSplace (); //读取图片的总空间
		$n_photosize = $this->O_Count->getUpLoadPhotoSize (); //读取已经占用的空间
		$n_photofreesplace = $n_photosplace - $n_photosize;//计算
		if ($n_photofreesplace < 0) {
			$n_photofreesplace = 0;
		}*/
		//return $n_photofreesplace;
		return 1024000000000;
	}
	//获取用户的文章图片的列表,用于显示到文本编辑器中
	public function getPictrueListForAjax() {
		$o_user_picture = new Base_User_Picture ();
		$o_user_picture->PushWhere ( array ('&&', 'Uid', '=', $this->O_Info->getUid () ) );
		$o_user_picture->PushOrder ( array ('Timecut', 'D' ) );
		$n_picturesum = $o_user_picture->getAllCount (); //获取图片总数	
		$s_result = '';
		if ($n_picturesum > 0) {
			//循环构造文件列表:'编号'+'<2>'+'地址'
			for($i = 0; $n_picturesum > $i; $i ++) {
				$s_result .= $o_user_picture->getId ( $i ) . '<2>' . $o_user_picture->getPath ( $i ) . '<1>'; //构造列表参数	
			}
			$s_result = substr ( $s_result, 0, strlen ( $s_result ) - 3 ); //去掉最后的'<1>'
			return $s_result;
		} else {
			return '';
		}
	}
}

?>