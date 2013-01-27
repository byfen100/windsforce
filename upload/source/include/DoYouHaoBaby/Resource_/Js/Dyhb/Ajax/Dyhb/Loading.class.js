/**
 * ajax返回消息表格容器
 *
 * @param string sInfo 消息内容
 * @param string sImages 消息图标
 */
Dyhb.Ajax.Dyhb.MessageTable=function(sInfo,sImages){
	var sContent='<table width="100%" border="0" align="left" valign="middle" cellpadding="0" cellspacing="0"><tr>';
	if(sImages){
		sContent+='<td width="20px" valign="middle">'+sImages+'</td>';
	}
	sContent+='<td valign="middle">'+sInfo+'</span></td></tr></table>';

	return sContent;
};

/**
 * Js消息提示函数
 *
 * @param string sInfo 消息内容
 * @param int nStatus 消息状态
 * @param int nDisplay消息显示时间(s)，0表示不显示
 * @param string sTarget 目标DIV ID
 * @param bool bShowTip 是否显示消息
 */
Dyhb.Ajax.Dyhb.Message=function(sInfo,nStatus,nDisplay,sTarget,bShowTip){
	if(sInfo==undefined){
		sInfo=Dyhb.Ajax.Dyhb.Info;
	}

	if(nDisplay==undefined){
		nDisplay=1;
	}

	if(sTarget==undefined){
		sTarget=Dyhb.Ajax.Dyhb.TipTarget;
	}

	if(bShowTip==undefined){
		bShowTip=Dyhb.Ajax.Dyhb.ShowTip;
	}

	if(nStatus==undefined){
		nStatus==1;
	}

	if(nDisplay && bShowTip && sInfo!=undefined && sInfo!=''){
		var sOldTarget=sTarget;
		sTarget=document.getElementById(sTarget);
		sTarget.style.display="block";

		if(nStatus==1){
			if(''!=Dyhb.Ajax.Dyhb.Image[1]){
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:blue">'+sInfo+'</span>','<img src="'+Dyhb.Ajax.Dyhb.Image[1]+'" class="'+sOldTarget+'Success" border="0" alt="success..." align="absmiddle">');
			}else{
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:blue">'+sInfo+'</span>');
			}
		}else{
			if(''!=Dyhb.Ajax.Dyhb.Image[2]){
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:red">'+sInfo+'</span>','<img src="'+Dyhb.Ajax.Dyhb.Image[2]+'" class="'+sOldTarget+'Error" border="0" alt="error..." align="absmiddle">');
			}else{
				sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span style="color:red">'+sInfo+'</span>');
			}
		}
	}

	/* 提示信息停留 Dyhb.Ajax.Dyhb.Display 秒 */
	if(nDisplay&& bShowTip && sInfo!=undefined && sInfo!=''){
		setTimeout(function(){sTarget.style.display="none";},nDisplay*1000);
	}
};

Dyhb.Message=Dyhb.Ajax.Dyhb.Message;

/**
 * DoYouHaoBaby Framework专用Ajax格式
 *
 * @param string sTarget 消息DIV ID
 * @param string sTips Ajax加载消息
 */
Dyhb.Ajax.Dyhb.Loading=function(sTarget,sTips){
	if(sTarget){
		sTarget=document.getElementById(sTarget);
		sTarget.style.display="block";

		if(''!=Dyhb.Ajax.Dyhb.Image[0]){
			sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span>'+sTips+'</span>','<img src="'+Dyhb.Ajax.Dyhb.Image[0]+'" border="0" alt="loading..." align="absmiddle">');
		}else{
			sTarget.innerHTML=Dyhb.Ajax.Dyhb.MessageTable('<span>'+sTips+'</span>');
		}
	}
};
