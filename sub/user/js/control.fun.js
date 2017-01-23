function get_user_status()
{
	//window.alert(id);
    var data='Ajax_FunName=GetUserStatus';//后台方法
    $.getJSON("include/bn_submit.switch.php",data,function (json){
		$('#status').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+json.status);
    })  
}
function open_photo(photo)
{
    dialog_photo('<img style="width:414px;height:414px;" src="'+photo+'"/>')	 
}
function block_enable(id)
{
    var data='Ajax_FunName=BlockEnable&id='+id;//后台方法
	dialog_confirm('真的要将这个粉丝解除黑名单吗？',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
    	})	 
	})
}
function user_block(id)
{
    var data='Ajax_FunName=UserBlock&id='+id;//后台方法
	dialog_confirm('真的要将这个粉丝加入黑名单吗？<br/>加入黑名单后，该微信号将无法再参见任何活动。',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
    	})	 
	})
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
	var fun='UserList';
	var id='Vcl_KeyUser'
	$('.small_loading').fadeIn(100);
	$.cookie(fun+"Page",1);
	$.cookie(fun+"Key",document.getElementById(id).value);
	var sort=$.cookie(fun+"Sort"); 
	var item=$.cookie(fun+"Item"); 
	table_load(fun,item,sort,1,encodeURIComponent(document.getElementById(id).value));    
}
$(function(){
	$('#Vcl_KeyUser').keypress(function(event){  
	    var keycode = (event.keyCode ? event.keyCode : event.which);  
	    if(keycode == '13'){  
	    	search_for_user()   
	    }  
	}); 
})
