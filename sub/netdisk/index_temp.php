<?php
define ( 'RELATIVITY_PATH', '../../' );
$s_path = RELATIVITY_PATH . 'userdata/netdisk/1/';
if (is_dir ( $s_path )) {
	if ($dh = opendir($s_path)) {
		while ( ($file = readdir ( $dh )) != false ) {
			//文件名的全路径 包含文件名
			$filePath = $s_path . $file;
			//获取文件修改时间
			$fmt = filemtime ( $filePath );
			$size =@filesize( $filePath );
			echo ("<span style='color:#666'>(" . date ( "Y-m-d H:i:s", $fmt ) . ") ".$size."</span> " . $filePath . "<br/>");
		}
		closedir ( $dh );
	}
}
?>