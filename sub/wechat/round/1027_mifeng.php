<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once '../include/db_table.class.php';

$a_color=array('#DFDCD6','#9DC4A1','#15A1BD','#EEC72F','#2EAF69','#EA5A30','#CB8A24','#166DAC','#81CACA','#AB66B3','#C5225A');
$a_font=array('30','36','40','44','50');
//---------------------------------------------------------配置信息-----------------
$a_title=array(//设置每轮的标题
		'第一轮',
		'第二轮',
		'第三轮',
		'第四轮');
$n_show_upload_photo='';//第几轮是可以查看上传图片的
//---------------------------------------------------------------------------------
$n_round=count($a_title);
if ($_GET['id']>=1 && $_GET['id']<=$n_round)
{
	$id=$_GET['id'];
}else{
	$id=1;
}
//获取活动Id
$o_date = new DateTime ( 'Asia/Chongqing' );
$s_date=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ) ;//获取当前日期
$s_date='2016-08-01';
$o_activity=new WX_Activity();
$o_activity->PushWhere(array("&&", "ActivityDate", "=",$s_date));
$o_activity->getAllCount();
//获取奖池
$o_round=new WX_User_Activity_Join();
$o_round->PushWhere(array("&&", "ActivityId", "=",$o_activity->getId(0)));
$o_round->PushWhere(array("&&", "Round".$id, "=",1));
$o_round->PushWhere(array("&&", "Success".$id, "=",0));
$n_count=$o_round->getAllCount();
$s_photo='';
$s_name='';
$s_javascript='';
for($i=0;$i<$n_count;$i++)
{
	$o_user=new WX_User_Info($o_round->getUserId($i));
	if ($o_user->getDelFlag()==0)
	{
		//$s_photo.='<img id="img_'.$o_round->getId($i).'" src="'.$o_user->getPhoto().'"/>';
		//$s_name.='<span id="name_'.$o_round->getId($i).'">'.$o_user->getUserName().'</span>';
		$s_name.='<div id="name_'.$o_round->getId($i).'" class="name" style="font-size:'.$a_font[rand(0,(count($a_font)-1))].'px;color:'.$a_color[rand(0,(count($a_color)-1))].'">'.$o_user->getUserName().'</div>';
		$s_javascript.='Round.push('.$o_round->getId($i).');';
	}
}
//如果奖池为空，那么显示默认照片
if($n_count==0)
{
	$s_photo.='<img id="img_0" src="images/photo_default.jpg"/>';
	$s_name.='<span id="name_0">用户姓名</span>';
	$s_javascript.='Round.push(0);';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>活动抽奖</title>
    <script src="../../../js/initialize.js" type="text/javascript"></script>
    <script src="js/jqfloat.min.js"></script>
    <link rel="stylesheet" href="css/weui.min.css"/>
    <style type="">
		body{
			margin:0px;
			overflow:hidden;
		}
		.title{
			float:left;
		}
		.title img{
			width:350px;
		}
		.logo{
			float:right;
		}
		.logo img{
			width:100%;
		}
		div{
			
		}
		.footer{
			height:auto;
			z-index: 1032;
		}
		.footer img{
			max-height:100px;
			width:100%;
		}
		.round_number
		{
			float:left;
			font-size:40px;
			color:#4D89A1;
			margin-left:30px;
		}
		.userphoto
		{
			float:left;
			width:40%; 
			margin-left:30%;
			margin-right:30%;
			position:fixed;
			text-align:center;
			top:30%;
		}
		.userphoto img
		{
			width:100%;
			border: 20px solid #012F49;
			display:none;
		} 
		.username
		{
			float:left;
			font-size:30px;
			margin-left:50px;
			margin-top:8px;
			color:#4D89A1;
		}
		.username span
		{
			display:none;
		}
		.body
		{
			padding-top:20px;
			width:100%;
			z-index:1031;
			position:fixed;
		}
		.button{
			padding-bottom:10px;
			float:right;
			margin-right:5px;
		}
		.button div{
			float:right;
			width:82px;
			height:82px;
			cursor:pointer;
		}
		.button div img:hover{
			border: 1px solid #4A8AA5;
		}
		.button div img{
			width:82px;
			height:82px;	
			border: 1px solid white;
		}
		.stop{
			
		}
		body{
		}
		.bj{
			width:100%;
			height:100%;
			position:fixed;
			z-index: 1032;
		}
		.name{
			position:absolute;
			z-index: 1033;
			top:50%;
			left:50%;
			font-weight:bold;
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			width:175px;
		}
	</style>
</head>
<body>
<img class="bj" src="images/1027_bj.jpg"/>
<?php echo($s_name)?>
</body>
<script>
$(".name").css("left",$('.name').position().left-80+"px");
$(".name").css("top",$('.name').position().top-30+"px");
//$(".name").left($('.name').position().left-100);
//$('.name').position().left('10px')
$(document).ready(function() {
	
	//vendor script

	$('.name').jqFloat({
		width:$(window).width(),
		height:$(window).height(),
		speed:1500
	});

});
//alert($(window).height());
$(".bj").height($(window).height());

</script>
</html>
