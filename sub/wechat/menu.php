<?php
define ( 'RELATIVITY_PATH', '../../' );

header('Content-Type: text/html; charset=UTF-8');

include(dirname(__FILE__)."/include/accessToken.class.php");

$token = new accessToken();
$ACC_TOKEN= $token->access_token;

$data='{ 
    "button": [
        {
            "name": "旅游折扣", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "立即预订", 
                    "url": "http://mp.weixin.qq.com/bizmall/mallshelf?id=&t=mall/list&biz=MzI2MTAxNTkzNg==&shelf_id=1&showwxpaytitle=1#wechat_redirect"
                }, 
                {
                    "type": "click", 
                    "name": "会员中心", 
                    "key":"VIP_CENTER"
                }, 
                {
                    "type": "click", 
                    "name": "联系我们", 
                    "key": "CONTACT_US"
                }
            ]
        }, 
        {
            "name": "旅游招聘", 
            "sub_button": [
                {
                    "type": "click", 
                    "name": "最新职位", 
                    "key":"HOT_JOBS"
                }, 
                {
					"type":"click",
					"name":"联络顾问",
					"key":"CONTACT_CONSULTANT"
                }, 
                {
                    "type":"click",
					"name":"猎头分享",
					"key":"HUNTER_SHARE"
                }
            ]
        }, 
        {
            "type":"view",
          	"name":"在线培训",
          	"url":"http://ctoa.travellinkdaily.com/"
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