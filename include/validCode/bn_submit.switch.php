<?php
error_reporting(0);
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
define ( 'RELATIVITY_PATH', '../../wp-second/' ); //定义相对路径
require_once RELATIVITY_PATH . 'include/bn_session.class.php';
$O_Session = new Session ();
require_once 'ajax_operate.class.php';
$o_operate = new Operate ();
$s_command = str_replace ( ';', '', $_POST['Ajax_FunName'] );
$s_command = '$o_operate->' . $s_command . '();';
$s_command = str_replace ( '\"', '"', $s_command );
try {
	eval ( $s_command );
} catch ( Exception $e ) {
	echo (0);
}
exit ( 0 );
?>