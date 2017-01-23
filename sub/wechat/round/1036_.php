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
		'抽奖');
$n_show_upload_photo='';//第几轮是可以查看上传图片的
//---------------------------------------------------------------------------------
$n_round=count($a_title);
$id=1;
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
	if ($o_user->getDelFlag()==0)
	{
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
			border: 20px solid #73A9A9;
		}
		.title{
			float:left;
			width:70%;
			margin-top:0px;
		}
		.title img{
			width:100%;
		}
		.logo{
			float:right;
			width:15%;
			text-align:right;
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
			width:40%; 
			text-align:center;
		}
		.userphoto img
		{
			width:100%;
			border: 20px solid #73A9A9;
			display:none;
		} 
		.username
		{
			float:right;
			width:30%;
			font-size:40px;
			text-align:center;
			padding-top:140px;
			color:#73A9A9;
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
			padding-top:40px;
			width:100%;
			padding-left:36%;
		}
		.button div{
			float:left;
			width:108px;
			height:113px;
			pading:3px;
			cursor:pointer;
		}
		.button div img:hover{
			border: 1px solid #BFE3F6;
		}
		.button div img{
			width:72px;
			height:75px;	
		}
		.stop{
			
		}
		body{
			padding:5px;
		}
	</style>
</head>
<body>
<div class="top">
	<div class="title"><img src="images/1036_title.png" /></div>
	<div class="logo"><img src="images/1036_logo.png" /></div>
</div>
<div class="body">
	<div class="round_number">
		<?php echo($s_name)?>
	</div>
	<div class="userphoto">
		<?php echo($s_photo)?>
	</div>
	<div class="username">
		<?php echo($s_company)?>
	</div>
</div>
<nav class="navbar navbar-fixed-bottom footer" style="margin-bottom: 20px;">
	<div class="button">
		<div class="stop" onclick="stop()">
			<img src="images/1036_stop.png"/>
		</div>
		<div class="save" onclick="save()">
			<img src="images/1036_save.png"/>
		</div>
		<div class="goon" onclick="start()">
			<img src="images/1036_play.png"/>
		</div>		
	</div>
</nav>
<script type="">


$(window).resize(function(){set_resize()});
set_resize()
$("body").height($(window).height())
function set_resize()
{
	$("body").height($(window).height()-50);//边框
	//精确定位按钮
	var width=$(window).width();
	width=Math.floor(width/2);
	width=width-(294/2);
	$('.button').css("padding-left",width+"px");
	//设置用户图片
	width=Math.floor(($(window).height()-160-150)/$(window).width()*100);//头像宽度
	$('.userphoto').css('width',width+'%')
	width=Math.floor((100-width)/2)
	$('.username').css('width',width+'%')
	$('.round_number').css('width',width+'%')
	//设置logo
	width=150-$('.logo').height()-10;
	$('.body').css('margin-top',width+'px')
	//window.alert($('.logo').height())
	width=Math.floor($(window).height()/2-210)
	$('.username').css('padding-top',width+'px')
	$('.round_number').css('padding-top',width+'px')
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
}
function stop()
{
	clearInterval(timer)
	timer=0
}
function round()
{
	id=Math.floor(Math.random()*Round.length)
	$('.userphoto img').hide();
	$('#img_'+Round[id]).show(); 
	$('.username span').hide();
	$('#company_'+Round[id]).show(); 
	$('.round_number span').hide();
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
function dialog_photo(text,fun)
{
    $.teninedialog({
        width:'300px',
        title: '<span style="' + Language.Font + '">上传分享照片</span>',
        content: '<span style="' + Language.Font + 'font-size:14px;">' + text + '</span>',
        showCloseButton:false,
        otherButtons:['<span style="'+Language.Font+'">'+Language.Confirm+'</span>'],
        otherButtonStyles:['btn-primary'],
        bootstrapModalOption:{keyboard: true},
        dialogShow:function(){
            //alert('即将显示对话框');
        },
        dialogShown:function(){
            //alert('显示对话框');
			/*$('.modal-backdrop fade in').backgroundBlur({
		    	imageURL:'images/2.png',
		    	blurAmount : 10,
		        sharpness: 40,
		        endOpacity : 1
		    });*/ 
        },
        dialogHide:function(){
            //alert('即将关闭对话框');
        },
        dialogHidden:function(){
            //alert('关闭对话框');
        },                    
        clickButton:function(sender,modal,index){
            //alert('选中第'+index+'个按钮：'+sender.html());
            if(fun){
                fun();
            }
            
            $(this).closeDialog(modal);
        }
    });
}
function dialog_show(text)
{
	document.getElementById("massagebox_text").innerHTML=text
	document.getElementById("massagebox").style.display="block"
}
function dialog_close()
{
	document.getElementById("massagebox").style.display="none"
}
start();
</script>
</body>
</html>
