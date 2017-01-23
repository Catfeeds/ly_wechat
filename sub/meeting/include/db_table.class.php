<?php
require_once RELATIVITY_PATH.'include/db_operate.class.php';
require_once RELATIVITY_PATH.'include/db_connect.class.php';
/**
活动提醒
 * */
class WX_User_Reminder extends CRUD{
   protected $Id;
   protected $OpenId;
   protected $Send;
   protected $First;
   protected $Keyword1;
   protected $Keyword2;
   protected $Remark;
   protected $ActivityId;
   
   protected function DefineKey(){
      return 'id';
   }
   protected function DefineTableName(){
      return 'wx_user_reminder';
   }
   
   protected function DefineRelationMap(){
      return(array('id' => 'Id',
      			   'open_id' => 'OpenId',
      			   'send' => 'Send',
      			   'first' => 'First',
      			   'keyword1' => 'Keyword1',
            	   'keyword2' => 'Keyword2',
      'activity_id' => 'ActivityId',
      			   'remark' => 'Remark'
                   ));
   }
}

?>