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
		'',
		'',
		'',
		'');
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
//$s_date='2016-08-01';
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
	if ($o_user->getDelFlag()==0 && $o_user->getRound()==1)
	{
		//必须为已关注用户和允许抽奖用户才可显示
		$s_photo.='<img id="img_'.$o_round->getId($i).'" src="'.$o_user->getPhoto().'"/>';
		$s_name.='<span id="name_'.$o_round->getId($i).'">'.$o_user->getUserName().'</span>';
		$s_company.='<span id="company_'.$o_round->getId($i).'">'.$o_user->getCompany().'</span>';
		$s_javascript.='Round.push('.$o_round->getId($i).');';
	}
}
//如果奖池为空，那么显示默认照片
if($n_count==0)
{
	$s_photo.='<img id="img_0" src="../images/photo_default.jpg"/>';
	$s_name.='<span id="name_0">姓名</span>';
	$s_company.='<span id="company_0">公司名称</span>';
	$s_javascript.='Round.push(0);';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>活动抽奖</title>
    <script src="../../../js/initialize.js" type="text/javascript"></script>
	<script src="js/jquery.fullscreen.js" type="text/javascript"></script>
    <style type="">
		body{
			margin:0px;
			padding:0px;
			background-color:#d51624;
			font-size:12px;
			
		}
		.logo{
			margin:0px;
			float:right;
			width:15%;
			text-align:right;
			background:white;
			padding:20px;
			border-radius:10px; 
			border-top-right-radius:0px;
			border-top-left-radius:0px;
			position: absolute;
		}
		.left{
			float:left;
			width:10%;
			height:200px;
			background-image:url('images/1044_left.jpg');
			background-position:center center;
			background-repeat:no-repeat;
		}
		.mid{
			float:left;
			width:25%;
		}
		.right{
			float:right;
			width:65%;
			height:200px;
			background-image:url('images/1044_right.jpg');
			background-position:center center;
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
			text-align:center;
			width:100%;
			margin-top:15%;
		}
		.line{
			margin-top:10%;
			width:90%;
			background-color:#ED99A0;
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
			display:none;
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
			display:none;
		}
		.userphoto
		{
			float:left;
			width:45%; 
			text-align:center;
			margin-left:26%;
			border: 10px solid #d51624;
			background-color:white;
		}
		.userphoto img
		{
			width:100%;
			
			display:none;
		} 
		.username span
		{
			display:none;
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
			border: 1px solid #ED99A0;
		}
		.button div img{
			width:100%;	
		}
		.stop{
			
		}
	</style>
</head>
<body>
<div class="logo"><img src="images/1044_logo.jpg" /></div>
<div class="left">
</div>
<div class="mid">
	<div class="title">
	德国国家旅游局<br/>
	2017春季旅游推介会
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
			<div class="stop" onclick="stop()">
				<img src="images/1044_stop.png"/>
			</div>
			<div class="play" onclick="start()">
				<img src="images/1044_play.png"/>
			</div>
			<div class="save" onclick="save()">
				<img src="images/1044_save.png"/>
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
var Round =[];
var timer='';
var Round_Id=0;
var Save=0;
<?php echo($s_javascript);?> 
function start()
{
	Save=0;
	clearInterval(timer)
	timer=setInterval('round()',50);
	$(".stop").show()
	$(".play").hide()
}
function stop()
{
	clearInterval(timer)
	timer=0
	$(".stop").hide()
	$(".play").show()
}
function round()
{
	id=Math.floor(Math.random()*Round.length)
	$('.userphoto img').hide();
	$('#img_'+Round[id]).show(); 
	$('.username span').hide();
	$('.company span').hide();
	$('#company_'+Round[id]).show(); 
	$('#name_'+Round[id]).show(); 
	Round_Id=id;
}
function show_photo()
{
	if(timer==0)
	{
   	 	var data='Ajax_FunName=getUserUploadPhoto&id='+Round[Round_Id]+'&round=<?php echo($id)?>';//后台方法
			 $.getJSON("include/bn_submit.switch.php",data,function (json){
				//window.alert(json)
				if (json.photo!='')
				{
					var ch = document.compatMode == "BackCompat"?document.body.clientHeight:document.documentElement.clientHeight;
					ch=ch-50
					//window.open(json.photo,'_blank');
					dialog_show('<img align="absmiddle" style="height:'+ch+'px;text-align:center" src="'+json.photo+'"/>')
					//dialog_photo('<img align="absmiddle" style="height:400px;text-align:center" src="'+json.photo+'"/>')	
				}
    		})	 
	}
}
function save()
{
	if(timer==0 && Save==0)
	{
   	 	var data='Ajax_FunName=SaveRound&id='+Round[Round_Id]+'&round=<?php echo($id)?>';//后台方法
		dialog_confirm('真的要保存中奖信息吗？',function(){
			Save=1;
			Round.splice(Round_Id,1)
			 	$.getJSON("../include/bn_submit.switch.php",data,function (json){
				//window.alert(json)
    		})	 
		})
	}
}
start();
</script>
</body>
</html>
