<?php
define ( 'RELATIVITY_PATH', '../../../' );
//require_once '../../include/it_include.inc.php';
require_once 'include/it_showpage.class.php';
if (is_numeric ( $_GET ['folderid'] )) {
	$n_folderid = $_GET ['folderid'];
} else {
	$n_folderid = 0;
}
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
$o_filelist= new ShowPage ( $O_Session->getUserObject ());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/ajax.fun.js"></script>
</head>
<body>
<div class="file_list">
<?php echo($o_filelist->getNetDiskFileListForAttchment($n_folderid))?>           
</div>
<script type="text/javascript" language="javascript">
//清空父窗口内的存储点击图片的数组
parent.filesList=[];
    </script>
</body>
</html>
