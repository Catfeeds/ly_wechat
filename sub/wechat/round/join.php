<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );

//判断是否有抽奖资格
require_once '../include/db_table.class.php';
include '../include/userUtil.php';
include '../include/accessToken.class.php';
$o_userUtil = new userUtil();
$openId = $o_userUtil->open_id;
//echo($openId);
//查找用户信息
$o_user = new WX_User_Info();
$o_user->PushWhere(array("&&", "OpenId", "=", $openId));
$o_user->getAllCount();
//获取活动Id
$o_date = new DateTime ( 'Asia/Chongqing' );
$s_date=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ) ;//获取当前日期
$s_date='2017-02-27';
$o_activity=new WX_Activity();
$o_activity->PushWhere(array("&&", "ActivityDate", "=",$s_date));
$o_activity->getAllCount();

//获取用户奖池
$o_round=new WX_User_Activity();
$o_round->PushWhere(array("&&", "ActivityId", "=",$o_activity->getId(0)));
$o_round->PushWhere(array("&&", "UserId", "=",$o_user->getId(0)));
$o_round->getAllCount(); 
$s_text='恭喜您，已进入摇奖池！';
if ($o_user->getAllCount()>0)
{
	$o_temp=new WX_User_Activity_Join();
	$o_temp->setActivityId($o_activity->getId(0));
	$o_temp->setUserId($o_user->getId(0));
	$o_temp->setRound1(1);
	$o_temp->Save();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="../css/weui.min.css"/>
    <script src="../js/common.fun.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>参与抽奖</title>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			}
			form div
			{
				overflow:hidden;
				width:100%;
			}
			img
			{
				width:100%;
			}
			*
			{
				margin: 0px;
				padding: 0px;
				color:#0C1F3F;
			}
			.input
			{
	           text-align:center;
				padding-top:20px;
				border-bottom: 0px solid #DDDDDD;
			}
			.line
			{
	            height:5px;
				width:90%;
				margin-left:5%;
				border-bottom: 1px solid #0C1F3F;
				border-left: 1px solid #0C1F3F;
				border-right: 1px solid #0C1F3F;
			}
			
			.input input
			{
	            color:#0C1F3F;
				font-size:18px;
				text-align:center;
				border: 0px solid #DDDDDD;
				background-color:white;
				width:90%;
				border-radius: 0px;
			}
			.input input::-webkit-input-placeholder{ 
			    color:#0C1F3F;
			}
			.input input:-moz-placeholder{ 
			     color:#0C1F3F;
			}
			.input input::-moz-placeholder{ 
			    color:#0C1F3F;
			 }
			 .input input:-ms-input-placeholder{ 
			     color:#0C1F3F;
			}
            .input button
			{
	            color:white;
				font-size:20px;
				text-align:center;
				padding:10px;
				
				border: 0px solid #DDDDDD;
				background-color:#27BEED;
				width:100%;
				font-weight:bold;
				
			}
			.item{
				width:100%;
				font-size:18px;
			}
			.item .detail{
				width:100%;
				font-size:16px;
				color:#9F9F9F;
				text-align:center;
				padding-top:10px;
			}
			.item .box{
				width:33%;
				float:left;
				margin-top:25px;
			}
			.item .box div{
				float:left;
			
			}
			.item .box .box1{
				border: 1px solid #0C1F3F;
				height:18px;
				width:18px;
				margin-top:4px;
				margin-left:24%;			
			}
			.item .box .on{
				background-color:#0C1F3F;
				height:12px;
				width:12px;
				margin:3px;		
			}
			.weui_dialog_loading
			{
				font-weight:bold;
				color:white;
				font-size:20px;
			}
	</style>
</head>
<body>
    <div id="page_1">
        <div>
    		<img src="../images/activity/1036_sign_top.jpg"/>
    	</div>
    	<p style="text-align:center;padding-top:60px;font-size:25px;font-weight:bold;color:black;color:#72A9A9;">
			<?php echo($s_text)?>
		</p>
	</div>
	<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});
	</script>
</body>
</html>