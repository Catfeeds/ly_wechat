<?php
define ( 'RELATIVITY_PATH', '' ); //定义相对路径
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
$o_date = new DateTime ( 'Asia/Chongqing' );
$n_nowTime = $o_date->format ( 'U' );
$S_Session_Id = md5 ( $_SERVER ['REMOTE_ADDR'] . $_SERVER ['HTTP_USER_AGENT'] . rand ( 0, 9999 ) . $n_nowTime );
setcookie ( 'VISITER', '', 0 );
setcookie ( 'SESSIONID', $S_Session_Id, 0 );
setcookie ( 'VALIDCODE', '', 0 );
require_once RELATIVITY_PATH . 'include/language_cn.php';
require_once RELATIVITY_PATH . 'include/db_table.class.php';
$o_setup=new Base_Setup(1);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?php echo(Text::Key('LoginTitle'))?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <script src="<?php echo(RELATIVITY_PATH)?>js/initialize.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" media="screen and (max-width: 767px)" href="<?php echo(RELATIVITY_PATH)?>css/mobile.css" />
</head>
<body style="background-color:#F2F4F8">
    <nav class="navbar navbar-default navbar-fixed-top sss_top">
<div class="sss_logo">
	<img src="<?php echo(RELATIVITY_PATH.$o_setup->getLogo())?>" alt="" />
</div>
<div class="sss_title sss_logo_title">
    <div><span class="glyphicon glyphicon-signal"></span>&nbsp;&nbsp;<?php echo($o_setup->getSystemName())?></div>
</div>
</nav>
<form method="post" id="submit_form" action="include/bn_submit.switch.php" enctype="multipart/form-data" target="submit_form_frame" onsubmit="this.submit();loading_show();">
	<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
	<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
	<input type="hidden" name="Vcl_FunName" value="Login"/>
	<div class="sss_login">
	    <div style="padding:15px;font-size:20px;color:#606060;border-bottom:1px solid #ddd;">
	        <?php echo(Text::Key('Login'))?>
	    </div>
	    <div style="padding:15px;">
	    <input name="Vcl_UserName" type="text" style="width:100%" class="form-control" placeholder="<?php echo(Text::Key('UserName'))?>" aria-describedby="basic-addon1" />
	    </div>
	    <div style="padding:15px;padding-top:0px;">
	    <input name="Vcl_Password" type="password" style="width:100%" class="form-control" placeholder="<?php echo(Text::Key('Password'))?>" aria-describedby="basic-addon1" />
	    </div>
	    <div style="padding:15px;padding-top:0px;">
	    <button id="user_add_btn" type="submit" class="btn btn-success" aria-hidden="true" style="outline: medium none" onclick="loading_show();">
	                                <span  class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;<?php echo(Text::Key('LoginButton'))?></button>
	    </div>
	</div>
</form>
<nav class="navbar navbar-default navbar-fixed-bottom sss_reg_footer">
<p>
<?php echo($o_setup->getFooter())?>
</p>
</nav>
<iframe id="submit_form_frame" name="submit_form_frame" src="about:blank" style="display:none"></iframe>
<div id="loading" class="modal fade in whiteback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-body loading_body">
      <img id="user_photo" src="<?php echo(RELATIVITY_PATH)?>images/loading.gif" alt="" />
  </div>
</div>
</body>
</html>