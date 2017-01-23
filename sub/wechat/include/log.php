<?php
$fp = fopen ( 'log.csv', 'a' );
$a_item = array ();
array_push ( $a_item, iconv ( 'UTF-8', 'gbk', $a_user_info['headimgurl']) );
array_push ( $a_item, iconv ( 'UTF-8', 'gbk', $nickname) );
array_push ( $a_item, iconv ( 'UTF-8', 'gbk', $a_user_info['sex']) );
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$this->getPost('Name')));
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$this->getPost('Company')));
array_push ( $a_item, iconv ( 'UTF-8', 'gbk', $this->getPost('DeptJob')));
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$this->getPost('Phone')));
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$this->getPost('Email')));
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$this->getPost('OpenId')));	
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$this->getPost('Id')));	
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$audit) );	
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$sign) );	
array_push ( $a_item, iconv ( 'UTF-8', 'gbk',$onsite) );	

fputcsv ( $fp, $a_item );
fclose ( $fp );
?>