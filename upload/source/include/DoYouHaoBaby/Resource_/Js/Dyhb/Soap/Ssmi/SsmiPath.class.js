/**
 * 框架语言包入口请求
 */
Dyhb.Soap.Ssmi._sAppPath=Dyhb.AppPath;

/**
 * DoYouHaoBaby Framework入口文件
 *
 * @param string sAppPath 框架入口文件
 * @returns 文本结果
 */
Dyhb.Soap.Ssmi.AppPath=function(sAppPath){
	if(typeof(sAppPath)!='undefined' && sAppPath!=''){
		Dyhb.Soap.Ssmi._sAppPath=sAppPath;
	}
};
