<?php
/**
 * 注册跳转页面
 * 判断用户是否已经注册
 * 已注册的跳转到注册确认页面
 * 未注册的跳转到注册页面
 * */
define ( 'RELATIVITY_PATH', '../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
include 'include/emoji.php';
//$o_userUtil = new userUtil();
//$openId = $o_userUtil->open_id;
$clean_text = emoji_docomo_to_unified($_POST[message]);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/emoji.css"/>
    <script src="js/common.fun.js" type="text/javascript"></script>
	<meta charset="utf-8">
    <title>报名</title>
</head>
<body>
<?php 
echo(emoji_unified_to_html(json_decode('"\u521d\u6d9b\u4e28\ud83d\udc30\ud83c\udf44\ud83c\udf90\u6d4b\u8bd5"')));
?>
</body>
</html>