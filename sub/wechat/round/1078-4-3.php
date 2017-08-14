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
		$s_photo.='<img id="img_'.$o_round->getId($i).'" src="'.$o_user->getPhoto().'"/>';
		$s_name.='<span id="name_'.$o_round->getId($i).'">'.$o_user->getUserName().'</span>';
		$s_javascript.='Round.push('.$o_round->getId($i).');';
	}
}
//如果奖池为空，那么显示默认照片
if($n_count==0)
{
	$s_photo.='<img id="img_0" src="../images/photo_default.jpg"/>';
	$s_name.='<span id="name_0">用户姓名</span>';
	$s_javascript.='Round.push(0);';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>活动抽奖 16:9</title>
    <script src="../../../js/initialize.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../css/weui.min.css"/>
    <style type="">
		body{
			margin:0px;
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
			font-size:40px;
			color:#293A88;
			position:fixed;
			margin-left:20%;
			font-weight:bold;
			top:65%;
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
		}
		.userphoto
		{
			float:left;
			width:26%; 
			margin-left:37%;
			margin-right:37%;
			position:fixed;
			text-align:center;
			top:55%;
		}
		.userphoto img
		{
			width:100%;
			border: 10px solid #E32182;
			display:none;
		} 
		.username
		{
			float:left;
			font-size:30px;
			margin-left:70%;
			top:66%;
			position:fixed;
			color:#293A88;
			font-weight:bold;
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
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
			width:auto;
			margin-left:auto;
			max-width:235px;
			min-width:235px;
			margin-right:auto;
		}
		.button div{
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
			float:left;
			cursor:pointer;
			color:#293A88;
			font-size:18px;
			font-weight:bold;
			cursor:pointer;
			margin-right:20px;
			margin-left:20px;
		}
		.button div img{
			width:82px;
			height:82px;	
			border: 0px solid white;
		}
		.stop{
			
		}
		body{
		}
	</style>
</head>
<body>
<img class="bj" src="images/1078_bj.jpg" style="width:100%"/>
<div class="body">
	<div class="round_number">
		<?php echo($a_title[$id-1])?>
	</div>
	<div class="userphoto">
		<?php echo($s_photo)?>
	</div>
	<div class="username">
		<?php echo($s_name)?>
	</div>
</div>
<nav class="navbar navbar-fixed-bottom footer" style="margin-bottom: 0px;">
	<div class="button">
		<div class="stop" onclick="stop()">
			暂停
		</div>
		<div class="save" onclick="save()">
			保存
		</div>
		<div class="goon" onclick="start()">
			继续
		</div>
	</div>
</nav>
<div class="weui_dialog_alert" id="massagebox" style="display:none" onclick="dialog_close()">
    <div class="weui_mask" style="z-index:1033;"></div>
    <div class="weui_dialog" style="z-index:1034;background-color:inherit">
        <div class="weui_dialog_bd" id="massagebox_text"></div>
    </div>
</div>
<script type="">
$(window).resize(function(){set_resize()});
set_resize()
function set_resize()
{
	//先定位logo
	var height=$(window).height();
	$('.bj').css('height',height+'px')
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
	$('#name_'+Round[id]).show(); 
	Round_Id=id;
}
function show_photo()
{
	if(timer==0)
	{
   	 	var data='Ajax_FunName=getUserUploadPhoto&id='+Round[Round_Id]+'&round=<?php echo($id)?>';//后台方法
			 $.getJSON("../include/bn_submit.switch.php",data,function (json){
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
