<?php
error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
class Operate extends Bn_Basic {
	protected $N_PageSize= 50;
	Public function MeetingList($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_activity = new WX_Activity();
		$s_key=$this->getPost('key');
		//开始判断用户权限，只显示已有的会议
		$a_activity=array();
		$a_activity=json_decode($o_user->getActivityId());
		if(count($a_activity)>0)
		{
			
			for($i=0;$i<count($a_activity);$i++)
			{
				$o_activity->PushWhere ( array ('||', 'Type', '=',1) );
				$o_activity->PushWhere ( array ('&&', 'Id', '=',$a_activity[$i]) );
			}
		}else{
			$o_activity->PushWhere ( array ('&&', 'Type', '=',1) );
		}
		$o_activity->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_activity->PushOrder ( array ('ActivityTime','A') );
		$o_activity->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_activity->setCountLine ( $this->N_PageSize );
		$n_count = $o_activity->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_activity->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_activity->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_activity->getAllCount ();//总记录数
		$n_count = $o_activity->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			$a_button = array ();
			array_push ( $a_button, array ('报名审核', "location='meeting_audit.php?id=".$o_activity->getId($i)."'" ) );//删除
			array_push ( $a_button, array ('KeyAgent', "location='meeting_keyagent.php?id=".$o_activity->getId($i)."'" ) );//删除
			array_push ( $a_button, array ('数据导出', "window.open('output_all.php?id=".$o_activity->getId($i)."','_blank')" ) );//删除
			//如果会议已经过期，那么不显示群发提醒按钮
            $o_date = new DateTime ( 'Asia/Chongqing' );
			$today=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' );
			
			if(strtotime($today) <= strtotime($o_activity->getActivityDate($i))){
				array_push ( $a_button, array ('发送提醒', "send_reminder(".$o_activity->getId($i).",'".rawurlencode($o_activity->getTitle($i))."','".rawurlencode($o_activity->getActivityDate($i).'（周'.$o_activity->getWeek($i).'）'.$o_activity->getActivityTime($i))."','".rawurlencode($o_activity->getRemFirst($i))."','".rawurlencode($o_activity->getRemRemark($i))."')" ) );//发送提醒
			}
			//判断是否显示培训题查看按钮
			$o_training=new Wx_Activity_Training_Questions();
			$o_training->PushWhere ( array ('&&', 'ActivityId', '=',$o_activity->getId($i)) );
			if ($o_training->getAllCount())
			{
				array_push ( $a_button, array ('随堂测试', "location='meeting_training.php?id=".$o_activity->getId($i)."'" ) );//删除
			}
			//array_push ( $a_button, array ('修改', "audit_reject(this,'".$o_activity->getId($i)."')" ) );//删除
			//统计总人数和待审核与签到
			$o_user_activity=new WX_User_Activity();
			$o_user_activity->PushWhere ( array ('&&', 'ActivityId', '=',$o_activity->getId($i)) );
			$o_user_activity->PushWhere ( array ('&&', 'AuditFlag', '=',0) );
			$n_audit=$o_user_activity->getAllCount();
			$o_user_activity=new WX_User_Activity();
			$o_user_activity->PushWhere ( array ('&&', 'ActivityId', '=',$o_activity->getId($i)) );
			$n_sum=$o_user_activity->getAllCount();
			$o_user_activity=new WX_User_Activity();
			$o_user_activity->PushWhere ( array ('&&', 'ActivityId', '=',$o_activity->getId($i)) );
			$o_user_activity->PushWhere ( array ('&&', 'SigninFlag', '=',1) );
			$n_signin=$o_user_activity->getAllCount();
			//判断会议是否结束
			$s_meeting_flag='';
			if (strtotime($this->GetDate())>strtotime($o_activity->getActivityDate ( $i )))
			{
				$s_meeting_flag='<span class="label label-warning">已结束</span>';
			}
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				$o_activity->getActivityDate ( $i ).' '.$o_activity->getActivityTime ( $i ),
				$o_activity->getTitle ( $i ).' '.$s_meeting_flag,
				$o_activity->getLocation ( $i ),
				$o_activity->getAddress ( $i ),
				$o_activity->getExpiryDate ( $i ),
				'<span class="label label-danger">'.$n_audit.'</span>',
				'<span class="label label-primary">'.$n_sum.'</span>',
				'<span class="label label-success">'.$n_signin.'</span>',
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0,40);
		$a_title=$this->setTableTitle($a_title,'会议日期', 'ActivityDate', 0, 0);
		$a_title=$this->setTableTitle($a_title,'名称', 'Title', 0, 0);
		$a_title=$this->setTableTitle($a_title,'地区', 'Location', 0, 40);
		$a_title=$this->setTableTitle($a_title,'地址', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'报名截止', '', 0, 90);
		$a_title=$this->setTableTitle($a_title,'待审核', '', 0, 60);
		$a_title=$this->setTableTitle($a_title,'总人数', '', 0, 60);
		$a_title=$this->setTableTitle($a_title,'签到人数', '', 0, 70);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 100);
		$this->SendJsonResultForTable($n_allcount,'MeetingList', 'yes', $n_page, $a_title, $a_row);
	}
	public function MeetingAuditList($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		require_once RELATIVITY_PATH . 'sub/wechat/include/db_view.class.php';
		$o_user = new View_WX_User_Info(); 
		$s_id=$this->getPost('key');
		if ($this->getPost('other_key')!='')
		{
			$o_user->PushWhere ( array ('||', 'UserName', 'Like','%'.$this->getPost('other_key').'%') );
			$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
			$o_user->PushWhere ( array ('||', 'Company', 'Like','%'.$this->getPost('other_key').'%') );
			$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
			$o_user->PushWhere ( array ('||', 'DeptJob', 'Like','%'.$this->getPost('other_key').'%') );
			$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
			$o_user->PushWhere ( array ('||', 'Phone', 'Like','%'.$this->getPost('other_key').'%') );
			$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
			$o_user->PushWhere ( array ('||', 'Email', 'Like','%'.$this->getPost('other_key').'%') );
			$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
		}else{
			$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
		}
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			
			//数据行
			$a_button = array ();
			if ($o_user->getAuditFlag ( $i )==1)
			{
				//是否审核通过
				$s_audit='<span class="label label-success">通过</span>';
				array_push ( $a_button, array ('删除', "audit_delete(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			}else{
				$s_audit='<span class="label label-danger">未批准</span>';
				if ($o_user->getSigninFlag ( $i )==0)
				{
					array_push ( $a_button, array ('批准', "audit_approve(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
					array_push ( $a_button, array ('拒绝', "audit_reject(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
				}else{
				    array_push ( $a_button, array ('删除', "audit_delete(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
				}				
			}
			if ($o_user->getRound ( $i )==1)
			{
			    array_push ( $a_button, array ('禁止抽奖', "audit_disable_round(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			}else{
			    array_push ( $a_button, array ('允许抽奖', "audit_enable_round(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			}
			
			array_push ( $a_button, array ('黑名单', "audit_blacklist(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			//如果已经取消关注，需要加标签
			$s_sign_name='';
			if ($o_user->getDelFlag ( $i )==1)
			{
				$s_sign_name=' <span class="label label-danger">取消关注</span>';
			}
			
			if ($o_user->getSigninFlag ( $i )==1)
			{
				if ($o_user->getOnsiteFlag ( $i )==1)
				{
					$s_signin='<span class="label label-success">现场签到</span>';
				}else{
					$s_signin='<span class="label label-success">签到</span>';
				}				
			}else{
				$s_signin='<span class="label label-danger">未签到</span>';
			}
			$s_round_state='';
			if ($o_user->getRound ( $i )==0)
			{
			    $s_round_state=' <span class="label label-danger">禁止抽奖</span>';
			}
			//构建参会场次
			$a_items=json_decode($o_user->getItems ( $i ));
			$s_items='';
			for($k=0;$k<count($a_items);$k++)
			{
			    $s_items.=urldecode($a_items[$k]).'<br/>';
			}
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				'<img style="width:32px;height:32px;cursor:pointer;" src="'.$o_user->getPhoto ( $i ).'" onclick="open_photo(\''.$o_user->getPhoto ( $i ).'\')">',
				$o_user->getNickname ( $i ),
				$o_user->getCompany ( $i ).'<br/>'.$o_user->getCompanyEn ( $i ),
				$o_user->getDeptJob ( $i ),
				$o_user->getUserName ( $i ).$s_sign_name.$s_round_state,
				$o_user->getPhone ( $i ),
				$o_user->getEmail ( $i ),
				$s_audit,
				$s_signin,
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 40);
		$a_title=$this->setTableTitle($a_title,'头像', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'微信昵称', 'Nickname', 0, 0);
		$a_title=$this->setTableTitle($a_title,'公司名称', 'Company', 0, 0);
		$a_title=$this->setTableTitle($a_title,'职务', 'DeptJob', 0, 0);
		$a_title=$this->setTableTitle($a_title,'姓名', 'UserName', 0, 60);
		$a_title=$this->setTableTitle($a_title,'手机号', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'邮箱', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'审核', 'AuditFlag', 0, 60);
		$a_title=$this->setTableTitle($a_title,'签到', 'SigninFlag', 0, 60);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 85);
		$this->SendJsonResultForTable($n_allcount,'MeetingAuditList', 'yes', $n_page, $a_title, $a_row);
	}
	public function MeetingKeyAgent($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$this->N_PageSize=1000;
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		require_once RELATIVITY_PATH . 'sub/wechat/include/db_view.class.php';
		$o_user = new WX_User_Info_Temp(); 
		$s_id=$this->getPost('key');
		$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
		$o_user->PushWhere ( array ('&&', 'Type', '=','key agent') );
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			//数据行
			if ($o_user->getSigninFlag ( $i )==1)
			{
				$s_signin='<span class="label label-success">签到</span>';
			}else{
				$s_signin='<span class="label label-danger">未签到</span>';
			}
			array_push ($a_row, array (
				($i+1+$this->N_PageSize*($n_page-1)),
				$o_user->getUserName ( $i ),
				$o_user->getCompany ( $i ),
				$o_user->getDeptJob ( $i ),				
				$o_user->getPhone ( $i ),
				$o_user->getEmail ( $i ),
				$s_signin,
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 40);
		$a_title=$this->setTableTitle($a_title,'姓名', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'公司名称', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'职务', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'手机号', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'邮箱', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'签到', 'SigninFlag', 0, 60);
		$this->SendJsonResultForTable($n_allcount,'MeetingKeyAgent', 'no', $n_page, $a_title, $a_row);
	}
	public function GetAuditStatus($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		//统计审核人数，和签到人数，取消关注人数
		$s_sceneid=$this->getPost('sceneid');
		$o_user = new WX_User_Activity();
		$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_sceneid) );
		$o_user->PushWhere ( array ('&&', 'AuditFlag', '=',1) );
		$n_audit=$o_user->getAllCount();
		$o_user = new WX_User_Activity();
		$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_sceneid) );
		$o_user->PushWhere ( array ('&&', 'SigninFlag', '=',1) );
		$n_signin=$o_user->getAllCount();
		$o_user = new WX_User_Activity();
		$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_sceneid) );
		$n_total=$o_user->getAllCount();
		
		$o_activity=new WX_Activity($s_sceneid);
		$a_result = array (
					'status' =>' <span class="label label-success">批准 '.$n_audit.'</span>&nbsp;&nbsp;<span class="label label-primary">签到 '.$n_signin.'</span>&nbsp;&nbsp;<span class="label label-default">总数 '.$n_total.'</span>',
					'table_title' =>'<b>'.$o_activity->getLocation().'站</b>&nbsp;&nbsp;报名审核'
				);
		echo(json_encode ($a_result));
	}
	public function AuditApprove($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$o_sys_info=new Base_Setup(1);
		$s_homepage=$o_sys_info->getHomeUrl();
		$o_user_activity = new WX_User_Activity($this->getPost('id'));
		$o_user_activity->setAuditFlag(1);
		$o_user_activity->Save();
		
		$o_user_info=new WX_User_Info($o_user_activity->getUserId());

		$o_activity=new WX_Activity($o_user_activity->getActivityId());
	    //给用户发送消息
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$o_token=new accessToken();
		$curlUtil = new curlUtil();
		$s_url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$o_token->access_token;
		$data = array(
	    	'touser' => $o_user_info->getOpenId(), // openid是发送消息的基础
			'template_id' => 'QZhuOiklHx5T1njlqx90J398pIl9JXSYCPJ8LkemDK0', // 模板id
			'url' => $o_sys_info->getHomeUrl().'sub/wechat/reg_transfer.php?id='.$o_activity->getId(), // 点击跳转地址
			'topcolor' => '#FF0000', // 顶部颜色
			'data' => array(
				'first' => array('value' => $o_activity->getAuditPassFirst().'
				'),
				'keyword1' => array('value' => '会议报名审核成功','color'=>'#173177'),
				'keyword2' => array('value' => Date('Y-m-d h:m:s',time()),'color'=>'#173177'),
				'remark' => array('value' => '
'.$o_activity->getAuditPassRemark()) 
			)
		);
		$curlUtil->https_request($s_url, json_encode($data));
		
		$a_result = array ();
		echo(json_encode ($a_result));
	}
	public function AuditReject($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$o_user_activity = new WX_User_Activity($this->getPost('id'));
		$o_user_info=new WX_User_Info($o_user_activity->getUserId());

		$o_activity=new WX_Activity($o_user_activity->getActivityId());
	    //给用户发送消息
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$o_token=new accessToken();
		$curlUtil = new curlUtil();
		$s_url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$o_token->access_token;
		$data = array(
	    	'touser' => $o_user_info->getOpenId(), // openid是发送消息的基础
			'template_id' => 'QZhuOiklHx5T1njlqx90J398pIl9JXSYCPJ8LkemDK0', // 模板id
			'url' => '', // 点击跳转地址
			'topcolor' => '#FF0000', // 顶部颜色
			'data' => array(
				'first' => array('value' => $o_activity->getAuditFailFirst().'
				'),
				'keyword1' => array('value' => '会议报名审核未通过','color'=>'#173177'),
				'keyword2' => array('value' => Date('Y-m-d h:m:s',time()),'color'=>'#173177'),
				'remark' => array('value' => '
'.$o_activity->getAuditFailRemark()) 
			)
		);
		$curlUtil->https_request($s_url, json_encode($data));

		$o_user_activity->Deletion();
		//将用户放入大众组
		/*$to_groupid=0;
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$token = new accessToken();
    	$curlUtil = new curlUtil();
        $data = '{"openid":"'.$openid.'",
        		  "to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token->access_token;
        $res = $curlUtil->https_request($url, $data);*/
		$a_result = array ();
		echo(json_encode ($a_result));
	}
	public function AuditBlacklist($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		//删除所有活动
		
		$o_user = new WX_User_Activity($this->getPost('id'));
		$o_user_id=$o_user->getUserId();
		$o_user->DeleteAll($o_user_id);
		//将用户标记为黑名单
		$o_user=new WX_User_Info($o_user_id);
		$o_user->setBlock(1);
		$o_user->Save();
		//将用户放入大众组
		/*$to_groupid=0;
		require_once RELATIVITY_PATH . 'sub/wechat/include/accessToken.class.php';
		$token = new accessToken();
    	$curlUtil = new curlUtil();
        $data = '{"openid":"'.$openid.'",
        		  "to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token->access_token;
        $res = $curlUtil->https_request($url, $data);*/
		$a_result = array ();
		echo(json_encode ($a_result));
	}
	public function AuditDisableRound($n_uid)
	{
	    if (! ($n_uid > 0)) {
	        $this->setReturn('parent.goto_login()');
	    }
	    $o_user = new Single_User($n_uid);
	    if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
	    //删除所有活动
	
	    $o_user = new WX_User_Activity($this->getPost('id'));
	    $o_user=new WX_User_Info($o_user->getUserId());
	    $o_user->setRound(0);
	    $o_user->Save();
	    $a_result = array ();
	    echo(json_encode ($a_result));
	}
	public function AuditEnableRound($n_uid)
	{
	    if (! ($n_uid > 0)) {
	        $this->setReturn('parent.goto_login()');
	    }
	    $o_user = new Single_User($n_uid);
	    if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
	    //删除所有活动
	
	    $o_user = new WX_User_Activity($this->getPost('id'));
	    $o_user=new WX_User_Info($o_user->getUserId());
	    $o_user->setRound(1);
	    $o_user->Save();
	    $a_result = array ();
	    echo(json_encode ($a_result));
	}
	public function AuditDelete($n_uid)
	{
	    if (! ($n_uid > 0)) {
	        $this->setReturn('parent.goto_login()');
	    }
	    $o_user = new Single_User($n_uid);
	    if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
	    //删除所有活动
	
	    $o_user = new WX_User_Activity($this->getPost('id'));
	    $o_user->Deletion();
	    $a_result = array ();
	    echo(json_encode ($a_result));
	}
	public function SendReminder($n_uid)
	{
		sleep(2);
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$o_sys_info=new Base_Setup(1);
		$s_homepage=$o_sys_info->getHomeUrl();
		$o_activity = new WX_Activity($this->getPost('id'));
		
		//获取用户列表
		require_once 'db_table.class.php';
		require_once RELATIVITY_PATH . 'sub/wechat/include/db_view.class.php';
		$o_user_info = new View_WX_User_Info(); 
		$o_user_info->PushWhere ( array ('&&', 'ActivityId', '=',$o_activity->getId()) );
		$o_user_info->PushWhere ( array ('&&', 'AuditFlag', '=',1) );
		$n_count=$o_user_info->getAllCount();
		for($i=0;$i<$n_count;$i++)
		{
			$o_reminder=new WX_User_Reminder();
			$o_reminder->setOpenId($o_user_info->getOpenId($i));
			$o_reminder->setFirst('尊敬的用户，您好！欢迎参加'.$o_activity->getTitle().'活动，请按以下行程安排好您的时间：');
			$o_reminder->setKeyword1($o_activity->getActivityDate().'（周'.$o_activity->getWeek().'）'.$o_activity->getActivityTime());
			$o_reminder->setKeyword2($o_activity->getRemFirst());
			$o_reminder->setRemark($o_activity->getRemRemark());
			$o_reminder->setActivityId($o_activity->getId());
			$o_reminder->Save();
		}
		$a_result = array ();
		echo(json_encode ($a_result));
	}
	public function MeetingTrainingList($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100401 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		require_once RELATIVITY_PATH . 'sub/wechat/include/db_view.class.php';
		$o_user = new Wx_Activity_Training_Questions(); 
		$s_id=$this->getPost('key');
		$o_user->PushWhere ( array ('&&', 'ActivityId', '=',$s_id) );
		$o_user->PushOrder ( array ($this->getPost('item'), $this->getPost('sort') ) );
		$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize ); //起始记录
		$o_user->setCountLine ( $this->N_PageSize );
		$n_count = $o_user->getAllCount ();
		if (($this->N_PageSize * ($n_page - 1)) >= $n_count) {
			$n_page = ceil ( $n_count / $this->N_PageSize );
			$o_user->setStartLine ( ($n_page - 1) * $this->N_PageSize );
			$o_user->setCountLine ( $this->N_PageSize );
		}
		$n_allcount = $o_user->getAllCount ();//总记录数
		$n_count = $o_user->getCount ();
		$a_row = array ();
		for($i = 0; $i < $n_count; $i ++) {
			//数据行
			$a_button = array ();
			$o_options=new Wx_Activity_Training_Options();
			$o_options->PushWhere ( array ('&&', 'QuestionId', '=',$o_user->getId($i)) );
			$s_option='';
			$a_right=json_decode($o_user->getAnswer($i));
			for($j=0;$j<$o_options->getAllCount();$j++)
			{
				if (in_array($o_options->getId($j),$a_right))
				{
					$s_option.='<div style="padding-top:3px;padding-bottom:3px;"><span class="label label-success">'.$o_options->getNumber($j).'.'.$o_options->getOption($j).'</span></div>';
				}else{
					$s_option.='<div>'.$o_options->getNumber($j).'.'.$o_options->getOption($j).'</div>';
				}
			}
			$n_rate=sprintf("%.1f",$o_user->getErrorNum($i)/($o_user->getRightNum($i)+$o_user->getErrorNum($i))*100);//百分比保留1位小数
			array_push ($a_row, array (
				$o_user->getNumber ( $i ),
				$o_user->getQuestion ( $i ),
				$s_option,
				$n_rate.'%',
				$o_user->getScore ( $i ).' 分'
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,'题号', 'Number', 0, 80);
		$a_title=$this->setTableTitle($a_title,'题目', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'选项', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'错误率', 'ErrorNum', 0,80);
		$a_title=$this->setTableTitle($a_title,'奖励分数', '', 0, 80);
		$this->SendJsonResultForTable($n_allcount,'MeetingTrainingList', 'no', $n_page, $a_title, $a_row);
	}
}

?>