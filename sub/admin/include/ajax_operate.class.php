<?php
//error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
class Operate extends Bn_Basic {
	protected $N_PageSize= 25;
	
	public function UserTable($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_user = new View_User_List ();
		$s_key=$this->getPost('key');
		if ($s_key!='')
		{
			$o_user->PushWhere ( array ('||', 'UserName', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('||', 'Name', 'like','%'.$s_key.'%') );
		}
		$o_user->PushWhere ( array ('&&', 'Type', '=', 1 ) );
		$o_user->PushWhere ( array ('&&', 'Deleted', '=', 0 ) );
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			$o_user2 = new Single_User ( $o_user->getUid ( $i ) );
			$s_deptname = $o_user2->getDeptNameForStr ();
			$s_rolename = '';
			if ($o_user->getSecRole1 ( $i ) > 0) {
				$o_role = new Base_Role ( $o_user->getSecRole1 ( $i ) );
				$s_rolename .= $o_role->getName () . '<br/>';
			}
			$s_space='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if ($o_user->getSecRole2 ( $i ) > 0) {
				$o_role = new Base_Role ( $o_user->getSecRole2 ( $i ) );
				$s_rolename .= $s_space.$o_role->getName () . '<br/>';
			}
			if ($o_user->getSecRole3 ( $i ) > 0) {
				$o_role = new Base_Role ( $o_user->getSecRole3 ( $i ) );
				$s_rolename .= $s_space.$o_role->getName () . '<br/>';
			}
			if ($o_user->getSecRole4 ( $i ) > 0) {
				$o_role = new Base_Role ( $o_user->getSecRole4 ( $i ) );
				$s_rolename .= $s_space.$o_role->getName () . '<br/>';
			}
			if ($o_user->getSecRole5 ( $i ) > 0) {
				$o_role = new Base_Role ( $o_user->getSecRole5 ( $i ) );
				$s_rolename .= $s_space.$o_role->getName ();
			}
			if ($s_rolename!='')
			{
				$s_rolename='
				<br/>
				<span class="ge_form_gray">'.Text::Key('AssistantRole').'：'.$s_rolename.'</span>
				';
			}
			//数据行
			$a_button = array ();
			array_push ( $a_button, array (Text::Key('Review'), "location='user_show.php?id=".$o_user->getUid($i)."'" ) );//查看
			array_push ( $a_button, array (Text::Key('Modify'), "location='user_modify.php?id=".$o_user->getUid($i)."'" ) );//修改
			
			if($o_user->getState ($i) == 1)
			{
				$s_state='<span class="label label-success">'.Text::Key('Status-Enable').'</span>';
				array_push ( $a_button, array (Text::Key('Disable'), "user_set_status(this,'".$o_user->getUid($i)."',0,'".Text::Key('Enable')."')" ) );//停用
			}else{
				$s_state='<span class="label label-danger">'.Text::Key('Status-Disable').'</span>';
				array_push ( $a_button, array (Text::Key('Enable'), "user_set_status(this,'".$o_user->getUid($i)."',1,'".Text::Key('Disable')."')" ) );//启用
			}
			array_push ( $a_button, array (Text::Key('Delete'), "user_delete_confirm(this,'".$o_user->getUid($i)."')" ) );//删除
			array_push ( $a_button, array (Text::Key('RestPassword'), "location='user_restpasswd.php?id=".$o_user->getUid($i)."'" ) );//重置密码
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				$o_user->getUserName ( $i ),
				$o_user->getName ( $i ),
				$s_deptname,
				Text::Key('PrimaryRole').'：'. $o_user->getRoleName ( $i ) . ''.$s_rolename,
				$s_state,
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('UserName'), 'UserName', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Name'), 'Name', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Department'), 'DeptId', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Role'), 'RoleName', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Status'), 'State', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 65);
		$this->SendJsonResultForTable($n_allcount, 'UserTable', 'yes', $n_page, $a_title, $a_row);
	}
	public function NetdiskTable($n_uid)//用户网盘使用情况列表
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100105 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_user = new View_User_List ();
		$o_user->PushWhere ( array ('&&', 'Type', '=', 1 ) );
		$o_user->PushWhere ( array ('&&', 'Deleted', '=', 0 ) );
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			//获取剩余空间
			require_once RELATIVITY_PATH . 'include/file.function.php';
			$a_result=folder_info(RELATIVITY_PATH. 'userdata/netdisk/'.md5($o_user->getUid($i).'ctisss'));
			$n_free=$o_user->getDiskSpace ( $i )-$a_result['size'];
			//获取文件和文件夹总数
			$s_files_num=$a_result['file_num'].' '.Text::Key('FileType').','.$a_result['folder_num'].' '.Text::Key('FolderType');
			//数据行
			$a_button = array ();
			array_push ( $a_button, array (Text::Key('Modify'), "location='netdisk_modify.php?id=".$o_user->getUid($i)."'" ) );//修改容量
			//array_push ( $a_button, array (Text::Key('Delete'), "netdisk_delete('".$o_user->getUid($i)."')" ) );//清空文件
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				$o_user->getUserName ( $i ),
				$o_user->getName ( $i ),
				size_format($n_free, 2),
				size_format($o_user->getDiskSpace ( $i ), 2),
				$s_files_num,
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('UserName'), 'UserName', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Name'), 'Name', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('FreeSpace'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('TotalSpace'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('FileNum'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 65);
		$this->SendJsonResultForTable($n_allcount, 'NetdiskTable', 'yes', $n_page, $a_title, $a_row);
	}
	public function DeptTable($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_user = new Base_Dept ();
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			//数据行
			$a_button = array ();
			array_push ( $a_button, array (Text::Key('Modify'), "location='dept_modify.php?id=".$o_user->getDeptId($i)."'" ) );//修改
			array_push ( $a_button, array (Text::Key('Delete'), "delete_dept(".$o_user->getDeptId($i).")" ) );//查看
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				$o_user->getName ( $i ),
				$o_user->getAddress($i),
				$o_user->getPhone($i),
				$o_user->getFax($i),
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('DeptName'), 'Name', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Address'), 'Address', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Phone'), 'Phone', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Fax'), 'Fax', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 65);
		$this->SendJsonResultForTable($n_allcount, 'DeptTable', 'yes', $n_page, $a_title, $a_row);
	}
	public function RoleTable($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100102 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_user = new Base_Role ();
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			//数据行
			$a_button = array ();
			array_push ( $a_button, array (Text::Key('Modify'), "location='role_modify.php?id=".$o_user->getRoleId($i)."'" ) );//修改
			array_push ( $a_button, array (Text::Key('Delete'), "delete_role('".$o_user->getRoleId($i)."')" ) );//查看
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				$o_user->getName ( $i ),
				$o_user->getExplain($i),
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('RoleName'), 'Name', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('RoleExplain'), 'Address', 0, 0);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 65);
		$this->SendJsonResultForTable($n_allcount, 'RoleTable', 'yes', $n_page, $a_title, $a_row);
	}
	public function UserAdd($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		sleep(1);
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return; //如果没有权限，不返回任何值
		if ($o_user->FindUserName ( $this->getPost('UserName') ) > 0) {
			//有重名
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('UserNameRepeat').'\')");' );
		}
		//用户基本信息
		$o_user = new Base_User ();
		$o_user->setPassword ( md5 ( 'welcome ' . $_POST ['Vcl_Password'] . ' to 教育城域网综合管理信息系统 !' ) );
		$o_user->setUserName ( $this->getPost ( 'UserName' ) );
		$o_user->setState ( 1 );
		$o_user->setRegIp ( $_SERVER ['REMOTE_ADDR'] );
		$o_user->setRegTime ( $this->GetDateNow () );
		$o_user->setType (1);
		$o_user->setDeleted (0);
		$o_user->setActivityId ('[]');
		$o_user->Save ();
		//用户不可修改的信息
		$o_user_info = new Base_User_Info ();
		$o_user_info->setUid ( $o_user->getUid () );
		$o_user_info->setName ( $this->getPost ( 'Name' ) );
		$o_user_info->setPhone ($this->getPost('Phone'));
		$o_user_info->setEmail ( $this->getPost('Email'));
		$o_user_info->setShowNavIcon (0);
		$o_user_info->setDiskSpace ( $this->getPost ( 'DiskSpace' )*1073741824 );
		$o_user_info->Save ();
		//用户可修改的信息
		$o_user_info_custom = new Base_User_Info_Custom ();
		$o_user_info_custom->setUid ( $o_user->getUid () );
		$o_user_info_custom->setBirthday ('0000-00-00');
		$o_user_info_custom->Save ();
		//默认头像
		$o_user_photo=new Base_User_Photo();
		$o_user_photo->setUid ($o_user->getUid ());
		$o_user_photo->setPath ('images/photo_default.png');
		$o_user_photo->Save ();
		//用户登陆信息
		$o_user_login = new Base_User_Login ();
		$o_user_login->setUid ( $o_user->getUid () );
		$o_user_login->setLastIp('');
  		$o_user_login->setLastTime(0);
   		$o_user_login->setOnline(0);
   		$o_user_login->setLoginTime(0);
   		$o_user_login->setOnlineTime(0);
  		$o_user_login->setUserAgent('');
   		$o_user_login->setSessionId('');
   		$o_user_login->setPassError(0);
   		$o_user_login->setLockTime(0);
		$o_user_login->Save ();
		//用户部门角色信息
		$o_user_role = new Base_User_Role ();
		$o_user_role->setUid ( $o_user->getUid () );
		$o_user_role->setDeptId ( $this->getPost('DeptId') );
		$o_user_role->setRoleId ( $this->getPost('Role0') );
		$o_user_role->setSecRoleId1 ( $this->getPost('Role1') );
		$o_user_role->setSecRoleId2 ( $this->getPost('Role2') );
		$o_user_role->setSecRoleId3 ( $this->getPost('Role3') );
		$o_user_role->setSecRoleId4 ( $this->getPost('Role4') );
		$o_user_role->setSecRoleId5 ( $this->getPost('Role5') );
		$o_user_role->Save ();
		$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('UserAddSuccess').'\',function(){parent.location=\''.$this->getPost('BackUrl').'\'})");' );
	}
	public function UserModify($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		sleep(1);
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return; //如果没有权限，不返回任何值
		$n_id=$this->getPost('Id');

		//用户不可修改的信息
		$o_user_info = new Base_User_Info ($n_id);
		$o_user_info->setName ( $this->getPost ( 'Name' ) );
		$o_user_info->setDiskSpace ( $this->getPost ( 'DiskSpace' )*1073741824 );
		$o_user_info->Save ();
		//用户可修改的信息
		$o_user_info_custom = new Base_User_Info_Custom ($n_id);
		$o_user_info_custom->setEmail ( $this->getPost('Email') );
		$o_user_info_custom->setPhone ( $this->getPost('Phone'));
		$o_user_info_custom->Save ();
		//用户部门角色信息
		$o_user_role = new Base_User_Role ($n_id);
		$o_user_role->setDeptId ( $this->getPost('DeptId') );
		$o_user_role->setRoleId ( $this->getPost('Role0') );
		$o_user_role->setSecRoleId1 ( $this->getPost('Role1') );
		$o_user_role->setSecRoleId2 ( $this->getPost('Role2') );
		$o_user_role->setSecRoleId3 ( $this->getPost('Role3') );
		$o_user_role->setSecRoleId4 ($this->getPost('Role4') );
		$o_user_role->setSecRoleId5 ( $this->getPost('Role5') );
		$o_user_role->Save ();
		$this->setReturn ( 'parent.location=\''.$this->getPost('BackUrl').'\';' );
	}
	public function NetdiskModify($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		sleep(1);
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100105 ))return; //如果没有权限，不返回任何值
		$n_id=$this->getPost('Id');
		$o_user_info = new Base_User_Info ($n_id);
		$n_old_space=$o_user_info->getDiskSpace();
		$o_user_info->setDiskSpace ( $this->getPost ( 'DiskSpace' )*1073741824 );
		if ($n_old_space!=($this->getPost ( 'DiskSpace' )*1073741824))
		{
			//如果不相等，说明有修改，那么保存并通知用户
			$o_user_info->Save ();
			$this->SendSysmsg($n_id, rawurlencode(Text::Key('Sysmsg_001').$this->getPost ( 'DiskSpace' ).'G。<a href="javascript:;" onclick="go_url(\'sub/netdisk/index.php\')">'.Text::Key('Sysmsg_002').'</a>'));
		}
		$this->setReturn ( 'parent.location=\''.$this->getPost('BackUrl').'\';' );
	}
	public function UserSetStatus($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return; //如果没有权限，不返回任何值
		$n_id=$this->getPost('id');
		$o_user=new Base_User($n_id);
		$o_user->setState ( $this->getPost('status') );
		$o_user->Save ();
	}
	public function UserResetPasswd($n_uid)
	{
		if (! ($n_uid > 0)) {
				//直接退出系统
			$this->setReturn('parent.goLoginPage()');
		}	
		sleep(1);
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return; //如果没有权限，不返回任何值
		$o_user = new Base_User ( $this->getPost('Id'));
		$o_user->setPassword ( md5 ( 'welcome ' . $_POST ['Vcl_Password'] . ' to 教育城域网综合管理信息系统 !' ) );
		$o_user->Save ();
		//加入提醒
		$this->SendSysmsg($this->getPost('Id'), rawurlencode(Text::Key('Sysmsg_004').$_POST ['Vcl_Password'].'，'.Text::Key('Sysmsg_005').' <a href="javascript:;" onclick="go_url(\'sub/control/password_index.php\')">'.Text::Key('Sysmsg_003').'</a>'));
		$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('ResetPasswordSuccess').'\',function(){parent.location=\''.$this->getPost('BackUrl').'\'})");' );
	}
	public function UserDelete($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100101 ))return; //如果没有权限，不返回任何值
		$n_id=$this->getPost('id');
		$o_user=new Base_User($n_id);
		$o_user->setState (0);
		$o_user->setDeleted (1);
		$o_user->Save ();
		$o_user=new Base_User_Role($n_id);
		$o_user->Deletion();
		echo (json_encode ( array() ));
		return;
	}
	public function RoleAdd($n_uid) {
		sleep(1);
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100102 ))return; //如果没有权限，不返回任何值
		$n_name=$this->getPost('Name');
		$n_exlain=$this->getPost('Explain');
		$s_check=$this->getPost('Check');
		$o_role = new Base_Role ();
		$o_role->setName ( $n_name );
		$o_role->setExplain ( $n_exlain );
		$o_role->Save ();
		
		$a_role_id = json_decode($s_check);
		for($i = 0; $i < count ( $a_role_id ); $i ++) {
			$o_right = new Base_Right ();
			$o_right->setRoleId ( $o_role->getRoleId () );
			$o_right->setModuleId ( $a_role_id [$i] );
			$o_right->Save ();
		}
		$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('RoleAddSuccess').'\',function(){parent.location=\''.$this->getPost('BackUrl').'\'})");' );
	}
	public function RoleModify($n_uid) {
		sleep(1);
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100102 ))return; //如果没有权限，不返回任何值
		$n_name=$this->getPost('Name');
		$n_exlain = $this->getPost ( 'Explain' );
		$n_id = $this->getPost ( 'Id' );
		$s_check=$this->getPost('Check');
		$o_role = new Base_Role ( $n_id );
		$o_role->setName ( $n_name );
		$o_role->setExplain ( $n_exlain );
		$o_role->Save ();
		//删除所有权限
		$o_right = new Base_Right ();
		$o_right->PushWhere ( array ('&&', 'RoleId', '=', $n_id ) );
		$n_count = $o_right->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$o_right2 = new Base_Right ( $o_right->getRightId ( $i ) );
			$o_right2->Deletion ();
		}
		$a_role_id = json_decode($s_check);
		for($i = 0; $i < count ( $a_role_id ); $i ++) {
			$o_right = new Base_Right ();
			$o_right->setRoleId ( $o_role->getRoleId () );
			$o_right->setModuleId ( $a_role_id [$i] );
			$o_right->Save ();
		}
		//$this->setReturn ( 'parent.window.alert("'.count ( $a_role_id ).'")' );
		$this->setReturn ( 'parent.location=\''.$this->getPost('BackUrl').'\';' );
	}
	public function RoleDelete($n_uid) {
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (! $o_user->ValidModule ( 100102 ))
			return; //如果没有权限，不返回任何值
		//验证有无用户使用这个角色
		$n_id=$this->getPost('id');
		$o_role = new Base_User_Role ();
		$o_role->PushWhere ( array ('||', 'RoleId', '=', $n_id ) );
		$o_role->PushWhere ( array ('||', 'SecRoleId1', '=', $n_id ) );
		$o_role->PushWhere ( array ('||', 'SecRoleId2', '=', $n_id ) );
		$o_role->PushWhere ( array ('||', 'SecRoleId3', '=', $n_id ) );
		$o_role->PushWhere ( array ('||', 'SecRoleId4', '=', $n_id ) );
		$o_role->PushWhere ( array ('||', 'SecRoleId5', '=', $n_id ) );
		if ($o_role->getAllCount () > 0) {
			$a_general = array (
				'success' => 0,
				'text' => Text::Key ( 'RoleDelError' ) 
			);
			echo (json_encode ( $a_general ));
			return;
		}
		sleep(1);
		$o_right = new Base_Right ();
		$o_right->PushWhere ( array ('&&', 'RoleId', '=', $n_id ) );
		$n_count = $o_right->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$o_right2 = new Base_Right ( $o_right->getRightId ( $i ) );
			$o_right2->Deletion ();
		}
		$o_role = new Base_Role ( $n_id );
		$o_role->Deletion ();
		$a_general = array (
			'success' => 1,
			'text' =>''
		);
		echo (json_encode ( $a_general ));
	}
	public function DeptAdd($n_uid) {
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (! $o_user->ValidModule ( 100103 ))return; //如果没有权限，不返回任何值
		//查找部门名称是否有相同的
		$o_dept = new Base_Dept ();
		$o_dept->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept->PushWhere ( array ('&&', 'Name', '=',$this->getPost('Name')) );
		$n_count = $o_dept->getAllCount ();
		if ($n_count > 0) {
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('DeptAddError').'\')");' );
		}
		sleep(1);
		$o_dept = new Base_Dept ();
		$o_dept->setName ($this->getPost('Name'));
		$o_dept->setParentId ( 0 );
		$o_dept->setNumber ( 0 );
		$o_dept->setPhone ($this->getPost('Phone'));
		$o_dept->setFax ($this->getPost('Fax'));
		$o_dept->setAddress ($this->getPost('Address'));
		$o_dept->Save ();
		$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('DeptAddSuccess').'\',function(){parent.location=\''.$this->getPost('BackUrl').'\'})");' );
	}
	public function DeptModify($n_uid) {
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (! $o_user->ValidModule ( 100103 ))return; //如果没有权限，不返回任何值
		$o_dept = new Base_Dept ();
		$o_dept->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept->PushWhere ( array ('&&', 'DeptId', '<>', $this->getPost ( 'Id' ) ) );
		$o_dept->PushWhere ( array ('&&', 'Name', '=',$this->getPost('Name')) );
		$n_count = $o_dept->getAllCount ();
		if ($n_count > 0) {
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('DeptAddError').'\')");' );
		}
		sleep(1);
		$o_dept = new Base_Dept ( $this->getPost ( 'Id' ) );
		$o_dept->setName ($this->getPost('Name'));
		$o_dept->setParentId ( 0 );
		$o_dept->setPhone ($this->getPost('Phone'));
		$o_dept->setFax ($this->getPost('Fax'));
		$o_dept->setAddress ($this->getPost('Address'));
		$o_dept->Save ();
		$this->setReturn ( 'parent.location=\'' . $this->getPost ( 'BackUrl' ) . '\';' );
	}
	public function DeptDelete($n_uid) {
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (! $o_user->ValidModule ( 100103 ))
			return; //如果没有权限，不返回任何值
		//验证有无用户使用这个角色
		$n_id=$this->getPost('id');
		$o_dept = new Base_User_Role ();
		$o_dept->PushWhere ( array ('&&', 'DeptId', '=',$n_id) );
		if ($o_dept->getAllCount () > 0) {
			$a_general = array (
				'success' => 0,
				'text' => Text::Key ( 'DeptDelError' ) 
			);
			echo (json_encode ( $a_general ));
			return;
		}
		sleep(1);
		$o_dept = new Base_Dept ($n_id);
		$o_dept->Deletion ();
		$a_general = array (
			'success' => 1,
			'text' =>''
		);
		echo (json_encode ( $a_general ));
	}
	public function ConfigModify($n_uid) {
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		sleep(1);
		$o_user = new Single_User ( $n_uid );
		if (! $o_user->ValidModule ( 100104 ))return; //如果没有权限，不返回任何值
		$o_sys = new Base_Setup (1);
		if ($_FILES ['Vcl_File'] ['size'] > 0) {
			if ($_FILES ['Vcl_File'] ['size']>(1024*1024))
			{
				$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('PhotoUploadError02').'\')");' );
			}
			mkdir ( RELATIVITY_PATH . 'userdata/logo', 0777 );
			$allowpictype = array ('jpg', 'jpeg', 'gif', 'png' );
			$fileext = strtolower ( trim ( substr ( strrchr ( $_FILES ['Vcl_File'] ['name'], '.' ), 1 ) ) );
			if (! in_array ( $fileext, $allowpictype )) {
				$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('PhotoUploadError').'\')");' );
			}
			$o_sys->setLogo ( 'userdata/logo/logo.' . $fileext );
			copy ( $_FILES ['Vcl_File'] ['tmp_name'], RELATIVITY_PATH . 'userdata/logo/logo.' . $fileext );
		}
		$o_sys->setSystemName ($this->getPost('SystemName'));
		$a_url=parse_url('http://'.$this->getPost('HomeUrl'));
		//判断path最后一位是不是/
		if (substr($a_url['path'], strlen($a_url['path'])-1,1)!='/')
		{
			$a_url['path']=$a_url['path'].'/';
		}
		$o_sys->setHomeUrl('http://'.$a_url['host'].$a_url['path']);
		$o_sys->setFooter ($this->getPost('Footer'));
		$o_sys->Save ();
		$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('SaveSuccess').'\',function(){parent.location.reload()})");' );
	}
}

?>