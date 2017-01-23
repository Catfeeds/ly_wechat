/**
 * 
 */
function selected(obj,answer)
{
	$('.select').removeClass('on');
	$(obj).addClass("on") ;
	document.getElementById('Vcl_Answer').value=answer;
}
/*配合1001的提交
function submit_answer()
{
	if (document.getElementById('Vcl_Answer').value=='')
	{
		dialog_show("请选择答案后再提交！")
		return;
	}
	//判断是否答对
	if(document.getElementById('Vcl_Answer').value!=document.getElementById('Vcl_Right').value)
	{
		if (document.getElementById('Vcl_Time').value=='1')
		{
			document.getElementById('Vcl_Time').value='2'
			dialog_show("答案好像不对哦，还有一次机会！")
			return;
		}else{
			dialog_loading()
			document.getElementById('submit_form').submit();
		}	
	}else{
		dialog_loading()
		document.getElementById('submit_form').submit();
	}
}*/
function submit_answer()
{
	if (document.getElementById('Vcl_Answer').value=='')
	{
		dialog_show("请选择答案后再提交！")
		return;
	}
	//判断是否答对
	if(document.getElementById('Vcl_Answer').value!=document.getElementById('Vcl_Right').value)
	{
		dialog_show("没有选对哦，请重新选择！")
		return;
	}else{
		dialog_loading()
		document.getElementById('submit_form').submit();
	}
}
/*配合1015的提交
function submit_answer()
{
	if (document.getElementById('Vcl_Answer').value=='')
	{
		dialog_show("请选择答案后再提交！")
		return;
	}
	dialog_loading()
	//$.cookie("Answer"+document.getElementById('Vcl_Question').value+"_Done","1")
	//$.cookie("Answer"+document.getElementById('Vcl_Question').value+"_Answer",document.getElementById('Vcl_Answer').value)
	document.getElementById('submit_form').submit();
}*/
function submit_success()
{
	dialog_loading_close()
	dialog_show_closewindow('恭喜您答对了，请您关注抽奖！');
}
function submit_error()
{
	dialog_loading_close()
	dialog_show_closewindow('非常遗憾，您没有答对！');
}
function close_window()
{
	document.addEventListener("WeixinJSBridgeReady", WeixinJSBridge.call("closeWindow"));
}