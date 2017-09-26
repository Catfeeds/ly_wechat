<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
include '../include/userUtil.php';
include '../include/db_table.class.php';
$o_userUtil = new userUtil();
$openId = $o_userUtil->open_id;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>抢答题 第三题 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="抢答题 第三题">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="../css/weui.min.css"/>
    <script src="../js/common.fun.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap/js/jquery-2.1.0.min.js" type="text/javascript"></script>
    <script src="js/function.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/1080_style.css"/>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
				background-color:#E9F6FF;
			}
			form div
			{
				width:100%;
				color:#72A9A9;
			}
			img
			{
				width:100%;
			}
			*
			{
				margin: 0px;
				padding: 0px;
				color:#72A9A9;
			}			
	</style>
</head>
<body>
<form action="../include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
    <input type="hidden" name="Vcl_Answer" id="Vcl_Answer" value=""/>
	<input type="hidden" name="Vcl_Question" id="Vcl_Question" value="3"/>
	<input type="hidden" name="Vcl_ActivityId" id="Vcl_ActivityId" value="1080"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_Right" id="Vcl_Right" value="C"/>
	<input type="hidden" name="Vcl_FunName" value="AnswerSubmitForTraining"/>
	<input type="hidden" name="Vcl_Url" id="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
	<div>
		<img src="../images/activity/1080_ucenter_top.jpg"/>
		</div>
	<div style="margin-top:0px;background-image:url('../images/activity/1080_reg_bj.jpg');background-repeat:no-repeat;background-size:100%;">
		<div class="question">
			<div class="title">
			Q&A
			</div>
			<div class="title" style="font-size:28px">
			现场抢答
			<div style="font-size:14px;margin-top:10px;color:#382A5D">抢答成后，+20积分</div>
			</div>
			<div class="item" style="margin-top:10px;">
			3.<br/>
			以下爱尔兰哪座城市与中国上海为友好城市？
			</div>
		</div>
		<div class="question">
			<div id="A" class="select" onclick="selected(this,'A')" style="margin-top:0px">A. 都柏林</div>
			<div id="B" class="select" onclick="selected(this,'B')">B. 戈尔韦</div>
			<div id="C" class="select" onclick="selected(this,'C')">C. 科克</div>
			<div id="D" class="select" onclick="selected(this,'D')">D. 利默里克</div>
			<div class="input" style="margin-top:10px;padding-bottom:25px;margin-left:8%;width:84%;">
	    	   <button type="button" id="submit_button" onclick="submit_answer()" style="background-position: left center;background-repeat: no-repeat;">提交</button>
	    	</div>
		</div>
	</div>
	
</form>
<script type="text/javascript">
	function submit_wroning()
	{
		dialog_loading_close()
		dialog_show_closewindow('对不起，只有签到人员才能参与抢答！');
	}
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});
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
    <img src="../images/loading.gif" style="width:30px;height:30px;"/>
    </div>
</div>
<iframe id="submit_form_frame" name="submit_form_frame" src="about:blank" style="display:none"></iframe>
</body>

</html>