<?php
error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
require_once 'db_table.class.php';
class Operate extends Bn_Basic {	
	public function Register()
	{
		sleep(2);
		require_once 'userGroup.class.php';
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$o_token=new accessToken();
		$curlUtil = new curlUtil();
		$openId = $this->getPost('OpenId');
		$o_token=new accessToken();
		$s_token=$o_token->access_token;
		//通过接口获取用户OpenId
		$s_url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$s_token.'&openid='.$openId.'&lang=zh_CN';
		$o_util=new curlUtil();
		$s_return=$o_util->https_request($s_url);
		$a_user_info=json_decode($s_return, true);
		//判断是否重复提交报名
		$o_user_info=new WX_User_Info();
		$o_user_info->PushWhere(array("&&", "OpenId", "=",$openId));
		$n_user_id=0;
		$b_rusult=true;
		//获取会议信息
		$o_activity=new WX_Activity($this->getPost('Id'));
		if ($o_user_info->getAllCount()==0)
		{
			//需要新建用户信息
			$o_new_user=new WX_User_Info();
		}else{
			$o_new_user=new WX_User_Info($o_user_info->getId(0));
		}
		$o_new_user->setPhoto($a_user_info['headimgurl']);
		$o_new_user->setNickname($this->FilterEmoji($a_user_info['nickname']));
		if ($a_user_info['sex']==2)
		{
			$o_new_user->setSex('女');
		}else{
			$o_new_user->setSex('男');
		}			
		$o_new_user->setUserName($this->getPost('Name'));
		$o_new_user->setCompany($this->getPost('Company'));
		$o_new_user->setCompanyEn($this->getPost('CompanyEn'));
		$o_new_user->setAddress('');
		$o_new_user->setDeptJob($this->getPost('DeptJob'));
		$o_new_user->setPhone($this->getPost('Phone'));
		$o_new_user->setEmail($this->getPost('Email'));
		$o_new_user->setRegisterDate($this->GetDate());
		$o_new_user->setOpenId($this->getPost('OpenId'));
		$o_new_user->setDelFlag(0);
		$b_rusult=$o_new_user->Save();
		$n_user_id=$o_new_user->getId();		
		if ($b_rusult!=true)
		{
			$nickname=$this->FilterEmoji($a_user_info['nickname']);
			$audit=0;
			$onsite=0;
			$sign=0;
			if ($o_activity->getNeedAudit()==1)
			{
				$audit=0;
			}else{
				//如果活动设置不需要审核，那么审核标准自动变成已审核
				$audit=1;
			}	
			require_once 'log.php';
			//$this->setReturn ( 'parent.dialog_close();parent.dialog_show("对不起，提交信息失败，请重试！<br/>如多次失败，请重新进入报名。");' );
		}
		//require_once 'log.php';	
		//检查是否已经报过名
		$o_user_activity=new WX_User_Activity();
		$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $this->getPost('Id')));
		$o_user_activity->PushWhere(array("&&", "UserId", "=", $n_user_id));
		if($o_user_activity->getAllCount()>0)
		{
			//说明已经报名过
			$this->setReturn ( 'parent.dialog_close();parent.dialog_show("对不起，不能重复报名。");' );
		}
		$o_user_activity=new WX_User_Activity();
		$o_user_activity->setActivityId($this->getPost('Id'));
		$o_user_activity->setUserId($n_user_id);
		$o_user_activity->setSigninFlag(0);
		$o_user_activity->setOnsiteFlag(0);
		if ($o_activity->getNeedAudit()==1)
		{
			$o_user_activity->setAuditFlag(0);
		}else{
			//如果活动设置不需要审核，那么审核标准自动变成已审核
			$o_user_activity->setAuditFlag(1);
		}		
		$b_rusult=$o_user_activity->Save();
		if ($b_rusult!=true)
		{
			//如果保存失败，那么给出提示。
			//$this->setReturn ( 'parent.dialog_close();parent.dialog_show("对不起，提交信息失败，请重试！<br/>如多次失败，请重新进入报名。");' );
		}
		//开始用户分组
		$o_sysinfo=new Base_Setup(1);
		//发送确认信息
		$s_url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$o_token->access_token;
		$data = array(
	    	'touser' => $openId, // openid是发送消息的基础
			'template_id' => 'xoHi_vpqoEkEthm49N_ejdPWPuGjceqOW4r21U_T5eM', // 模板id
			'url' => $o_sysinfo->getHomeUrl().'sub/wechat/reg_transfer.php?id='.$this->getPost('Id'), // 点击跳转地址
			'topcolor' => '#FF0000', // 顶部颜色
			'data' => array(
				'first' => array('value' =>$o_activity->getRegFirst().'
				'),
				'keyword1' => array('value' => $this->getPost('Name'),'color'=>'#173177'),
				'keyword2' => array('value' => $this->getPost('Phone'),'color'=>'#173177'),
				'keyword3' => array('value' => $this->GetDate(),'color'=>'#173177'),
				'remark' => array('value' =>'
'.$o_activity->getRegRemark())
			)
			);
		$curlUtil->https_request($s_url, json_encode($data));
		$this->setReturn ( 'parent.submit_success()' );
	}
	public function Modify()
	{
		sleep(2);
		$o_user_info=new WX_User_Info();
		$o_user_info->PushWhere(array("&&", "OpenId", "=",$this->getPost('OpenId')));
		if ($o_user_info->getAllCount()>0)
		{
			//需要新建用户信息
			$o_new_user=new WX_User_Info($o_user_info->getId(0));			
			$o_new_user->setUserName($this->getPost('Name'));
			$o_new_user->setCompany($this->getPost('Company'));
			$o_new_user->setCompanyEn($this->getPost('CompanyEn'));
			$o_new_user->setDeptJob($this->getPost('DeptJob'));
			$o_new_user->setPhone($this->getPost('Phone'));
			$o_new_user->setEmail($this->getPost('Email'));
			$o_new_user->Save();
		}
		$this->setReturn ( 'parent.submit_success()' );
	}
	public function Signin()
	{
		sleep(2);
		require_once 'userGroup.class.php';
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$o_token=new accessToken();
		$curlUtil = new curlUtil();
		$openId = $this->getPost('OpenId');
		$o_token=new accessToken();
		$s_token=$o_token->access_token;
		//通过接口获取用户OpenId
		$s_url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$s_token.'&openid='.$openId.'&lang=zh_CN';
		$o_util=new curlUtil();
		$s_return=$o_util->https_request($s_url);
		$a_user_info=json_decode($s_return, true);
		//判断是否重复提交报名
		$o_user_info=new WX_User_Info();
		$o_user_info->PushWhere(array("&&", "OpenId", "=",$openId));
		$n_user_id=0;
		if ($o_user_info->getAllCount()==0)
		{
			//是现场签到的用户，并且没有信息，需要新建信息
			//需要新建用户信息
			$o_new_user=new WX_User_Info();
			$o_new_user->setPhoto($a_user_info['headimgurl']);
			$o_new_user->setNickname($this->FilterEmoji($a_user_info['nickname']));
			if ($a_user_info['sex']==2)
			{
				$o_new_user->setSex('女');
			}else{
				$o_new_user->setSex('男');
			}			
			$o_new_user->setUserName($this->getPost('Name'));
			$o_new_user->setCompany($this->getPost('Company'));
			$o_new_user->setCompanyEn($this->getPost('CompanyEn'));
			$o_new_user->setAddress('');
			$o_new_user->setDeptJob($this->getPost('DeptJob'));
			$o_new_user->setPhone($this->getPost('Phone'));
			$o_new_user->setEmail($this->getPost('Email'));
			$o_new_user->setRegisterDate($this->GetDate());
			$o_new_user->setOpenId($this->getPost('OpenId'));
			$o_new_user->setDelFlag(0);
			$b_rusult=$o_new_user->Save();
			if ($b_rusult!=true)
			{
				$nickname=$this->FilterEmoji($a_user_info['nickname']);
				$audit=1;
				$onsite=0;
				if ($this->getPost('Reg')=='0')
				{
					$onsite=1;;//如果没有临时信息，那么属于现场签到
				}
				$sign=1;
				//如果保存失败，那么给出提示。
				require_once 'log.php';
				//$this->setReturn ( 'parent.dialog_close();parent.dialog_show("对不起，提交信息失败，请重试！<br/>如多次失败，请重新进入报名。");' );
			}
			$n_user_id=$o_new_user->getId();
		}else{
			$n_user_id=$o_user_info->getId(0);
			$o_new_user=new WX_User_Info($n_user_id);
			$o_new_user->setPhoto($a_user_info['headimgurl']);
			$o_new_user->setNickname($this->FilterEmoji($a_user_info['nickname']));
			$o_new_user->setUserName($this->getPost('Name'));
			$o_new_user->setCompany($this->getPost('Company'));
			$o_new_user->setCompanyEn($this->getPost('CompanyEn'));
			$o_new_user->setDeptJob($this->getPost('DeptJob'));
			$o_new_user->setPhone($this->getPost('Phone'));
			$o_new_user->setEmail($this->getPost('Email'));
			$o_new_user->setDelFlag(0);
			$o_new_user->Save();
		}
		//检查是否已经报过名
		$o_user_activity=new WX_User_Activity();
		$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $this->getPost('Id')));
		$o_user_activity->PushWhere(array("&&", "UserId", "=", $n_user_id));
		if($o_user_activity->getAllCount()>0)
		{
			//说明是已经报名的用户，标记为已签到
			$o_user_activity=new WX_User_Activity($o_user_activity->getId(0));
			$o_user_activity->setSigninFlag(1);
			if ($this->getPost('Reg')=='0')
			{
				$o_user_activity->setOnsiteFlag(1);//如果没有临时信息，那么属于现场签到
			}
			$o_user_activity->Save();
		}else{
			//说明是现场签到的用户
			$o_user_activity=new WX_User_Activity();
			$o_user_activity->setActivityId($this->getPost('Id'));
			$o_user_activity->setUserId($n_user_id);
			$o_user_activity->setSigninFlag(1);
			if ($this->getPost('Reg')=='0')
			{
				$o_user_activity->setOnsiteFlag(1);//如果没有临时信息，那么属于现场签到
			}
			$o_user_activity->setAuditFlag(1);
			$o_user_activity->Save();
		}
		//开始用户分组
		$o_sysinfo=new Base_Setup(1);
		$o_activity=new WX_Activity($this->getPost('Id'));
		//是否加注册成功页面
		$s_reg_page='';
		if ($this->getPost('Reg')=='0')
		{
			$s_reg_page='reg_';
		}
		//发送确认信息
		$s_url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$o_token->access_token;
		$data = array(
	    	'touser' => $openId, // openid是发送消息的基础
			'template_id' => 'ncHgsg53CN7CQYs7MJMk9iW-U5NDTUyMyTC3fnsKPIo', // 模板id
			'url' => $o_sysinfo->getHomeUrl().'sub/wechat/signin_success_'.$s_reg_page.$this->getPost('SceneId').'.php', // 点击跳转地址
			'topcolor' => '#FF0000', // 顶部颜色
			'data' => array(
				'first' => array('value' => '微信扫码签到成功！
			'),
				'keyword1' => array('value' => $o_activity->getTitle(),'color'=>'#173177'),
				'keyword2' => array('value' => $o_activity->getAddress(),'color'=>'#173177'),
				'keyword3' => array('value' => $this->getPost('Name'),'color'=>'#173177'),
				'keyword4' => array('value' => $this->getPost('Phone'),'color'=>'#173177'),
				'remark' => array('value' => '')
			)
			);
		$curlUtil->https_request($s_url, json_encode($data));
		//检查临时用户是否签到，如果是，标注签到
		$o_user_temp=new WX_User_Info_Temp();
		$o_user_temp->PushWhere(array("&&", "ActivityId", "=", $this->getPost('Id')));
		$o_user_temp->PushWhere(array("&&", "Phone", "=", $this->getPost('Phone')));
		$n_count=$o_user_temp->getAllCount();
		for($i=0;$i<$n_count;$i++)
		{
			$o_temp=new WX_User_Info_Temp($o_user_temp->getId($i));
			$o_temp->setSigninFlag(1);
			$o_temp->Save();
		}
		//如果活动ID=1042，那么自动进入奖池
		if($this->getPost('Id')=='1042')
		{
		    $o_join=new WX_User_Activity_Join();
		    $o_join->PushWhere(array("&&", "UserId", "=", $o_new_user->getId()));
		    $o_join->PushWhere(array("&&", "ActivityId", "=", '1042'));
		    if ($o_join->getAllCount()>0)
		    {
		        //更新
		        $o_join=new WX_User_Activity_Join($o_join->getId(0));
		    }else{
		        //新建
		        $o_join=new WX_User_Activity_Join();
		        $o_join->setUserId($o_new_user->getId());
		        $o_join->setActivityId('1042');
		    }
		    $o_join->setRound1(1);
		    $o_join->Save();
		}
		$this->setReturn ( 'parent.submit_success()' );
	}
	/* 2016迪拜邮轮中国路演
	public function Signin()
	{
		sleep(2);
		require_once 'userGroup.class.php';
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$o_token=new accessToken();
		$curlUtil = new curlUtil();
		$openId = $this->getPost('OpenId');
		$o_token=new accessToken();
		$s_token=$o_token->access_token;
		//通过接口获取用户OpenId
		$s_url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$s_token.'&openid='.$openId.'&lang=zh_CN';
		$o_util=new curlUtil();
		$s_return=$o_util->https_request($s_url);
		$a_user_info=json_decode($s_return, true);
		//判断是否重复提交报名
		$o_user_info=new WX_User_Info();
		$o_user_info->PushWhere(array("&&", "OpenId", "=",$openId));
		$n_user_id=0;
		if ($o_user_info->getAllCount()==0)
		{
			//是现场签到的用户，并且没有信息，需要新建信息
			//需要新建用户信息
			$o_new_user=new WX_User_Info();
			$o_new_user->setPhoto($a_user_info['headimgurl']);
			$o_new_user->setNickname($a_user_info['nickname']);
			if ($a_user_info['sex']==2)
			{
				$o_new_user->setSex('女');
			}else{
				$o_new_user->setSex('男');
			}			
			$o_new_user->setUserName($this->getPost('Name'));
			$o_new_user->setCompany($this->getPost('Company'));
			$o_new_user->setAddress('');
			$o_new_user->setDeptJob($this->getPost('DeptJob'));
			$o_new_user->setPhone($this->getPost('Phone'));
			$o_new_user->setEmail($this->getPost('Email'));
			$o_new_user->setRegisterDate($this->GetDate());
			$o_new_user->setOpenId($this->getPost('OpenId'));
			$o_new_user->setDelFlag(0);
			$b_rusult=$o_new_user->Save();
			if ($b_rusult!=true)
			{
				$audit=1;
				$onsite=1;
				$sign=1;
				//如果保存失败，那么给出提示。
				require_once 'log.php';
				//$this->setReturn ( 'parent.dialog_close();parent.dialog_show("对不起，提交信息失败，请重试！<br/>如多次失败，请重新进入报名。");' );
			}
			$n_user_id=$o_new_user->getId();
		}else{
			$n_user_id=$o_user_info->getId(0);
			$o_new_user=new WX_User_Info($n_user_id);
			$o_new_user->setPhoto($a_user_info['headimgurl']);
			$o_new_user->setNickname($a_user_info['nickname']);
			$o_new_user->setUserName($this->getPost('Name'));
			$o_new_user->setCompany($this->getPost('Company'));
			$o_new_user->setDeptJob($this->getPost('DeptJob'));
			$o_new_user->setPhone($this->getPost('Phone'));
			$o_new_user->setEmail($this->getPost('Email'));
			$o_new_user->setDelFlag(0);
			$o_new_user->Save();
		}
		//检查是否已经报过名
		$o_user_activity=new WX_User_Activity();
		$o_user_activity->PushWhere(array("&&", "ActivityId", "=", $this->getPost('Id')));
		$o_user_activity->PushWhere(array("&&", "UserId", "=", $n_user_id));
		if($o_user_activity->getAllCount()>0)
		{
			//说明是已经报名的用户，标记为已签到
			$o_user_activity=new WX_User_Activity($o_user_activity->getId(0));
			$o_user_activity->setSigninFlag(1);
			$o_user_activity->Save();
		}else{
			//说明是现场签到的用户
			$o_user_activity=new WX_User_Activity();
			$o_user_activity->setActivityId($this->getPost('Id'));
			$o_user_activity->setUserId($n_user_id);
			$o_user_activity->setSigninFlag(1);
			$o_user_activity->setOnsiteFlag(1);
			$o_user_activity->setAuditFlag(1);
			$o_user_activity->Save();
		}
		//开始用户分组
		$o_sysinfo=new Base_Setup(1);
		$o_activity=new WX_Activity($this->getPost('Id'));
		//发送确认信息
		$s_url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$o_token->access_token;
		$data = array(
	    	'touser' => $openId, // openid是发送消息的基础
			'template_id' => 'ncHgsg53CN7CQYs7MJMk9iW-U5NDTUyMyTC3fnsKPIo', // 模板id
			'url' => $o_sysinfo->getHomeUrl().'sub/wechat/signin_guide_'.$this->getPost('SceneId').'.php', // 点击跳转地址
			'topcolor' => '#FF0000', // 顶部颜色
			'data' => array(
				'first' => array('value' => '微信扫码签到成功！
			'),
				'keyword1' => array('value' => $o_activity->getTitle(),'color'=>'#173177'),
				'keyword2' => array('value' => $o_activity->getAddress(),'color'=>'#173177'),
				'keyword3' => array('value' => $this->getPost('Name'),'color'=>'#173177'),
				'keyword4' => array('value' => $this->getPost('Phone'),'color'=>'#173177'),
				'remark' => array('value' => '
日程：
　　14:00 签到
　　14:30 迪拜目的地介绍
　　15:15 迪拜邮轮产品培训会
　　16:35 自由洽谈
　　17:00 抽奖
　　          活动结束
')
			)
			);
		$curlUtil->https_request($s_url, json_encode($data));
		$this->setReturn ( 'parent.submit_success()' );
		
	}*/
	public function SaveRound()
	{
		$o_round=new WX_User_Activity_Join($this->getPost('id'));
		if($o_round->getUserId()>0)//判断用户抽奖信息是否存在。
		{
			//--------------------------------确保只要被抽中一次，就没有别的机会了
			$o_round->setSuccess1(1);
			$o_round->setSuccess2(1);
			$o_round->setSuccess3(1);
			$o_round->setSuccess4(1);
			$o_round->setSuccess5(1);
			//---------------------------------
			if ($this->getPost('round')==1)
			{
				$o_round->setSuccess1(2);
			}
			if ($this->getPost('round')==2)
			{
				$o_round->setSuccess2(2);
			}
			if ($this->getPost('round')==3)
			{
				$o_round->setSuccess3(2);
			}
			if ($this->getPost('round')==4)
			{
				$o_round->setSuccess4(2);
			}
			if ($this->getPost('round')==5)
			{
				$o_round->setSuccess5(2);
			}
			$o_round->Save();
		}
		$a_result = array ();
		echo(json_encode ($a_result));
		
	}
	public function getUserUploadPhoto()
	{
		$o_round=new WX_User_Activity_Join($this->getPost('id'));
		$o_photo=new WX_User_Activity_Join_Uploadphoto();
		$o_photo->PushWhere(array("&&", "UserId", "=", $o_round->getUserId()));
		$o_photo->PushOrder ( array ('Id', 'D') );
		$o_photo->getAllCount();
		if ($o_photo->getAllCount()>0)
		{
			$a_result = array ('photo'=>'../../'.$o_photo->getPath(0));
		}else{
			$a_result = array ('photo'=>'');
		}
		echo(json_encode ($a_result));
	}
	public function GetUserTempInfo()
	{
		
		//$a_result = array ('flag'=>'0');
		//echo(json_encode ($a_result));
		//exit(0);
		$o_user=new WX_User_Info_Temp();
		$o_user->PushWhere(array("&&", "Phone", "=", $this->getPost('phone')));
		if ($o_user->getAllCount()>0)
		{
			//找到用户签到信息
			$a_result = array ('flag'=>'1',
			'company'=>$o_user->getCompany(0),
			'deptjob'=>$o_user->getDeptJob(0),
			'name'=>$o_user->getUserName(0),
			'email'=>$o_user->getEmail(0)
			);
		}else{
			//未找到
			$a_result = array ('flag'=>$this->getPost('phone'));
		}
		echo(json_encode ($a_result));
	}
	public function AnswerSubmit()
	{
		sleep(2);
		$s_answer=$this->getPost('Answer');
		$s_question=$this->getPost('Question');
		$s_open_id=$this->getPost('OpenId');
		$s_right=$this->getPost('Right');
		$s_date=$this->GetDate();
		//查找活动里是否有当前日期的活动
		$o_activity=new WX_Activity();
		$o_activity->PushWhere(array("&&", "ActivityDate", "=", $s_date));
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
				//说明有活动
				$s_round=$s_question;//抽奖奖池
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
				if ($s_right==$s_answer)
				{
					eval ('$o_join->setRound'.$s_round.'(1);');
				}else{
					eval ('$o_join->setRound'.$s_round.'(0);');
				}
				eval ('$o_join->setAnswer'.$s_round.'($s_answer);');
				//$o_join->setRound5(1);//因为第五轮是参与所有答题的人，所以自动进入奖池
				$o_join->Save();				
			}
		}
		//回答正确
		if ($s_right==$s_answer)
		{
			$this->setReturn ( 'parent.submit_success()' );
		}else{
			//回答错误
			$this->setReturn ( 'parent.submit_error()' );
		}
	}
	/*
	 * 2016迪拜路演后备份，支持每次只打一道题。
	public function AnswerSubmit()
	{
		sleep(2);
		$s_answer=$this->getPost('Answer');
		$s_question=$this->getPost('Question');
		$s_open_id=$this->getPost('OpenId');
		$s_right=$this->getPost('Right');
		if ($s_right==$s_answer)
		{
			$s_date=$this->GetDate();
			//查找活动里是否有当前日期的活动
			$o_activity=new WX_Activity();
			$o_activity->PushWhere(array("&&", "ActivityDate", "=", $s_date));
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
					
					//说明有活动
					$s_round=$s_question;//抽奖奖池
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
					//$o_join->setRound5(1);//因为第五轮是参与所有答题的人，所以自动进入奖池
					$o_join->Save();
				}
			}
			//回答正确
			$this->setReturn ( 'parent.submit_success()' );
		}else{
			//回答错误
			$this->setReturn ( 'parent.submit_error()' );
		}
	}
	*/
}

?>