<?php
define ( 'RELATIVITY_PATH', '../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
$f_handle = fopen ( RELATIVITY_PATH . 'sub/wechat/include/log.csv', "r" );
while ( ! feof ( $f_handle ) ) {
	$f_buffer = fgetss ( $f_handle, 2048 );
	$a_data = explode ( ",", $f_buffer );
	$n_count = count ( $a_data );
	if (str_replace ( " ", "", $a_data [0] ) == '') {
		break;
	}
	//先查找OpenId是否已经存在
	$o_user_info=new WX_User_Info();
	$o_user_info->PushWhere(array("&&", "OpenId", "=",$a_data [8]));
	$n_user_id=0;
	if ($o_user_info->getAllCount()==0)
	{
		//不存在，补资料
		$o_new_user=new WX_User_Info();
		$o_new_user->setPhoto(iconv ( 'gb2312', 'UTF-8', $a_data[0] ));
		$o_new_user->setNickname(iconv ( 'gb2312', 'UTF-8', $a_data[1] ));
		if (iconv ( 'gb2312', 'UTF-8', $a_data[2] )==2)
		{
			$o_new_user->setSex('女');
		}else{
			$o_new_user->setSex('男');
		}			
		$o_new_user->setUserName(iconv ( 'gb2312', 'UTF-8', $a_data[3] ));
		$o_new_user->setCompany(iconv ( 'gb2312', 'UTF-8', $a_data[4] ));
		$o_new_user->setAddress('');
		$o_new_user->setDeptJob(iconv ( 'gb2312', 'UTF-8', $a_data[5] ));
		$o_new_user->setPhone(iconv ( 'gb2312', 'UTF-8', $a_data[6] ));
		$o_new_user->setEmail(iconv ( 'gb2312', 'UTF-8', $a_data[7] ));
		$o_date = new DateTime ( 'Asia/Chongqing' );
		$o_new_user->setRegisterDate($o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ));
		$o_new_user->setOpenId(iconv ( 'gb2312', 'UTF-8', $a_data[8] ));
		$o_new_user->setDelFlag(0);
		$b_rusult=$o_new_user->Save();
		//echo('不存在');
		if ($b_rusult!=true)
		{
			echo('false');
			exit(0);
		}
		$o_user_activity=new WX_User_Activity();
		$o_user_activity->setActivityId(iconv ( 'gb2312', 'UTF-8', $a_data[9] ));
		$o_user_activity->setUserId($o_new_user->getId());
		$o_user_activity->setAuditFlag(iconv ( 'gb2312', 'UTF-8', $a_data[10] ));
		$o_user_activity->setSigninFlag(iconv ( 'gb2312', 'UTF-8', $a_data[11] ));
		$o_user_activity->setOnsiteFlag(iconv ( 'gb2312', 'UTF-8', $a_data[12] ));
		$b_rusult=$o_user_activity->Save();
	}
}
echo ('Finished');
?>