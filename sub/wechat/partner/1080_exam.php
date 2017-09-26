<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
include '../include/userUtil.php';
include '../include/db_table.class.php';
require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
$o_userUtil = new userUtil();
$openId = $o_userUtil->open_id;
//$openId ='ogCdfwqkTg-t4JPjszNWh2kwbVBw';
$n_activityid=1080;
//获取用户信息
$o_user_info=new WX_User_Info();
$o_user_info->PushWhere(array("&&", "OpenId", "=", $openId));
$o_user_info->getAllCount();
//如果已经答题，那么跳转到个人中心。
$o_answer=new Wx_User_Training_Answers();
$o_answer->PushWhere(array("&&", "UserId", "=", $o_user_info->getId(0)));
$o_answer->PushWhere(array("&&", "ActivityId", "=", $n_activityid));
if ($o_answer->getAllCount()>0)
{
	echo "<script>location.href='../signin_success_".$n_activityid.".php?openid=".$openId."'</script>"; 
	exit(0);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>第一次 随堂测验 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="第一次 随堂测验">
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
			.question .select
			{
				border: 1px solid #E9F6FF;
				margin-top:2px;
			}	
			.question .on
			{
				background-color:#FFFFFF;
				border: 1px solid #382A5D;
			}
			.question .item {
				padding-bottom:0px;	
			}
			.question {
				padding-bottom:0px;	
			}
	</style>
</head>
<body>
<form action="../include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
	<input type="hidden" name="Vcl_ActivityId" id="Vcl_ActivityId" value="<?php echo($n_activityid)?>"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_FunName" value="ExamSubmitForTraining"/>
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
			随堂测验
			<div style="font-size:14px;margin-top:10px;color:#382A5D">共15提，答对一题，+10积分</div>
			</div>
		</div>
		<?php 
		$o_question=new Wx_Activity_Training_Questions();
		$o_question->PushWhere(array("&&", "ActivityId", "=", 1080));
		$o_question->PushOrder ( array ('Number','A') );
		for($i=0;$i<$o_question->getAllCount();$i++)
		{
			?>
			<div class="question">
				<div class="item" style="margin-top:10px;">
				<?php echo($i+1)?>.<?php echo($o_question->getQuestion($i))?>
				</div>
			</div>
			<div class="question" id="question_<?php echo($o_question->getId($i))?>">
			<?php
			$o_option=new Wx_Activity_Training_Options();
			$o_option->PushWhere(array("&&", "QuestionId", "=", $o_question->getId($i)));
			$o_option->PushOrder ( array ('Number','A') );
			for($j=0;$j<$o_option->getAllCount();$j++)
			{
				echo('<div class="select" onclick="exam_selected(this,'.$o_question->getId($i).','.$o_option->getId($j).')">'.$o_option->getNumber($j).'. '.$o_option->getOption($j).'</div>');
			}
			?>
				<input type="hidden" name="Vcl_Question_<?php echo($o_question->getId($i))?>" id="Vcl_Question_<?php echo($o_question->getId($i))?>" value=""/>
			</div>
			<?php
		}
		?>
		<div class="question">
			<div class="input" style="margin-top:10px;padding-bottom:25px;margin-left:8%;width:84%;">
	    	   <button type="button" id="submit_button" onclick="submit_exam_answer()" style="background-position: left center;background-repeat: no-repeat;">提交</button>
	    	</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	function exam_selected(obj,question_id,answer_id)
	{
		$('#question_'+question_id+' .select').removeClass('on');
		$(obj).addClass('on') ;
		document.getElementById('Vcl_Question_'+question_id).value=answer_id;
	}
	function submit_wroning(number)
	{
		dialog_loading_close()
		dialog_show('请完成第'+number+'题后提交！');
	}
	function submit_exam_answer()
	{
		
		dialog_loading()
		document.getElementById('submit_form').submit();
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