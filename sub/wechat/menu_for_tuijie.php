<?php
define ( 'RELATIVITY_PATH', '../../' );

header('Content-Type: text/html; charset=UTF-8');

include(dirname(__FILE__)."/include/accessToken.class.php");

$token = new accessToken();
$ACC_TOKEN= $token->access_token;

$data='{ 
    "button": [
        {
            "name": "注册报名", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "上海站", 
                    "url": "http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1036"
                }, 
                {
                    "type": "view", 
                    "name": "广州站", 
                    "url": "http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1037"
                }, 
                {
                    "type": "view", 
                    "name": "北京站", 
                    "url": "http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1038"
                }
            ]
        }, 
        {
        	"type": "click",
            "name": "关于活动", 
            "key":"ABOUT"
        }, 
        {
            "type":"click",
          	"name":"联系我们",
          	"key":"CONTACT"
        }
    ]
}';



var_dump($data);

$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;

$curl = curl_init($MENU_URL);
curl_setopt($curl, CURLOPT_URL, $MENU_URL);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
if (!empty($data)){
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
}
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$info = curl_exec($curl);
$menu = json_decode($info);
print_r($info);		//创建成功返回：{"errcode":0,"errmsg":"ok"}


if($menu->errcode == "0"){
	echo "菜单创建成功";
}else{
	echo "菜单创建失败";
}

?>