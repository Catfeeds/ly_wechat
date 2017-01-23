/**
 * 开发版本的文件导入
 */
var RootPath='/event/'; 
(function (){
    var paths_js  = [
			'js/language_cn.js',
            'js/bootstrap/js/jquery-2.1.0.min.js',
			'js/bootstrap/js/jquery.mCustomScrollbar.concat.min.js',
            'js/bootstrap/js/jquery.bootstrap.teninedialog.js',
            'js/bootstrap/js/bootstrap-select.min.js',
            'js/bootstrap/js/bootstrap.js',
            'js/bootstrap/js/jquery.cookie.js',
			'js/bootstrap/js/icheck/icheck.min.js',
            'js/dialog.fun.js',
            'js/sss.fun.js'
            
        ],
    baseURL = RootPath;
    for (var i=0,pi;pi = paths_js[i++];) {
        document.write('<script type="text/javascript" src="'+ baseURL + pi +'"></script>');
    }
     var paths_css  = [
	 		'js/bootstrap/css/jquery.mCustomScrollbar.css',
            'js/bootstrap/css/bootstrap.min.css',
            'js/bootstrap/css/bootstrap-select.min.css',
            'js/bootstrap/js/icheck/skins/all.css',
            'css/public.css',
			'css/common.css'
        ],
    baseURL = RootPath;
    for (var i=0,pi;pi = paths_css[i++];) {
        document.write('<link rel="stylesheet" type="text/css" href="'+ baseURL + pi +'"/>');
    }
})();