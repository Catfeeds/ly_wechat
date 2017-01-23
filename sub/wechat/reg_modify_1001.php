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
if ($o_user_info->getAllCount()==0)
{
	exit();
}
$o_user_activity=new WX_User_Activity();
$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $sceneId));
$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user_info->getId(0)));
if ($o_user_activity->getAllCount()==0)
{
	exit();
}
$a_act1=new WX_Activity($sceneId);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/weui.min.css"/>
    <script src="js/common.fun.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>修改报名信息</title>
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
<form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
    <input type="hidden" name="Vcl_Id" id="Vcl_Id" value="<?php echo $sceneId;?>"/>
    <input type="hidden" name="Vcl_OpenId" id="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_FunName" value="Modify"/>
    <div id="page_1">
        <div>
    		<img src="images/activity/1001_reg_top.png"/>
    	</div>
		<p style="text-align:center;padding-top:10px;font-size:22px;font-weight:bold">
			修改报名信息
		</p>
		<div class="item">
			<div class="detail" id="detail">
			<?php echo($a_act1->getLocation().'站：'.$a_act1->getActivityDate().'（周'.$a_act1->getWeek().'）'.$a_act1->getActivityTime());?>
			</div>
		</div>
    	<div class="input" style="margin-top:0px;">
    	   <input type="text" placeholder="公司名称" name="Vcl_Company" id="Vcl_Company" value="<?php echo($o_user_info->getCompany(0))?>"/>
    	   <div class="line"></div>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="职务" name="Vcl_DeptJob" id="Vcl_DeptJob" value="<?php echo($o_user_info->getDeptJob(0))?>"/>
    	   <div class="line"></div>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="姓名" name="Vcl_Name" id="Vcl_Name" value="<?php echo($o_user_info->getUserName(0))?>"/>
    	   <div class="line"></div>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="手机号" name="Vcl_Phone" id="Vcl_Phone" value="<?php echo($o_user_info->getPhone(0))?>"/>
    	   <div class="line"></div>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="邮箱" name="Vcl_Email" id="Vcl_Email" value="<?php echo($o_user_info->getEmail(0))?>"/>
    	   <div class="line"></div>
    	</div>
    	<div class="input" style="margin-top:22px;padding-bottom:25px;margin-left:5%;width:90%;">
    	   <button type="button" onclick="submit_info()" style="background-image:url('images/activity/1001_but_icon_01.png');background-position: left center;background-repeat: no-repeat;">提交</button>
    	</div>
	</div>
</form>
<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});

	function submit_info()
	{
		if (del_trim(document.getElementById("Vcl_Company").value)=='')
		{
			dialog_show("请填写公司名称")
			return;
		}
		if (del_trim(document.getElementById("Vcl_DeptJob").value)=='')
		{
			dialog_show('请填写职务');
			return;
		}
		if (del_trim(document.getElementById("Vcl_Name").value)=='')
		{
			dialog_show('请填写姓名');
			return;
		}
		if (del_trim(document.getElementById("Vcl_Phone").value)=='')
		{
			dialog_show('请填写手机');
			return;
		}else if(isMobile(del_trim(document.getElementById("Vcl_Phone").value))==false)
		{
			dialog_show('请填写正确的手机号');
			return;
		}
		if (del_trim(document.getElementById("Vcl_Email").value)=='')
		{
			dialog_show('请填写邮箱');
			return;
		} if(isEmail(del_trim(document.getElementById("Vcl_Email").value))==false)
		{
			dialog_show('请填写正确的邮箱');
			return;
		}
		//dialog_success()
		dialog_loading()
		document.getElementById('submit_form').submit();
		
	}
	function submit_success()
	{
		dialog_close()
		dialog_show_closewindow('修改报名信息成功');
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