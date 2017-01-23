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
	//说明没有关注微信,直接关闭
	echo("<script>
		document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('closeWindow');});
	</script>");
	exit(0);
}
//通过OpenId获取用户信息
$o_user = new WX_User_Info();
$o_user->PushWhere(array("&&", "OpenId", "=", $openId));
$o_user->getAllCount();
//查找活动信息
$o_activity=new WX_Activity($sceneId);
$sceneId=$o_activity->getSceneId();
//为了分配到正确的签到页面，需要读取改活动的SceneName，然后查询出所有相同名称的活动，然后取第一个Id值作为编号
$o_activity=new WX_Activity($sceneId);
$o_temp=new WX_Activity();
$o_temp->PushWhere(array("&&", "SceneName", "=", $o_activity->getSceneName()));
$o_temp->PushOrder ( array ('Id', 'A') );
$o_temp->getAllCount();

//查找用户活动
echo "<script>location.href='signin_".$o_temp->getId(0).".php?openid=".$openId."&id=".$sceneId."&sceneid=".$o_temp->getId(0)."'</script>"; 
?>