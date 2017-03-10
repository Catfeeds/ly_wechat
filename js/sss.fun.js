function change_nav(obj) {
    $('.sss_nav_menu a').removeClass('active')
    $(obj).addClass('active');
}
function change_sub_nav(obj) {
    $('.sss_main_sub_nav li').removeClass('active')
    $(obj).addClass('active');
}
function go_url(url)
{
	location=RootPath+url;
}
var NavIcon=0
try {
	$(window).resize(function(){
		resize_nav();
		show_sub_table_title();
	});
	$(function(){
		resize_nav();
		if (NavIcon == 1) {
			$(".sss_nav").css('margin-left', '-220px');
			$(".sss_main_box").css('margin-left', '30px');
		}
		get_sys_msg_num();
		$("[data-toggle='tooltip']").tooltip({
			delay: {
				show: 500,
				hide: 100
			}
		});
		$(".dropdown").on('shown.bs.dropdown', function(){
			$(this).find('.dropdown-menu').hide()
			$(this).find('.dropdown-menu').stop(true, true).delay(0).fadeIn(300)
		})
		
		$('.dropdown').on('hidden.bs.dropdown', function(){
			$(this).find('.dropdown-menu').stop(true, true).delay(0).fadeOut(300)
		})
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
		
		$(window).scroll(function () {
        //$(window).scrollTop()这个方法是当前滚动条滚动的距离
        //$(window).height()获取当前窗体的高度
        //$(document).height()获取当前文档的高度
	        if($(window).scrollTop()>200)
			{
				//显示置顶按钮
				$('.sss_gotop').fadeIn(300)
			}else{
				//隐藏置顶按钮
				$('.sss_gotop').fadeOut(300)
			}
    	});
		$('.sss_gotop').click(function () {
			$("body,html").animate({scrollTop:0}, 500);
		});	
	});
	verify_browser()
}catch(e){}

function verify_browser()
{
    var userAgent = navigator.userAgent.toLowerCase();
    jQuery.browser = {
            version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
            safari: /webkit/.test(userAgent),
            opera: /opera/.test(userAgent),
            msie: /msie/.test(userAgent) && !/opera/.test(userAgent),
            mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
        }; 
    var bro=$.browser;
    var jump=true;
    if(bro.mozilla || bro.mozilla || bro.safari || bro.opera) 
    {
        jump=false
    }
    if (bro.msie && parseInt(bro.version)>8)
    {
        jump=false
    }
    if (jump)
    {
        location=RootPath+"browser_error.html"
    }
}
function show_nav() {
    if (parseInt($('.sss_nav').css('marginLeft')) < 0) {
        $(".sss_nav").animate({ marginLeft: '0px' },'fast')
        $(".sss_main_box").animate({ marginLeft: '0px' },'fast')
    } else {
        $(".sss_nav").animate({ marginLeft: '-250px' },'fast')
        $(".sss_main_box").animate({ marginLeft: '0px' },'fast')
    }
	$(".sss_nav .title").show();
	$(".sss_nav .glyphicon").css('float', 'inherit');
	$(".sss_nav .list-group-item").css('padding-right', '15px');
	//删除文字提示
	var item=$(".sss_nav_menu").children('.list-group-item')
	for(var i=0;i<item.length;i++)
	{
		$(item[i]).removeAttr('title')
	}
}
function show_nav_wide() {
    if (parseInt($('.sss_nav').css('marginLeft')) < 0) {
		//还原按钮文字显示
		$(".sss_nav .title").show();
		$(".sss_nav .glyphicon").css('float', 'inherit');
		$(".sss_nav .list-group-item").css('padding-right', '15px');
		//保存用户设置
		NavIcon=0;
		$.getJSON(RootPath+"include/bn_submit.switch.php",'Ajax_FunName=SetShowNavIcon&val=0',function (json){})
		//删除文字提示
		var item=$(".sss_nav_menu").children('.list-group-item')
		for(var i=0;i<item.length;i++)
		{
			$(item[i]).removeAttr('title')
		}
        $(".sss_nav").animate({ marginLeft: '0px' },'fast')
        $(".sss_main_box").animate({ marginLeft: '250px' },'fast',function(){
			//加入修正表格标题浮动代码
			show_sub_table_title();
		}) 
    } else {
		//保存用户设置
		NavIcon=1;
		$.getJSON(RootPath+"include/bn_submit.switch.php",'Ajax_FunName=SetShowNavIcon&val=1',function (json){})
        $(".sss_nav").animate({ marginLeft: '-220px' },'fast')
        $(".sss_main_box").animate({ marginLeft: '30px' },'fast',function(){
			//导航变图标
			$(".sss_nav .title").hide();
			$(".sss_nav .glyphicon").css('float', 'right');
			$(".sss_nav .list-group-item").css('padding-right', '10px');
			//添加文字提示
			var item=$(".sss_nav_menu").children('.list-group-item')
			for(var i=0;i<item.length;i++)
			{
				var title=$(item[i]).find('.title')
				title=$(title[0]).html();
				title=title.replace('&nbsp;','')
				title=title.replace('&nbsp;','')
				title=title.replace('&nbsp;','')
				$(item[i]).attr('title',title)
			}
			//加入修正表格标题浮动代码
			show_sub_table_title();
		})
    }
}
function resize_nav()
{
	var height=$(window).height()-45
	$('.sss_nav').height(height)
	height=$(window).height()-100
	$('.sss_main').css('min-height',height+'px')
	$('.sss_footer').css('display','block')
	if ($(window).width()<767)
	{
		$(".sss_nav").css('margin-left', '-250px');
		$(".sss_main_box").css('margin-left', '0px');
	}else{
		if (NavIcon == 1) {
			$(".sss_nav").css('margin-left', '-220px');
			$(".sss_main_box").css('margin-left', '30px');
			$(".sss_nav .title").hide();
			$(".sss_nav .glyphicon").css('float', 'right');
			$(".sss_nav .list-group-item").css('padding-right', '10px');
			//添加文字提示
			var item=$(".sss_nav_menu").children('.list-group-item')
			for(var i=0;i<item.length;i++)
			{
				var title=$(item[i]).find('.title')
				title=$(title[0]).html();
				title=title.replace('&nbsp;','')
				title=title.replace('&nbsp;','')
				title=title.replace('&nbsp;','')
				$(item[i]).attr('title',title)
			}
		}else{
			$(".sss_nav").css('margin-left', '0px');
			$(".sss_main_box").css('margin-left', '250px');
		}
	}
}
function logout()
{
	if ($(window).width()<767)
	{
		//关闭导航
		$(".sss_nav").animate({ marginLeft: '-250px' },'fast')
        $(".sss_main_box").animate({ marginLeft: '0px' },'fast')
	}
	dialog_confirm(Language['LogoutMessage'],function(){goto_login()})
}
function goto_login()
{
    try
    {
        parent.parent.window.open(RootPath+'index.php','_parent')
        return
    }
    catch(e)
    {
    }
    try
    {
        parent.window.open(RootPath+'index.php','_parent')
        return
    }
    catch(e)
    {
    }
    try
    {
        window.open(RootPath+'index.php','_parent')
        return
    }
    catch(e)
    {
    }
}
function clear_cookie(fun)
{
	$.cookie(fun+"Page",'');
	$.cookie(fun+"Sort",''); 
	$.cookie(fun+"Item",''); 
	var key=$.cookie(fun+"Key"); 
	$.cookie(fun+"Key",'');
	$.cookie(fun+key+"OtherKey",'');
	
}
function table_sort(fun,item,sort,page,key,other_key) {
	$('.small_loading').fadeIn(100);
	$.cookie(fun+"Page",page);
	$.cookie(fun+"Sort",sort); 
	$.cookie(fun+"Item",item); 
	$.cookie(fun+"Key",key);
	$.cookie(fun+key+"OtherKey",other_key)
	table_load(fun,item,sort,page,encodeURIComponent(key),encodeURIComponent(other_key));
}
function table_refresh(fun) {
	$('.small_loading').fadeIn(100);
	var page=$.cookie(fun+"Page");
	var sort=$.cookie(fun+"Sort"); 
	var item=$.cookie(fun+"Item"); 
	var key=$.cookie(fun+"Key"); 
	var other_key=$.cookie(fun+key+"OtherKey"); 
	table_load(fun,item,sort,page,encodeURIComponent(key),encodeURIComponent(other_key));
}
function table_load(fun,item,sort,page,key,other_key)
{
    var data='Ajax_FunName='+fun;//后台方法
    data=data+'&item='+item+'&sort='+sort+'&page='+page+'&key='+key+'&other_key='+other_key;
	$.getJSON("include/bn_submit.switch.php",data,function (json){
		var a_arr=[];
		var a_title=json.title;
		//构建标题
		for(var i=0;i<a_title.length;i++)
		{
			var a_temp=a_title[i];
			var style=' style="';
			if(a_temp.minwidth>0)
			{
				style=style+'min-width:'+a_temp.minwidth+'px';
			}
			if(a_temp.width>0)
			{
				style=style+'width:'+a_temp.width+'px';
			}
			style=style+'"';
			a_arr.push('<th'+style+'>');
			if (a_temp.item=='')
			{
				a_arr.push(a_temp.title);
			}else{
				var sort='A';
				var s_sign='';
				if (json.item==a_temp.item)
				{
					//排序了
					sort=json.sort
					if (sort=='A')
					{
						sort='D';
						s_sign=' <span class="glyphicon glyphicon-triangle-top"></span>';
					}else{
						sort='A';
						s_sign=' <span class="glyphicon glyphicon-triangle-bottom"></span>';
					}
				}		
				a_arr.push('<span class="sort_title" onclick="table_sort(\''+json.funname+'\',\''+a_temp.item+'\',\''+sort+'\',1,\''+key+'\',\''+other_key+'\')">'+a_temp.title+'</span>'+s_sign);
			}
			a_arr.push('</th>');
		}
		$('.table thead tr').html(a_arr.join('\n'));
		//构建内容
		a_arr=[];
		var a_row=json.rows;
		for (var i = 0; i < a_row.length; i++) {
			var single=a_row[i];
			a_arr.push('<tr>');
			for (var j = 0; j < single.length; j++) {
				var a_button=single[j];
				if(json.havebutton=='yes' && a_button.length>0 && (j+1>=single.length))
				{
					//说明是最后一个，并且总信息标注最后一个是按钮，那么以按钮的方式显示
					var a_button=single[j];
					a_arr.push('<td>');
					a_arr.push('<div class="dropdown" id="test">');
					a_arr.push('<button class="btn btn-default but-operate" aria-label="Left Align" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" aria-hidden="true" style="outline: medium none">');
					a_arr.push('<span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>');
					a_arr.push('</button>');
					a_arr.push('<ul class="dropdown-menu">');
					for(var k=0;k<a_button.length;k++)
					{
						var s_btn_info=a_button[k];
						a_arr.push('<li><a href="javascript:;" aria-hidden="true" style="outline: medium none" onclick="'+s_btn_info[1]+'">'+s_btn_info[0]+'</a></li>');
					}
					a_arr.push('</ul>');
					a_arr.push('</div>');
					a_arr.push('</td>');
				}else{
					a_arr.push('<td>'+single[j]+'</td>');
				}				
			}
			a_arr.push('</tr>');
		}
		$('.table tbody').html(a_arr.join('\n'));
		//构建翻页按钮和说明
		a_arr=[];
		//计算总页数
		var page=parseInt(json.current)
		var pagesize=parseInt(json.pagesize)
		var pagesum=Math.ceil(parseInt(json.total)/parseInt(json.pagesize))
		var show=8;
		a_arr.push('<div class="explain">'+json.total+' '+Language['TableTotal']+'&nbsp;&nbsp;&nbsp;&nbsp;'+Language['Total']+' '+pagesum+' '+Language['Page']+' </div>');
		if(pagesum>1)
		{
			a_arr.push('<ul class="pagination">');
			if (page>1){
				a_arr.push('<li><a href="javascript:;" data-toggle="tooltip" title="'+Language['Prev']+'" aria-label="Previous" onclick="table_sort(\''+json.funname+'\',\''+json.item+'\',\''+json.sort+'\',\''+(page-1)+'\',\''+key+'\',\''+other_key+'\')"><span aria-hidden="true">&laquo;</span></a></li>');
			}
			//取余数
			var yu=Math.floor((page-1)/show);
			for(var i=1; i<=show;i++){
				var number=yu*show+i
				if(number<=pagesum){
					var active='';
					if(number==page)
					{
						active='on';
					}
					a_arr.push('<li><a href="javascript:;" onclick="table_sort(\''+json.funname+'\',\''+json.item+'\',\''+json.sort+'\',\''+number+'\',\''+key+'\',\''+other_key+'\')" class="'+active+'">'+number+'</a></li>');
				}
			}
			if (page < pagesum) {
				a_arr.push('<li><a href="javascript:;" data-toggle="tooltip" title="'+Language['Next']+'" aria-label="Next" onclick="table_sort(\''+json.funname+'\',\''+json.item+'\',\''+json.sort+'\',\''+(page+1)+'\',\''+key+'\',\''+other_key+'\')"><span aria-hidden="true">&raquo;</span></a></li>');
			}
			a_arr.push('</ul>');
		}
		$('.sss_page').html(a_arr.join('\n'));
		loading_hide()
		$('.small_loading').fadeOut(100);
		//激活按钮提示
		$("[data-toggle='tooltip']").tooltip({
			delay: {
				show: 500,
				hide: 100
			}
		});
		$(".sss_main").css('padding-bottom', '0px');
		$("body,html").animate({scrollTop:0}, 500);
		//window.alert(json.pagesize);
    })	
}
function get_sys_msg_num()
{
    var data='Ajax_FunName=GetSysMsgNum';//后台方法
    $.getJSON(RootPath+"include/bn_submit.switch.php",data,function (json){
    	if (json.number==0)
    	{
    		$('#mail_number').hide();
    	}
    	$('#mail_number').html(json.number);
    })  	
}
function show_sub_table_title()
{
	$('.sss_main .sss_main_sub .sss_main_sub_top .tablehead').hide();
	if ($(document).scrollTop()>210)
	{
		if ($('.sss_sub_table .table thead').html()==undefined)
		{
			return;
		}	
		$('.sss_main .sss_main_sub .sss_main_sub_top .tablehead').html('<table class="table table-striped"><thead>'+$('.sss_sub_table .table thead').html()+'</thead></table>');
		var count=$('.sss_sub_table .table thead tr').children().length;
		for(var i=0;i<count;i++)
		{
			var width=$('.sss_sub_table .table thead tr th:nth-child('+i+')').width();
			$('.sss_main .sss_main_sub .sss_main_sub_top .tablehead table thead tr th:nth-child('+i+')').width(width)
		}
		$('.sss_main .sss_main_sub .sss_main_sub_top .tablehead').show();
	}else{
		$('.sss_main .sss_main_sub .sss_main_sub_top .tablehead').hide();
		$('.sss_main .sss_main_sub .sss_main_sub_top .tablehead').html('');
	}
}
$(document).scroll(function(){
	show_sub_table_title();
})