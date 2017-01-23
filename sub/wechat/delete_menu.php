<?php
define ( 'RELATIVITY_PATH', '../../' );

header('Content-Type: text/html; charset=UTF-8');

include(dirname(__FILE__)."/include/accessToken.class.php");

$token = new accessToken();
$ACC_TOKEN= $token->access_token;


$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$ACC_TOKEN;

$curl = curl_init($MENU_URL);
curl_setopt($curl, CURLOPT_URL, $MENU_URL);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
if (!empty($data)){
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
}
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$info = curl_exec($curl);
$menu = json_decode($info);
print_r($info);		//删除成功返回：{"errcode":0,"errmsg":"ok"}


if($menu->errcode == "0"){
	echo "菜单删除成功";
}else{
	echo "菜单删除失败";
}

?>