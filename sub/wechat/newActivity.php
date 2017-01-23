<?php
/**
 * 测试新建活动
 * */
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
define ( 'RELATIVITY_PATH', '../../' );
require_once './include/db_table.class.php';
include './include/userGroup.class.php';

//创建活动
$city = "Beijing";
$sceneId = "2";
$qrCode = "qrcode20160327081322.jpg";

/*
$o_new= new WX_Activity();
$o_new->setSceneId($sceneId); //暂时存储，之后利用ID作为ScenID
$o_new->setSceneName($city);
$o_new->setQrCode($qrCode);
$o_new->setMessageType('2');
$o_new->setMessageUrl('http://1.hollandmeeting.applinzi.com/sub/wechat/transfer.php?sceneId='.$sceneId);
$o_new->setPicUrl('https://www.baidu.com/img/bd_logo1.png');
$o_new->setDescription('欢迎关注');
$o_new->setTitle("成都测试图文");
$o_new->setExpiryDate('2016-03-27');
if($o_new->Save()){
	echo "创建活动".$city."成功";
}else{
	echo "创建活动".$city."失败";
}*/

$o_new= new WX_Activity();
$o_new->setSceneId($sceneId); //暂时存储，之后利用ID作为ScenID
$o_new->setSceneName($city);
$o_new->setQrCode($qrCode);
$o_new->setMessageType('2');
$o_new->setMessageUrl('http://1.hollandmeeting.applinzi.com/sub/wechat/transfer.php?sceneId='.$sceneId);
$o_new->setPicUrl('https://www.baidu.com/img/bd_logo1.png');
$o_new->setDescription('欢迎关注');
$o_new->setTitle($city."测试图文");
$o_new->setExpiryDate('2016-03-27');
if($o_new->Save()){
	echo "创建活动".$city."成功";
}else{
	echo "创建活动".$city."失败";
}

//创建分组,分组创建一次就可以
$o_select = new WX_Group();
$o_select->PushWhere(array("&&", "GroupName", "=", $city));
$count = $o_select->getAllCount();

if($count == 0){
	$o_usergroup = new userGroup();
	$result = $o_usergroup->createGroup($city);
	$groupID = $result["group"]["id"];
	$name = $result["group"]["name"];
	echo "groupId=".$groupID;
	echo "name=".$name;
	
	$o_newGroup = new WX_Group();
	$o_newGroup->setGroupId($groupID);
	$o_newGroup->setGroupName($name);
	if($o_newGroup->Save()){
		echo "保存成功";
	}else{
		echo "保存失败";
	}
}

?>