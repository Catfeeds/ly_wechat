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
    <title>2016迪拜邮轮中国路演-皇家加勒比 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="皇家加勒比">
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
	<input type="hidden" name="Vcl_Question" value="2"/>
	<input type="hidden" name="Vcl_Time" id="Vcl_Time" value="1"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_Right" id="Vcl_Right" value="B"/>
	<input type="hidden" name="Vcl_FunName" value="AnswerSubmit"/>
	<div>
		<img src="images/logo.png"/>
	</div>
	<div>
		<div style="float:left;margin-left:8%;width:84%;padding-top:20px;">
			<img src="images/02_logo.jpg" style=""/> 		
		</div>
		<div style="float:left;margin-left:8%;width:84%;text-align:center;font-size:20px;color:#555555;font-weight:bold;padding-top:20px;">
			皇家加勒比
		</div>
	</div>
	<div class="content">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;皇家加勒比国际游轮是一个备受赞誉的全球游轮品牌，有着47年的创新历史，开创了诸多行业先河。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;皇家加勒比国际游轮这一品牌隶属皇家加勒比游轮有限公司(NYSE/OSE: RCL)，旗下拥有25艘世界上最具创新性的游轮，航线涵盖了全球最受欢迎的诸多旅游胜地，如加勒比海、欧洲、阿拉斯加、南美、亚洲、澳大利亚和新西兰。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;凭借其享誉世界的金锚服务，皇家加勒比国际游轮已连续十三年在北美Travel Weekly读者投票中蝉联“最佳游轮公司”大奖，并且是唯一一家游轮公司连续七年蝉联。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;作为在中国发展速度最快的游轮公司，自2009年开启第一条中国母港航线，皇家加勒比一直以来致力于引领游轮产业的发展，率先在中国市场部署和经营世界顶级级游轮，先后引进两艘亚洲吨位最大、船龄最新、设施最先进的游轮——“海洋航行者号”及“海洋水手号”，引领中国游轮行业进入“大船时代”。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;皇家加勒比于2015年将全球最新、科技含量最高的游轮“海洋量子号”引入中国，自此中国与全球游轮业同步进入“量子时代”。<br/><br/>
	</div>
	<div class="question">
		<img src="images/question_title.png" style=""/> 
		<div class="item">
		目前皇家加勒比旗下有几艘邮轮？
		</div>
		<div class="select" onclick="selected(this,'A')">A. 15</div>
		<div class="select" onclick="selected(this,'B')">B. 25</div>
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