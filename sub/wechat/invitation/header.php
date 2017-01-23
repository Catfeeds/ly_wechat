<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once '../include/db_table.class.php';
require_once '../include/db_table.class.php';
include '../include/accessToken.class.php';
//报名页面需要验证是否已经关注微信号，如果没有关注，需要跳转到邀请函，进行二维码扫描。
$o_token = new accessToken ();
$s_token = $o_token->access_token;
$jsapiTicket =getJsApiTicket($s_token);
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$timestamp = time();
$nonceStr =createNonceStr();

$string = 'jsapi_ticket='.$jsapiTicket.'&noncestr='.$nonceStr.'&timestamp='.$timestamp.'&url='.$url;
$signature = sha1($string);
function createNonceStr($length = 16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for($i = 0; $i < $length; $i ++) {
		$str .= substr ( $chars, mt_rand ( 0, strlen ( $chars ) - 1 ), 1 );
	}
	return $str;
}
function getJsApiTicket($s_token) {
	$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$s_token;
	$o_util=new curlUtil();
	$s_return=$o_util->https_request($url);
	$res=json_decode($s_return, true);
	//var_dump($res);
	return $res['ticket'];
}
?>