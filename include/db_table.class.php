<?php
require_once RELATIVITY_PATH.'include/db_operate.class.php';
require_once RELATIVITY_PATH.'include/db_connect.class.php';
//1111111111111111111111111111111111111111111111
class Base_Dept extends CRUD
{
   protected $DeptId;
   protected $Name;
   protected $ParentId;
   protected $Number;
   protected $Phone;
   protected $Fax;
	protected $Address;
	protected $Type;
	protected $StudentSum;
	 
   protected function DefineKey()
   {
      return 'dept_id';
   }
   protected function DefineTableName()
   {
      return 'base_dept';
   }
   protected function DefineRelationMap()
   {
      return(array('dept_id' => 'DeptId',
      							'name' => 'Name',
      							'parent_id' => 'ParentId',
      							'number' => 'Number',
     								'phone' => 'Phone',
      							'fax' => 'Fax',    
      'type' => 'Type',  
      'student_sum' => 'StudentSum',  
      'precent' => 'Precent',  
      							'address' => 'Address'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Module  extends CRUD
{
   protected $ModuleId;
   protected $Name;
   protected $Module;
   protected $Explain;
   protected $IconId;
   protected $Path;
   protected $ParentModuleId;
   protected $MiniIconId;
   protected $WaitReadTable;
    
   protected function DefineKey()
   {
      return 'module_id';
   }
   protected function DefineTableName()
   {
      return 'base_module';
   }
   protected function DefineRelationMap()
   {
      return(array('module_id' => 'ModuleId',
      				'name' => 'Name',
      				'module' => 'Module',
      				'explain' => 'Explain',
     				'icon_id' => 'IconId',
      				'path' => 'Path',
      				'parent_module_id' => 'ParentModuleId',
                   	'mini_icon_id' => 'MiniIconId',
      				'wait_read_table' => 'WaitReadTable'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Module_Icon extends CRUD
{
   protected $IconId;
   protected $Path;

   protected function DefineKey()
   {
      return 'icon_id';
   }
   protected function DefineTableName()
   {
      return 'base_module_icon';
   }
   protected function DefineRelationMap()
   {
      return(array('icon_id' => 'IconId',
                   'path' => 'Path'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Module_Icon_Mini extends CRUD
{
   protected $MiniIconId;
   protected $Path;

   protected function DefineKey()
   {
      return 'mini_icon_id';
   }
   protected function DefineTableName()
   {
      return 'base_module_icon_mini';
   }
   protected function DefineRelationMap()
   {
      return(array('mini_icon_id' => 'MiniIconId',
                   'path' => 'Path'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Right extends CRUD
{
   protected $RightId;
   protected $RoleId;
   protected $ModuleId;

   protected function DefineKey()
   {
      return 'right_id';
   }
   protected function DefineTableName()
   {
      return 'base_right';
   }
   protected function DefineRelationMap()
   {
      return(array('right_id' => 'RightId',
                   'role_id' => 'RoleId',
                   'module_id' => 'ModuleId'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Role extends CRUD
{
   protected $RoleId;
   protected $Name;
   protected $Explain;

   protected function DefineKey()
   {
      return 'role_id';
   }
   protected function DefineTableName()
   {
      return 'base_role';
   }
   protected function DefineRelationMap()
   {
      return(array('role_id' => 'RoleId',
                   'name' => 'Name',
                   'explain' => 'Explain'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Setup extends CRUD
{
   protected $Id;
   protected $Version;
   protected $UpdateUrl;
   protected $SystemName;
   protected $Footer;
   protected $HomeUrl;
   protected $Logo;

   protected function DefineKey()
   {
      return 'id';
   }
   protected function DefineTableName()
   {
      return 'base_setup';
   }
   protected function DefineRelationMap()
   {
      return(array('id' => 'Id',
      'version' => 'Version',
      'logo' => 'Logo',
      'update_url' => 'UpdateUrl',
      'system_name' => 'SystemName',
      'footer' => 'Footer',
      'home_url' => 'HomeUrl'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_System_Msg extends CRUD
{
   protected $Id;               
   protected $Uid;          
   protected $Text;     
   protected $CreateDate;
   protected $IsShow;         
   protected $IsRead;
   protected $ReadDate;

   protected function DefineKey()
   {
      return 'id';
   }
   protected function DefineTableName()
   {
      return 'base_system_msg';
   }
   protected function DefineRelationMap()
   {
      return(array('id' => 'Id',               
                   'uid' => 'Uid',          
                   'text' => 'Text',          
                   'create_date' => 'CreateDate',
                   'is_show' => 'IsShow',         
                   'is_read' => 'IsRead',
                   'read_date' => 'ReadDate'
                   ));
   }
	public function DeleteAll($n_uid)
	{
		$this->Execute ( 'DELETE FROM `base_system_msg` WHERE `base_system_msg`.`uid`='.$n_uid );		
	}
}

//1111111111111111111111111111111111111111111111
class Base_User extends CRUD
{
   protected $UserName;
   protected $Password;
   protected $State;
   protected $Uid;
   protected $RegIp;
   protected $RegTime;
   protected $Type;
   protected $Deleted;
   protected $ActivityId;

   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'username' => 'UserName',
                   'password' => 'Password',
                   'state' => 'State',
                   'reg_ip' => 'RegIp',
                   'reg_time' => 'RegTime',
      				'deleted' => 'Deleted',
      'activity_id' => 'ActivityId',
      			   'type' => 'Type',
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Desktop extends CRUD
{
   protected $Uid;
   protected $ModuleSort;

   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user_desktop';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'module_sort' => 'ModuleSort'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Info extends CRUD
{
   protected $Uid;
   protected $Name;
   protected $Email;
   protected $Sex;
   protected $Phone;
   protected $DiskSpace;
   protected $ShowNavIcon;
   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user_info';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'name' => 'Name',
                   'email' => 'Email',
                   'sex' => 'Sex',
      			   'phone' => 'Phone',
      				'show_nav_icon' => 'ShowNavIcon',
      			   'disk_space' => 'DiskSpace'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Info_Custom extends CRUD
{
   protected $Uid;
   protected $Birthday;
   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user_info_custom';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'birthday' => 'Birthday',
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Login extends CRUD
{
   protected $Uid;
   protected $LastIp;
   protected $LastTime;
   protected $Online;
   protected $LoginTime;
   protected $OnlineTime;
   protected $UserAgent;
   protected $SessionId;
   protected $PassError;
   protected $LockTime;
      
   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user_login';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'last_ip' => 'LastIp',
                   'last_time' => 'LastTime',
                   'online' => 'Online',
                   'login_time' => 'LoginTime',
                   'online_time' => 'OnlineTime',
                   'user_agent' => 'UserAgent',
                   'session_id' => 'SessionId',
                   'pass_error' => 'PassError',
                   'lock_time' => 'LockTime'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Photo extends CRUD
{
   protected $Uid;
   protected $Path;
      
   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user_photo';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'path' => 'Path'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Picture extends CRUD
{
   protected $Id;
   protected $Uid;
   protected $Path;
   protected $Timecut;
   protected $Filesize;
      
   protected function DefineKey()
   {
      return 'id';
   }
   protected function DefineTableName()
   {
      return 'base_user_picture';
   }
   protected function DefineRelationMap()
   {
      return(array('id' => 'Id',
                   'uid' => 'Uid',
                   'path' => 'Path',
                   'timecut' => 'Timecut',
                   'filesize' => 'Filesize'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Group extends CRUD
{
   protected $GroupId;
   protected $Name;
   protected $Explain;
      
   protected function DefineKey()
   {
      return 'group_id';
   }
   protected function DefineTableName()
   {
      return 'base_user_group';
   }
   protected function DefineRelationMap()
   {
      return(array('group_id' => 'GroupId',
                   'name' => 'Name',
                   'explain' => 'Explain'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Role extends CRUD
{
   protected $Uid;
   protected $DeptId;
   protected $RoleId;
   protected $SecRoleId1;
   protected $SecRoleId2;
   protected $SecRoleId3;
   protected $SecRoleId4;
   protected $SecRoleId5;
      
   protected function DefineKey()
   {
      return 'uid';
   }
   protected function DefineTableName()
   {
      return 'base_user_role';
   }
   protected function DefineRelationMap()
   {
      return(array('uid' => 'Uid',
                   'dept_id' => 'DeptId',
                   'role_id' => 'RoleId',
                   'sec_role_id_1' => 'SecRoleId1',
                   'sec_role_id_2' => 'SecRoleId2',
                   'sec_role_id_3' => 'SecRoleId3',
                   'sec_role_id_4' => 'SecRoleId4',
                   'sec_role_id_5' => 'SecRoleId5'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_Cmcc extends CRUD
{
   protected $Id;
   protected $Phone;
   protected $Content;
   protected $Sended;
   protected $SendTime;
      
   protected function DefineKey()
   {
      return 'id';
   }
   protected function DefineTableName()
   {
      return 'base_cmcc';
   }
   protected function DefineRelationMap()
   {
      return(array('id' => 'Id',
                   'phone' => 'Phone',
                   'content' => 'Content',
                   'sended' => 'Sended',
                   'sendtime' => 'SendTime'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_Files  extends CRUD
{
   protected $FileId;
   protected $Filename;
   protected $Filesize;
   protected $Date;
   protected $Suffix;
   protected $Crc;
   protected $ShareUsername;
   protected $ShareUid;
   protected $Path;
   protected $Uid;
   protected $Delete;
   protected $KeyWord;
   protected $FolderId;
   protected $DeleteDate;
   protected $OriginalPath;
   protected $OriginalFilename;
	 
   protected function DefineKey()
   {
      return 'file_id';
   }
   protected function DefineTableName()
   {
      return 'base_user_files';
   }
   protected function DefineRelationMap()
   {
      return(array( 'file_id' => 'FileId',
      				'filename' => 'Filename',
      				'filesize' => 'Filesize',
      				'date' => 'Date',
      				'suffix' => 'Suffix',
     				'crc' => 'Crc',
      				'share_username' => 'ShareUsername',
      				'share_uid' => 'ShareUid',
      				'path' => 'Path',
      				'uid' => 'Uid',
      				'delete' => 'Delete',
      				'key_word' => 'KeyWord',
      				'folder_id' => 'FolderId',
      				'delete_date' => 'DeleteDate',
      				'original_path' => 'OriginalPath',
      				'original_filename' => 'OriginalFilename'
                   ));
   }
}
//1111111111111111111111111111111111111111111111
class Base_User_File_Type extends CRUD
{
   protected $Suffix;
   protected $ClassName;
   protected $Explain;
	 
   protected function DefineKey()
   {
      return 'suffix';
   }
   protected function DefineTableName()
   {
      return 'base_user_file_type';
   }
   protected function DefineRelationMap()
   {
      return(array( 'suffix' => 'Suffix',
      				'classname' => 'ClassName',
      				'explain' => 'Explain'
                   ));
   }
}
//1111111111111111111111111111111111111111111111












































?>