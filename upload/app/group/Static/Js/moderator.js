/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Group应用帖子操作($Liu.XiangMin)*/

/** 操作原因 */
function replaceIdcontent(id,content){
	$('#'+id).val(content);
}

/** 通用操作 */
var sCurrentAction='';

function modTopiccommon(sAction,sExtra){
	var sUrl=D.U('group://grouptopicadmin/'+sAction+'_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+(!sExtra?'':'&'+sExtra));
	sCurrentAction=sAction;

	oCommonNewmodtopics=windsforceAjax(sUrl,D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopiccommontopic,'',400,100);
}

function modTopiccommontopic(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/'+sCurrentAction),'result',commonComplete);
	return false;
}

function commonComplete(data,status){
	if(status==1){
		if(sCurrentAction=='deletetopic'){
			window.location.href=data.group_url;
		}else{
			window.location.reload();
		}
	}

	return false;
}

/** 删除主题 */
function modTopicdelete(){
	modTopiccommon('deletetopic');
}

/** 关闭或者打开主题 */
function modTopicclose(nStatus){
	modTopiccommon('closetopic','status='+nStatus);
}

/** 置顶或者取消置顶主题 */
function modTopicstick(nStatus){
	modTopiccommon('sticktopic','status='+nStatus);
}

/** 精华或者取消精华主题 */
function modTopicdigest(nStatus){
	modTopiccommon('digesttopic','status='+nStatus);
}

/** 推荐或者取消推荐主题 */
function modTopicrecommend(nStatus){
	modTopiccommon('recommendtopic','status='+nStatus);
}

/** 隐藏或者显示主题 */
function modTopicstatus(nStatus){
	modTopiccommon('hidetopic','status='+nStatus);
}

/** 设置主题分类 */
function modTopiccategory(nCategoryid){
	modTopiccommon('categorytopic','category_id='+nCategoryid);
}

/** 设置主题标签 */
function modTopictag(){
	modTopiccommon('tagtopic');
}

/** 设置主题高亮 */
function modTopiccolor(){
	modTopiccommon('colortopic');
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

	oDeleteNewmodcomments=windsforceAjax(D.U('group://grouptopicadmin/deletecomment_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&commentids='+topiclist),D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicdeletecomment,'',400,100);
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

	oStatusNewmodcomments=windsforceAjax(D.U('group://grouptopicadmin/statuscomment_dialog?groupid='+nGroupid+'&dataids[]='+nGrouptopicid+'&commentids='+topiclist),D.L('你选择了一篇帖子','Js/Moderator_Js'),'',modTopicstatuscomment,'',400,100);
}

function modTopicstatuscomment(){
	Dyhb.AjaxSubmit('moderateform',D.U('group://grouptopicadmin/statuscomment'),'result',statuscommentComplete);
	return false;
}

function statuscommentComplete(data,status){
	if(status==1){
		window.location.reload();
	}

	return false;
}
