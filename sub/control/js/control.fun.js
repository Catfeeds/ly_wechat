function info_modify()
{
	var val = $('#Vcl_Name').val();
    if (val.length == 0) {
        dialog_message(Language['Message009'])
        return
    }
    loading_show();
	$('#submit_form').submit();
}
function password_modify()
{	
	var val = $('#Vcl_OldPassword').val();
    if (val.length == 0) {
        dialog_message(Language['Message012'])
        return
    }
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
//根据业务需要，可以自行获取红点
function get_red_point(n_module_id)
{
	var number=5;
	//发送ajax到后台获取数值
	if(number>0)
	{
		$('#sub_nav_'+n_module_id).show()
	}else{
		$('#sub_nav_'+n_module_id).hide()
	}
}