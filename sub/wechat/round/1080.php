<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once '../include/db_table.class.php';
//---------------------------------------------------------配置信息-----------------
$a_title=array(//设置每轮的标题
		'抢答 第一轮',
		'抢答 第二轮',
		'抢答 第三轮');
$n_show_upload_photo='';//第几轮是可以查看上传图片的
//---------------------------------------------------------------------------------
$n_round=count($a_title);
if ($_GET['id']>=1 && $_GET['id']<=$n_round)
{
	$id=$_GET['id'];
}else{
	$id=1;
}
//获取奖池
$o_round=new Wx_Activity_Training_Race();
$o_round->PushWhere(array("&&", "ActivityId", "=",1080));
$o_round->PushWhere(array("&&", "Number", "=",$id));
$o_round->PushWhere(array("&&", "UserId", ">",0));
$n_count=$o_round->getAllCount();
$s_photo='';
$s_name='';
$s_javascript='';
if ($n_count>0)
{
	$o_user=new WX_User_Info($o_round->getUserId(0));
	$s_photo.='<img src="'.$o_user->getPhoto().'"/>';
	$s_name.='<span>'.$o_user->getUserName().'</span>';
	$s_company.='<span >'.$o_user->getCompany().'</span>';
	$s_javascript.='Round.push(0);';
}else{
	$s_photo.='<img id="img_0" src="images/1080_photo_default.jpg"/>';
	$s_name.='<span id="name_0">姓名</span>';
	$s_company.='<span id="company_0">公司名称</span>';
	$s_javascript.='Round.push(0);';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>抢答</title>
    <script src="../../../js/initialize.js" type="text/javascript"></script>
	<script src="js/jquery.fullscreen.js" type="text/javascript"></script>
    <style type="">
		body{
			margin:0px;
			padding:0px;
			background-color:#362A5C;
			font-size:12px;
			
		}
		.logo{
			margin:0px;
			margin-top:30px;
			float:right;
			width:50%;
			text-align:right;
			position: absolute;
		}
		.left{
			float:left;
			width:10%;
			height:200px;
			background-image:url('images/1080_left.jpg');
			background-color:#E9F3FC;
			background-position:bottom right;
			background-repeat:no-repeat;
		}
		.mid{
			float:left;
			width:30%;
		}
		.right{
			float:right;
			width:70%;
			height:200px;
			background-image:url('images/1080_right.jpg');
			background-position:bottom right;
			background-repeat:no-repeat;
		}
		.mid div
		{
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			color:white;
			font-weight:bold;
		}
		.title{
			font-size:200%;
			text-align:left;
			width:100%;
			margin-top:15%;
		}
		.line{
			margin-top:10%;
			width:90%;
			background-color:#ffffff;
			height:2px;
		}
		.title img{
			width:100%;
		}
		.round{
			font-size:300%;
			margin-top:40%;
			margin-left:10%;
		}
		.username{
			font-size:250%;
			margin-top:15%;
			margin-left:10%;
			height:50px;
		}
		.company{
			font-size:150%;
			margin-top:5%;
			margin-left:10%;
			height:100px;
		}
		.company span
		{
			
		}
		.logo img{
			width:100%;
		}
		div{
			overflow:hidden;
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
			width:30%;
			text-align:center;
			font-size:50px;
			padding-top:140px;
			color:#73A9A9;
		}
		.round_number span
		{
			
		}
		.userphoto
		{
			float:left;
			width:45%; 
			text-align:center;
			margin-left:26%;
			border: 10px solid #362A5C;
			background-color:white;
		}
		.userphoto img
		{
			width:100%;
		} 
		.username span
		{
			
		}
		.body
		{
			padding-top:0px;
			width:100%;
			z-index:1031;			
		}
		.button{
			width:100%;
		}
		.button div{
			float:left;
			width:25%;
			height:25%;
			pading:3px;
			cursor:pointer;
			margin-left:15%;
		}
		.button div img:hover{
			border: 1px solid #ffffff;
		}
		.button div img{
			width:100%;	
		}
		.stop{
			
		}
	</style>
</head>
<body>
<div class="logo"><img src="images/1080_logo.jpg" /></div>
<div class="mid">
	<div class="title">
	&nbsp;&nbsp;&nbsp;&nbsp;拥抱爱尔兰•重庆2017
	</div>
	<div class="line">
	</div>
	<div class="round">
	<?php echo($a_title[$id-1])?>
	</div>
	<div class="username">
	<?php echo($s_name)?>
	</div>
	<div class="company">
	<?php echo($s_company)?>
	</div>
	<div style="margin-top:30%">
		<div class="button">
			<div class="save" onclick="location='1080.php?id=<?php echo(($id+1))?>'" style="margin-left:30%">
				<img src="images/1080_next.jpg"/>
			</div>	
		</div>
	</div>
</div>
<div class="right">
	<div class="userphoto">
		<?php echo($s_photo)?>
	</div>
</div>
<script type="">
$(window).resize(function(){set_resize()});
set_resize()
function set_resize()
{
	//先定位logo
	var width=$(window).width();
	var height=$(window).height();
	var temp=$('.logo').width()
	$('.logo').css('left',(width-temp-100)+'px')
	//左图定位
	$('.left').css('height',height+'px')
	$('.right').css('height',height+'px')
	//自动计算字体
	var font_size=Math.round((width-1024)/110)+12
	$('body').css('font-size',font_size+'px')
	//计算用户头像位置
	temp=$('.userphoto').width()
	$('.userphoto').css('margin-top',(Math.round((height-temp)/2))+'px')
	$('.userphoto').css('height',temp+'px')
	
}
setInterval('location.reload()',5000);
</script>
</body>
</html>
