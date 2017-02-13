<?php
/**
 * 注册跳转页面
 * 判断用户是否已经注册
 * 已注册的跳转到注册确认页面
 * 未注册的跳转到注册页面
 * */
define ( 'RELATIVITY_PATH', '../../' );
require_once './include/db_table.class.php';
include './include/userUtil.php';
include './include/accessToken.class.php';
$o_userUtil = new userUtil();
$openId = $o_userUtil->open_id;
$sceneId = $_GET["id"];
//报名页面需要验证是否已经关注微信号，如果没有关注，需要跳转到邀请函，进行二维码扫描。
$o_token=new accessToken();
$s_token=$o_token->access_token;
$s_url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$s_token.'&openid='.$openId.'&lang=zh_CN';
$o_util=new curlUtil();
$s_return=$o_util->https_request($s_url);
$a_return=json_decode($s_return, true);
if ($a_return['subscribe']!=1)
{
	//说明没有关注微信,退出
	echo "<script>location.href='invitation/'.$sceneId.'.php'</script>"; 
	exit(0);
}
$o_user = new WX_User_Info();
//通过OpenId获取用户信息
$o_user->PushWhere(array("&&", "OpenId", "=", $openId));
$o_user->getAllCount();

if ($sceneId=='')
{
	//用来处理一个邀请函可以进行多个报名
	$o_user_activity=new WX_User_Activity();
	$o_user_activity->PushWhere(array("&&", "ActivityId", "=", 1044));
	$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
	$o_user_activity->PushWhere(array("||", "ActivityId", "=", 1045));
	$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
	$o_user_activity->PushWhere(array("||", "ActivityId", "=", 1046));
	$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
	$o_user_activity->PushWhere(array("||", "ActivityId", "=", 1047));
	$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
	$count=$o_user_activity->getAllCount();
	if ($count==0)
	{
		$sceneId=1044;
	}else{
		$sceneId=$o_user_activity->getActivityId(0);
	}	
}else{
	//用来处理单独邀请函
	$o_user_activity=new WX_User_Activity();
	$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $sceneId));
	$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
	$count=$o_user_activity->getAllCount();
}
//为了分配到正确的注册，和修改信息，以及签到，需要读取改活动的SceneName，然后查询出所有相同名称的活动，然后取第一个Id值作为编号
$o_activity=new WX_Activity($sceneId);
$o_temp=new WX_Activity();
$o_temp->PushWhere(array("&&", "SceneName", "=", $o_activity->getSceneName()));
$o_temp->PushOrder ( array ('Id', 'A') );
$o_temp->getAllCount();
if(0 == $count){
	echo "<script>location.href='reg_".$o_temp->getId(0).".php?openid=".$openId."&id=".$sceneId."'</script>"; 
	exit(0);
}else{
	
	echo "<script>location.href='reg_modify_".$o_temp->getId(0).".php?openid=".$openId."&id=".$sceneId."'</script>"; 
	exit(0);
}
?>