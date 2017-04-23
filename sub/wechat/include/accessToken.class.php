<?php
include(dirname(dirname(__FILE__))."/include/curlUtil.class.php");
include(dirname(dirname(__FILE__))."/include/config.php");

require_once 'db_table.class.php';

class accessToken{
	private $appid = APPID;
	private $appsecret = APPSECRET;
	private $accessToken = "";
	
    public function __construct($appid = NULL, $appsecret = NULL)
    {	
        if($appid){
            $this->appid = $appid;
        }
        if($appsecret){
            $this->appsecret = $appsecret;
        }
        
        $o_syscode = new WX_Syscode();
		$count = $o_syscode->getAllCount();
        $token = $o_syscode->getSysToken(0);
		$curlUtil = new curlUtil();
		$openId = 'ogCdfwqkTg-t4JPjszNWh2kwbVBw';//为了防止accesstoken失效，先获取一个用户的信息，如果获取不成功，说明失效 
		//Access Token时效目前只有7200s，这里是判断超时重新生成新的Token 
		$lasttime = $o_syscode->getCreateDate(0);
		
		//验证token是否有效
		$s_url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openId.'&lang=zh_CN';
		$s_return=$curlUtil->https_request($s_url);
		$a_return=json_decode($s_return, true);
		//如果超时并且	
        if (time() > ($lasttime + 7100) ||$a_return['errcode']=='40001'){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $curlUtil->https_request($url);
            $result = json_decode($res, true);
            $accessToken = $this->access_token = $result["access_token"];
            $Id = $o_syscode->getId(0);
            $o_insert = new WX_Syscode($Id);
            $o_insert->setSysToken($this->access_token);
            $o_insert->setCreateDate(time());
            $o_insert->Save(); 
        }else{
			$accessToken = $this->access_token = $token;
			//var_dump("sadasd".$this->access_token );
		}
   	}
}