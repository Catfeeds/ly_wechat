<?php
//error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
class Operate extends Bn_Basic {
	
	public function InfoModify($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		sleep(1);
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100301 ))return; //如果没有权限，不返回任何值

		//用户不可修改的信息
		$o_user_info = new Base_User_Info ($n_uid);
		$o_user_info->setName ( $this->getPost ( 'Name' ) );
		$o_user_info->setSex ( $this->getPost ( 'Sex' ));
		$o_user_info->setPhone ( $this->getPost ( 'Phone' ));
		$o_user_info->setEmail ( $this->getPost ( 'Email' ));
		$o_user_info->Save ();
		//用户可修改的信息
		$o_user_info_custom = new Base_User_Info_Custom ($n_uid);
		$o_user_info_custom->setBirthday ( $this->getPost('Birthday') );
		$o_user_info_custom->Save ();
		$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('SaveSuccess').'\')");' );
	}
	public function PhotoModify($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100302 ))return; //如果没有权限，不返回任何值
		if ($_FILES ['Vcl_File'] ['size'] > 0) {
			if ($_FILES ['Vcl_File'] ['size']>(1024*1024))
			{
				$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('PhotoUploadError02').'\')");' );
			}
			mkdir ( RELATIVITY_PATH . 'userdata/photo', 0777 );
			mkdir ( RELATIVITY_PATH . 'userdata/photo/'.md5($n_uid.'ctisss'), 0777 );
			$allowpictype = array ('jpg', 'jpeg', 'gif', 'png' );
			$fileext = strtolower ( trim ( substr ( strrchr ( $_FILES ['Vcl_File'] ['name'], '.' ), 1 ) ) );
			if (! in_array ( $fileext, $allowpictype )) {
				$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('PhotoUploadError').'\')");' );
			}
			sleep(1);
			$o_user=new Base_User_Photo($n_uid);
			$o_user->setPath ( 'userdata/photo/' . md5($n_uid.'ctisss') . '/photo.' . $fileext );
			$o_user->Save();
			copy ( $_FILES ['Vcl_File'] ['tmp_name'], RELATIVITY_PATH . 'userdata/photo/' . md5($n_uid.'ctisss') . '/photo.' . $fileext );
			$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('PhotoUploadSuccess').'\',function(){location.reload()})");' );
		}else{
			$this->setReturn ( 'parent.form_return("dialog_message(\''.Text::Key('PhotoUploadMessage').'\')");' );
		}
	}
	public function PasswordModify($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100303 ))return; //如果没有权限，不返回任何值
		$o_user = new Base_User ( $n_uid );
		if (md5 ( 'welcome ' . $_POST ['Vcl_OldPassword'] . ' to 教育城域网综合管理信息系统 !' ) == $o_user->getPassword ()) { //密码输入正确
			$o_user->setPassword ( md5 ( 'welcome ' . $_POST ['Vcl_Password'] . ' to 教育城域网综合管理信息系统 !' ) );
			$o_user->Save();
			$this->setReturn ( 'parent.form_return("dialog_success(\''.Text::Key('ResetPasswordSuccess').'\',function(){location.reload()})");' );
		} else {
			$this->setReturn ( 'parent.form_return("dialog_error(\''.Text::Key('ResetPasswordError').'\')");' );
		}
	}
}

?>