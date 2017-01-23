<?php
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
class Session extends Bn_Basic {
	private $O_User;
	private $S_UserIp;
	private $S_Agent;
	private $S_Session_Id;
	private $B_Login;
	
	public function __construct() {
		$this->S_Ip = $_SERVER ['REMOTE_ADDR'];
		$this->S_Agent = $_SERVER ['HTTP_USER_AGENT'];
		$o_date = new DateTime ( 'Asia/Chongqing' );
		$n_nowTime = $o_date->format ( 'U' );
		if (isset ( $_COOKIE ['SESSIONID'] )) {
			$this->S_Session_Id = $_COOKIE ['SESSIONID'];
			$uid = $this->FindUserName ( $this->S_Session_Id );
			if ($uid > 0) {
				$n_out_time = 100000000000000;
				$this->N_Uid = $uid;
				$this->O_User = new Single_User ( $uid );
				$this->O_User->setSessionID ( $this->S_Session_Id );
				$n_last_time = $this->O_User->get_Login_LastTime ();
				if (($n_nowTime - $n_last_time) > $n_out_time) {
				
				} else {
					$this->B_Login = true;
					$this->O_User->set_Login_LastTime ( $n_nowTime );
					$this->O_User->Save ();
				}
			} else {
				$this->B_Login = false;
				$this->N_Uid = 0;
			}
		} else {
			//$this->S_Session_Id = md5 ( $this->S_Ip . $this->S_Agent . rand ( 0, 9999 ) . $n_nowTime );
			//$s_username = '游客' . rand ( 0, 9999 );
			//setcookie ( 'VISITER', $s_username, 0 );
			//setcookie ( 'SESSIONID', $this->S_Session_Id, 0 );
			//setcookie ( 'VALIDCODE', '  ', 0 );
			$this->B_Login = false;
		}
	
	}
	public function Login() {
		return $this->B_Login;
	}
	private function FindUserName($s_sessionid) {
		$s_sessionid = str_replace ( '\'', '`', $s_sessionid );
		$o_user = new Base_User_Login ();
		$o_user->PushWhere ( array ('&&', 'SessionId', '=', $s_sessionid ) );
		//两个session都可以.只要满足一个
		$o_user->setItem ( array ('Uid' ) );
		if ($o_user->getAllCount () > 0) {
			return $o_user->getUid ( 0 );
		} else {
			return 0;
		}
	}
	public function ValidModuleForPage($n_module_id) {
		$o_userModule = new View_User_Right ();
		$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->N_Uid) );
		$o_userModule->PushWhere ( array ('&&', 'ModuleId', '=', $n_module_id ) );
		$n_count=$o_userModule->getAllCount ();
		for($i = 1; $i <= 5; $i ++) {
			eval ( '$o_userModule = new View_User_Right_Sec' . $i . ' ();' );
			$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->N_Uid) );
			$o_userModule->PushWhere ( array ('&&', 'ModuleId', '=', $n_module_id ) );
			$n_count = $o_userModule->getAllCount ()+$n_count;
		}
		if ($n_count==0){
			echo('
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<title></title>
					<link href="../../theme/default/style.css" rel="stylesheet"
						type="text/css" />
					<link href="css/style.css" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="js/control.fun.js"></script>
					<script type="text/javascript" src="js/ajax.fun.js"></script>
					<script type="text/javascript" src="../../js/ajax.class.js"></script>
					<script type="text/javascript" src="../../js/dialog.fun.js"></script>
					<script type="text/javascript" src="../../js/common.fun.js"></script>
					</head>
					<body class="bodycolor" topmargin="50">
						<table class="MessageBox" align="center" width="350">
						   <tbody><tr class="head-no-title">
						      <td class="left"></td>
						      <td class="center">
						      </td>
						      <td class="right"></td>
						   </tr>
						   <tr class="msg">
						      <td class="left"></td>
						      <td class="center info">
						         <div class="msg-content">对不起<br/>没有权限访问该功能！</div>
						      </td>
						      <td class="right"></td>
						   </tr>
						   <tr class="foot">
						      <td class="left"></td>
						      <td class="center"></td>
						      <td class="right"></td>
						   </tr>
							</tbody>
						</table>
					</body>
					</html>');
			exit(0);
		}
	}
	public function getUid() {
		return $this->N_Uid;
	}
	public function getUserObject() {
		return $this->O_User;
	}
}
?>