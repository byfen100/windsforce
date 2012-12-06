/**
 * 获取DoYouHaoBaby Framework通信接口 Url接口
 *
 * @param string sClassName DoYouHaoBaoBaby Framework 通信接口类
 * @param string sMethodName DoYouHaoBaoBaby Framework 通信接口方法
 * @param array arrParams 请求数组参数
 */
Dyhb.Soap.Ssmi.GetRequestURL=function(sClassName,sMethodName,arrParams){
	/* 参数整理 */
	if(typeof(arrParams)=='undefined'){
		arrParams=[];
	}

	/* 参数必须是数组，否则抛出异常 */
	if(!(arrParams instanceof Array)){
		throw new Error('arrParams must be a Array!');
	};

	/** API
	 * 请在模板中预先调用框架中的PHP代码 App::U()初始化相关参数
	 */
	sUrl=Dyhb.Soap.Ssmi._sAppPath+'?'+_APP_VAR_NAME_+'='+_APP_NAME_+'&c=ssmi&a=index&class='+sClassName+'&method='+sMethodName;
	
	/* 参数 */
	for(nI=0;nI<arrParams.length;nI++){
		sUrl+='&param_soap'+(nI+1)+'='+arrParams[nI];
	};

	return sUrl;
};
