/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Home应用基础Javascript($)*/

/** 登录回调 */
function login_handle(data,status){
	if(status==1){
		sUrl=data.url;
		setTimeout("window.location=sUrl;",1000);
	}
}
