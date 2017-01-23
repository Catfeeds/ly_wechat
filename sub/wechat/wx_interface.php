<?php
define ( 'RELATIVITY_PATH', '../../' );
include(dirname(__FILE__)."/include/db_table.class.php");
define("TOKEN", "travellinkdaily");
date_default_timezone_set("Asia/Shanghai");

$wechatObj = new wechat();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechat
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
			header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
             
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收事件消息
    private function receiveEvent($object)
    {
    	$eventkey = "";
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                //$content = "欢迎关注 ";
                //$content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
            	$eventkey = $object->EventKey;
            	if(isset($eventkey) && !empty($eventkey)){
            		//如果二维码带参数，Sceneid存在，且不等于空，那么进入跳转分配流程
            		$eventkey = str_replace("qrscene_","",$object->EventKey);
            		$result = $this->getMessageFromQr($object, $eventkey);
            		//$resultStr .= "\n来自二维码场景".$object->EventKey;
            	}
            	break;
            case "unsubscribe":
            	$openId = $object->FromUserName;
            	$this->setDelFlag($openId);
                //$content = "取消关注";
                break;
            case "SCAN":
                //$content = "扫描场景 ".$object->EventKey;
                $eventkey = $object->EventKey;
       	 		if(isset($eventkey) && !empty($eventkey)){
        			$result = $this->getMessageFromQr($object, $eventkey);
				}
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                	/*
                    case "ABOUT":
						$result= $this->transmitText($object, '2016中国斯堪的纳维亚旅游推介会（丹麦、挪威、瑞典）由北欧旅游局和瑞典旅游局联合主办，此次活动将有来自北欧地区50多家业内伙伴，包括航空公司、景点、酒店、邮轮公司、地接社和地区旅游局参加。我们将借此机会向您展示北欧的多元主题、丰富路线以及一些全新的资源。诚邀您注册报名参会，报名审核通过后工作人员将与您取得联系。');
						 break;
					case "CONTACT":
						$result= $this->transmitText($object, '您有任何关于活动及系统的疑问，可邮件联系活动执行小组Travel Link：campaign@tlmchina.com

或联系各站活动执行组负责人

上海站：
Cynthia Zhu 010-64301593
 
广州站：
Judy Hu 020-87607815

北京站：
Renee Zhang 010-64301593');
						 break;*/
                }
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "MASSSENDJOBFINISH":
                $content = "消息ID：".$object->MsgID."，结果：".$object->Status."，粉丝数：".$object->TotalCount."，过滤：".$object->FilterCount."，发送成功：".$object->SentCount."，发送失败：".$object->ErrorCount;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
        /*
        if(is_array($content)){
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }*/

        return $result;
    }

    //接收文本消息
    private function receiveText($object)
    {
    	$keyword = trim($object->Content);
    	$result='';
    	if (strstr($keyword, "注册") || strstr($keyword, "报名") || strstr($keyword, "北京")|| strstr($keyword, "上海")|| strstr($keyword, "广州"))
    	{
    		//$result = $this->transmitText($object, '微信注册报名：<a href="http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1036">点击这里</a>');
    	}
    	if (strstr($keyword, "密码"))
    	{
    		/*$result = $this->transmitText($object, '如您忘记密码，请联系我们：

可邮件联系活动执行小组Travel Link：campaign@tlmchina.com

或联系各站活动执行组负责人

上海站：
Cynthia Zhu 010-64301593
 
广州站：
Judy Hu 020-87607815

北京站：
Renee Zhang 010-64301593');*/
    	}
    	return $result;
        /*$keyword = trim($object->Content);
        //多客服人工回复模式
        if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗")){
            $result = $this->transmitService($object);
        }else{
            if (strstr($keyword, "文本")){
                $content = "这是个文本消息";
            }else if (strstr($keyword, "单图文")){
                $content = array();
                $content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
                $content = array();
                $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            }else if (strstr($keyword, "音乐")){
                $content = array();
                $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3");
            }else{
                $content = date("Y-m-d H:i:s",time())."\n".$object->FromUserName."\n技术支持 ";
            }
            
            if(is_array($content)){
                if (isset($content[0]['PicUrl'])){
                    $result = $this->transmitNews($object, $content);
                }else if (isset($content['MusicUrl'])){
                    $result = $this->transmitMusic($object, $content);
                }
            }else{
                $result = $this->transmitText($object, $content);
            }
        }

        return $result;*/
        
    }

    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
    	/*
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
        */
    }

    //接收语音消息
    private function receiveVoice($object)
    {
    	/*
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
        */
    }

    //接收视频消息
    private function receiveVideo($object)
    {
    	/*
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
        */
    }

    //接收链接消息
    private function receiveLink($object)
    {
    	/*
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;*/
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
					</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
	public function saveMediaFile($fileName, $fileContent){
		$localFile = fopen($fileName, 'w');
		if(false !== $localFile){
			if(false !== fwrite($localFile, $fileContent)){
				fclose($localFile);
			}
		}
	}
    //回复图片消息
    private function transmitImage($object, $imageArray)
    {/*
    	$mediaId=$imageArray['MediaId'];
    	define ( 'RELATIVITY_PATH', '../../' );
    	
		//判断用户是否已经报名，并且是当天上传的
		$o_date = new DateTime ( 'Asia/Chongqing' );
		$s_date=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ) ;//获取当前日期
		//查找活动里是否有当前日期的活动
		require_once 'include/db_table.class.php';
		$o_activity=new WX_Activity();
		$o_activity->PushWhere(array("&&", "ActivityDate", "=", $s_date));
		if ($o_activity->getAllCount()>0)
		{
			//查看用户是否已经报名
			
			include 'include/accessToken.class.php';
			$openId = $object->FromUserName;
			$o_user = new WX_User_Info();
			$o_user->PushWhere(array("&&", "OpenId", "=", $openId));
			$o_user->getAllCount();
			$o_user_activity=new WX_User_Activity();
			$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $o_activity->getId(0)));
			$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
			$o_user_activity->PushWhere(array("&&", "SigninFlag", "=", 1));
			if ($o_user_activity->getAllCount()>0)
			{
				
				//说明有活动，那么保存图片
				$s_round=4;//第五个抽奖奖池
				
		    	$o_token=new accessToken();
				$s_token=$o_token->access_token;
		    	$curlUtil = new curlUtil();
				//保存奖池
				$o_join=new WX_User_Activity_Join();
				$o_join->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
				$o_join->PushWhere(array("&&", "ActivityId", "=", $o_activity->getId(0)));
				if ($o_join->getAllCount()>0)
				{
					//更新
					$o_join=new WX_User_Activity_Join($o_join->getId(0));
				}else{
					//新建
					$o_join=new WX_User_Activity_Join();
					$o_join->setUserId($o_user->getId(0));
					$o_join->setActivityId($o_activity->getId(0));
				}
				eval ('$o_join->setRound'.$s_round.'(1);');
				$o_join->Save();
				//保存到数据库
				$o_uploadphoto=new WX_User_Activity_Join_Uploadphoto();
				$o_uploadphoto->setUserId($o_user->getId(0));
				$o_uploadphoto->setPath("sub/wechat/upload_photo/".$mediaId.".jpg");
				$o_uploadphoto->setDate($o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ) . ' ' . $o_date->format ( 'H' ) . ':' . $o_date->format ( 'i' ) . ':' . $o_date->format ( 's' ));
				$o_uploadphoto->Save();
				//保存图片
				$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$s_token."&media_id=".$mediaId;
				$fileName = "upload_photo/".$mediaId.".jpg";
				$fileInfo = $curlUtil->downloadWxFile($url);
				$this->saveMediaFile($fileName, $fileInfo["body"]);//保存图片到服务器
		    	$content = '恭喜您，您的分享图片已经上传成功（以最后一次上传图片为准），感谢您的参与！您将参与本次活动最后的抽奖，请您关注。';	
		        $result = $this->transmitText($object, $content);
		        return $result;
			}
			
		}
		*/
    	/*
    	
        $itemTpl = "<Image>
    					<MediaId><![CDATA[%s]]></MediaId>
					</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[image]]></MsgType>
						$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
       return $result;*/
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    					<MediaId><![CDATA[%s]]></MediaId>
					</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[voice]]></MsgType>
						$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
					    <MediaId><![CDATA[%s]]></MediaId>
					    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
					    <Title><![CDATA[%s]]></Title>
					    <Description><![CDATA[%s]]></Description>
					</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[video]]></MsgType>
						$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
					        <Title><![CDATA[%s]]></Title>
					        <Description><![CDATA[%s]]></Description>
					        <PicUrl><![CDATA[%s]]></PicUrl>
					        <Url><![CDATA[%s]]></Url>
					    </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[news]]></MsgType>
						<ArticleCount>%s</ArticleCount>
						<Articles>
						$item_str</Articles>
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
					    <Title><![CDATA[%s]]></Title>
					    <Description><![CDATA[%s]]></Description>
					    <MusicUrl><![CDATA[%s]]></MusicUrl>
					    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
					</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[music]]></MsgType>
						$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[transfer_customer_service]]></MsgType>
					</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //日志记录
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
    
    //报名截止时间
	function isExpiry($sceneId){
		//判断超时不需要比对Sceneid，随便找个活动的截止日期即可，因为所有活动的报名截止日期都一样
		$o_select = new WX_Activity();
		$o_select->PushWhere(array('&&','Id','=',$sceneId));
		$count = $o_select->getAllCount();
		$expiryDate = $o_select->getExpiryDate(0);
		$today = date('Y-m-d', time());
		if(strtotime($today) > strtotime($expiryDate)){ //超时
			return true;
		}else{
			return false;
		}
	}
	
	//获取参数二维码信息
    function getMessageFromQr($postObj,$sceneId){
		$content = array();
		//判断Type是否是1，如果是1，说明是报名，否则是图文消息
		//判断OpenId是否在数据库，如果在，那么标记已关注
		$b_block=false;
		$o_user = new WX_User_Info();
		$o_user->PushWhere(array("&&", "OpenId", "=", $postObj->FromUserName));
		$n_count_user=$o_user->getAllCount();
		if($n_count_user>0)
		{
			if ($o_user->getBlock(0)==1)
			{
				//检查是否加入了黑名单
				$b_block=true;
			}
			if ($o_user->getDelFlag(0)==1)
			{
				//如果之前去掉关注了，那么需要恢复关注标志
				$o_user = new WX_User_Info($o_user->getId(0));
				$o_user->setDelFlag(0);
				$o_user->Save();
			}
		}
		if($sceneId>1000 && $b_block==false){
			$activity = new WX_Activity();
			$activity->PushWhere(array("&&", "Id", "=", $sceneId));
			if($activity->getAllCount()==0)
			{
				$resultStr='';
			}
			if ($activity->getType(0)==1)
			{
				//说明是报名
				if($this->isExpiry($sceneId)){
					//如果超过报名截止日期，已报名的可以看到报名信息
					if($n_count_user==0){
						//如果不存在，弹出截止报名
		        		$resultStr = $this->transmitText($postObj, "十分抱歉，本次活动报名已经截止");
					}else{
						//如果存在，搜索是否已经在本次活动中报名，
						$user_activity = new WX_User_Activity();
						$user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
						$user_activity->PushWhere(array("&&", "ActivityId", "=", $activity->getId(0)));
						if ($user_activity->getAllCount()==0)
						{
							//如果没有在本次活动中报名，那么弹出过期
							$resultStr = $this->transmitText($postObj, "十分抱歉，本次活动报名已经截止");
						}else{
							//如果已经报名，弹出报名图文，应该是修改用户信息
							$content = $this->getActivityArray($sceneId);
		        			$resultStr = $this->transmitNews($postObj, $content);
						}		
					}
		        }else{
		        	$content = $this->getActivityArray($sceneId);
		        	$resultStr = $this->transmitNews($postObj, $content);
		        }
			}else if ($activity->getType(0)==2){
				//说明是图文消息
				if(is_array($this->getActivityArray($sceneId))){ //回复图文
		        	$content = $this->getActivityArray($sceneId);
		        	$resultStr = $this->transmitNews($postObj, $content);
		        }elseif(is_string($this->getActivityArray($sceneId))){	//回复文字
		        	$resultStr = $this->transmitText($postObj, $this->getActivityArray($sceneId));
		        }
		        //如果SceneId>8000,那么进入参加抽奖程序（本程序只针对 《2016中国斯堪的纳维亚旅游推介会》）
				/*
		        $sceneId=(int)$sceneId;
		        if ($sceneId>8000)
		        {
		        	$o_date = new DateTime ( 'Asia/Chongqing' );
			        $s_date=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' );
					//查找活动里是否有当前日期的活动
					$o_activity=new WX_Activity();
					$o_activity->PushWhere(array("&&", "ActivityDate", "=", $s_date));
					$s_open_id=$postObj->FromUserName;
					if ($o_activity->getAllCount()>0)
					{
						//查看用户是否已经报名
						$o_user = new WX_User_Info();
						$o_user->PushWhere(array("&&", "OpenId", "=", $s_open_id));
						$o_user->getAllCount();
						$o_user_activity=new WX_User_Activity();
						$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $o_activity->getId(0)));
						$o_user_activity->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
						$o_user_activity->PushWhere(array("&&", "SigninFlag", "=", 1));
						if ($o_user_activity->getAllCount()>0)
						{
							//保存奖池
							$o_join=new WX_User_Activity_Join();
							$o_join->PushWhere(array("&&", "UserId", "=", $o_user->getId(0)));
							$o_join->PushWhere(array("&&", "ActivityId", "=", $o_activity->getId(0)));
							if ($o_join->getAllCount()>0)
							{
								//更新
								$o_join=new WX_User_Activity_Join($o_join->getId(0));
							}else{
								//新建
								$o_join=new WX_User_Activity_Join();
								$o_join->setUserId($o_user->getId(0));
								$o_join->setActivityId($o_activity->getId(0));
							}
							$o_join->Save();
							//$sceneId=12345;
							//获取当前奖池数组
							$a_scan=$o_join->getScan1();
							if($a_scan=='')
							{
								$a_scan=array();
							}else{
								$a_scan=json_decode($a_scan);
							}						
							if (in_array($sceneId, $a_scan))
							{
								//扫描了相同的展商，不做变更
							}else{
								//扫描了不同的展商，需要加入数组
								array_push($a_scan, $sceneId);
							}
							$o_join->setScan1(json_encode($a_scan));
							$o_join->setScanSum1(count($a_scan));
							$o_join->Save();	
							//$resultStr = $this->transmitText($postObj, $sceneId);		
						}
					}
		        }*/
			}
		}else {
			/*$resultStr = $this->transmitText($postObj, '2016丹麦旅游局 、挪威旅游局、瑞典旅游局联合主办的斯堪的纳维亚旅游推介会在线报名。此次推介会将有来自北欧地区50多家业内伙伴，包括航空公司、景点、酒店、邮轮公司、地接社和地区旅游局参加。我们将借此机会向您展示北欧的多元主题、丰富路线以及一些全新的资源。现真诚邀请贵公司北欧业务骨干或负责地接采购的相关人员1-2名参加此次斯堪的纳维亚旅游推介会活动，立即点击以下网址，与三国旅业伙伴交流畅谈，发掘北欧三国的更多惊喜之处。
 
欢迎您报名参加：

<a href="http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1036">上海站：2016-11-21(周一)</a>

<a href="http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1037">广州站：2016-11-23(周三)</a>

<a href="http://wechat.travellinkdaily.com/event/sub/wechat/reg_transfer.php?id=1038">北京站：2016-11-25(周五)</a>');*/
		}
		//$resultStr = $this->transmitText($postObj, $activity->getAllCount());
        echo $resultStr;
    }
    
	function getActivityArray($sceneId){ //通用获取活动信息方法
		
		$content = array();
		$activity = new WX_Activity();
		$activity->PushWhere(array("&&", "Id", "=", $sceneId));
		$count = $activity->getAllCount();
		$type = $activity->getMessageType(0);
		if("1" == $type){ //文字回复
			$descritpion = $activity->getDescription(0);
			return $descritpion; 
		}elseif("2" == $type){  //图文回复
			$content[] = array(
				'Title' => $activity->getTitle(0),
				'Description' => $activity->getDescription(0),
				'PicUrl' => $activity->getPicUrl(0),
				'Url' => $activity->getMessageUrl(0)
				);
			/*
			$data = $activity->getActivity($scene);
			while($row = mysql_fetch_array($data) ){
				$rows[]=$row;
			}
			foreach ($rows as $obj){
				$content[] = array(
				'Title' =>$obj['scene_name'],
				'Description' =>$obj['description'],
				'PicUrl' => $obj['image_url'],
				'Url' =>$obj['message_url']
				);
			}*/
			return $content;
		}
	}
	
	//将用户删除标志置1
	function setDelFlag($openId){
		$o_selectUser = new WX_User_Info();
		$o_selectUser->PushWhere(array("&&", "OpenId", "=", $openId));
		$n_count = $o_selectUser->getAllCount();
		for($i=0;$i<$n_count;$i++){
			$userId = $o_selectUser->getId($i);
			$o_updateUser = new WX_User_Info($userId);
			//$o_updateUser->Deletion();//直接删除
			$o_updateUser->setDelFlag(1);//直接删除
			$o_updateUser->Save();
		}
	}
}
?>