<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
include '../include/userUtil.php';
$o_userUtil = new userUtil();
$openId = $o_userUtil->open_id;
//$openId='';

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>2016迪拜邮轮中国路演-地中海邮轮 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="地中海邮轮">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="../css/weui.min.css"/>
    <script src="../js/common.fun.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="js/function.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/style.css"/>
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
<body>
<form action="../include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
    <input type="hidden" name="Vcl_Answer" id="Vcl_Answer" value=""/>
	<input type="hidden" name="Vcl_Question" value="3"/>
	<input type="hidden" name="Vcl_Time" id="Vcl_Time" value="1"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_Right" id="Vcl_Right" value="A"/>
	<input type="hidden" name="Vcl_FunName" value="AnswerSubmit"/>
	<div>
		<img src="images/logo.png"/>
	</div>
	<div>
		<div style="float:left;margin-left:8%;width:84%;padding-top:20px;">
			<img src="images/03_logo.jpg" style=""/> 		
		</div>
		<div style="float:left;margin-left:8%;width:84%;text-align:center;font-size:20px;color:#555555;font-weight:bold;padding-top:20px;">
			地中海邮轮
		</div>
	</div>
	<div class="content">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MSC地中海邮轮（MSC CRUISES）在2003年至2015年期间，公司规模增长了百分之八百。现已经成为欧洲，南美及南非邮轮行业的领导品牌。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;地中海邮轮全年航行于地中海，并季节性航行于北欧、大西洋、安的列斯群岛、南美、南非、加纳利群岛和阿拉伯联合酋长国，并于2015年宣布了全新的目的地——古巴，以及在2016年进入中国，以上海为母港开启中国母港航次。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MSC地中海邮轮是一家欧洲家族企业，目前共有16,000员工遍布世界45个国家。 <br/><br/>
	</div>
	<div class="question">
		<img src="images/question_title.png" style=""/> 
		<div class="item">
		2016-17冬季MSC哪艘邮轮走中东航线及船只的吨位？
		</div>
		<div class="select" onclick="selected(this,'A')">A. FANTASIA幻想曲号-137936吨</div>
		<div class="select" onclick="selected(this,'B')">B. PREZIOSA珍爱号-137936吨</div>
		<div class="input" style="margin-top:22px;padding-bottom:25px;margin-left:5%;width:90%;">
    	   <button type="button" onclick="submit_answer()" style="background-image:url('images/but_icon.png');background-position: left center;background-repeat: no-repeat;">提交答案</button>
    	</div>
	</div>
</form>
<script type="text/javascript">
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