/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Shop应用基础Javascript($)*/

/** 登录回调 */
function login_handle(data,status){
	if(status==1){
		sUrl=D.U('shop://public/index');
		setTimeout("window.location=sUrl;",1000);
	}
}
