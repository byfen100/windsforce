/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Group应用帖子操作($Liu.XiangMin)*/

/** 操作原因 */
function replaceIdcontent(id,content){
	$('#'+id).val(content);
}

/** 删除主题 */
function modTopicdelete(){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/deletetopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid),
		async: false
	}).responseText;

	oDeleteNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopicdeletetopic,'',400,100);
}

function modTopicdeletetopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/deletetopic'),'result',deletetopicComplete);
	return false;
}

function deletetopicComplete(data,status){
	if(status==1){
		window.location.href=data.group_url;
	}

	return false;
}

/** 关闭或者打开主题 */
function modTopicclose(nStatus){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/closetopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&status='+nStatus),
		async: false
	}).responseText;

	oCloseNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopicclosetopic,'',400,100);
}

function modTopicclosetopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/closetopic'),'result',closetopicComplete);
	return false;
}

function closetopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}

/** 置顶或者取消置顶主题 */
function modTopicstick(nStatus){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/sticktopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&status='+nStatus),
		async: false
	}).responseText;

	oStickNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopicsticktopic,'',400,100);
}

function modTopicsticktopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/sticktopic'),'result',sticktopicComplete);
	return false;
}

function sticktopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}

/** 精华或者取消精华主题 */
function modTopicdigest(nStatus){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/digesttopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&status='+nStatus),
		async: false
	}).responseText;

	oDigestNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopicdigesttopic,'',400,100);
}

function modTopicdigesttopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/digesttopic'),'result',digesttopicComplete);
	return false;
}

function digesttopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}

/** 隐藏或者显示主题 */
function modTopicstatus(nStatus){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/statustopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&status='+nStatus),
		async: false
	}).responseText;

	oStatusNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopicstatustopic,'',400,100);
}

function modTopicstatustopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/statustopic'),'result',statustopicComplete);
	return false;
}

function statustopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}

/** 设置主题分类 */
function modTopiccategory(nCategoryid){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/categorytopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&category_id='+nCategoryid),
		async: false
	}).responseText;

	oCategoryNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopiccategorytopic,'',400,100);
}

function modTopiccategorytopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/categorytopic'),'result',categorytopicComplete);
	return false;
}

function categorytopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}

/** 设置主题标签 */
function modTopictag(){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/tagtopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid),
		async: false
	}).responseText;

	oTagNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopictagtopic,'',400,100);
}

function modTopictagtopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/tagtopic'),'result',tagtopicComplete);
	return false;
}

function tagtopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}

/** 设置主题高亮 */
function modTopiccolor(){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/colortopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid),
		async: false
	}).responseText;

	oColorNewmodtopics=windsforceAlert(sHtml,'你选择了一篇帖子','',modTopiccolortopic,'',400,100);
}

function modTopiccolortopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/colortopic'),'result',colortopicComplete);
	return false;
}

function colortopicComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}
