<?php
define ( 'RELATIVITY_PATH', '../../../' );
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once '../include/db_table.class.php';

$a_color=array('#DFDCD6','#9DC4A1','#15A1BD','#EEC72F','#2EAF69','#EA5A30','#CB8A24','#166DAC','#81CACA','#AB66B3','#C5225A');
$a_font=array('28','32','36','40','44');
//---------------------------------------------------------配置信息-----------------
$a_title=array(//设置每轮的标题
		'第一轮',
		'第二轮',
		'第三轮',
		'第四轮',
		'第五轮');
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
$s_timer='';
$n_j=0;
for($i=0;$i<$n_count;$i++)
{
	$o_user=new WX_User_Info($o_round->getUserId($i));
	if ($o_user->getDelFlag()==0)
	{
		//$s_photo.='<img id="img_'.$o_round->getId($i).'" src="'.$o_user->getPhoto().'"/>';
		//$s_name.='<span id="name_'.$o_round->getId($i).'">'.$o_user->getUserName().'</span>';
		$s_name.='<div id="name_'.$o_round->getId($i).'" class="name" style="font-size:'.$a_font[rand(0,(count($a_font)-1))].'px;color:'.$a_color[rand(0,(count($a_color)-1))].'">'.$o_user->getUserName().'</div>';
		$s_javascript.='Round.push('.$o_round->getId($i).');';
		
		$s_timer.='setInterval("float('.$o_round->getId($i).','.$n_j.','.rand(4,8).')", '.rand(30,60).');';
		$n_j++;
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
    <link rel="stylesheet" href="css/weui.min.css"/>
    <style type="">
		body{
			margin:0px;
			overflow:hidden;
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
			position:absolute;
			z-index: 1034;
		}
		.button div{
			float:left;
			width:136px;
			height:149px;
			cursor:pointer;
			margin-left:10px;
		}
		.button div img:hover{
			border: 1px solid white;
		}
		.button div img{
			width:82px;
			height:82px;	
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
		.logo{
			position:absolute;
			z-index: 1034;
		}
		.name{
			position:absolute;
			z-index: 1033;
			top:50%;
			left:50%;
			font-weight:bold;
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
		}
		.title{
			position:absolute;
			z-index: 1034;
			top:30px;
			left:30px;
			color:white;
			border: 4px solid white;
			font-size:30px;
			font-weight:bold;
			padding: 30px 20px 30px 20px;
		}
		.show_name
		{
			position:absolute;
			z-index: 1034;
			display:none;
			color:#46A851;
			font-size:150px;
			font-weight:bold;
		}
	</style>
</head>
<body>
<img class="logo" src="images/1027_logo.png"/>
<img class="bj" src="images/1027_bj.jpg"/>
<div class="title"><?php echo($a_title[($id-1)])?></div>
<?php echo($s_name)?>
	<div class="button">
		<div class="stop" onclick="stop()">
			<img src="images/1027_stop.png"/>
		</div>
		<div class="goon" onclick="play()">
			<img src="images/1027_play.png"/>
		</div>
		<div class="save" onclick="save()">
			<img src="images/1027_save.png"/>
		</div>
		
		<div class="next" onclick="location='1027.php?id=<?php echo(($id+1))?>'">
			<img src="images/1027_next.png"/>
		</div>
		
		
		
	</div>
<div class="show_name">

</div>
</body>
<script>
var Round =[];
var timer=1;
var Round_Id=0;
var Save=0;
$(".logo").css("top","30px");
$(".logo").css("left",$(window).width()-180+"px");
$(".button").css("top",($(window).height()-120)+"px");
$(".button").css("left",($(window).width()/2-280)+"px");
<?php echo($s_javascript)?>
var X=[];
var Y=[];
var Xin=[];
var Yin=[];
$(document).ready(function() {

	for(i=0;i<Round.length;i++)
	{	
		x= $(window).width()-175 
		y= $(window).height()-30
		x=Math.floor(Math.random()*x);
		y=Math.floor(Math.random()*y);
		$("#name_"+Round[i]).css("left",x+"px")
		$("#name_"+Round[i]).css("top",y +"px")
		
		X.push($("#name_"+Round[i]).position().left);
		Y.push($("#name_"+Round[i]).position().top);
		Xin.push(true)
		Yin.push(true)
	}
	<?php 
	echo($s_timer);
	?>
});
function float(id,i,step) { 
	var L=T=0 
	var R= $(window).width()-$("#name_"+id).width();
	var B =$(window).height()-$("#name_"+id).height();
	$("#name_"+id).css("left",X[i]+document.body.scrollLeft+"px")
	$("#name_"+id).css("top",Y[i] + document.body.scrollTop +"px")
	X[i] = X[i] + step*(Xin[i]?1:-1) 
	if (X[i] < L) { Xin[i] = true; X[i] = L} 
	if (X[i] > R){ Xin[i] = false; X[i] = R} 
	Y[i] = Y[i] + step*(Yin[i]?1:-1) 
	if (Y[i] < T) { Yin[i] = true; Y[i] = T } 
	if (Y[i] > B) { Yin[i] = false; Y[i] = B } 
} 
$(".bj").height($(window).height());
$(window).resize(function(){location.reload()});

function stop()
{
	if (timer==0)
	{
		return;
	}
	var id=Math.floor(Math.random()*Round.length)
	$('.name').hide();//隐藏所有，并且显示一个名字到正中间
	
	$('.show_name').html($("#name_"+Round[id]).html());
	$('.show_name').css("left",Math.floor($(window).width()/2-$('.show_name').width()/2)+"px")
	$('.show_name').css("top",Math.floor($(window).height()/2-$('.show_name').height()+50)+"px")
	$('.show_name').show();
	Round_Id=id
	timer=0
}
function play()
{
	Round_Id=0;
	Save=0;
	timer=1;
	$('.name').show();//隐藏所有，并且显示一个名字到正中间
	$('.show_name').hide();
	$('.show_name').html('');
}
function save()
{
	if(timer==0 && Save==0)
	{
   	 	var data='Ajax_FunName=SaveRound&id='+Round[Round_Id]+'&round=<?php echo($id)?>';//后台方法
		dialog_confirm('真的要保存中奖信息吗？',function(){
			Save=1;			
			$("#name_"+Round[Round_Id]).html('');
			Round.splice(Round_Id,1);
			//window.alert($("#name_"+Round[Round_Id]))
			 	$.getJSON("../include/bn_submit.switch.php",data,function (json){
				//window.alert(json)
    		})	 
		})
	}
}
</script>
</html>
