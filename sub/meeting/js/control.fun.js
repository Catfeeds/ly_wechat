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
function send_reminder(activity_id)
{
    var data='Ajax_FunName=SendReminder&id='+activity_id;//后台方法
	dialog_confirm('真的要发送给所有粉丝会议提醒吗？<br/>注：每个审核通过的粉丝都会收到微信消息提醒。',function(){
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
