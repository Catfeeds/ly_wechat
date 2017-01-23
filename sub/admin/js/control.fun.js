function user_set_status(obj,id,status,text)
{
	var old_text=$(obj).html();
	$(obj).html(text);
	if(status==1)
	{
		var temp=$(obj).parents('tr').find('.label-danger');
		$(temp).html(Language['StatusEnable'])
		$(temp).removeClass('label-danger')
		$(temp).addClass('label-success')
		$(obj).attr('onclick',"user_set_status(this,'"+id+"',0,'"+old_text+"')");
	}else{
		var temp=$(obj).parents('tr').find('.label-success');
		$(temp).html(Language['StatusDisable'])
		$(temp).removeClass('label-success')
		$(temp).addClass('label-danger')
		$(obj).attr('onclick',"user_set_status(this,'"+id+"',1,'"+old_text+"')");
	}
	var data='Ajax_FunName=UserSetStatus';//后台方法
    data=data+'&id='+id+'&status='+status;
    $.getJSON("include/bn_submit.switch.php",data,function (json){
	})
}
function user_delete_confirm(obj,id)
{

	dialog_confirm(Language['UserDelConfirm'],function(){user_delete(obj,id)})
}
function user_delete(obj,id)
{
	obj=$(obj).parents('tr');
	obj.hide();
	var data='Ajax_FunName=UserDelete';//后台方法
    data=data+'&id='+id;
    $.getJSON("include/bn_submit.switch.php",data,function (json){
	})
}
function user_modify()
{
	var val='';
	try{
		val=document.getElementById('Vcl_UserName').value;
	    if (val.length==0){
	        dialog_message(Language['Message001']);
	        return
	    }else{
	         function isDigit(str){ 
	            var reg = /^[0-9a-zA-Z]*$/; 
	            return reg.test(str); 
	         }
	         if (!isDigit(val))
	         {
	            dialog_message(Language['Message002'])
	            return 
	         }
	    }
		if (val.length<6){
	        dialog_message(Language['Message003'])
	        return
	    }
	}catch(e){
		
	}
	
	try{
        val = document.getElementById('Vcl_Password').value;
        if (val.length == 0) {
            dialog_message(Language['Message006'])
            return
        }
        if (val.length < 6) {
            dialog_message(Language['Message007'])
            return
        }
        if (val != document.getElementById('Vcl_Password2').value) {
            dialog_message(Language['Message008'])
            return
        }
    } catch (e) {
    }
	val =$('#Vcl_Name').val();
    if (val== '') {
        dialog_message(Language['Message009'])
        return
    }
	val =$('#Vcl_DeptId').val();
    if (val== '') {
        dialog_message(Language['Message004'])
        return
    }
    val=$('#Vcl_Role0').val();
    if (val==''){
        dialog_message(Language['Message005'])
        return
    }
	loading_show();
	$('#submit_form').submit();
}
function user_resetpasswd()
{	
    var val = $('#Vcl_Password').val();
    if (val.length == 0) {
        dialog_message(Language['Message010'])
        return
    }
    if (val.length < 6) {
        dialog_message(Language['Message007'])
        return
    }
    if (val != $('#Vcl_Password2').val()) {
        dialog_message(Language['Message008'])
        return
    }
	loading_show();
	$('#submit_form').submit();
}
$(function(){
	$('ins').click(function(){
		//先获得自己是否选中
		var parent=this.parentNode
		var own=$(parent).find('input')
		own=own[0]
		if (own.checked)
		{
			//如果选中，需要将父勾选，和子的所有都勾选
			//将是他的父都选上
			if ($(this.parentNode.parentNode.parentNode).attr('class')!=undefined)
			{
				if ($(this.parentNode.parentNode.parentNode.parentNode).attr('class')!=undefined)
				{
					var temp=$(this.parentNode.parentNode.parentNode.parentNode).find('input')
					$(temp[0]).iCheck('check')
				}
				var temp=$(this.parentNode.parentNode.parentNode).find('input')
				$(temp[0]).iCheck('check')
			}
			//将是他的子，都选上
			$(this.parentNode.parentNode).find('.sub_role').iCheck('check')	
		}else{
			//将他的子都不选上
			$(this.parentNode.parentNode).find('.sub_role').iCheck('uncheck')
			//如果他的父下，所有的平列子都是未选，那么父不选	
			uncheck_parent(this,true)
		}
	})
	$('#Vcl_KeyUser').keypress(function(event){  
	    var keycode = (event.keyCode ? event.keyCode : event.which);  
	    if(keycode == '13'){  
	    	search_for_user()   
	    }  
	}); 
})
function search_for_user()
{
	var fun='UserTable';
	var id='Vcl_KeyUser'
	$('.small_loading').fadeIn(100);
	$.cookie(fun+"Page",1);
	$.cookie(fun+"Key",document.getElementById(id).value);
	var sort=$.cookie(fun+"Sort"); 
	var item=$.cookie(fun+"Item"); 
	table_load(fun,item,sort,1,encodeURIComponent(document.getElementById(id).value));    
}
function uncheck_parent(obj,loop)
{
	var father=$(obj.parentNode.parentNode.parentNode).children('.icheckbox_square-blue').children('input')//获取父选项
	var bother=$(obj.parentNode.parentNode.parentNode).children('.sub_role')//获取同级的DIV
	var b=false
	for(var i=0;i<bother.length;i++)
	{
		var temp=$(bother[i]).children('.icheckbox_square-blue').children('input')
		if (temp[0].checked)//检验每个并列的是否被选中
		{
			b=true
			break;
		}
	}
	if (b==false)
	{
		$(father[0]).iCheck('uncheck')//如果并列的选项都未选，那么取消父选项的勾选
	}
	if (loop)
	{
		uncheck_parent(father[0],false)//在往上看一级的父选项
	}	
}
function role_modify()
{
	var val = $('#Vcl_Name').val();
    if (val.length == 0) {
        dialog_message(Language['Message011'])
        return
    }
    var explain=$('#Vcl_Explain').val();
    var checked_data='';
    var a_input=$('.main_role').find('input')
    //循环判断哪些被选中
    for(var i=0;i<a_input.length;i++)
    {
    	if (a_input[i].checked)
    	{
    		checked_data = checked_data + '' + a_input[i].id.replace('module_','') + ','
    	}
    }
    checked_data=checked_data.substr(0,checked_data.length-1)//去掉最后一个逗号
    checked_data='[' + checked_data + ']'
    $('#Vcl_Check').val(checked_data)
    loading_show();
	$('#submit_form').submit();
}
function delete_role(id) {
    dialog_confirm(Language.RoleDelConfirm,function(){
    	$('.small_loading').fadeIn(100);
    	var data = 'Ajax_FunName=RoleDelete'; //后台方法
        data = data + '&id=' + id;
        $.getJSON("include/bn_submit.switch.php", data, function (json) {
        	if (json.success==0)
        	{
        		$('.small_loading').fadeOut(100);
        		dialog_error(json.text)
        	}else{
        		table_refresh('RoleTable')
        	}        	
        })
    })
}
function netdisk_delete(id) {
    dialog_confirm(Language.RoleDelConfirm,function(){
    	$('.small_loading').fadeIn(100);
    	var data = 'Ajax_FunName=RoleDelete'; //后台方法
        data = data + '&id=' + id;
        $.getJSON("include/bn_submit.switch.php", data, function (json) {
        	if (json.success==0)
        	{
        		$('.small_loading').fadeOut(100);
        		dialog_error(json.text)
        	}else{
        		table_refresh('RoleTable')
        	}        	
        })
    })
}
function netdisk_modify()
{
	loading_show();
	$('#submit_form').submit();
}
function dept_modify()
{
	var val = $('#Vcl_Name').val();
    if (val.length == 0) {
        dialog_message(Language['Message014'])
        return
    }
    loading_show();
	$('#submit_form').submit();
}
function delete_dept(id) {
    dialog_confirm(Language.DeptDelConfirm,function(){
    	$('.small_loading').fadeIn(100);
    	var data = 'Ajax_FunName=DeptDelete'; //后台方法
        data = data + '&id=' + id;
        $.getJSON("include/bn_submit.switch.php", data, function (json) {
        	if (json.success==0)
        	{
        		$('.small_loading').fadeOut(100);
        		dialog_error(json.text)
        	}else{
        		table_refresh('DeptTable')
        	}        	
        })
    })
}
function config_modify()
{
	
	var val = $('#Vcl_SystemName').val();
    if (val.length == 0) {
        dialog_message(Language['Message015'])
        return
    }
	var val = $('#Vcl_HomeUrl').val();
    if (val.length == 0) {
        dialog_message(Language['Message016'])
        return
    }
	var val = $('#Vcl_Footer').val();
    if (val.length == 0) {
        dialog_message(Language['Message017'])
        return
    }
    loading_show();
	$('#submit_form').submit();
}
