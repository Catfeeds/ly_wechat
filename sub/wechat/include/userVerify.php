<?php
//include(dirname(dirname(__FILE__))."/include/config.php");
require_once RELATIVITY_PATH . 'sub/wechat/include/config.php';
class userVerify
{
	const OAUTH2 = 'https://open.weixin.qq.com/connect/oauth2';
	const AUTHOR = '/authorize?';
	const APIBA = 'https://api.weixin.qq.com'; 
	const OAUTHTO = '/sns/oauth2/access_token?';

	private $user_token;
	private $appid = APPID;
	private $appsecret = APPSECRET;
	
	public function __construct($appid = NULL, $appsecret = NULL)
	{
//		$this->appid = "wx4e1053bfbcf434f1";
//		$this->appsecret = "b5f2e15dbfa08451c202d415d86ec951";
		if($appid){
            $this->appid = $appid;
        }
        if($appsecret){
            $this->appsecret = $appsecret;
        }
	}

	private function http_get($url){
		$oCurl = curl_init();
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); 
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}
	
	public function getOauthRedirect($callback,$state='',$scope='snsapi_userinfo'){
		return self::OAUTH2.self::AUTHOR.'appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
	}
	
	public function getOauthAccessToken(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if (!$code) return false;
		$result = $this->http_get(self::APIBA.self::OAUTHTO.'appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code');
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			}
			$this->user_token = $json['access_token'];
			return $json;
		}
		return false;
	}
}
