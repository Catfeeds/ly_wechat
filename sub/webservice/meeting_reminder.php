<?php
error_reporting(0);
set_time_limit(0);
define ( 'RELATIVITY_PATH', '../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'sub/meeting/include/db_table.class.php';
$o_sys_info=new Base_Setup(1);
$s_homepage=$o_sys_info->getHomeUrl();
$o_reminder=new WX_User_Reminder();
$o_reminder->PushWhere ( array ('&&', 'Send', '=',0) );
$n_count=$o_reminder->getAllCount();
if ($n_count>10)
{
	$n_count=10;
}
for($i=0;$i<$n_count;$i++)
{
	$o_temp=new WX_User_Reminder($o_reminder->getId($i));
	$o_temp->setSend(1);
	$o_temp->Save();
	sleep(1);
	//给用户发送消息
	require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
	$o_token=new accessToken();
	$curlUtil = new curlUtil();
	$s_url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$o_token->access_token;
	$data = array(
		'touser' => $o_reminder->getOpenId($i), // openid是发送消息的基础
		'template_id' => 'g_hr9W9NdKlo3h4WoWWMjHdlTNUqI9ueteXnVbJ5AFs', // 模板id
		'url' => $o_sys_info->getHomeUrl().'sub/wechat/reg_transfer.php?id='.$o_reminder->getActivityId($i), // 点击跳转地址
		'topcolor' => '#FF0000', // 顶部颜色
		'data' => array(
		'first' => array('value' => $o_reminder->getFirst($i).'
		'),
		'keyword1' => array('value' =>$o_reminder->getKeyword1($i),'color'=>'#173177'),
		'keyword2' => array('value' => $o_reminder->getKeyword2($i),'color'=>'#173177'),
		'remark' => array('value' =>$o_reminder->getRemark($i)) 
				)
		);
	$curlUtil->https_request($s_url, json_encode($data));
}
echo('Finished');
?>