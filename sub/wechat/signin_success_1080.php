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
//获取用户信息
$o_user_info=new WX_User_Info();
$o_user_info->PushWhere(array("&&", "OpenId", "=", $openId));
if ($o_user_info->getAllCount()==0)
{
	exit(0);
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/weui.min.css"/>
    <script src="js/common.fun.js" type="text/javascript"></script>
    <script src="<?php echo(RELATIVITY_PATH)?>js/bootstrap/js/jquery-2.1.0.min.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>个人中心</title>
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
			.input button
			{
	            color:white;
				font-size:20px;
				text-align:center;
				padding:10px;
				
				border: 0px solid #DDDDDD;
				background-color:#382A5D;
				width:100%;
				font-weight:bold;
				
			}		
	</style>
</head>
<body>
    <div id="page_1">    	
    	<div id="mark" style="height:50px;position:absolute;width:100%;">
    		<div class="btn" style="width:33%;float:left;height:50px" onclick="location='signin_guide_1080.php'">
    		</div>
    		<div class="btn" style="width:34%;float:left;height:50px">
    			<img style="border-radius:60%;width:60%;margin-left:20%" src="<?php echo($o_user_info->getPhoto(0))?>">
    			<div style="overflow:hidden;white-space:nowrap;text-overflow: ellipsis;color:#ffffff;width:100%;text-align:center;font-weight:bold;font-size:20px;margin-top:10px"><?php echo($o_user_info->getNickname(0))?></div>
    		</div>
    		<div class="btn" style="width:33%;float:left;height:50px" onclick="location='signin_prize_1080.php'">
    		</div>
    	</div>
    	<div style="background-color:#2D224A;">
    		<img id="image_top" src="images/activity/1080_ucenter_top.jpg"/> 
    		<img id="image_btn" src="images/activity/1080_ucenter_01.png"/>
    	</div>
    	<div style="height:70px;">
    		<div style="background-color:#D2DEE6;width:33%;float:left;height:70px">
    			<div style="color:#382A5D;text-align:center;font-weight:bold;font-size:30px;"><?php 
    			//获取本次活动积分
    			$o_user_activity=new WX_User_Activity();
				$o_user_activity->PushWhere(array("&&", "ActivityId", "=", 1080));
				$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user_info->getId(0)));
				$count=$o_user_activity->getAllCount();
				if($count>0)
				{
					 echo($o_user_activity->getScore(0));
				}else{
					echo(0);
				}
    			?></div>
    			<div style="color:#382A5D;text-align:center;font-size:12px;">9月26日活动积分</div>
    		</div>
    		<div style="background-color:#D2DEE6;width:34%;float:left;height:70px">
    			<div style="color:#8A8A8A;text-align:center;font-weight:bold;font-size:30px;">0</div>
    			<div style="color:#8A8A8A;text-align:center;font-size:12px;">11月22日活动积分</div>
    		</div>
    		<div style="background-color:#D2DEE6;width:33%;float:left;height:70px">
    			<div style="color:#382A5D;text-align:center;font-weight:bold;font-size:30px;"><?php 
    			if($count>0)
				{
					 echo($o_user_activity->getScore(0));
				}else{
					echo(0);
				}
    			?></div>
    			<div style="color:#382A5D;text-align:center;font-size:12px;">活动总积分</div>
    		</div>
    	</div>
    	<div style="margin-top:0px;background-image:url('images/activity/1080_reg_bj.jpg');background-repeat:no-repeat;background-size:100%;">
	    	<div style="text-align:center;padding-top:80px;font-size:28px;line-height:50px;font-weight:bold;color:black;color:#382A5D;width:100%">
				<?php 
				//获取最后一条记录
				$o_user_training_log=new Wx_User_Training_Hint_Log();
				$o_user_training_log->PushWhere(array("&&", "ActivityId", "=", 1080));
				$o_user_training_log->PushWhere(array("&&", "UserId", "=", $o_user_info->getId(0)));
				$o_user_training_log->PushOrder ( array ('Date','D') );
				$o_user_training_log->getAllCount();
				echo($o_user_training_log->getComment(0));
				?>
				<br/><br/>
			</div>
    	</div>
    	
	</div>
	<script type="text/javascript">
	$(function(){
		var window_width=$(window).width()
		$('#mark').css('margin-top',parseInt(window_width*0.2))//按钮距离顶端距离
		$('#mark .btn').css('height',parseInt(window_width*0.35))//按钮高度
	});
	$(window).resize(function(){
		var window_width=$(window).width()
		$('#mark').css('margin-top',parseInt(window_width*0.2))//按钮距离顶端距离
		$('#mark .btn').css('height',parseInt(window_width*0.35))//按钮高度
	});
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('hideOptionMenu');});
	</script>
</body>
</html>