<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" charset="utf-8"></script>
<script>
	wx.config({
	    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	    appId: 'wx9b3a7ba12b95d87e', // 必填，公众号的唯一标识
	    timestamp: <?php echo($timestamp)?>, // 必填，生成签名的时间戳
	    nonceStr: '<?php echo($nonceStr)?>', // 必填，生成签名的随机串
	    signature: '<?php echo($signature);?>',// 必填，签名，见附录1
	    jsApiList: ['onMenuShareAppMessage','wx.checkJsApi'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
	wx.ready(function(){
		wx.checkJsApi({
		    jsApiList: ['chooseImage'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
		    success: function(res) {
			    //window.alert()
		        // 以键值对的形式返回，可用的api值true，不可用为false
		        // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
		    }
		});
		wx.onMenuShareAppMessage({
		    title: '<?php echo($s_title);?>', // 分享标题
		    desc: '<?php echo($s_desc)?>', // 分享描述
		    link: '<?php echo($s_link)?>', // 分享链接
		    imgUrl: '<?php echo($s_imgUrl)?>', // 分享图标
		    type: '', // 分享类型,music、video或link，不填默认为link
		    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () { 
		        // 用户确认分享后执行的回调函数
		        //window.alert('ok');
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    	//window.alert('no');
		    }
		});
	});
</script>