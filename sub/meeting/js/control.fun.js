function get_audit_status(id)
{
	//window.alert(id);
    var data='Ajax_FunName=GetAuditStatus&sceneid='+id;//后台方法
    $.getJSON("include/bn_submit.switch.php",data,function (json){
		$('#status').html('&nbsp;&nbsp;'+json.status);
		$('#table_title').html(json.table_title);
    })  
}
function audit_approve(obj,id,activity_id)
{
    var data='Ajax_FunName=AuditApprove&id='+id;//后台方法
    $('.small_loading').fadeIn(100);
    $.getJSON("include/bn_submit.switch.php",data,function (json){
    	table_refresh(table);
		get_audit_status(activity_id);
    })	 
}
function audit_reject(obj,id,activity_id)
{
    var data='Ajax_FunName=AuditReject&id='+id;//后台方法
	dialog_confirm('真的要取消这个报名信息吗？<br/>取消后，粉丝微信将会收到未通过审核提醒。',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 table_refresh(table);
		 get_audit_status(activity_id);
    	})	 
	})
}
function audit_blacklist(obj,id,activity_id)
{
    var data='Ajax_FunName=AuditBlacklist&id='+id;//后台方法
	dialog_confirm('真的要将这个粉丝加入黑名单吗？<br/>加入黑名单后，该微信号将无法再参见任何会议。',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
		 	get_audit_status(activity_id);
    	})	 
	})
}
function audit_delete(obj,id,activity_id)
{
    var data='Ajax_FunName=AuditDelete&id='+id;//后台方法
	dialog_confirm('真的要删除这个报名信息吗？',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
		 	get_audit_status(activity_id);
    	})	 
	})
}
function audit_disable_round(obj,id,activity_id)
{
    var data='Ajax_FunName=AuditDisableRound&id='+id;//后台方法
	dialog_confirm('真的要禁止这个用户参与所有抽奖吗？',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
		 	get_audit_status(activity_id);
    	})	 
	})
}
function audit_enable_round(obj,id,activity_id)
{
    var data='Ajax_FunName=AuditEnableRound&id='+id;//后台方法
	dialog_confirm('真的要允许这个用户参与所有抽奖吗？',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
		 	get_audit_status(activity_id);
    	})	 
	})
}
function send_reminder(activity_id,title,time,address,explain)
{
	explain=decodeURIComponent(explain)
	explain=explain.replace(/\r\n/g,"<br/>") ;
    var data='Ajax_FunName=SendReminder&id='+activity_id;//后台方法
    var a_arr=[];
	a_arr.push('<div style="padding:10px;">');
    a_arr.push('    <div style="border: 1px solid #DDDDDD;border-radius:5px;padding:10px;font-size:14px;background-color:#F2F4F8">');
    a_arr.push('    	<div style="font-size:16px;">行程安排提醒</div>');
    a_arr.push('    	<div style="margin-top:15px;">尊敬的用户，您好！欢迎参加'+decodeURIComponent(title)+'活动，请按以下行程安排好您的时间：</div>');
	a_arr.push('		<div style="margin-top:15px;">行程时间：<span style="color:#173177">'+decodeURIComponent(time)+'</span></div>');
	a_arr.push('		<div>行程安排：<span style="color:#173177">'+decodeURIComponent(address)+'</span></div>');
	a_arr.push('		<div>'+decodeURIComponent(explain)+'</div>');
	a_arr.push('	</div>');
    a_arr.push('</div>');
	dialog_confirm('真的要发送给所有粉丝会议提醒吗？<br/>每个审核通过的粉丝都会收到以下内容微信消息提醒，如信息有误，请及时联系管理员修改后再发送：<br/><br/>'+a_arr.join(''),function(){
		 $('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
		 	$('.small_loading').fadeOut(100);
		 	dialog_success("发送提醒成功！")
    	})	 
	})
}
function open_photo(photo)
{
    dialog_photo('<img style="width:414px;height:414px;" src="'+photo+'"/>')	 
}
function dialog_photo(text,fun)
{
    $.teninedialog({
        width:'450px',
        title: '<span style="' + Language.Font + '">粉丝头像预览</span>',
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
function search_for_user()
{
	var fun='MeetingAuditList';
	var id='Vcl_KeyUser'
	$('.small_loading').fadeIn(100);
	$.cookie(fun+"Page",1);
	$.cookie(fun+$.cookie(fun+"Key")+"OtherKey",document.getElementById(id).value);
	var sort=$.cookie(fun+"Sort"); 
	var item=$.cookie(fun+"Item"); 
	var key=$.cookie(fun+"Key");
	 
	table_load(fun,item,sort,1,key,encodeURIComponent(document.getElementById(id).value));    
}
$(function(){
	$('#Vcl_KeyUser').keypress(function(event){  
	    var keycode = (event.keyCode ? event.keyCode : event.which);  
	    if(keycode == '13'){  
	    	search_for_user()   
	    }  
	}); 
})
