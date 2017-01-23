function dialog_message(text,fun)
{
    $.teninedialog({
        width:'450px',
        title: '<span class="glyphicon glyphicon-info-sign" style="top:2px;color:#F0AD4E;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.MessageBoxTitle_01 + '</span>',
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
function dialog_success(text, fun) {
    $.teninedialog({
        width: '450px',
        title: '<span class="glyphicon glyphicon-ok-sign" style="top:2px;color:#5CB85C;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.MessageBoxTitle_02 + '</span>',
        content: '<span style="' + Language.Font + 'font-size:14px;">' + text + '</span>',
        showCloseButton: false,
        otherButtons: ['<span style="' + Language.Font + '">' + Language.Confirm + '</span>'],
        otherButtonStyles: ['btn-primary'],
        bootstrapModalOption: { keyboard: true },
        clickButton: function (sender, modal, index) {
            if (fun) {
                fun();
            }
            $(this).closeDialog(modal);
        }
    });

	
}
function dialog_error(text, fun) {
    $.teninedialog({
        width: '450px',
        title: '<span class="glyphicon glyphicon-remove-sign" style="top:2px;color:#D9534F;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.MessageBoxTitle_03 + '</span>',
        content: '<span style="' + Language.Font + 'font-size:14px;">' + text + '</span>',
        showCloseButton: false,
        otherButtons: ['<span style="' + Language.Font + '">' + Language.Confirm + '</span>'],
        otherButtonStyles: ['btn-primary'],
        bootstrapModalOption: { keyboard: true },
        clickButton: function (sender, modal, index) {
            if (fun) {
                fun();
            }
            $(this).closeDialog(modal);
        },
		dialogShown:function(){
            //alert('显示对话框');
			//$('.modal-backdrop').addClass('blur');
        }
    });
}
function dialog_confirm(text, fun) {
    $.teninedialog({
        width: '450px',
        title: '<span class="glyphicon glyphicon-question-sign" style="top:2px;color:#F0AD4E;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.MessageBoxTitle_04 + '</span>',
        content: '<span style="' + Language.Font + 'font-size:14px;">' + text + '</span>',
        showCloseButton: false,
        otherButtons: ['<span style="' + Language.Font + '">' + Language.Confirm + '</span>', '<span style="' + Language.Font + '">' + Language.Cancel + '</span>'],
        otherButtonStyles: ['btn-primary', 'btn-default'],
        bootstrapModalOption: { keyboard: true },
        clickButton: function (sender, modal, index) {
            if (fun && index==0) {
                fun();
            }
            $(this).closeDialog(modal);
        }
    });
}
function form_return(command)
{
    loading_hide()
    eval(command)    
}
function loading_show(){
    $('#loading').modal({ backdrop: 'static', keyboard: false});
}
function loading_hide()
{
    $('#loading').modal('hide');
}