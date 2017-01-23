<?php

header("Content-Type: text/html; charset=UTF-8");
include("userVerify.php");
class userUtil {
	public $open_id;
	public function __construct(){
		session_start();
		
		$this->userUtil();
		//session_start();
		session_unset();
		session_destroy();
	}
	
	public function userUtil(){
		$scope = 'snsapi_base';
		$code = isset($_GET['code'])?$_GET['code']:'';
		$token_time = isset($_SESSION['token_time'])?$_SESSION['token_time']:0;
		if(!$code && isset($_SESSION['open_id']) && isset($_SESSION['user_token']) && $token_time>time()-3600)
		{
			$this->open_id=$_SESSION['open_id'];
			return $this->open_id;
		}
		else
		{
			$we_obj = new userVerify();
			if ($code) {
				$json = $we_obj->getOauthAccessToken();
				if (!$json) {
					//unset($_SESSION['wx_redirect']);
					die('');
				}
				$_SESSION['open_id'] = $this->open_id = $json["openid"];
				$access_token = $json['access_token'];
				$_SESSION['user_token'] = $access_token;
				$_SESSION['token_time'] = time();
				

				return $this->open_id;
				//$scope = 'snsapi_userinfo';
			}
			if ($scope=='snsapi_base') {
				$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				//$_SESSION['wx_redirect'] = $url;
			}
			$oauth_url = $we_obj->getOauthRedirect($url,"wxbase",$scope);
			header('Location: ' . $oauth_url);
		}
	}
}
