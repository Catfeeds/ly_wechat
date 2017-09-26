<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once '../include/db_table.class.php';
//获取奖池
$o_answer=new Wx_User_Training_Answers();
$o_answer->PushWhere(array("&&", "ActivityId", "=",1080));
$o_answer->PushOrder ( array ('Rate','D') );
$o_answer->PushOrder ( array ('Date','A') );
$o_answer->getAllCount();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>专家排行</title>
    <script src="../../../js/initialize.js" type="text/javascript"></script>
    <style type="">
		body{
			margin:0px;
			padding:0px;
			background-color:#E9F3FC;
			font-size:26px;
			background-image:url('images/1080_right.jpg');
			background-position:bottom right;
			background-repeat:no-repeat;
			overflow:hidden;
			font-weight:bold;
			}
			body{
				font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			}
			div
			{
				overflow:hidden;
			}
			img
			{
				width:100%;
			}
			*
			{
				margin: 0px;
				padding: 0px;
			}
			table{
				width:90%;
				margin-left:5%;
				margin-right:5%;
				margin-top:20px;
			}
			table td{
				text-align:left;
				color:#362A5C;
				padding:10px;
				padding-top:10px;
				padding-bottom:15px;
				font-size:24px;
			}
			table td div{
				text-align:left;
				color:#ffffff;
				background-color:#362A5C;
				border-radius:60%;
				width:50px;
				text-align:center;
				line-height:50px;
				height:50px;
				float:left;
			}
		
	</style>
</head>
<body>
	<div style="margin-left:40px;color:#ffffff;font-size:40px;font-weight:bold;background-color:#362A5C;float:left;padding:10px;padding-top:20px;">
		拥抱爱尔兰•重庆2017
	</div>
	<div style="margin:0px;margin-top:20px;float:right;width:40%;text-align:right;">
		<img src="images/1080_logo.jpg" />
	</div>
	<div style="font-weight:bold;margin-left:40px;margin-top:10px;width:90%;text-align:left;color:#362A5C">
		专家排行榜
	</div>
	<table>
	<?php 
	for($i=0;$i<8;$i++)
	{
		$o_user_info1=new WX_User_Info($o_answer->getUserId($i));
		$o_user_info2=new WX_User_Info($o_answer->getUserId($i+9));
		?>
		<tr>
			<td style="width:5%">
				<div><?php echo(($i+1))?></div>
			</td>
			<td style="width:15%">
			<?php echo($o_user_info1->getUserName())?>&nbsp;
			</td>
			<td style="width:30%;font-size:20px;">
			<?php echo($o_user_info1->getCompany())?>&nbsp;
			</td>
			<td style="width:5%">
				<?php if(($i+9)<16)echo('<div>'.($i+9).'</div>')?>
			</td>
			<td style="width:15%">
			<?php if(($i+9)<16)echo($o_user_info2->getUserName())?>&nbsp;
			</td>
			<td style="width:30%;font-size:20px;">
			<?php if(($i+9)<16)echo($o_user_info2->getCompany())?>&nbsp;
			</td>
		</tr>
		<?php
	}
	?>
	</table>
<script type="">
$(window).resize(function(){set_resize()});
set_resize()
function set_resize()
{
	//先定位logo
	var width=$(window).width();
	var height=$(window).height();
	var font_size=Math.round((width-1024)/110)+12
	$('body').css('height',height+'px')
}
setInterval('location.reload()',5000);
</script>
</body>
</html>
