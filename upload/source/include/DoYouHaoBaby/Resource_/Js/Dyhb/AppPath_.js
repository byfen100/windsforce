/**
 * 框架入口路径
 * 需要在框架Js库使用前定义
 */
if(typeof(__DYHB_JS_ENTER__)!='undefined' && __DYHB_JS_ENTER__!=''){
	Dyhb.AppPath=__DYHB_JS_ENTER__;
}else{
	Dyhb.AppPath='/';
}
