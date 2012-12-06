/**
 * ajax消息显示
 *
 * @param oRequest xmlHttp对象
 * @param sTarget DIV ID
 * @param Response 请求函数
 * @param sTips 加载消息
 */
Dyhb.Ajax.Dyhb.AjaxResponse=function(oRequest,sTarget,Response,sTips){
	var sStr=oRequest.responseText;

	sStr=sStr.replace(/([\x00-\x1f\\"])/g, function(a, b){
		var c=m[b];
		if(c){
			return c;
		}else{
			return b;
		}
	});

	try{
		arrReturn=eval('('+sStr+')');
		if(Dyhb.Ajax.Dyhb.Debug){
			alert(sStr);
		}
	}catch(ex){
		if(Dyhb.Ajax.Dyhb.Debug){
			alert("The server returns data in non-JS:\n\n"+sStr);
		}
		alert('The server returns data error!'+sStr);
		return;
	};

	/* 服务器返回数据格式 */
	Dyhb.Ajax.Dyhb.Status=arrReturn.status;
	Dyhb.Ajax.Dyhb.Info=arrReturn.info;
	Dyhb.Ajax.Dyhb.Data=arrReturn.data;
	Dyhb.Ajax.Dyhb.Type=arrReturn.type;
	Dyhb.Ajax.Dyhb.Display=arrReturn.display;

	if(Dyhb.Ajax.Dyhb.Display && Dyhb.Ajax.Dyhb.ShowTip){
		Dyhb.Ajax.Dyhb.Loading(sTarget,sTips);
	}

	if(Dyhb.Ajax.Dyhb.Type=='EVAL'){
		eval(Dyhb.Ajax.Dyhb.Data);/* 直接执行返回的脚本 */
	}else{
		if(Response==undefined){/* 需要在客户端定义ajaxReturn方法 */
			try{(AjaxBack).apply(this,[Dyhb.Ajax.Dyhb.Data,Dyhb.Ajax.Dyhb.Status,Dyhb.Ajax.Dyhb.Info,Dyhb.Ajax.Dyhb.Type]);}
			catch(e){}
		}else{
			try{(Response).apply(this,[Dyhb.Ajax.Dyhb.Data,Dyhb.Ajax.Dyhb.Status,Dyhb.Ajax.Dyhb.Info,Dyhb.Ajax.Dyhb.Type]);}
			catch(e){}
		}
	}

	/* 显示提示信息 */
	if(Dyhb.Ajax.Dyhb.Display && Dyhb.Ajax.Dyhb.ShowTip && Dyhb.Ajax.Dyhb.Info!=undefined && Dyhb.Ajax.Dyhb.Info!=''){
		var sOldTarget=sTarget;
		sTarget=document.getElementById(sTarget);
		sTarget.style.display="block";

		if(Dyhb.Ajax.Dyhb.Status==1){
			if(''!=Dyhb.Ajax.Dyhb.Image[1]){
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:blue">'+Dyhb.Ajax.Dyhb.Info+'</span>','<img src="'+Dyhb.Ajax.Dyhb.Image[1]+'" class="'+sOldTarget+'Success" border="0" alt="success..." align="absmiddle">');
			}else{
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:blue">'+Dyhb.Ajax.Dyhb.Info+'</span>');
			}
		}else{
			if(''!=Dyhb.Ajax.Dyhb.Image[2]){
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:red">'+Dyhb.Ajax.Dyhb.Info+'</span>','<img src="'+Dyhb.Ajax.Dyhb.Image[2]+'" class="'+sOldTarget+'Error" border="0" alt="error..." align="absmiddle">');
			}else{
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:red">'+Dyhb.Ajax.Dyhb.Info+'</span>');
			}
		}
	}

	/* 提示信息停留 Dyhb.Ajax.Dyhb.Display 秒 */
	if(Dyhb.Ajax.Dyhb.Display && Dyhb.Ajax.Dyhb.ShowTip && Dyhb.Ajax.Dyhb.Info!=undefined && Dyhb.Ajax.Dyhb.Info!=''){
		setTimeout(function(){sTarget.style.display="none";},Dyhb.Ajax.Dyhb.Display*1000);
	}
};
