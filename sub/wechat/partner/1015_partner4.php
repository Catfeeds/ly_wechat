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
//$openId='';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>有奖问答-第四题 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="有奖问答-第四题">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="../css/weui.min.css"/>
    <script src="../js/common.fun.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="js/function.js" type="text/javascript"></script>
    
	<link rel="stylesheet" href="css/1015_style.css"/>
    <style type="text/css">
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			}
			div
			{
				overflow:hidden;
			}
			img
			{
				width:100%;
			}
			*
			{
				margin: 0px;
				padding: 0px;
			}
	</style>
</head>
<body style="background-color:#F7F7F7">
<form action="../include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
    <input type="hidden" name="Vcl_Answer" id="Vcl_Answer" value=""/>
	<input type="hidden" name="Vcl_Question" id="Vcl_Question" value="4"/>
	<input type="hidden" name="Vcl_Time" id="Vcl_Time" value="1"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_Right" id="Vcl_Right" value="A"/>
	<input type="hidden" name="Vcl_FunName" value="AnswerSubmit"/>
	<div style="background-color:white;">
		<img src="images/1015_logo.jpg"/>
		</div>
	<div class="question" style="background-color:white;">
		<div class="title">
		有奖问答 第四题
		</div>
		<img src="images/1015_question_title.jpg" style=""/> 
		<div class="item">
		PPT中提到的以下特色主题活动可在砂拉越举办的是：
		</div>
	</div>
	<div class="question">
		<div id="A" class="select" onclick="selected(this,'A')" style="margin-top:40px;">A. 猫科主题晚宴</div>
		<div id="B" class="select" onclick="selected(this,'B')">B. 海洋世界</div>
		<div id="C" class="select" onclick="selected(this,'C')">C. 婆罗州雨林晚宴</div>
		<div id="D" class="select" onclick="selected(this,'D')">D. 丰收沙巴</div>
		<div class="input" style="margin-top:22px;padding-bottom:25px;margin-left:8%;width:84%;">
    	   <button type="button" id="submit_button" onclick="submit_answer()" style="background-position: left center;background-repeat: no-repeat;">提交答案</button>
    	</div>
	</div>
</form>
<script type="text/javascript">
<?php 
//如果答过，就不能再答题
$o_user = new WX_User_Info();
$o_user->PushWhere(array("&&", "OpenId", "=", $openId));
if ($o_user->getAllCount()>0)
{
	$o_date = new DateTime ( 'Asia/Chongqing' );
	$s_date=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ) ;
	//查找活动里是否有当前日期的活动
	$o_activity=new WX_Activity();
	$o_activity->PushWhere(array("&&", "ActivityDate", "=", $s_date));
	if ($o_activity->getAllCount()>0)
	{
		$o_user_activity_join=new WX_User_Activity_Join();
		$o_user_activity_join->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
		$o_user_activity_join->PushWhere(array("&&", "ActivityId", "=", $o_activity->getId(0)));
		if ($o_user_activity_join->getAllCount()>0)
		{
			if ($o_user_activity_join->getAnswer4(0)!='0')
			{
				//说明已经答过
				?>
				document.getElementById('submit_button').style.display="none"
				$('.select').removeClass('on');
				document.getElementById("A").onclick=null;
				document.getElementById("B").onclick=null;
				document.getElementById("C").onclick=null;
				document.getElementById("D").onclick=null;
				$('#<?php echo($o_user_activity_join->getAnswer4(0))?>').addClass("on error");
				$('#'+document.getElementById('Vcl_Right').value).addClass("on right");
				<?php
			}
		}
	}else{
		//查找最新的记录
		$o_user_activity_join=new WX_User_Activity_Join();
		$o_user_activity_join->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
		$o_user_activity_join->PushOrder ( array ('Id', 'D') );
		if ($o_user_activity_join->getAllCount()>0)
		{
			if ($o_user_activity_join->getAnswer4(0)!='0')
			{
				//说明已经答过
				?>
				document.getElementById('submit_button').style.display="none"
				$('.select').removeClass('on');
				document.getElementById("A").onclick=null;
				document.getElementById("B").onclick=null;
				document.getElementById("C").onclick=null;
				document.getElementById("D").onclick=null;
				$('#<?php echo($o_user_activity_join->getAnswer4(0))?>').addClass("on error");
				$('#'+document.getElementById('Vcl_Right').value).addClass("on right");
				<?php
			}
		}
	}
}
?>
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