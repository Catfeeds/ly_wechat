<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once '../include/db_table.class.php';
//include '../include/userUtil.php';
$n_id=$_GET['id'];
$o_partner=new WX_Activity($n_id);
if ($o_partner->getSceneName()!='2016中国斯堪的纳维亚旅游推介会' || $n_id<8000)
{
	exit(0);
}
//$o_userUtil = new userUtil();
//$openId = $o_userUtil->open_id;
//$openId='';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?php echo($o_partner->getTitle())?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="<?php echo($o_partner->getTitle())?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="stylesheet" href="css/1036_style.css"/>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			}
			
	</style>
</head>
<body>
	<div>
		<img src="images/1036_logo.jpg"/>
	</div>
	<div class="title">
		<?php echo($o_partner->getRegFirst())?><br/>	
		<?php echo($o_partner->getRegRemark());?>
	</div>
	<img src="<?php 
	if ($o_partner->getAddress()!='')
	{
		echo($o_partner->getAddress());
	}else{
		echo($o_partner->getPicUrl());
	}
	?>" style="width:84%;margin-left:8%;margin-top:25px;"/>
	<div class="content">
		<?php echo($o_partner->getRemFirst())?>
	</div>
	<div class="contact">
		网站：<?php echo($o_partner->getAuditFailFirst())?><br/>
		地址：<?php echo($o_partner->getAuditFailRemark())?><br/>
		国家：<?php echo($o_partner->getAuditPassFirst())?><br/>
		城市：<?php echo($o_partner->getAuditPassRemark())?><br/>
		Zip Code：<?php echo($o_partner->getRemRemark())?><br/>
	</div>
<script type="text/javascript">
	//document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});
</script>
</body>
</html>