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
    <title>2016迪拜邮轮中国路演-歌诗达游轮</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="歌诗达游轮">
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
	<input type="hidden" name="Vcl_Question" value="4"/>
	<input type="hidden" name="Vcl_Time" id="Vcl_Time" value="1"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_Right" id="Vcl_Right" value="B"/>
	<input type="hidden" name="Vcl_FunName" value="AnswerSubmit"/>
	<div>
		<img src="images/logo.png"/>
	</div>
	<div>
		<div style="width:30%;float:left;margin-left:8%;">
			<img src="images/04_logo.jpg" style=""/> 		
		</div>
		<div style="width:auto;float:left;margin-left:8%;font-size:20px;padding-top:50px;color:#555555;font-weight:bold;">
			歌诗达游轮		
		</div>
	</div>
	<div class="content">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;歌诗达邮轮集团隶属于世界邮轮业翘楚嘉年华集团，嘉年华集团是全球最大邮轮公司，在北美、欧洲、澳洲和亚洲拥有10个邮轮品牌，包括嘉年华邮轮（Carnival Cruise Lines）、荷美邮轮（Holland America Line）、公主邮轮（Princess Cruises）、世邦游艇（The Yachts of Seabourn），AIDA邮轮，歌诗达邮轮（Costa Cruises），冠达邮轮（Cunard Line），铁行（澳大利亚）邮轮（P&O Cruises Australia），铁行（英国）邮轮（P&O Cruises UK）和Fathom。<br/><br/>
	</div>
	<div class="question">
		<img src="images/question_title.png" style=""/> 
		<div class="item">
		今年冬天服务迪拜市场的歌诗达邮轮是哪一艘？
		</div>
		<div class="select" onclick="selected(this,'A')">A. Costa neoROMANTICA</div>
		<div class="select" onclick="selected(this,'B')">B. Costa neoRIVIERA</div>
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