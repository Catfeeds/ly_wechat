<?php
define ( 'RELATIVITY_PATH', '../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/weui.min.css"/>
    <script src="js/common.fun.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>活动说明</title>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			}
			form div
			{
				overflow:hidden;
				width:100%;
				color:#026EB9;
			}
			img
			{
				width:100%;
			}
			*
			{
				margin: 0px;
				padding: 0px;
				color:#026EB9;
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
				border-bottom: 1px solid #026EB9;
				border-left: 1px solid #026EB9;
				border-right: 1px solid #026EB9;
			}
			
			.input input
			{
	            color:#026EB9;
				font-size:18px;
				text-align:center;
				border: 0px solid #DDDDDD;
				background-color:white;
				width:90%;
				border-radius: 0px;
			}
			.input input::-webkit-input-placeholder{ 
			    color:#026EB9;
			}
			.input input:-moz-placeholder{ 
			     color:#026EB9;
			}
			.input input::-moz-placeholder{ 
			    color:#026EB9;
			 }
			 .input input:-ms-input-placeholder{ 
			     color:#026EB9;
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
				border: 1px solid #026EB9;
				height:18px;
				width:18px;
				margin-top:4px;
				margin-left:24%;			
			}
			.item .box .on{
				background-color:#026EB9;
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
    		<img src="images/activity/1001_reg_top.png"/>
    	</div>
    	<p style="text-align:center;padding-top:10px;font-size:25px;font-weight:bold">
			互动参与步骤
		</p>
		<div style="margin-left:8%;padding-top:20px;margin-right:8%;width:auto;color:#6b6b6b;font-size:16px;line-height:25px;">
1. 与签到台旁迪拜邮轮景观合影留念。<br/>
2. 将照片传至微信朋友圈。<br/>
3. 将页面截图并发送至签到后台公众号参与抽奖。<br/><br/>
大奖会在下午五点抽出(如有任何疑问，可咨询现场工作人员)
	</div>
	<div style="margin-left:8%;padding-top:40px;margin-right:8%;width:auto;color:#6b6b6b;font-size:14px;line-height:25px;text-align:right">
迪拜旅游局
	</div>
	</div>
	<script type="text/javascript">
		document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});
	</script>
</body>
</html>