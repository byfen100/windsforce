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

/** 主题回帖管理 */
function pidchecked(oObj){
	if(oObj.checked){
		try{
			var inp=document.createElement('<input name="topiclist[]" />');
		}catch(e){
			try{
				var inp=document.createElement('input');
				inp.name='topiclist[]';
			}catch(e){
				return;
			}
		}

		inp.id='topiclist_'+oObj.value;
		inp.value=oObj.value;
		inp.type='hidden';
		document.getElementById('modactions').appendChild(inp);
	}else{
		document.getElementById('modactions').removeChild(document.getElementById('topiclist_'+oObj.value));
	}
}

function getCurrentStyle(obj, cssproperty, csspropertyNS) {
	if(obj.style[cssproperty]){
		return obj.style[cssproperty];
	}
	if (obj.currentStyle) {
		return obj.currentStyle[cssproperty];
	} else if (document.defaultView.getComputedStyle(obj, null)) {
		var currentStyle = document.defaultView.getComputedStyle(obj, null);
		var value = currentStyle.getPropertyValue(csspropertyNS);
		if(!value){
			value = currentStyle[cssproperty];
		}
		return value;
	} else if (window.getComputedStyle) {
		var currentStyle = window.getComputedStyle(obj, "");
		return currentStyle.getPropertyValue(csspropertyNS);
	}
}

function fetchOffset(obj, mode) {
	var left_offset = 0, top_offset = 0, mode = !mode ? 0 : mode;

	if(obj.getBoundingClientRect && !mode) {
		var rect = obj.getBoundingClientRect();
		var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
		var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
		if(document.documentElement.dir == 'rtl') {
			scrollLeft = scrollLeft + document.documentElement.clientWidth - document.documentElement.scrollWidth;
		}
		left_offset = rect.left + scrollLeft - document.documentElement.clientLeft;
		top_offset = rect.top + scrollTop - document.documentElement.clientTop;
	}
	if(left_offset <= 0 || top_offset <= 0) {
		left_offset = obj.offsetLeft;
		top_offset = obj.offsetTop;
		while((obj = obj.offsetParent) != null) {
			position = getCurrentStyle(obj, 'position', 'position');
			if(position == 'relative') {
				continue;
			}
			left_offset += obj.offsetLeft;
			top_offset += obj.offsetTop;
		}
	}
	return {'left' : left_offset, 'top' : top_offset};
}

var modclickcount = 0;
function modclick(obj, pid) {
	if(obj.checked) {
		modclickcount++;
	} else {
		modclickcount--;
	}
	document.getElementById('mdct').innerHTML = modclickcount;
	if(modclickcount > 0) {
		var offset = fetchOffset(obj);
		document.getElementById('mdly').style.top = offset['top'] - 65 + 'px';
		document.getElementById('mdly').style.left = offset['left'] - 350 + 'px';
		document.getElementById('mdly').style.display = '';
	} else {
		document.getElementById('mdly').style.display = 'none';
	}
}

/** 删除回帖 */
function modCommentdelete(){
	/**/

/*var topiclist = $('input[name=topiclist]').val();  */
var topiclist=new Array();

		var count=0;
		var checked=0;
		for(var i = 0; i < document.getElementById('modactions').elements.length; i++) {
			if(document.getElementById('modactions').elements[i].name.match('topiclist')) {
				topiclist[count]=document.getElementById('modactions').elements[i].value;
					count++;
					if(checked==0){
						checked=1;
					}
			}
		}
	
	if(!checked) {
		Dyhb.Message('请选择需要操作的帖子',0,2);
	} 

	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/deletecomment_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&commentids='+topiclist),
		async: false
	}).responseText;

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oDeleteNewmodcomments=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicdeletecomment,'',400,100);
	}
}

function modTopicdeletecomment(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/deletecomment'),'result',deletecommentComplete);
	return false;
}

function deletecommentComplete(data,status){
	if(status==1){
		window.location.href=data.grouptopic_url;
	}

	return false;
}


/** 屏蔽或者显示回帖 */
function modCommentstatus(){
	/**/

/*var topiclist = $('input[name=topiclist]').val();  */
var topiclist=new Array();

		var count=0;
		var checked=0;
		for(var i = 0; i < document.getElementById('modactions').elements.length; i++) {
			if(document.getElementById('modactions').elements[i].name.match('topiclist')) {
				topiclist[count]=document.getElementById('modactions').elements[i].value;
					count++;
					if(checked==0){
						checked=1;
					}
			}
		}
	
	if(!checked) {
		Dyhb.Message('请选择需要操作的帖子',0,2);
	} 

	var sHtml=$.ajax({
		url: D.U('group://grouptopicadmin/statuscomment_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&commentids='+topiclist),
		async: false
	}).responseText;

	try{
		arrReturn=eval('('+sHtml+')');
		Dyhb.Message(arrReturn.info,0,2);
	}catch(ex){
		oStatusNewmodcomments=windsforceAlert(sHtml,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicstatuscomment,'',400,100);
	}
}

function modTopicstatuscomment(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/statuscomment'),'result',statuscommentComplete);
	return false;
}

function statuscommentComplete(data,status){
	if(status==1){
		window.location.href=data.grouptopic_url;
	}

	return false;
}
