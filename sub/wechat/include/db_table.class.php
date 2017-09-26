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
class Wx_Activity_Training_Questions extends CRUD
{
    protected $Id;
    protected $ActivityId;
    protected $Question;
    protected $Type;
    protected $Number;
    protected $Score;
    protected $RightNum;
    protected $ErrorNum;
    protected $Answer;

    protected function DefineKey()
    {
        return 'id';
    }
    protected function DefineTableName()
    {
        return 'wx_activity_training_questions';
    }
    protected function DefineRelationMap()
    {
        return(array(
                    'id' => 'Id',
                    'activity_id' => 'ActivityId',
                    'question' => 'Question',
                    'type' => 'Type',
                    'number' => 'Number',
                    'score' => 'Score',
                    'right_num' => 'RightNum',
                    'error_num' => 'ErrorNum',
                    'answer' => 'Answer'
        ));
    }
}
class Wx_Activity_Training_Options extends CRUD
{
    protected $Id;
    protected $QuestionId;
    protected $Option;
    protected $Number;

    protected function DefineKey()
    {
        return 'id';
    }
    protected function DefineTableName()
    {
        return 'wx_activity_training_options';
    }
    protected function DefineRelationMap()
    {
        return(array(
                    'id' => 'Id',
                    'question_id' => 'QuestionId',
                    'option' => 'Option',
                    'number' => 'Number'
        ));
    }
}
class Wx_Activity_Training_Race extends CRUD
{
    protected $Id;
    protected $ActivityId;
    protected $Number;
    protected $UserId;
    protected $Score;

    protected function DefineKey()
    {
        return 'id';
    }
    protected function DefineTableName()
    {
        return 'wx_activity_training_race';
    }
    protected function DefineRelationMap()
    {
        return(array(
                    'id' => 'Id',
                    'activity_id' => 'ActivityId',
                    'number' => 'Number',
                    'user_id' => 'UserId',
                    'score' => 'Score'
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
   protected $Score;

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
      			   'onsite_flag' => 'OnsiteFlag',
      			   'score' => 'Score'
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
	protected $CompanyEn;
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
	protected $Score;
	
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
      	'company_en' => 'CompanyEn',
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
      	'Score' => 'score',
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
class Wx_User_Training_Answers extends CRUD
{
    protected $Id;
    protected $ActivityId;
    protected $UserId;
    protected $Date;
    protected $Answer1;
    protected $Answer2;
    protected $Answer3;
    protected $Answer4;
    protected $Answer5;
    protected $Answer6;
    protected $Answer7;
    protected $Answer8;
    protected $Answer9;
    protected $Answer10;
    protected $Answer11;
    protected $Answer12;
    protected $Answer13;
    protected $Answer14;
    protected $Answer15;
    protected $Answer16;
    protected $Answer17;
    protected $Answer18;
    protected $Answer19;
    protected $Answer20;
    protected $Answer21;
    protected $Answer22;
    protected $Answer23;
    protected $Answer24;
    protected $Answer25;
    protected $Answer26;
    protected $Answer27;
    protected $Answer28;
    protected $Answer29;
    protected $Answer30;
    protected $Answer31;
    protected $Answer32;
    protected $Answer33;
    protected $Answer34;
    protected $Answer35;
    protected $Answer36;
    protected $Answer37;
    protected $Answer38;
    protected $Answer39;
    protected $Answer40;
    protected $Answer41;
    protected $Answer42;
    protected $Answer43;
    protected $Answer44;
    protected $Answer45;
    protected $Answer46;
    protected $Answer47;
    protected $Answer48;
    protected $Answer49;
    protected $Answer50;
    protected $Rate;

    protected function DefineKey()
    {
        return 'id';
    }
    protected function DefineTableName()
    {
        return 'wx_user_training_answers';
    }
    protected function DefineRelationMap()
    {
        return(array(
                    'id' => 'Id',
                    'activity_id' => 'ActivityId',
                    'user_id' => 'UserId',
                    'date' => 'Date',
        			'rate' => 'Rate',
                    'answer_1' => 'Answer1',
                    'answer_2' => 'Answer2',
                    'answer_3' => 'Answer3',
                    'answer_4' => 'Answer4',
                    'answer_5' => 'Answer5',
                    'answer_6' => 'Answer6',
                    'answer_7' => 'Answer7',
                    'answer_8' => 'Answer8',
                    'answer_9' => 'Answer9',
                    'answer_10' => 'Answer10',
                    'answer_11' => 'Answer11',
                    'answer_12' => 'Answer12',
                    'answer_13' => 'Answer13',
                    'answer_14' => 'Answer14',
                    'answer_15' => 'Answer15',
                    'answer_16' => 'Answer16',
                    'answer_17' => 'Answer17',
                    'answer_18' => 'Answer18',
                    'answer_19' => 'Answer19',
                    'answer_20' => 'Answer20',
                    'answer_21' => 'Answer21',
                    'answer_22' => 'Answer22',
                    'answer_23' => 'Answer23',
                    'answer_24' => 'Answer24',
                    'answer_25' => 'Answer25',
                    'answer_26' => 'Answer26',
                    'answer_27' => 'Answer27',
                    'answer_28' => 'Answer28',
                    'answer_29' => 'Answer29',
                    'answer_30' => 'Answer30',
                    'answer_31' => 'Answer31',
                    'answer_32' => 'Answer32',
                    'answer_33' => 'Answer33',
                    'answer_34' => 'Answer34',
                    'answer_35' => 'Answer35',
                    'answer_36' => 'Answer36',
                    'answer_37' => 'Answer37',
                    'answer_38' => 'Answer38',
                    'answer_39' => 'Answer39',
                    'answer_40' => 'Answer40',
                    'answer_41' => 'Answer41',
                    'answer_42' => 'Answer42',
                    'answer_43' => 'Answer43',
                    'answer_44' => 'Answer44',
                    'answer_45' => 'Answer45',
                    'answer_46' => 'Answer46',
                    'answer_47' => 'Answer47',
                    'answer_48' => 'Answer48',
                    'answer_49' => 'Answer49',
                    'answer_50' => 'Answer50'
        ));
    }
}
class Wx_User_Training_Hint_Log extends CRUD
{
    protected $Id;
    protected $ActivityId;
    protected $UserId;
    protected $Date;
    protected $Comment;

    protected function DefineKey()
    {
        return 'id';
    }
    protected function DefineTableName()
    {
        return 'wx_user_training_hint_log';
    }
    protected function DefineRelationMap()
    {
        return(array(
                    'id' => 'Id',
                    'activity_id' => 'ActivityId',
                    'user_id' => 'UserId',
                    'date' => 'Date',
                    'comment' => 'Comment'
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