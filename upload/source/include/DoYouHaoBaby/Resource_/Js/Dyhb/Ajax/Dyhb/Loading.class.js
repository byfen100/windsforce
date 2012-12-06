/**
 * ajax返回消息表格容器
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
