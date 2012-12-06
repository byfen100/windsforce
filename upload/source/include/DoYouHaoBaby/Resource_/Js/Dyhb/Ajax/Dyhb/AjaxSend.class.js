/**
 * 表单提交ajax
 *
 * @param string url Ajax请求url
 * @param string Params Ajax请求剩余参数
 * @param string target 返回的消息的div id值
 * @param function response 回调函数 
 * @param string sTips  Ajax加载消息
 * @param string sType Ajax发送类型[get/post,默认为get]
 */
Dyhb.Ajax.Dyhb.AjaxSend=function(sUrl,Params,sTarget,Response,sTips,sType){
	/* 请求URL */
	sUrl=(sUrl===undefined || sUrl==''||sUrl===null)?Dyhb.Ajax.Dyhb.Options['url']:sUrl;

	/* 消息DIV id */
	if(sTarget===undefined || sTarget =='' || sTarget===null){
		Dyhb.Ajax.Dyhb.TipTarget=(Dyhb.Ajax.Dyhb.Options['target'])?Dyhb.Ajax.Dyhb.Options['target']:Dyhb.Ajax.Dyhb.TipTarget;
	}else{
		Dyhb.Ajax.Dyhb.TipTarget=sTarget;
	}

	/* 成功后回调函数 */
	Dyhb.Ajax.Dyhb.Response=(Response===undefined||Response==''||Response===null)?Dyhb.Ajax.Dyhb.Response:Response;
	if(sTips===undefined || sTips=='' || sTips===null){
		sTips=(Dyhb.Ajax.Dyhb.Options['tips'])?Dyhb.Ajax.Dyhb.Options['tips']:Dyhb.Ajax.Dyhb.UpdateTips;
	}

	if(Params===undefined || Params=='' || Params===null){
		Params=(Dyhb.Ajax.Dyhb.Options['var'])?Dyhb.Ajax.Dyhb.Options['var']:'ajax=1';
	}

	if(sType=='post'){
		Dyhb.Ajax.Post(sUrl,
			Params,
			function(xhr,responseText){
				Dyhb.Ajax.Dyhb.AjaxResponse(xhr,Dyhb.Ajax.Dyhb.TipTarget,Response,sTips);
			}
		);
 	}else{
		Dyhb.Ajax.Get(sUrl,
			Params,
			function(xhr,responseText){
				Dyhb.Ajax.Dyhb.AjaxResponse(xhr,Dyhb.Ajax.Dyhb.TipTarget,Response,sTips);
			}
		);
	}
};

Dyhb.AjaxSend=Dyhb.Ajax.Dyhb.AjaxSend;
