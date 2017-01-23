<?php
/**
 * 测试新建微信分组
 * */
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
define ( 'RELATIVITY_PATH', '../../' );
//require_once './include/db_table.class.php';
include 'include/userGroup.class.php';

$o_usergroup = new userGroup();
$result = $o_usergroup->createGroup('Chengdu');
$groupID = $result["group"]["id"];
$name = $result["group"]["name"];
echo "groupId=".$groupID;
echo "name=".$name;


?>