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
$o_user_info=new WX_User_Info();
$o_user_info->PushWhere(array("&&", "OpenId", "=", $openId));
$o_user_info->getAllCount();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/weui.min.css"/>
    <script src="js/common.fun.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>微信报名</title>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
				background-color:#F5F5F5;
			}
			form div
			{
				overflow:hidden;
				width:100%;
				color:#73A9A9;
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
	            color:#000000;
				font-size:18px;
				text-align:center;
				border: 1px solid #BBBBBB;
				background-color:#F2F0F1;
				padding-top:8px;
				padding-bottom:10px;
				width:90%;
				border-radius: 0px;
				-webkit-appearance: none;
			    -webkit-rtl-ordering: none;
			}
            .input button
			{
	            color:white;
				font-size:20px;
				text-align:center;
				padding:10px;
				
				border: 0px solid #DDDDDD;
				background-color:#E40521;
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
				color:#72A9A9;
				text-align:center;
				padding-top:10px;
			}
			.item .box{
				width:19%;
				float:left;
				margin-top:25px;
				margin-left:2%;
				margin-right:2%;
				border: 3px solid #FED43A;
			}
			.item .box div{
				float:left;
			}
			.item .box1{
				width:100%;
				text-align:center;
				color:#464646;
				font-size:14px;
				line-height:16px;
				padding-top:5px;
				padding-bottom:5px;
			}
			.item .on{
				background-color:#FED43A;	
				width:19%;
				float:left;
				margin-top:25px;
				margin-left:2%;
				margin-right:2%;
				border: 3px solid #FED43A;
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
    <input type="hidden" name="Vcl_Id" id="Vcl_Id" value=""/>
    <input type="hidden" name="Vcl_OpenId" id="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_FunName" value="Register"/>
    <div id="page_1">
        <div>
    		<img src="images/activity/1044_reg_top.jpg"/>
    	</div>
		<div class="item">
			<div id="1044" class="box" onclick="change_location(1044)">
				<div class="box1">
				2月27日<br/>
					南京站
				</div>
			</div>
			<div id="1045" class="box" onclick="change_location(1045)">
				<div class="box1">
				2月28日<br/>
					杭州站
				</div>
			</div>
			<div id="1046" class="box" onclick="change_location(1046)">
				<div class="box1">
				3月2日<br/>
					重庆站
				</div>
			</div>
			<div id="1047" class="box" onclick="change_location(1047)">
				<div class="box1">
				3月3日<br/>
					成都站
				</div>
			</div>
		</div>
		
    	<div class="input" style="margin-top:0px;">
    	   <input type="text" placeholder="公司名称（中文）" name="Vcl_Company" id="Vcl_Company" value="<?php echo($o_user_info->getCompany(0))?>"/>
    	</div>
    	<div class="input" style="margin-top:0px;">
    	   <input type="text" placeholder="公司名称（英文）" name="Vcl_CompanyEn" id="Vcl_CompanyEn" value="<?php echo($o_user_info->getCompanyEn(0))?>"/>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="职务" name="Vcl_DeptJob" id="Vcl_DeptJob" value="<?php echo($o_user_info->getDeptJob(0))?>"/>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="姓名" name="Vcl_Name" id="Vcl_Name" value="<?php echo($o_user_info->getUserName(0))?>"/>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="手机号" name="Vcl_Phone" id="Vcl_Phone" value="<?php echo($o_user_info->getPhone(0))?>"/>
    	</div>
    	<div class="input">
    	   <input type="text" placeholder="邮箱" name="Vcl_Email" id="Vcl_Email" value="<?php echo($o_user_info->getEmail(0))?>"/>
    	</div>
    	<div class="input" style="margin-top:22px;padding-bottom:25px;margin-left:5%;width:90%;">
    	   <button type="button" onclick="submit_info()">立刻提交</button>
    	</div>
	</div>
</form>
<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});
	function change_location(id)
	{
		document.getElementById('1044').className="box";
		document.getElementById('1045').className="box";
		document.getElementById('1046').className="box";
		document.getElementById('1047').className="box";
		document.getElementById(id).className="on";
		document.getElementById('Vcl_Id').value=id;
	}
	function submit_info()
	{
		if (del_trim(document.getElementById("Vcl_Id").value)=='')
		{
			dialog_show("请选择地点")
			return;
		}
		if (del_trim(document.getElementById("Vcl_Company").value)=='')
		{
			dialog_show("请填写公司名称（中文）")
			return;
		}
		if (del_trim(document.getElementById("Vcl_CompanyEn").value)=='')
		{
			dialog_show("请填写公司名称（英文）")
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
		parent.dialog_close();
		dialog_success();
	}
	function close_window()
	{
		document.addEventListener("WeixinJSBridgeReady", WeixinJSBridge.call("closeWindow"));
	}
	</script>
<div class="weui_dialog_alert" id="massagebox" style="display:none">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title" style="color:#73A9A9">系统提示</strong></div>
        <div class="weui_dialog_bd" id="massagebox_text"></div>
        <div class="weui_dialog_ft">
            <a href="javascript:dialog_close();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<div class="weui_dialog_alert" id="loading" style="display:none">
    <div class="weui_mask"></div>
    <div class="weui_dialog_loading">
    <img src="images/loading.gif" style="width:30px;height:30px;"/>
    </div>
</div>
<div class="weui_dialog_alert" id="success" style="display:none">
    <div class="weui_mask" style="background:rgba(228,5,33,.9)"></div>
    <div class="weui_dialog_loading">
    <div onclick="close_window()"><img src="images/activity/1036_box_icon_01.png" style="width:12%;"/></div>
    <br/>
    <br/>
    <br/>
    <br/> 
    你的报名信息已收到，审核后，<br/>
    我们会尽快回复您是否报名成功。<br/><br/>
    谢谢您对德国国家旅游局<br/>
  2017春季旅游推介会支持和关注！<br/><br/>
    </div>
</div>
<iframe id="submit_form_frame" name="submit_form_frame" src="about:blank" style="display:none"></iframe>
</body>
</html>