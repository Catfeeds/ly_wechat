<?php
error_reporting(0);
//在线用户人数
if (isset ( $_COOKIE ['SESSIONID'] )) {
	if ($_GET ['loginout'] == 'true') {
		$o_date = new DateTime ( 'Asia/Chongqing' );
		$n_nowTime = $o_date->format ( 'U' );
		$S_Session_Id = md5 ( $_SERVER ['REMOTE_ADDR'] . $_SERVER ['HTTP_USER_AGENT'] . rand ( 0, 9999 ) . $n_nowTime );
		setcookie ( 'VISITER', '', 0 );
		setcookie ( 'SESSIONID', $S_Session_Id, 0 );
		setcookie ( 'VALIDCODE', '2222', 0 );
		echo ('<script>location=\'index.php\'</script>');
		exit ( 0 );
	}
} else {
	$o_date = new DateTime ( 'Asia/Chongqing' );
	$n_nowTime = $o_date->format ( 'U' );
	$S_Session_Id = md5 ( $_SERVER ['REMOTE_ADDR'] . $_SERVER ['HTTP_USER_AGENT'] . rand ( 0, 9999 ) . $n_nowTime );
	setcookie ( 'VISITER', '', 0 );
	setcookie ( 'SESSIONID', $S_Session_Id, 0 );
	setcookie ( 'VALIDCODE', '222', 0 );
	echo ('<script>location.reload()</script>');
	exit ( 0 ); 
}
?>
<script>
<?php
if (isset ( $_GET ['url'] )) {
	echo ('location=\'' . $_GET ['url'] . '\'');

} else {
	echo ('location=\'login.php\'');
}
?>
</script>