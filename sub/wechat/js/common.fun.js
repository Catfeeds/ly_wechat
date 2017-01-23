/**
 * 
 */
function dialog_show(text)
{
	document.getElementById("massagebox_text").innerHTML=text
	//window.alert(text)
	document.getElementById("massagebox").style.display="block"
}
function dialog_close()
{
	document.getElementById("massagebox").style.display="none"
	document.getElementById("loading").style.display="none"
}
function dialog_loading()
{
	//window.alert(text)
	document.getElementById("loading").style.display="block"
}
function dialog_loading_close()
{
	//window.alert(text)
	document.getElementById("loading").style.display="none"
}
function dialog_success()
{
	//window.alert(text)
	document.getElementById("success").style.display="block"
}
function dialog_show_closewindow(text)
{
	document.getElementById("massagebox_text_close").innerHTML=text
	//window.alert(text)
	document.getElementById("massagebox_closewindow").style.display="block"
}
function del_trim(str)
{ 
    return str.replace(/(^\s*)|(\s*$)/g, ""); 
}
function isMobile(str) 
{
    if (str.toString().length != 11) return false;
	return true;
    var prefix = [130,133,135,189,137,136,131,134,138,139,150,151,152,155,156,132,153,158,187,182,159,186,180,157,182,187,188,145,147,181];
    var re = new RegExp("^(" + prefix.join("|") + ")\\d+$");
    return re.test(str);
}
function isEmail(strEmail) { 
    if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
         return true; 
    else 
        return false;
}