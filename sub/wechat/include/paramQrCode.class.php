<?php
include 'accessToken.class.php';

class paramQrCode{
	
	public function getQrCode($scene){
		$curlUtil = new curlUtil();
		
		$ticket = $this->getTicket($scene);
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
		//$imageInfo = $curlUtil->downloadWxFile($url);
		$filename = "qrcode".date('YmdHis').".jpg";
		$this->filename = $filename;
		return $url;
		/*
		$local_file = fopen("./images/".$filename, 'w');
		if(false !== $local_file){
			if(false !== fwrite($local_file, $imageInfo["body"])){
				fclose($local_file);
				return true;
			}
		}*/
	}
	
	public function getTicket($scene){
		$token = new accessToken();
		//echo "Token=".$token."<br/>";
		$curlUtil = new curlUtil(); //accessToken.php中已经引入
		
		//获取永久
		$qrCode = '{
			"action_name": "QR_LIMIT_STR_SCENE", 
			"action_info": {
				"scene": {"scene_str": "'.$scene.'"}}
		}';
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token->access_token;
		$result = $curlUtil->https_request($url, $qrCode);
		$jsoninfo = json_decode($result, true);
		$ticket = $jsoninfo["ticket"];
		return $ticket;
	}
	
	public function getTempTicket($scene){
		$token = new accessToken();
		$curlUtil = new curlUtil(); //accessToken.php中已经引入
		
		//获取临时，最长有效期30天
		$qrCode = '{
			"expire_seconds": 2592000, 
			"action_name": "QR_SCENE", 
			"action_info": {
				"scene": {"scene_id": 123}}
		}';
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token->access_token;
		$result = $curlUtil->https_request($url, $qrCode);
		$jsoninfo = json_decode($result, true);
		$ticket = $jsoninfo["ticket"];
		return $ticket;
	}
	
}

?>