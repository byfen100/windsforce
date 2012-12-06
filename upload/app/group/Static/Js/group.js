/* [NeedForBug!] (C)Dianniu From 2010.
   Group应用基础Javascript($)*/

/** 登录回调 */
function login_handle(data,status){
	if(status==1){
		sUrl=D.U('group://public/index');
		setTimeout("window.location=sUrl;",1000);
	}
}
