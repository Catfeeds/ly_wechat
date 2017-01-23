<?php
define ( 'RELATIVITY_PATH', '../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' ); 

	include(dirname(__FILE__)."/include/paramQrCode.class.php");
	/*
	include(dirname(__FILE__)."/include/paramQrCode.class.php");
	//include(dirname(__FILE__)."/include/paramQrCode.php");
//	require_once '/sub/wechat/include/db_table.class.php';
	include './include/userUtil.php';
	ini_set('display_errors', 'on');
	//$token = new accessToken();
	//echo $token->access_token;
	
	
	$qrCode = new paramQrCode();
	if($qrCode->getQrCode(2)){
			echo "下载成功".$qrCode->filename;
		}else{
			echo "下载失败";
		}	
	$count = 10;
	for ($i=1; $i<$count;$i++){
		if($qrCode->getQrCode($i)){
			echo "第".$i."次下载成功".$qrCode->filename;
			sleep(2);
		}else{
			echo "下载失败";
			sleep(2);
		}	
	}

	$o_userUtil = new userUtil();
	$openId = $o_userUtil->open_id;
	echo "openId=".$openId;
	*/
	$qrCode = new paramQrCode();
	$url = $qrCode->getQrCode($_GET['id']);
	echo $url;
	echo "生成成功".$qrCode->filename."<br/>";
	echo "<img style='style:height:500px;width:500px;' src='".$url."'/>";
	
?>