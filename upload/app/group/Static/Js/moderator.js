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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oDeleteNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicdeletetopic,'',400,100);
	}
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oCloseNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicclosetopic,'',400,100);
	}
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oStickNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicsticktopic,'',400,100);
	}
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oDigestNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicdigesttopic,'',400,100);
	}
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

/** 推荐或者取消推荐主题 */
function modTopicrecommend(nStatus){
	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/recommendtopic_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&status='+nStatus),
		async: false
	}).responseText;

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oRecommendNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicrecommendtopic,'',400,100);
	}
}

function modTopicrecommendtopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/recommendtopic'),'result',recommendtopicComplete);
	return false;
}

function recommendtopicComplete(data,status){
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oStatusNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicstatustopic,'',400,100);
	}
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oCategoryNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopiccategorytopic,'',400,100);
	}
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oTagNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopictagtopic,'',400,100);
	}
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

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oColorNewmodtopics=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopiccolortopic,'',400,100);
	}
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
