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
    <title>2016迪拜邮轮中国路演-阿联酋航空 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="description" id="metaDescription" content="阿联酋航空">
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
	<input type="hidden" name="Vcl_Question" value="1"/>
	<input type="hidden" name="Vcl_Time" id="Vcl_Time" value="1"/>
	<input type="hidden" name="Vcl_OpenId" value="<?php echo $openId;?>"/>
	<input type="hidden" name="Vcl_Right" id="Vcl_Right" value="A"/>
	<input type="hidden" name="Vcl_FunName" value="AnswerSubmit"/>
	<div>
		<img src="images/logo.png"/>
	</div>
	<div>
		<div style="width:30%;float:left;margin-left:8%;">
			<img src="images/01_logo.jpg" style=""/> 		
		</div>
		<div style="width:auto;float:left;margin-left:8%;font-size:20px;padding-top:50px;color:#555555;font-weight:bold;">
			阿联酋航空		
		</div>
	</div>
	<div class="content">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;阿联酋航空成立于1985年10月25日，总部位于迪拜，航线网络覆盖全球81个国家和地区超过 153 个目的地;公司拥有全球最大的空中客车A380和波音777机队以空中最时新、最高效的宽体客机竭诚为我们的乘客提供舒适体验; <br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;公司日益增长的全球目的地网络、行业领先的机上娱乐设施、特色化地域美食及世界级优质服务为全球各地的旅行者带来了无限灵感和诸多欢乐; 现在搭乘阿联酋航空公司的班机前往迪拜即可享受“阿联酋航空畅游迪拜专属礼遇”，丰富你的迪拜之旅。<br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;你可以在迪拜及周边的酒店、水疗馆、高尔夫俱乐部和其他场所，以特惠价格用餐、休闲和娱乐。这样，你就可以节省更多的度假基金。在2016年6月1日至8月31日期间，搭乘阿联酋航空航班飞往迪拜。只需出示自己的登机牌，即可享受我们提供的众多诱人礼遇。详情请关注阿联酋航空公司官<br/>
		<a href="http://www.emirates.com/">www.emirates.com</a>
	</div>
	<div class="question">
		<img src="images/question_title.png" style=""/> 
		<div class="item">
		请选出阿航在中国的七个始发站分别是哪里？
		</div>
		<div class="select" onclick="selected(this,'A')">A. 北京、上海、广州、郑州、银川、香港、台北</div>
		<div class="select" onclick="selected(this,'B')">B. 北京、上海、广州、成都、兰州、昆明、乌鲁木齐</div>
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