<?php
require_once RELATIVITY_PATH.'include/db_operate.class.php';
require_once RELATIVITY_PATH.'include/db_connect.class.php';
/**
 * 活动表
 * SceneID是场景id，用来区别二维码的
 * */
class WX_Activity extends CRUD{
   protected $Id;
   protected $SceneId;
   protected $SceneName;
   protected $QrCode;
   protected $MessageType;
   protected $MessageUrl;
   protected $PicUrl;
   protected $Description;
   protected $ExpiryDate;
   protected $ActivityDate;
   protected $Title;
   protected $GroupId;
   protected $Address;
   protected $Location;
   protected $ActivityTime;
   protected $Week;
   protected $Type;
   protected $RegFirst;
   protected $RegRemark;
   protected $AuditPassFirst;
   protected $AuditPassRemark;
   protected $AuditFailFirst;
   protected $AuditFailRemark;
   protected $RemFirst;
   protected $RemRemark;
   protected $NeedAudit;

   protected function DefineKey(){
      return 'id';
   }
   protected function DefineTableName(){
      return 'wx_activity';
   }
   
   protected function DefineRelationMap(){
      return(array('id' => 'Id',
      			   'scene_id' => 'SceneId',
      			   'scene_name' => 'SceneName',
      			   'qr_code' => 'QrCode',
      			   'message_type' => 'MessageType',
            	   'message_url' => 'MessageUrl',
      			   'pic_url' => 'PicUrl',
      			   'description' => 'Description',
      			   'expiry_date' => 'ExpiryDate',
      				'activity_date' => 'ActivityDate',
     				 'group_id' => 'GroupId',
      			   'title' => 'Title',
      'location' => 'Location',
      'activity_time' => 'ActivityTime',
      'address' => 'Address',
      'week' => 'Week',
      'reg_first' => 'RegFirst',
      'reg_remark' => 'RegRemark',
      'audit_pass_first' => 'AuditPassFirst',
      'audit_pass_remark' => 'AuditPassRemark',
      'audit_fail_first' => 'AuditFailFirst',
      'audit_fail_remark' => 'AuditFailRemark',
      'rem_first' => 'RemFirst',
      'rem_remark' => 'RemRemark',
      'need_audit' => 'NeedAudit',
      'type' => 'Type'
                   ));
   }
}

/**
 * 微信用户表
 * 
 * */
class WX_User_Activity extends CRUD{
   protected $Id;
   protected $ActivityId;
   protected $UserId;
   protected $SigninFlag;
   protected $AuditFlag;
   protected $OnsiteFlag;

   protected function DefineKey(){
      return 'id';
   }
   protected function DefineTableName(){
      return 'wx_user_activity';
   }
   
   protected function DefineRelationMap(){
      return(array('id' => 'Id',
      			   'activity_id' => 'ActivityId',
      			   'user_id' => 'UserId',
      			   'signin_flag' => 'SigninFlag',
      				'audit_flag' => 'AuditFlag',
      			   'onsite_flag' => 'OnsiteFlag'
                   ));
   }
	public function DeleteAll($n_uid)
	{
		$this->Execute ( 'DELETE FROM `wx_user_activity` WHERE `wx_user_activity`.`user_id`='.$n_uid );		
	}
}
class WX_User_Info extends CRUD{
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
	protected $Block;
	protected $Round;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_info';
   	}
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      				'photo' => 'Photo',
		      	'nickname' => 'Nickname',
		      	'sex' => 'Sex',
      			'user_name' => 'UserName',
		      	'company' => 'Company',
		      	'dept_job' => 'DeptJob',
		      	'address' => 'Address',
		      	'openid' => 'OpenId',
		      	'phone' => 'Phone',
		      	'email' => 'Email',
      	'block' => 'Block',
		      	'register_date' => 'RegisterDate',
      	    'round' => 'Round',
		      	'del_flag' => 'DelFlag'
               ));
    }
}
class WX_User_Info_Temp extends CRUD{
	protected $Id;
	protected $UserName;
	protected $Company;
	protected $DeptJob;
	protected $Phone;
	protected $Email;
	protected $Type;
	protected $ActivityId;
	protected $SigninFlag;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_info_temp';
   	}
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      			'user_name' => 'UserName',
      			'type' => 'Type',
      			'activity_id' => 'ActivityId',
		      	'company' => 'Company',
		      	'dept_job' => 'DeptJob',
		      	'phone' => 'Phone', 
      			'signin_flag' => 'SigninFlag',
		      	'email' => 'Email'
               ));
    }
}
class WX_User_Group extends CRUD{
	protected $Id;
	protected $GroupId;
	protected $UserId;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_group';
   	}
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      				'group_id' => 'GroupId',
		      	'user_id' => 'UserId'
               ));
    }
	public function DelGroup($n_id)
	{
		$this->Execute ( 'DELETE FROM `wx_user_group` WHERE `wx_user_group`.`group_id`='.$n_id );		
	}
}
/**
 * 微信AccessToken表
 * 
 * */
class WX_Syscode extends CRUD{
	protected $Id;
	protected $SysToken;
	protected $CreateDate;
	protected $Date;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_syscode';
   	}
   	
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      			     'sys_token' => 'SysToken',
      	'date' => 'Date',
      				 'create_date' => 'CreateDate'
               ));
    }
}

/**
 * 微信分组表
 * 
 * */
class WX_Group extends CRUD{
	protected $Id;
	protected $GroupId;
	protected $GroupName;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_group';
   	}
   	
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      			     'group_id' => 'GroupId',
      				 'group_name' => 'GroupName'
               ));
    }
}
class WX_User_Blacklist extends CRUD{
	protected $Id;
	protected $OpenId;
	protected $Date;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_blacklist';
   	}
   	
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      			     'open_id' => 'OpenId',
      				 'date' => 'Date'
               ));
    }
}
class WX_User_Activity_Join extends CRUD{
	protected $Id;
	protected $ActivityId;
	protected $UserId;
	protected $Round1;
	protected $Success1;
	protected $Answer1;
	protected $Scan1;
	protected $ScanSum1;
	protected $Round2;
	protected $Success2;
	protected $Answer2;
	protected $Round3;
	protected $Success3;
	protected $Answer3;
	protected $Round4;
	protected $Success4;
	protected $Answer4;
	protected $Round5;
	protected $Success5;
	protected $Answer5;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_activity_join';
   	}
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
      				'activity_id' => 'ActivityId',
		      	'user_id' => 'UserId',
		      	'round1' => 'Round1',
      			'success1' => 'Success1',
      	'answer1' => 'Answer1',
      	'scan1' => 'Scan1',
      	'scan_sum1' => 'ScanSum1',
		      	'round2' => 'Round2',
		      	'success2' => 'Success2',
      	'answer2' => 'Answer2',
		      	'round3' => 'Round3',
		      	'success3' => 'Success3',
      	'answer3' => 'Answer3',
		      	'round4' => 'Round4',
		      	'success4' => 'Success4',
      	'answer4' => 'Answer4',
      			'round5' => 'Round5',
		      	'success5' => 'Success5',
		      	'answer5' => 'Answer5'
               ));
    }
}
class WX_User_Activity_Join_Uploadphoto extends CRUD{
	protected $Id;
	protected $UserId;
	protected $Path;
	protected $Date;
	
	protected function DefineKey(){
      return 'id';
   	}
   	
   	protected function DefineTableName(){
   		return 'wx_user_activity_join_uploadphoto';
   	}
   	protected function DefineRelationMap(){
      	return(array('id' => 'Id',
		      	'user_id' => 'UserId',
		      	'path' => 'Path',
      			'date' => 'Date'
               ));
    }
}
?>