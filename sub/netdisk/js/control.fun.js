try{
	$(window).resize(function(){
		resize_filelist();
	});
}catch(e){}
var Upload_Form=$('#upload_form').html();
$('#upload_form').html('')
var uploader;
refresh('')
function resize_filelist()
{
	var height=$(window).height()-297
	$('.sss_filelist').css('min-height',height+'px')
}
$(function () {
    resize_filelist();
    //设置列表或图标的显示样式
	var type=$.cookie("Netdisk_List"); 
	if (type=='list')
	{
		$('#file_list').addClass('sss_filelist_min');
		$('.list_on').removeClass('list_on')
		$($('.glyphicon-th-list').parents('button')).addClass('list_on');
	}
});
function change_layout(obj,type)
{
	$('.list_on').removeClass('list_on')
	$(obj).addClass('list_on')
	if (type=='list')
	{
		$('#file_list').fadeOut(300,function(){
			$('#file_list').addClass('sss_filelist_min');
			$('#file_list').fadeIn(300);			
		});
	}else{
		$('#file_list').fadeOut(300,function(){
			$('#file_list').removeClass('sss_filelist_min');
			$('#file_list').fadeIn(300);			
		});		
	}
	//让该对象失去焦点
	$(obj).blur()
	$.cookie("Netdisk_List",type)
}
function disable_select() {
    $('.sss_filelist').unbind('mousedown')
    $('.sss_filelist').unbind('mousemove')
    $(document).unbind('mouseup')
	$('button').blur();
}
function build_select() {
    $('.sss_filelist').mousedown(function (e) {
        if (e.which == 3 || $('#file_menu_sub').is(':hidden') == false || e.ctrlKey) {
            return;
        }
        //window.alert(e.which)
        var selList = [];
        var fileNodes = $("#file_list").find('.file');
        for (var i = 0; i < fileNodes.length; i++) {
            fileNodes[i].className = "file";
            selList.push(fileNodes[i]);
        }
        var isSelect = true;

        var evt = window.event || arguments[0];
        var startX = (evt.x || evt.clientX);
        var startY = (evt.y || evt.clientY) + $(document).scrollTop();
        var selDiv = document.createElement("div");
        selDiv.style.cssText = "position:absolute;width:0px;height:0px;font-size:0px;margin:0px;padding:0px;background-color:#C3D5ED;z-index:1000;filter:alpha(opacity:60);opacity:0.6;display:none;";
        selDiv.id = "selectDiv";
        document.body.appendChild(selDiv);

        selDiv.style.left = startX + "px";
        selDiv.style.top = startY + "px";

        var _x = null;
        var _y = null;
        clear_event_bubble(evt);

        $('.sss_filelist').mousemove(function (e) {
            evt = window.event || arguments[0];
            if (isSelect) {
                if (selDiv.style.display == "none") {
                    selDiv.style.display = "";
                }
                _x = (evt.x || evt.clientX);
                _y = (evt.y || evt.clientY) + $(document).scrollTop();
                selDiv.style.left = Math.min(_x, startX) + "px";
                selDiv.style.top = Math.min(_y, startY) + "px";
                selDiv.style.width = Math.abs(_x - startX) + "px";
                selDiv.style.height = Math.abs(_y - startY) + "px";

                // ---------------- 关键算法 --------------------- 
                var _l = selDiv.offsetLeft, _t = selDiv.offsetTop;
                var _w = selDiv.offsetWidth, _h = selDiv.offsetHeight;
                for (var i = 0; i < selList.length; i++) {
                    var sl = selList[i].offsetWidth + selList[i].offsetLeft;
                    var st = selList[i].offsetHeight + selList[i].offsetTop;
                    if (sl > _l && st > _t && selList[i].offsetLeft < _l + _w && selList[i].offsetTop < _t + _h) {
                        $(selList[i]).removeClass('onfocus');
                        $(selList[i]).addClass('onfocus');
                    } else {
                        $(selList[i]).removeClass('onfocus');
                    }
                }

            }
            clear_event_bubble(evt);
        });
        document.onmouseup = function () {
            isSelect = false;
            if (selDiv) {
                document.body.removeChild(selDiv);
                //showSelDiv(selList);
            }
            selList = null, _x = null, _y = null, selDiv = null, startX = null, startY = null, evt = null;
        }
    });
}
function clear_event_bubble(evt) {
	if (evt.stopPropagation)
		evt.stopPropagation();
	else
		evt.cancelBubble = true;
	if (evt.preventDefault)
		evt.preventDefault();
	else
		evt.returnValue = false;
}
function setup_all_icon_event()
//设置所有文件图标的鼠标事件
{
	$('.file').contextmenu({
		//建立file类的右键菜单
		target: "#file_menu",//右键菜单
		before: function(e,$menu) {
			//在菜单显示之前
			$('#file_menu').find('li').removeClass('disabled');//将所有菜单选项都显示
			var a_li=$('#file_menu').find('li');//获取所有菜单选项
			var item = $("#file_list").find('.onfocus')//获取所有已经被选中的文件图标
			if (item.length>1)
			{
				//如果是多选，屏蔽相应的菜单按钮
				$(a_li[0]).addClass('disabled');//屏蔽打开按钮
				$(a_li[1]).addClass('disabled');//屏蔽下载按钮
				$(a_li[2]).addClass('disabled');//屏蔽分享按钮
				
				$(a_li[9]).addClass('disabled');//屏蔽重命名按钮
				$(a_li[4]).addClass('disabled');//屏蔽解压缩按钮
			}else{
				//如果是zip文件，高亮解压缩按钮
				if ($(item[0]).attr('data-type')!='zip')
				{
					$(a_li[4]).addClass('disabled')//屏蔽解压缩按钮
				}else{
					$(a_li[3]).addClass('disabled');//屏蔽压缩按钮
				}
				
			}			
			if ($(item[0]).attr('data-type')=='folder')
			{
				$(a_li[1]).addClass('disabled');//屏蔽下载按钮
				$(a_li[2]).addClass('disabled');//屏蔽分享按钮
			}
			//屏蔽黏贴按钮
			
			return true;
		}
	});
	$('.sss_filelist').contextmenu({
		//建立file类的右键菜单
		target: "#all_menu",//右键菜单
		before: function(e,$menu) {
			//在菜单显示之前
			$('#all_menu').find('li').removeClass('disabled');//将所有菜单选项都显示
			var a_li=$('#all_menu').find('li');//获取所有菜单选项
			//如果黏贴板是空的，那么屏蔽黏贴按钮
			if($.cookie("Netdisk_Paste")=='' ||$.cookie("Netdisk_Paste")==null)
			{
				$(a_li[2]).addClass('disabled');//屏蔽主菜单黏贴按钮
			}	
			return true;
		}
	});
	$('.file').click(function(e){
		if (e.ctrlKey==false) {
			$('.file').removeClass('onfocus');
		}
		if (e.ctrlKey)
		{
			if ($(this).hasClass('onfocus'))
			{
				$(this).removeClass('onfocus');
			}
			else{
				$(this).addClass('onfocus');
			}
		}else{
			$(this).addClass('onfocus');
		}
	});
    $('.file').mousedown(function (e) {
        if (e.ctrlKey == false) {
			if(!$(this).hasClass('onfocus'))
			{
				 $('.file').removeClass('onfocus');
			}           
        }
		if (e.which==3)
		{
			$(this).addClass('onfocus');
		}		
	});
	$('.file').dblclick(function(e){
	    open_file_folder(this)		
	});
}
function delete_files_folders() {
    dialog_confirm(Language.DeleteFilesConfirm,function () { delete_files() })
}
function delete_files() {

    var item = $("#file_list").find('.onfocus')
    if (item.length == 0) {
        dialog_message(Language.DeleteFilesError002);
        return;
    }
    var s_item = '';
    for (var i = 0; i < item.length; i++) {
        if ((i + 1) >= item.length) {
            s_item = s_item + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '"'
        } else {
            s_item = s_item + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '",'
        }
    }
    s_item = '[' + s_item + ']'
    var data = 'Ajax_FunName=DeleteFilesFolders'; //后台方法
    data = data + '&item=' + s_item;
    setup_all_icon_event();
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
        } else {
            refresh('')
        }
    })
    //window.alert(JSON.parse(a_arr)) 

}
function open_click() {
    var item = $("#file_list").find('.onfocus') 
    if (item.length > 1) {
        dialog_error(Language.OpenFilesError001)
        return;
    }
    if (item.length == 0) {
        dialog_message(Language.OpenFilesError002)
        return;
    }
    open_file_folder(item[0])
}
function open_file_folder(obj) {
    switch ($(obj).attr('data-type')) {
        case 'folder':
            open_folder(obj);
            break;
        case 'jpg':
        	var item=$(obj).children('a');
        	MyPicasa.display(item[0]);
            break;
        case 'png':
        	var item=$(obj).children('a');
        	MyPicasa.display(item[0]);
            break;
        case 'gif':
        	var item=$(obj).children('a');
        	MyPicasa.display(item[0]);
            break;
        case 'bmp':
        	var item=$(obj).children('a');
        	MyPicasa.display(item[0]);
            break;
        default:
        	download_file()
            break;
    }	
}
function open_folder(obj) {
    var path = $.cookie("Netdisk_Path")+$(obj).attr('title')+'/';
    refresh(path);
}
function open_up() { 
    var path=$.cookie("Netdisk_Path");
    var a_path = path.split('/')
    path = '/'
    for (var i = 1; i < (a_path.length - 2); i++) {
        path = path + a_path[i] + '/';
    }
    refresh(path)
}
function show_hinit(b,text)
{
	$('.alert').removeClass('alert-success');
	$('.alert').removeClass('alert-danger');
	$('.alert .glyphicon').removeClass('glyphicon-ok-sign');
	$('.alert .glyphicon').removeClass('glyphicon-remove-sign');
	if(b==1)
	{
		//$('.alert').addClass('alert-success');
		//$('.alert .text').html(text);
		//$('.alert').fadeIn(500);
		//$('.alert .glyphicon').addClass('glyphicon-ok-sign');
		//setTimeout('$(".alert").fadeOut(700)',3000)
	}else if(b==2){
		$('.alert').addClass('alert-success');
		$('.alert .text').html(text);
		$('.alert').fadeIn(500);
		$('.alert .glyphicon').addClass('glyphicon-ok-sign');
		setTimeout('$(".alert").fadeOut(700)',3000)
	}else{
		$('.alert').addClass('alert-danger');
		$('.alert .text').html(text);
		$('.alert').fadeIn(500);
		$('.alert .glyphicon').addClass('glyphicon-remove-sign');
		setTimeout('$(".alert").fadeOut(700)',3000)
	}
}
function get_disk_space()
{
	var data = 'Ajax_FunName=GetDiskSpace'; //后台方法
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
    	
		//计算百分比
		var percent=Math.round(json.used/json.total*100)
		//设置进度条
		var css='progress-bar-info';
		if(percent>25)css='progress-bar-success';
		if(percent>50)css='progress-bar-warning';
		if(percent>75)css='progress-bar-danger';
		$('.diskspace .progress-bar').removeClass('progress-bar-info');
		$('.diskspace .progress-bar').removeClass('progress-bar-success');
		$('.diskspace .progress-bar').removeClass('progress-bar-warning');
		$('.diskspace .progress-bar').removeClass('progress-bar-danger');
		$('.diskspace .progress-bar').addClass(css);
		$('.diskspace .progress-bar').css('width',percent+'%');
		//计算可用空间，和总空间，并输出
		var free=json.total-json.used
		if (free<0)free=0
		free=format_filesize(free)
		//输出
		$('.diskspace_text').html(free+Language.DiskFreeText+format_filesize(json.total))
    })
}
function refresh(s_path)
{
	get_disk_space()
	disable_select()
	//不为空是进入，为空是刷新
	$('.small_loading').fadeIn(300);
	var path=$.cookie("Netdisk_Path"); 
	if (s_path!='')
	{
		$.cookie("Netdisk_Path",s_path)
		path=s_path;
	}else{
		if (path==null)
		{
			$.cookie("Netdisk_Path","/")
			path="/"
		}
	}
	path=encodeURIComponent(path);
	var data='Ajax_FunName=Refresh';//后台方法
    data=data+'&path='+path;
    $.getJSON("include/bn_submit.switch.php",data,function (json){
		if (json.success==0)
		{
			show_hinit(json.success,json.text);
			$('.small_loading').fadeOut(300);
			return;
		}
		var a_arr=[];
		var files=json.files;
		files.sort(function(a,b){//按文件名排序
            return a.name.localeCompare(b.name);
		});
		for(var i=0;i<files.length;i++)
		{
			//先显示文件夹
			if (files[i].isfile==0)
			{
				if (files[i].name==false)
				{
					files[i].name='null';
				}
			    a_arr.push('<div class="file" data-path="' + files[i].path + '" data-type="folder" data-toggle="context" title="' + files[i].name + '">');
				a_arr.push('<img id="small_photo" src="images/folder.png" alt="" />');
				a_arr.push('<div class="filename">'+files[i].name+'</div>');
				a_arr.push('<div class="filetype">'+Language.FileTypeFolder+'</div>');
				a_arr.push('<div class="filesize">&nbsp;</div>');
				a_arr.push('<div class="filedate">'+files[i].date+'</div>');
				a_arr.push('</div>');
			}
		}
		var file_sum=0;
		var file_size=0;
		for(var i=0;i<files.length;i++)
		{
			//后显示文件
			if (files[i].isfile)
			{
				if (files[i].name==false)
				{
					files[i].name='null';
				}
				var a_file=files[i].name.split(".");
				a_arr.push('<div class="file" data-path="' + files[i].path + '" data-type="' + a_file[a_file.length - 1] + '" data-toggle="context" title="'+Language.FileName+files[i].name + '\n'+Language.Size+format_filesize(files[i].size)+'\n'+Language.Date+files[i].date+'">');
				if (a_file.length>=2)
				{
					if(a_file[a_file.length-1]=='jpg' || a_file[a_file.length-1]=='png' || a_file[a_file.length-1]=='gif'|| a_file[a_file.length-1]=='bmp')
					{
						//直接显示图片 
						a_arr.push('<a rel="Picasa['+encodeURIComponent('../../'+files[i].path)+']"><img id="small_photo" src="include/bn_submit.switch.php?Ajax_FunName=GetImg&path='+encodeURIComponent(files[i].path)+'" alt=""/></a>');
					}else{
						//过滤后缀，如果不在list里，还要显示file.png
						a_arr.push('<img id="small_photo" src="images/'+a_file[a_file.length-1]+'.png" alt="" />'); 
					}
				}else{
					a_arr.push('<img id="small_photo" src="images/file.png" alt="" />');
				}
				
				a_arr.push('<div class="filename">'+files[i].name+'</div>');
				a_arr.push('<div class="filetype">'+a_file[a_file.length-1]+'&nbsp;'+Language.FileType+'</div>');
				a_arr.push('<div class="filesize">'+format_filesize(files[i].size)+'</div>');
				a_arr.push('<div class="filedate">'+files[i].date+'</div>');
				a_arr.push('</div>');
				file_size=file_size+files[i].size;
				file_sum++;
			}
		}
		if (files.length==0)
		{
			a_arr.push('<div style="text-align:center;font-size:14px;color:#666666">'+Language.EmptyFolder+'</div>');
		}
		$('#file_list').fadeOut(300,function(){
			var title='<div class="list_title"><div class="name">'+Language.FileListTitleName+'</div><div class="type">'+Language.FileListTitleType+'</div><div class="size">'+Language.FileListTitleSize+'</div><div class="date">'+Language.FileListTitleDate+'</div></div>';
			$('#file_list').html(title+a_arr.join('\n'));
			$('#file_list').fadeIn(300);
			setup_all_icon_event();
			
			MyPicasa.initialize();//因为文件刷新了，所以重新构建图片浏览器插件
		});
		//显示文件个数据和大小
		$('.sss_page .explain').html(file_sum+'&nbsp;'+Language.NetdiskFilesTotal+format_filesize(file_size));
		//是否显示向上按钮
		if(json.path=='/')
		{
			$('#up').hide();
		}else{
			$('#up').show();
		}
		//制作路径
		var a_path=json.path.split("/");
		a_arr_path=[];
		if(a_path.length>2)
		{
			a_arr_path.push('<li><a href="javascript:;" onclick="refresh(\'/\')" title="'+Language.Root+'" data-toggle="tooltip"><span class="glyphicon glyphicon-home"></span></a></li>');
		}else{
			a_arr_path.push('<li class="active"><span class="glyphicon glyphicon-home"></span></li>');
		}
		var len=a_path.length-1;
		for(var i=1;i<len;i++)
		{
			if((i+1)>=len)
			{
				//说明是最后一个
				a_arr_path.push('<li class="active">'+a_path[i]+'</li>');
			}else{
				a_arr_path.push('<li><a href="javascript:;" onclick="go_path(this,'+i+')">'+a_path[i]+'</a></li>');
			}
		}
		$('.breadcrumb').html(a_arr_path.join('\n'));
		show_hinit(json.success,json.text);
		$('.small_loading').fadeOut(300,function(){
			$("[data-toggle='tooltip']").tooltip({
					delay: {
						show: 500,
						hide: 100
					}
			});	
		});
		build_select();
	});
}
function go_path(obj,number)
{
	var temp=$(obj).parents('ol').find('li');
	var path='/';
	for(var i=1;i<=number; i++)
	{
		path=path+$(temp[i]).find('a').html()+'/';
	}
	refresh(path)
}
function show_upload() {
	var text=Upload_Form;
	var fun=function(){refresh('')};
    $.teninedialog({
        width: '450px',
        title: '<span class="glyphicon glyphicon-cloud-upload" style="top:1px;color:#5CB85C;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.UploadTitle + '</span>',
        content: '<div style="' + Language.Font + 'font-size:14px;">' + text + '</div>',
        showCloseButton: false,
        otherButtons: ['<span style="' + Language.Font + '">' + Language.Close + '</span>'],
        otherButtonStyles: ['btn-default'],
        bootstrapModalOption: { keyboard: true },
        clickButton: function (sender, modal, index) {
            if (fun) {
                fun();
            }
            $(this).closeDialog(modal);
        }
    });
    upload_build()
}
function upload_build()
{
    uploader = new plupload.Uploader({
    	runtimes : 'html5,flash,silverlight,html4',
    	browse_button : 'pick_files', // you can pass an id...
    	//container: document.getElementById('container'), // ... or DOM Element itself
    	url : 'include/bn_submit.switch.php?Ajax_FunName=UploadFiles&path='+encodeURIComponent($.cookie("Netdisk_Path")),
    	flash_swf_url : '../js/Moxie.swf',
    	silverlight_xap_url : '../js/Moxie.xap',
    	filters : {
    		max_file_size : '10000000mb',
    		mime_types: [
    			{title : "files type", extensions : "doc,docx,xls,xlsx,ppt,pptx,jpg,gif,png,bmp,psd,mp3,rar,zip,txt,php,exe,dll,pdf,fla,swf,rb,avi,air,ini,js,xml,css,ttf,csv"}
    		]
    	},
    	init: {
    		PostInit: function() {
    			document.getElementById('upload_list').innerHTML = '';

    			document.getElementById('upload_start').onclick = function() {
    				uploader.start();
    				return false;
    			};
    		},
    		FilesAdded: function(up, files) {
    			plupload.each(files, function(file) {
    				//document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
    				
    				document.getElementById('upload_list').innerHTML += '<div id="' + file.id + '" class="progress progress-striped"><div class="info"><div>' + file.name + '</div><div style="float:right;">'+ plupload.formatSize(file.size) +'&nbsp;&nbsp;&nbsp;&nbsp;<span class="percent">0</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="file_button"><span onclick="upload_file_cancel(\''+file.id+'\')" class="glyphicon glyphicon-remove cancel"></span></span></div></div><br/><div class="bar" style="width:0%;"></div></div>';
    				file.name=encodeURIComponent(file.name)
    			});
    		},
    		FileUploaded: function(up, file, info) {
    			                // Called when file has finished uploading
    			var info=JSON.parse(info.response)
    			if (info.success==0)
    			{
    				//上传失败
    				$('#'+file.id).addClass('progress-danger');
    				$('#'+file.id).removeClass('progress-success');
    				//$('#'+file.id+' .bar').hide();
    				$('#'+file.id+' .info span.percent').html(info.text);
    				$('#'+file.id+' .info span.file_button').html('')
    			}
    		},
    		UploadProgress: function(up, file) {
    			if(file.percent==100)
    			{
    				$('#'+file.id).addClass('progress-success');
    				$('#'+file.id+' .bar').css('width','100%');
    				$('#'+file.id+' .info span.percent').html( Language.UploadSuccess);
    				$('#'+file.id+' .info span.file_button').html('<span class="glyphicon glyphicon-ok success"></span>')
    			}else{
    				$('#'+file.id+' .bar').css('width',file.percent+'%');
    				$('#'+file.id+' .info span.percent').html(file.percent+'%');
    			}
    			//document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
    		},
    		Error: function(up, err) {
    			$('#'+file.id).addClass('progress-danger');
				$('#'+file.id+' .bar').css('width','100%');
				$('#'+file.id+' .info span').html(err.message);
    			//document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
    		}
    	}
    });
    uploader.init();
}
function upload_file_cancel(file)
{
	$('#'+file).remove();
	uploader.removeFile(file);
}
function format_filesize(size) {//根据数字，返回文件的大小，支持TB GB MB KB

	function round(num, precision) {
		return Math.round(num * Math.pow(10, precision)) / Math.pow(10, precision);
	}
	var boundary = Math.pow(1024, 4);
	// TB
	if (size > boundary) {
		return round(size / boundary, 1) + " " + plupload.translate('TB');
	}
	// GB
	if (size > (boundary/=1024)) {
		return round(size / boundary, 1) + " " + plupload.translate('GB');
	}
	// MB
	if (size > (boundary/=1024)) {
		return round(size / boundary, 1) + " " + plupload.translate('MB');
	}
	// KB
	if (size > 1024) {
		return Math.round(size / 1024) + " " + plupload.translate('KB');
	}
	return size + " " + plupload.translate('Byte');
}
function new_folder() {
    //先查找是否存在
    disable_select()
    var folder = $("#file_list").find('.newfolder')
    var id = 0;
    var number = '';
    if (folder.length > 0) {
        id = folder.length;
        number = '(' + folder.length + ')';
    }
    var a_arr = [];
    a_arr.push('<div id="newfolder_' + id + '" class="file newfolder" data-path="" data-type="folder" data-toggle="context" data-target="#file_menu" title="">');
    a_arr.push('<img id="small_photo" src="images/folder.png" alt="" />');
    if ($.cookie("Netdisk_List")=='list')
    {
		//如果布局是列表，那么显示单行文本输入框，并按回车后，激发重命名
    	a_arr.push('<div class="filename"><input type="text" style="width:200px;overflow:hidden;border: 1px solid #DDDDDD;height:22px;" onkeydown="var keycode=(event.keyCode?event.keyCode:event.which);if(keycode==\'13\'){this.onblur()}" onblur="create_folder(this,' + id + ')" value="' + Language.NewFolder + number + '"></input></div>');
    }else{
		//如果布局是图标，那么显示多行文本输入框，并按回车后，激发重命名
    	a_arr.push('<div class="filename"><textarea onkeydown="var keycode=(event.keyCode?event.keyCode:event.which);if(keycode==\'13\'){this.onblur()}" style="width:78px;height:34px;overflow:hidden;border: 1px solid #DDDDDD;text-align:center;" onblur="create_folder(this,' + id + ')">' + Language.NewFolder + number + '</textarea></div>');
    }
    a_arr.push('</div>');
    var html=''
    if ($.cookie("Netdisk_List")=='list')
    {
    	var title='<div class="list_title"><div class="name">'+Language.FileListTitleName+'</div><div class="type">'+Language.FileListTitleType+'</div><div class="size">'+Language.FileListTitleSize+'</div><div class="date">'+Language.FileListTitleDate+'</div></div>';
    	var list=$("#file_list").html()
    	list=list.replace(title,'')
    	$("#file_list").html(title+a_arr.join('\n') + list)
    }else{
    	$("#file_list").html(a_arr.join('\n') + $("#file_list").html())
    }    
    $('#newfolder_' + id + ' textarea').focus()
    $('#newfolder_' + id + ' textarea').select();
    $('#newfolder_' + id + ' input').focus()
    $('#newfolder_' + id + ' input').select();
}
function create_folder(obj,id) {
    var name = obj.value
    //过滤文件名去掉回车
    while(name.indexOf('\n')>-1)
    {
    	name=name.replace('\n',"")
    }

    var div = $('#newfolder_'+id)
    if (name == '') {
        //直接删除这个文件夹
        $(div).remove()
    }
    //设置属性
    $(div).attr('title', name)
    $('#newfolder_' + id + ' .filename').html(name)

    var path=$.cookie("Netdisk_Path")+name; 
	path=encodeURIComponent(path);

    var data='Ajax_FunName=CreateFolder';//后台方法
    data = data + '&path=' + path;
    setup_all_icon_event();
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
            //删除原来的文件夹
            $(div).remove()
            disable_select()
        } else {
            refresh('')
        }
    })
}
function download_file() {
    var item = $("#file_list").find('.onfocus')
    if (item.length > 1) {
        dialog_error(Language.DownloadFilesError001)
        return;
    }
	if($(item[0]).attr('data-type')=='folder')
	{
		dialog_message(Language.DownloadFilesError003)
        return;
	}
    if (item.length == 0) {
        dialog_message(Language.DownloadFilesError002)
        return;
    }
    var path=$(item[0]).attr('data-path')
	path=encodeURIComponent(path);
    var data='Ajax_FunName=DownLoad';//后台方法
    data = data + '&path=' + path;
	window.open('include/bn_submit.switch.php?'+data,'_blank')
}
function share_file() {
    var item = $("#file_list").find('.onfocus')
    if (item.length > 1) {
        return;
    }
	if($(item[0]).attr('data-type')=='folder')
	{
        return;
	}
    if (item.length == 0) {
        return;
    }
    var path=$(item[0]).attr('data-path')
	path=encodeURIComponent(path);
    var data='Ajax_FunName=DownLoad';//后台方法
    data = data + '&path=' + path;
    var url='http://'+window.location.host+':'+window.location.port+RootPath+'sub/netdisk/include/bn_submit.switch.php?'+data;
	var text=Upload_Form;
	var fun=function(){copy_to_clipboard(url);show_hinit(2,Language.CopyToClipSuccess);}; 
    $.teninedialog({
        width: '450px',
        title: '<span class="glyphicon glyphicon-info-sign" style="top:2px;color:#F0AD4E;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.ShareTitle + '</span>',
        content: '<div style="' + Language.Font + 'font-size:14px;">' + Language.ShareText + '<br/><div style="word-break:break-all;font-size:12px;color:#3498DB;text-decoration:underline;margin-left:20px;">' + url + '</div></div>',
        showCloseButton: false,
        otherButtons: ['<span style="' + Language.Font + '">' + Language.CopyToClip + '</span>'],
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
function copy_to_clipboard(txt) {
    if (window.clipboardData) {
        window.clipboardData.clearData();
        window.clipboardData.setData("Text", txt);
    } else if (navigator.userAgent.indexOf("Opera") != -1) {
        window.location = txt;
    } else if (window.netscape) {
        try {
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
        } catch (e) {
            //alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
            return;
        }
        var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip) return;
        var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if (!trans) return;
        trans.addDataFlavor('text/unicode');
        var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext = txt;
        str.data = copytext;
        trans.setTransferData("text/unicode", str, copytext.length * 2);
        var clipid = Components.interfaces.nsIClipboard;
        if (!clip) return false;
        clip.setData(trans, null, clipid.kGlobalClipboard);
        //alert("已经成功复制到剪帖板上！");
    }

}
function copy_files(type) {
    var item = $("#file_list").find('.onfocus')
    $("#file_list img").css("opacity",1);
    //显示已经成功进入剪切板
    show_hinit(2,Language.CopySuccess);
    var s_item='';
    for (var i = 0; i < item.length; i++) {
        if ((i + 1) >= item.length) {
            s_item = s_item + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '"'
        } else {
            s_item = s_item + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '",'
        }
        if (type=='cut')
        {
        	//如果是剪切，需要半透明效果
        	var temp=$(item[i]).find('img')
            $(temp).css("opacity",0.4);
        }        
    }
    s_item = '[' + s_item + ']';
    $.cookie("Netdisk_Paste",s_item)
    $.cookie("Netdisk_Paste_Type",type)
}
function paste_files() {
    var s_item = $.cookie("Netdisk_Paste");
    var type=$.cookie("Netdisk_Paste_Type")
    var data = 'Ajax_FunName=PasteFiles'; //后台方法
    data = data + '&item=' + s_item+'&type='+type+'&path='+encodeURIComponent($.cookie("Netdisk_Path"));
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
        }else{
        	$.cookie("Netdisk_Paste",'')
        	$.cookie("Netdisk_Paste_Type",'')
        	refresh('')
        	
        }  
    })
    //window.alert(JSON.parse(a_arr)) 

}
function rename_files()
{
	var item = $("#file_list").find('.onfocus')
	if (item.length > 1) {
        return;
    }
	disable_select()
	var filename=$(item[0]).find('.filename')
	var name=$(filename[0]).html()

	if ($.cookie("Netdisk_List")=='list')
	{
		$(filename[0]).html('<input type="text" style="width:200px;overflow:hidden;border: 1px solid #DDDDDD;height:22px;" onkeydown="var keycode=(event.keyCode?event.keyCode:event.which);if(keycode==\'13\'){this.onblur()}" onblur="rename_files_blur(this,\'' + name + '\')" value="' + name + '"></input>');
		var textarea=$(filename[0]).find('input')
	}else{
		$(filename[0]).html('<textarea style="width:78px;height:34px;overflow:hidden;border: 1px solid #DDDDDD;text-align:center;" onkeydown="var keycode=(event.keyCode?event.keyCode:event.which);if(keycode==\'13\'){this.onblur()}" onblur="rename_files_blur(this,\''+name+'\')">' + name + '</textarea>')
		var textarea=$(filename[0]).find('textarea')
	}
	$(textarea).focus()
    $(textarea).select();
	//window.alert($(filename[0]).html())
}
function rename_files_blur(obj,old_name) {
    var name = obj.value
    //过滤文件名去掉回车
    while(name.indexOf('\n')>-1)
    {
    	name=name.replace('\n',"")
    }
    var div_name =obj.parentNode
    var div =obj.parentNode.parentNode

    if (name == '') {
        //直接删除这个文件夹
    	$(div_name).html(old_name)
        return
    }
    //设置属性
    $(div).attr('title', name)
    $(div_name).html(name)
    
    
    var path=$.cookie("Netdisk_Path")+name; 
	path=encodeURIComponent(path);
	
	var old_path=$.cookie("Netdisk_Path")+old_name
	old_path=encodeURIComponent(old_path);
	
    var data='Ajax_FunName=RenameFiles';//后台方法
    data = data + '&path=' + path+ '&old_path=' + old_path;
    setup_all_icon_event();
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
            //删除原来的文件夹
            $(div_name).html(old_name)
            $(div).attr('title', old_name)
            disable_select()
        } else {
            refresh('')
        }
    })
}
function zip_files()
{
	var item = $("#file_list").find('.onfocus')
	loading_show()
	var path = '';
    for (var i = 0; i < item.length; i++) {
        if ((i + 1) >= item.length) {
        	path = path + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '"'
        } else {
        	path = path + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '",'
        }
    }
    path = '[' + path + ']'
    var data='Ajax_FunName=Zip';//后台方法
    data = data + '&path=' + path;
    //window.open('include/bn_submit.switch.php?'+data,'_blank')
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
            loading_hide()
        } else {
        	loading_hide()
            refresh('')
        }
    }) 
}
function unzip_files()
{
	//window.alert()
	var item = $("#file_list").find('.onfocus')
	loading_show()
	var path = '';
	path =encodeURIComponent($(item[0]).attr('data-path'))
    var data='Ajax_FunName=Unzip';//后台方法
    data = data + '&path=' + path;
    //window.open('include/bn_submit.switch.php?'+data,'_blank')
    $.getJSON("include/bn_submit.switch.php", data, function (json) {
		//window.alert(json.t)
        if (json.success == 0) {
            show_hinit(json.success, json.text);
            loading_hide()
        } else {
        	loading_hide()
            refresh('')
        }
    }) 
}
function property_files()
{
	var item = $("#file_list").find('.onfocus')
	loading_show()
	var path = '';
    for (var i = 0; i < item.length; i++) {
        if ((i + 1) >= item.length) {
        	path = path + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '"'
        } else {
        	path = path + '"' + encodeURIComponent($(item[i]).attr('data-path')) + '",'
        }
    }
    path = '[' + path + ']'
	var data='Ajax_FunName=PropertyFiles';//后台方法
	data = data + '&path=' + path;
	//window.open('include/bn_submit.switch.php?'+data,'_blank')
	$.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
            loading_hide()
        } else {
        	loading_hide()
            show_property(json)
        }
    }) 
}
function property_folder()
{
	var item = $("#file_list").find('.onfocus')
	loading_show()
	var path='["' + encodeURIComponent($.cookie("Netdisk_Path")) + '"]'; 
	var data='Ajax_FunName=PropertyFiles';//后台方法
	data = data + '&path=' + path;
	$.getJSON("include/bn_submit.switch.php", data, function (json) {
        if (json.success == 0) {
            show_hinit(json.success, json.text);
            loading_hide()
        } else {
        	loading_hide()
            show_property(json)
        }
    }) 
}
function format_date(nS) {     
   return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");      
}  
function show_property(data) {//显示文件属性
	var info=data.data
	//构建显示内容
	var a_arr=[];
	if(data.type=='single')
	{
		//单个文件
		a_arr.push('<div style="padding:10px 30px 10px 30px;padding-top:0px;overflow:hidden;"><div style="float:left;width:15%;"><img style="width:32px;height:32px;" src="'+RootPath+'sub/netdisk/images/'+data.filetype+'.png"/></div><div style="float:left;width:85%;padding-top:8px;font-size:14px;">'+info.name+'</div></div>');//文件名和图标
		a_arr.push('<div style="overflow:hidden;border-bottom: 1px solid #DDDDDD;"></div>');//分割线
		if (data.filetype=='folder')
		{
			a_arr.push('<div style="padding:10px 30px 10px 30px;overflow:hidden;"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyType+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+Language.FileTypeFolder+'</div></div>');
		}else{
			a_arr.push('<div style="padding:10px 30px 10px 30px;overflow:hidden;"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyType+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+info.ext.toUpperCase()+' '+Language.FileType+'</div></div>');
		}
		a_arr.push('<div style="padding:10px 30px 10px 30px;overflow:hidden;padding-top:0px;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyPosition+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+info.path+'</div></div>');
		if (data.filetype=='folder')
		{
			//如果是文件夹，需要显示文件夹内的文件数	
			a_arr.push('<div style="padding:10px 30px 10px 30px;padding-top:0px;overflow:hidden;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.FileListTitleSize+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+info.size_friendly+' ('+info.size+'Byte)</div></div>');
			a_arr.push('<div style="padding:10px 30px 10px 30px;padding-top:0px;overflow:hidden;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyInclude+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+info.file_num+' '+Language.FileType+','+info.folder_num+' '+Language.FileTypeFolder+'</div></div>');
		}else{
			a_arr.push('<div style="padding:10px 30px 10px 30px;padding-top:0px;overflow:hidden;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.FileListTitleSize+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+info.size_friendly+' ('+info.size+'Byte)</div></div>');
		}
		a_arr.push('<div style="overflow:hidden;border-bottom: 1px solid #DDDDDD;"></div>');//分割线
		a_arr.push('<div style="padding:10px 30px 10px 30px;overflow:hidden;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyCreateTime+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+format_date(info.ctime)+'</div></div>');
		a_arr.push('<div style="padding:10px 30px 10px 30px;overflow:hidden;padding-top:0px;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyModifyTime+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+format_date(info.mtime)+'</div></div>');
		a_arr.push('<div style="padding:10px 30px 0px 30px;overflow:hidden;padding-top:0px;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.PropertyReadTime+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+format_date(info.atime)+'</div></div>');
	}else{
		//多文件
		a_arr.push('<div style="padding:10px 30px 10px 30px;padding-top:0px;overflow:hidden;word-break:break-all"><div style="float:left;width:25%;font-weight:bold"><img style="width:32px;height:32px;" src="'+RootPath+'sub/netdisk/images/'+data.filetype+'.png"/></div><div style="float:left;width:75%;padding-top:10px;vertical-align:middle;font-size:12px;color:#777777">'+info.file_num+' '+Language.FileType+','+info.folder_num+' '+Language.FileTypeFolder+'</div></div>');
		a_arr.push('<div style="overflow:hidden;border-bottom: 1px solid #DDDDDD;"></div>');//分割线
		a_arr.push('<div style="padding:10px 30px 0px 30px;overflow:hidden;word-break:break-all"><div style="float:left;width:25%;font-weight:bold">'+Language.FileListTitleSize+':</div><div style="float:left;width:75%;vertical-align:middle;font-size:12px;color:#777777">'+info.size_friendly+' ('+info.size+'Byte)</div></div>');
	}
	var text=a_arr.join('\n');
    $.teninedialog({
        title: '<span class="glyphicon glyphicon-info-sign" style="top:3px;color:#3498DB;"></span>&nbsp;&nbsp;<span style="' + Language.Font + '">' + Language.PropertyTitle + '</span>',
        content: '<div style="' + Language.Font + 'font-size:14px;">' + text + '</div>',
        showCloseButton: false,
        otherButtons: ['<span style="' + Language.Font + '">' + Language.Confirm + '</span>'],
        otherButtonStyles: ['btn-default'],
        bootstrapModalOption: { keyboard: true },
        clickButton: function (sender, modal, index) {
            $(this).closeDialog(modal);
        }
    });
}