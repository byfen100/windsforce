/**
 * 表单提交ajax
 *
 * @param string form 表单ID
 * @param string url Ajax请求url
 * @param string target 返回的消息的div id值
 * @param function response 回调函数
 * @param string sTips Ajax加载消息
 */
Dyhb.Ajax.Dyhb.AjaxSubmit=function(sForm,sUrl,sTarget,Response,sTips){
	/* 请求URL */
	sUrl=(sUrl===undefined || sUrl=='' || sUrl===null)?Dyhb.Ajax.Dyhb.Options['url']:sUrl;

	/* 消息DIV id */
	if(sTarget===undefined || sTarget=='' || sTarget===null){
		Dyhb.Ajax.Dyhb.TipTarget=(Dyhb.Ajax.Dyhb.Options['target'])?Dyhb.Ajax.Dyhb.Options['target']:Dyhb.Ajax.Dyhb.TipTarget;
	}else{
		Dyhb.Ajax.Dyhb.TipTarget=sTarget;
	}

	/* 成功后回调函数 */
	Dyhb.Ajax.Dyhb.Response=(Response===undefined || Response==''||Response===null)?Dyhb.Ajax.Dyhb.Response:Response;
	if(sTips===undefined||sTips==''||sTips===null){
		sTips=(Dyhb.Ajax.Dyhb.Options['tips'])?Dyhb.Ajax.Dyhb.Options['tips']:Dyhb.Ajax.Dyhb.UpdateTips;
	}

	var oSubmitFrom=document.getElementById(sForm);
	oSubmitFrom.action=sUrl;
	arrAjaxOption={
		async:true,
		onsuccess:function(xhr,responseText){
			Dyhb.Ajax.Dyhb.AjaxResponse(xhr,Dyhb.Ajax.Dyhb.TipTarget,Response,sTips);
		},
		onfailure:function(xhr){
			alert('Request Error!');
		}
	};

	/* 提交Ajax */
	Dyhb.Ajax.Form(oSubmitFrom,arrAjaxOption);
};

Dyhb.AjaxSubmit=Dyhb.Ajax.Dyhb.AjaxSubmit;
