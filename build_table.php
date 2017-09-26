<?php
define ( 'RELATIVITY_PATH', '' ); //定义相对路径
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
$db = @mysql_connect('127.0.0.1:3306', 'root', '1q2w3e4r');
$table='wx_activity_training_race';
mysql_select_db('ly_wechat', $db);
mysql_query("SET NAMES 'utf8'");
$result = mysql_query('SHOW FULL COLUMNS FROM '.$table, $db);
$row='';
function formatKey($str)
{
	$a_temp=explode('_', $str);
	$str='';
	for($i=0;$i<count($a_temp);$i++)
	{
		if (($i+1)<count($a_temp))
		$str.=ucfirst($a_temp[$i]).'_';
		else 
		$str.=ucfirst($a_temp[$i]);
	}
	return $str;
}

echo('class '.formatKey($table).' extends CRUD<br/>');
echo('{<br/>');
$a_field=array();
while($row = mysql_fetch_array($result)){
	array_push($a_field, $row['Field']);
}
for($i=0;$i<count($a_field);$i++)
{
	echo('&nbsp;&nbsp;&nbsp;&nbsp;protected $'.str_replace('_', '', formatKey($a_field[$i])).';<br/>');
}
echo('<br/>&nbsp;&nbsp;&nbsp;&nbsp;protected function DefineKey()<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;{<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return \''.$a_field[0].'\';<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;}<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;protected function DefineTableName()<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;{<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return \''.$table.'\';<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;}<br/>');

echo('&nbsp;&nbsp;&nbsp;&nbsp;protected function DefineRelationMap()<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;{<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return(array(<br/>');

for($i=0;$i<count($a_field);$i++)
{
	if (($i+1)<count($a_field))
	{
		echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\''.$a_field[$i].'\' => \''.str_replace('_', '', formatKey($a_field[$i])).'\',<br/>');
	}else{
		echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\''.$a_field[$i].'\' => \''.str_replace('_', '', formatKey($a_field[$i])).'\'<br/>');
	}
	
}

echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;));<br/>');
echo('&nbsp;&nbsp;&nbsp;&nbsp;}<br/>');


echo('}');
exit(0);
?>