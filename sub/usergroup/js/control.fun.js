function group_del(id)
{
    var data='Ajax_FunName=GroupDel&id='+id;//后台方法
	dialog_confirm('真的要将删除这个标签吗？<br/>删除后，该标签将从用户标签属性中移除。',function(){
		$('.small_loading').fadeIn(100);
		 $.getJSON("include/bn_submit.switch.php",data,function (json){
    	 	table_refresh(table);
    	})	 
	})
}
function group_modify()
{
	val =$('#Vcl_GroupName').val();
    if (val== '') {
        dialog_message("请填写“标签名称”！")
        return
    }
	loading_show();
	$('#submit_form').submit();
}

