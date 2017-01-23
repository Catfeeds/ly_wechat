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