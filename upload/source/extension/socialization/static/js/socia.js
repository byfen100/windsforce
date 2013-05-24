/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   社会化帐号登录跳转($Liu.XiangMin)*/

function sociaWinopen(sUrl){
	/** 使用COOKIE存储登陆记住时间 */
	if($("#remember_me").attr("checked")=='checked'){
		var nTime=$("#remembertime").val();
		
		Dyhb.AjaxSend(D.U('home://misc/socia_login'),'ajax=1&time='+nTime,'',function(data,status){
			if(status==1){
				setTimeout(function(){window.location.href=sUrl;},2000);
			}
		});
	}else{
		window.location.href=sUrl;
	}
}
