get_sysmsg()
function get_sysmsg()
{
	$('.small_loading').fadeIn(300);
	var data='Ajax_FunName=GetSysMsg';
    $.getJSON(RootPath+"include/bn_submit.switch.php",data,function (json){
		if (json.length==0)
		{
			$('#msg_list').html('<div class="nothing">'+Language.NothingRecord+'</div>');
			$('.small_loading').fadeOut(300);
			$('#msg_list').fadeIn(300);
			return;
		}
		a_arr=[];
		for(var i=0;i<json.length;i++)
		{
			if (json[i].isread==0)
			{
				a_arr.push('<div class="box unread">');
			}else{
				a_arr.push('<div class="box">');
			}
            a_arr.push('	<div style="padding:10px;width:100%"><div>'+Language.Time+'：'+sys_msg_format_date(json[i].date)+'<br/>');
            a_arr.push(decodeURIComponent(json[i].text));
            a_arr.push('   </div>');
            a_arr.push('   <div class="button" onclick="sys_msg_delete(this,'+json[i].id+')" title="'+Language.Delete+'" data-toggle="tooltip">');
            a_arr.push('   	<span class="glyphicon glyphicon-remove-circle"></span>');
            a_arr.push('   </div></div>');
            a_arr.push('</div>');
		}
		$('#msg_list').html(a_arr.join('\n'));
		$('.small_loading').fadeOut(300);
		$('#msg_list').fadeIn(300);
		$("[data-toggle='tooltip']").tooltip({
			delay: {
				show: 500,
				hide: 100
			}
		});
	});
}
function sys_msg_delete(obj,id)
{
	$(obj).blur()
	var parent=$(obj).parents('.box')
	$(parent).animate({height: '0px',margin: '0px'})
	var data='Ajax_FunName=SysMsgDelete&id='+id;
	$.getJSON(RootPath+"include/bn_submit.switch.php",data);
}
function sys_msg_delete_all()
{
 	dialog_confirm(Language.ConfirmDeleteAllRecord,function () {
		$('#msg_list').slideToggle();
		var data='Ajax_FunName=SysMsgDeleteAll';
		$.getJSON(RootPath+"include/bn_submit.switch.php",data);
	})
}
function sys_msg_format_date(d_date)
{
	var minute = 1000 * 60;
	var hour = minute * 60;
	var day = hour * 24;
	var halfamonth = day * 15;
	var month = day * 30;
	dateTimeStamp=Date.parse(d_date.replace(/-/gi,"/"));
	var now = new Date().getTime();
	var diffValue = now - dateTimeStamp;
	var monthC =diffValue/month;
	var weekC =diffValue/(7*day);
	var dayC =diffValue/day;
	var hourC =diffValue/hour;
	var minC =diffValue/minute;
	if(monthC>=1){
		result=d_date;
	}else if(weekC>=1){
		result=d_date;
	}else if(dayC>=1){
		result=parseInt(dayC) +Language.Day+Language.Before;
	}else if(hourC>=1){
		result=parseInt(hourC) +Language.Hour+Language.Before;
	}else if(minC>=1){
		result=parseInt(minC) +Language.Min+Language.Before;
	}else{
		result=Language.Just;
	}
    return result;
}