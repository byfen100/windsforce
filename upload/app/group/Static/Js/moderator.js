/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Group应用帖子操作($Liu.XiangMin)*/

function modTopicdelete(){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/deletetopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid),
		async: false
	}).responseText;

	oEditNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopicdeletetopic,'',400,100);
}

function modTopicdeletetopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/deletetopic'),'result',deletetopicComplete);
	return false;
}

function deletetopicComplete(){
	return true;
}

function replaceIdcontent(id,content){
	$('#'+id).val(content);
}
