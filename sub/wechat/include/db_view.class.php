<?php
require_once RELATIVITY_PATH.'include/db_operate.class.php';
require_once RELATIVITY_PATH.'include/db_connect.class.php';
//1111111111111111111111111111111111111111111111
class View_WX_User_Info extends CRUD
{
	protected $Id;
	protected $Photo;
	protected $Nickname;
	protected $UserName;
	protected $Company;
	protected $DeptJob;
	protected $Address;
	protected $OpenId;
	protected $Phone;
	protected $Email;
	protected $RegisterDate;
	protected $DelFlag;
	protected $Sex;
	protected $ActivityId;
	protected $SigninFlag;
	protected $AuditFlag;
	protected $OnsiteFlag;
	protected $Round;
	protected $UserActivityId;
	
	protected function DefineKey(){
      return 'wx_user_info.id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_info` INNER JOIN `wx_user_activity` ON `wx_user_info`.`id` = `wx_user_activity`.`user_id';
   	}
   	protected function DefineRelationMap(){
      	return(array('wx_user_info.id' => 'Id',
      				'wx_user_info.photo' => 'Photo',
		      	'wx_user_info.nickname' => 'Nickname',
		      	'wx_user_info.sex' => 'Sex',
      			'wx_user_info.user_name' => 'UserName',
		      	'wx_user_info.company' => 'Company',
		      	'wx_user_info.dept_job' => 'DeptJob',
		      	'wx_user_info.address' => 'Address',
		      	'wx_user_info.openid' => 'OpenId',
		      	'wx_user_info.phone' => 'Phone',
		      	'wx_user_info.email' => 'Email',
		      	'wx_user_info.register_date' => 'RegisterDate',
		      	'wx_user_info.del_flag' => 'DelFlag',
      			'wx_user_activity.audit_flag' => 'AuditFlag',
      			'wx_user_activity.signin_flag' => 'SigninFlag',
      			'wx_user_activity.onsite_flag' => 'OnsiteFlag',
      			'wx_user_activity.id' => 'UserActivityId',
      	        'wx_user_info.round' => 'Round',
      			'wx_user_activity.activity_id' => 'ActivityId'
               ));
    }
}

//1111111111111111111111111111111111111111111111
?>