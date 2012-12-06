/* [NeedForBug!] (C)Dianniu From 2010.
   Group应用帖子操作($)*/

function modTopicdelete(){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/deletetopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid),
		async: false
	}).responseText;

	oEditNewmodtopics=needforbugAlert(sHtml,'你选择了一篇帖子','',modTopicdeletetopic,'',400,100);
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
