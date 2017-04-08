<?php
error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
class Operate extends Bn_Basic {
	protected $N_PageSize= 25;
	
	public function UserList($n_uid)
	{	
		$this->N_PageSize= 50;
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100501 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_user = new WX_User_Info(); 
		$s_key=$this->getPost('key');
		if ($s_key!='')
		{
			$o_user->PushWhere ( array ('||', 'Nickname', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
			$o_user->PushWhere ( array ('||', 'UserName', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
			$o_user->PushWhere ( array ('||', 'Company', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
			$o_user->PushWhere ( array ('||', 'DeptJob', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
			$o_user->PushWhere ( array ('||', 'Email', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
			$o_user->PushWhere ( array ('||', 'Phone', 'like','%'.$s_key.'%') );
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
		}else{
			$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
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
			$a_button=array();
			//array_push ( $a_button, array ('批准', "audit_approve(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			//array_push ( $a_button, array ('拒绝', "audit_reject(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			array_push ( $a_button, array ('黑名单', "user_block('".$o_user->getId($i)."')" ) );//删除
			//如果已经取消关注，需要加标签
			$s_sign_name='';
			if ($o_user->getDelFlag ( $i )==1)
			{
				$s_sign_name=' <span class="label label-danger">取消关注</span>';
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
				$o_user->getCompany ( $i ),
				$o_user->getDeptJob ( $i ),
				$o_user->getUserName ( $i ).$s_sign_name,
				$o_user->getPhone ( $i ),
				$o_user->getEmail ( $i ),
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 40);
		$a_title=$this->setTableTitle($a_title,'头像', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'微信昵称', 'Nickname', 150, 0);
		$a_title=$this->setTableTitle($a_title,'公司名称', 'Company', 0, 0);
		$a_title=$this->setTableTitle($a_title,'职务', 'DeptJob', 0, 0);
		$a_title=$this->setTableTitle($a_title,'姓名', 'UserName', 0, 60);
		$a_title=$this->setTableTitle($a_title,'手机号', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'邮箱', '', 0, 0);
		//$a_title=$this->setTableTitle($a_title,'审核', 'AuditFlag', 0, 60);
		//$a_title=$this->setTableTitle($a_title,'签到', 'SigninFlag', 0, 60);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 65);
		$this->SendJsonResultForTable($n_allcount,'UserList', 'yes', $n_page, $a_title, $a_row);
	}
	public function BlockList($n_uid)
	{	
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100502 ))return;//如果没有权限，不返回任何值
		$n_page=$this->getPost('page');
		if ($n_page<=0)$n_page=1;
		$o_user = new WX_User_Info(); 
		$o_user->PushWhere ( array ('&&', 'Block', '=',1) );
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
			$a_button=array();
			array_push ( $a_button, array ('解除', "block_enable('".$o_user->getId($i)."')" ) );//删除
			//array_push ( $a_button, array ('拒绝', "audit_reject(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			//array_push ( $a_button, array ('黑名单', "audit_blacklist(this,'".$o_user->getUserActivityId($i)."','".$o_user->getActivityId($i)."')" ) );//删除
			//如果已经取消关注，需要加标签
			$s_sign_name='';
			if ($o_user->getDelFlag ( $i )==1)
			{
				$s_sign_name=' <span class="label label-danger">取消关注</span>';
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
				$o_user->getCompany ( $i ),
				$o_user->getDeptJob ( $i ),
				$o_user->getUserName ( $i ).$s_sign_name,
				$o_user->getPhone ( $i ),
				$o_user->getEmail ( $i ),
				$a_button
				));
		}
		//标题行,列名，排序名称，宽度，最小宽度
		$a_title = array ();
		$a_title=$this->setTableTitle($a_title,Text::Key('Number'), '', 0, 40);
		$a_title=$this->setTableTitle($a_title,'头像', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'微信昵称', 'Nickname', 150, 0);
		$a_title=$this->setTableTitle($a_title,'公司名称', 'Company', 0, 0);
		$a_title=$this->setTableTitle($a_title,'职务', 'DeptJob', 0, 0);
		$a_title=$this->setTableTitle($a_title,'姓名', 'UserName', 0, 60);
		$a_title=$this->setTableTitle($a_title,'手机号', '', 0, 0);
		$a_title=$this->setTableTitle($a_title,'邮箱', '', 0, 0);
		//$a_title=$this->setTableTitle($a_title,'审核', 'AuditFlag', 0, 60);
		//$a_title=$this->setTableTitle($a_title,'签到', 'SigninFlag', 0, 60);
		$a_title=$this->setTableTitle($a_title,Text::Key('Operation'), '', 0, 65);
		$this->SendJsonResultForTable($n_allcount,'BlockList', 'yes', $n_page, $a_title, $a_row);
	}
	public function GetUserStatus($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100501 ))return;//如果没有权限，不返回任何值
		//统计审核人数，和签到人数，取消关注人数
		$o_user = new WX_User_Info();
		$o_user->PushWhere ( array ('&&', 'DelFlag', '=',1) );
		$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
		$n_del=$o_user->getAllCount();
		$o_user = new WX_User_Info();
		$o_user->PushWhere ( array ('&&', 'Block', '=',0) );
		$n_total=$o_user->getAllCount();
		$a_result = array (
					'status' =>'<span class="label label-success">'.$n_total.' 粉丝</span>&nbsp&nbsp&nbsp&nbsp<span class="label label-warning">'.$n_del.' 取消关注</span>'
				);
		echo(json_encode ($a_result));
	}	
	public function BlockEnable($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100502 ))return;//如果没有权限，不返回任何值
		$o_user = new WX_User_Info($this->getPost('id'));
		$o_user->setBlock(0);
		$o_user->Save();
		$a_result = array ();
		echo(json_encode ($a_result));
	}
	public function UserBlock($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User($n_uid);
		if (!$o_user->ValidModule ( 100502 ))return;//如果没有权限，不返回任何值
		$o_user = new WX_User_Info($this->getPost('id'));
		$o_user->setBlock(1);
		$o_user->Save();
		$a_result = array ();
		echo(json_encode ($a_result));
	}	
	
	
}

?>