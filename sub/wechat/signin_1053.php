<?php
define ( 'RELATIVITY_PATH', '../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once './include/db_table.class.php';
$openId = "";
if(!empty($_GET['openid']))
{
    $openId = $_GET['openid'];
}
$sceneId = "";
if(!empty($_GET['id']))
{
    $sceneId = $_GET['id'];
}
//获取用户信息
$o_user_info=new WX_User_Info();
$o_user_info->PushWhere(array("&&", "OpenId", "=", $openId));
$o_user_info->getAllCount();

$a_act1=new WX_Activity($sceneId);
$o_user_activity=new WX_User_Activity();
$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $sceneId));
$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user_info->getId(0)));
$count=$o_user_activity->getAllCount();
if($o_user_activity->getSigninFlag(0)==1)
{
	//如果已经签到成功，那么要看是否是现场报名，如果现场报名，那么跳转到reg页面
	if($o_user_activity->getOnsiteFlag(0)==1)
	{
		echo "<script>location.href='signin_success_reg_".$_GET['sceneid'].".php'</script>"; 
		exit(0);
	}else{
		echo "<script>location.href='signin_success_".$_GET['sceneid'].".php'</script>"; 
		exit(0);
	}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/weui.min.css"/>
    <script src="js/common.fun.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>微信签到</title>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			}
			form div
			{
				overflow:hidden;
				width:100%;
				color:#0C1F3F;
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
			    color:#737373;
			}
			.input input:-moz-placeholder{ 
			     color:#737373;
			}
			.input input::-moz-placeholder{ 
			    color:#737373;
			 }
			 .input input:-ms-input-placeholder{ 
			     color:#737373;
			}
            .input button
			{
	            color:white;
				font-size:20px;
				text-align:center;
				padding:10px;
				
				border: 0px solid #DDDDDD;
				background-color:#0E1E3F;
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
<form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
    <input type="hidden" name="Vcl_Id" id="Vcl_Id" value="<?php echo $sceneId;?>"/>
    <input type="hidden" name="Vcl_SceneId" id="Vcl_SceneId" value="<?php echo $_GET['sceneid'];?>"/>
    <input type="hidden" name="Vcl_OpenId" id="Vcl_OpenId" value="<?php echo $openId;?>"/>
    <input type="hidden" name="Vcl_Url" id="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
	<input type="hidden" name="Vcl_FunName" value="SigninFor1055"/>
    <div id="page_1">
        <div>
    		<img src="images/activity/1053_reg_top.jpg"/>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="手机号码" name="Vcl_Phone" id="Vcl_Phone" value=""/>
    	   <div class="line"></div>
    	</div>
    	<p style="text-align:center;padding-top:20px;font-size:14px;font-weight:bold">
			请填写注册时手机号码确认签到！
		</p>
    	<div class="input" style="margin-top:10px;padding-bottom:25px;margin-left:5%;width:90%;">
    	   <button type="button" onclick="submit_info()" style="background-position: left center;background-repeat: no-repeat;">确认签到</button>
    	</div>
	</div>
</form>
<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});

	function submit_info()
	{
		if (del_trim(document.getElementById("Vcl_Phone").value)=='')
		{
			dialog_show('请填写手机');
			return;
		}else if(isMobile(del_trim(document.getElementById("Vcl_Phone").value))==false)
		{
			dialog_show('请填写正确的手机号');
			return;
		}
		//dialog_success()
		dialog_loading()
		document.getElementById('submit_form').submit();
		
	}
	function submit_success()
	{
		var url=document.getElementById("Vcl_Url").value
		location=url+'signin_success_<?php echo $_GET['sceneid'];?>.php';
	}
	function submit_goto_reg()
	{
		dialog_loading_close()
		dialog_show_reg('对不起，您之前没有注册过，需要完善信息。');
	}
	function submit_goto_reg_action()
	{
		var url=document.getElementById("Vcl_Url").value
		location=url+'signin_<?php echo $_GET['sceneid'];?>_reg.php?openid=<?php echo $_GET['openid'];?>&id=<?php echo $sceneId;?>&sceneid=<?php echo $_GET['sceneid'];?>&phone='+document.getElementById("Vcl_Phone").value;
	}
	function dialog_show_reg(text)
	{
		document.getElementById("massagebox_text_reg").innerHTML=text
		//window.alert(text)
		document.getElementById("massagebox_reg").style.display="block"
	}
	function close_window()
	{
		document.addEventListener("WeixinJSBridgeReady", WeixinJSBridge.call("closeWindow"));
	}
	</script>
<div class="weui_dialog_alert" id="massagebox" style="display:none">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">系统提示</strong></div>
        <div class="weui_dialog_bd" id="massagebox_text"></div>
        <div class="weui_dialog_ft">
            <a href="javascript:dialog_close();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<div class="weui_dialog_alert" id="massagebox_reg" style="display:none">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">系统提示</strong></div>
        <div class="weui_dialog_bd" id="massagebox_text_reg"></div>
        <div class="weui_dialog_ft">
            <a href="javascript:submit_goto_reg_action();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<div class="weui_dialog_alert" id="massagebox_closewindow" style="display:none">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">系统提示</strong></div>
        <div class="weui_dialog_bd" id="massagebox_text_close"></div>
        <div class="weui_dialog_ft">
            <a href="javascript:close_window();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<div class="weui_dialog_alert" id="loading" style="display:none">
    <div class="weui_mask"></div>
    
    <div class="weui_dialog_loading">
    <img src="images/loading.gif" style="width:30px;height:30px;"/>
    </div>
</div>
<iframe id="submit_form_frame" name="submit_form_frame" src="about:blank" style="display:none"></iframe>
</body>
</html>