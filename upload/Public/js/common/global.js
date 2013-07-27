/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce 前后台公用($Liu.XiangMin)*/

function isUndefined(variable){
	return typeof variable=='undefined'?true:false;
}

function isArray(oObj){
	return oObj && !(oObj.propertyIsEnumerable('length')) && typeof oObj==='object' && typeof oObj.length==='number';
}

function in_array(needle,haystack){
	if(typeof needle=='string' || typeof needle=='number'){
		for(var i in haystack){
			if(haystack[i]==needle){
				return true;
			}
		}
	}

	return false;
}

function trim(str){
	return(str+'').replace(/(\s+)$/g,'').replace(/^\s+/g,'');
}

function strlen(str){
	return(Dyhb.Browser.Ie && str.indexOf('\n')!=-1)?str.replace(/\r?\n/g,'_').length:str.length;
}

function $WF(id){
	return document.getElementById(id);
}

function mb_strlen(str){
	var len=0;

	for(var i=0;i<str.length;i++){
		len+=str.charCodeAt(i)<0 || str.charCodeAt(i)>255?(charset=='utf-8'?3:2):1;
	}

	return len;
}

function subStr(str,len,elli){
	if(!str || !len){
		return '';
	}

	if(!elli){
		elli='';
	}

	var a=0;
	var i=0;
	var temp='';
	for(i=0;i<str.length;i++){
		if(str.charCodeAt(i)>255){
			a+=2;
		}else{
			a++;
		}
		if(a>len){
			return temp+elli;
		}
		temp+=str.charAt(i);
	}

	return str;
}

function getObjectClass(obj) {
	if (typeof obj!="object" || obj===null){
		return false;
	}else{
		return /(\w+)\(/.exec(obj.constructor.toString())[1];
	}
}

function preg_replace(search,replace,str,regswitch){
	var regswitch=!regswitch?'ig':regswitch;
	var len=search.length;

	for(var i=0;i<len;i++){
		re=new RegExp(search[i],regswitch);
		str=str.replace(re,typeof replace=='string'?replace:(replace[i]?replace[i]:replace[0]));
	}

	return str;
}

function htmlspecialchars(str){
	return preg_replace(['&','<','>','"'],['&amp;','&lt;','&gt;','&quot;'],str);
}

function stripscript(s){
	return s.replace(/<script.*?>.*?<\/script>/ig,'');
}

function getCurrentStyle(obj,cssproperty,csspropertyNS){
	if(obj.style[cssproperty]){
		return obj.style[cssproperty];
	}

	if(obj.currentStyle){
		return obj.currentStyle[cssproperty];
	}else if (document.defaultView.getComputedStyle(obj,null)){
		var currentStyle=document.defaultView.getComputedStyle(obj,null);
		var value=currentStyle.getPropertyValue(csspropertyNS);
		
		if(!value){
			value=currentStyle[cssproperty];
		}

		return value;
	}else if(window.getComputedStyle){
		var currentStyle = window.getComputedStyle(obj,"");
		return currentStyle.getPropertyValue(csspropertyNS);
	}
}

function fetchOffset(obj,mode){
	var left_offset=0,top_offset=0,mode=!mode?0:mode;

	if(obj.getBoundingClientRect && !mode){
		var rect=obj.getBoundingClientRect();
		var scrollTop=Math.max(document.documentElement.scrollTop,document.body.scrollTop);
		var scrollLeft=Math.max(document.documentElement.scrollLeft,document.body.scrollLeft);
		
		if(document.documentElement.dir=='rtl'){
			scrollLeft=scrollLeft+document.documentElement.clientWidth-document.documentElement.scrollWidth;
		}

		left_offset=rect.left+scrollLeft-document.documentElement.clientLeft;
		top_offset=rect.top+scrollTop-document.documentElement.clientTop;
	}

	if(left_offset<=0 || top_offset<=0){
		left_offset=obj.offsetLeft;
		top_offset=obj.offsetTop;
		while((obj=obj.offsetParent)!= null){
			position=getCurrentStyle(obj,'position','position');
			if(position=='relative'){
				continue;
			}

			left_offset+=obj.offsetLeft;
			top_offset+=obj.offsetTop;
		}
	}

	return {'left':left_offset,'top':top_offset};
}

function showDiv(id){
	try{
		var oDiv=$WF(id);
		if(oDiv){
			if(oDiv.style.display=='none'){
				oDiv.style.display='block';
			}else{
				oDiv.style.display='none';
			}
		}
	}catch(e){}
}

function resizeUp(obj){
	var newheight=parseInt($WF(obj).style.height,10)+50;
	$WF(obj).style.height=newheight+'px';
}

function resizeDown(obj){
	var newheight=parseInt($WF(obj).style.height,10)-50;
	if(newheight>0){
		$WF(obj).style.height=newheight+'px';
	}
}

function updateSeccode(){
	if($WF("seccodeImage").innerHTML==''){
		$WF('seccodeImage').style.display='block';
		$WF("seccodeImage").innerHTML=D.L('验证码正在加载中','__COMMON_LANG__@Common');
	}

	var timenow=new Date().getTime();
	$WF("seccodeImage").innerHTML='<img id="seccode" onclick="updateSeccode()" src="'+D.U('public/seccode?update='+timenow)+'" style="cursor:pointer" title="'+D.L('单击图片换个验证码','__COMMON_LANG__@Common')+'" alt="'+D.L('验证码正在加载中','__COMMON_LANG__@Common')+'" />';
}

function checkAll(str,bThis){
	var i;
	var nLength;
	var inputs=$WF(str).getElementsByTagName("input");
	var nSelect=0;

	if(isUndefined(bThis)){
		var bThis=inputs[0].checked;
		i=1;
		nLength=inputs.length;
	}else{
		i=0;
		nLength=inputs.length;
	}

	for(i=0;i<nLength;i++){
		inputs[i].checked=bThis;

		if(bThis===true){
			nSelect++;
		}else{
			if(nSelect>0){
				nSelect--;
			}
		}
	}

	if(nSelect>0){
		nSelect--;
	}

	return nSelect;
}

function showDistrict(sContainer,oElems,nTotallevel,nChangelevel,sContainertype,sDistrictPrefix){
	var getdid=function(oElem){
		var op=oElem.options[oElem.selectedIndex];
		return op['did'] || op.getAttribute('did') || '0';
	};

	var nPid=nChangelevel>=1 && oElems[0] && $WF(oElems[0])?getdid($WF(oElems[0])):0;
	var nCid=nChangelevel>=2 && oElems[1] && $WF(oElems[1])?getdid($WF(oElems[1])):0;
	var nDid=nChangelevel>=3 && oElems[2] && $WF(oElems[2])?getdid($WF(oElems[2])):0;
	var nCoid=nChangelevel>=4 && oElems[3] && $WF(oElems[3])?getdid($WF(oElems[3])):0;

	var sUrl=Dyhb.U('home://misc/district?container='+sContainer+'&containertype='+sContainertype+'&districtprefix='+sDistrictPrefix+
			'&province='+oElems[0]+'&city='+oElems[1]+'&district='+oElems[2]+'&community='+oElems[3]+
			'&pid='+nPid+'&cid='+nCid+'&did='+nDid+'&coid='+nCoid+'&level='+nTotallevel+'&handlekey='+sContainer);
	
	Dyhb.Ajax.Get(sUrl,
		'',
		function(xhr,responseText){
			var sStr=xhr.responseText;
			var oContainer=$WF(sContainer);
			oContainer.innerHTML=sStr;
		}
	);
}

function loadEditor(name){
	var editor=KindEditor.create('textarea[name="'+name+'"]',{
		langType:sEditorLang,
		resizeType:1,
		allowPreviewEmoticons:false,
		allowImageUpload:false,
		allowFlashUpload:false,
		allowMediaUpload:false,
		allowFileManager:false,
		items:['source','|','formatblock','fontname','fontsize','|','forecolor','hilitecolor','bold','italic','underline',
		'removeformat','|','justifyleft','justifycenter','justifyright','insertorderedlist',
		'insertunorderedlist','|','link','unlink','image','flash','code','|','fullscreen'],
		newlineTag:'<p>'
	});

	return editor;
}

function loadEditorThin(name){
	var editor=KindEditor.create('textarea[name="'+name+'"]',{
		langType:sEditorLang,
		resizeType:1,
		allowPreviewEmoticons:false,
		allowImageUpload:false,
		allowFlashUpload:false,
		allowMediaUpload:false,
		allowFileManager:false,
		items:['source','|','formatblock','fontname','fontsize','|','bold','forecolor','hilitecolor','italic','underline',
		'removeformat','|','link','unlink','image','code','|','fullscreen'],
		newlineTag:'<p>'
	});

	return editor;
}

/** 对话框 */
function windsforceAlert(sContent,sTitle,nTime,ok,cancel,width,height,lock){
	if(!sTitle){
		sTitle=D.L('提示信息','__COMMON_LANG__@Common');
	}

	if(!ok){
		ok=function(){
			return true;
		}
	}
	
	if(!cancel){
		cancel=function(){
			return true;
		}
	}

	var oDialog=$.dialog({
		fixed:true,
		title:sTitle,
		content: sContent,
		okValue: D.L('确定','__COMMON_LANG__@Common'),
		ok: ok,
		cancelValue: D.L('取消','__COMMON_LANG__@Common'),
		cancel: cancel
	});

	if(width && height){
		oDialog.size(width,height);
	}

	if(lock!=1){
		oDialog.lock();
	}

	if(nTime){
		oDialog.time(nTime*1000);
	}

	return oDialog;
}

function windsforceConfirm(sContent,ok,cancel,sTitle,nTime,width,height,lock){
	if(!sTitle){
		sTitle=D.L('提示信息','__COMMON_LANG__@Common');
	}

	if(!ok){
		ok=function(){
			return true;
		}
	}
	
	if(!cancel){
		cancel=function(){
			return true;
		}
	}

	var oDialog=$.dialog({
		id:'Confirm',
		fixed:true,
		title:sTitle,
		content:sContent,
		okValue: D.L('确定','__COMMON_LANG__@Common'),
		ok:ok,
		cancelValue: D.L('取消','__COMMON_LANG__@Common'),
		cancel: cancel
	});

	if(width && height){
		oDialog.size(width,height);
	}

	if(lock!=1){
		oDialog.lock();
	}

	if(nTime){
		oDialog.time(nTime*1000);
	}

	return oDialog;
}

/** 通用ajax对话框 */
function windsforceAjaxhtml(sUrl,nCheck,sExtend){
	if(nCheck!=-1){
		nCheck=1;
	}
	
	var sHtml=$.ajax({
		url:sUrl,
		data:sExtend?sExtend:'',
		async:false
	}).responseText;

	if(nCheck==1){
		try{
			arrReturn=eval('('+sHtml+')');
			Dyhb.Message(arrReturn.info,0,2);

			return false;
		}catch(ex){
			return sHtml;
		}
	}else{
		return sHtml;
	}
}

function windsforceAjax(sUrl,sTitle,nTime,ok,cancel,width,height,sExtend,lock){
	sHtml=windsforceAjaxhtml(sUrl,1,sExtend);

	if(sHtml===false){
		return;
	}

	if(!width){
		width="400";
	}

	if(!height){
		height="100";
	}

	return windsforceAlert(sHtml,sTitle,nTime,ok,cancel,width,height,lock);
}

/** 媒体对话框 */
var oEditNewattachmentcategory;
function globalAddattachment(sFunction,nType){
	/* type：0 所有，1 图片，2附件*/
	if(!nType){
		nType=0;
	}

	var sUrl=_ROOT_+'/index.php?app=home&c=attachment&a=dialog_add&function='+sFunction+'&filetype='+nType;
	var sHtml='<iframe id="iframe_dialog" name="iframe_dialog" frameborder="0" style="margin: 0;width: 700px; height: 450px;overflow-x:hidden;margin:0;padding:0;" src="'+sUrl+'"></iframe>';

	oEditNewattachment=windsforceAlert(sHtml,D.L('媒体管理器','__COMMON_LANG__@Common'),'',globalCancelattachment,'',700,450,1);
}

function globalCancelattachment(){
	windsforceConfirm(D.L('注意，这个不是上传按钮，请拖动垂直滚动条查看上传按钮','__COMMON_LANG__@Common')+'<br/>'+D.L('你确定关闭媒体管理器?','__COMMON_LANG__@Common'),function(){
		oEditNewattachment.close();
		return true;
	},function(){
		return true;
	});
	return false;
}

function addEditorContent(oEditor,sContent){
	if(oEditor.designMode==false){
		windsforceAlert(D.L('请先切换到所见所得模式','__COMMON_LANG__@Common'),'',3);
	}else{
		oEditor.insertHtml(sContent);
	}
}

function replaceEditorContent(oEditor,sContent){
	if(oEditor.designMode==false){
		windsforceAlert(D.L('请先切换到所见所得模式','__COMMON_LANG__@Common'),'',3);
	}else{
		oEditor.html(sContent);
	}
}

function insertAttachment(editor,nAttachmentid){
	addEditorContent(editor,'[attachment]'+nAttachmentid+'[/attachment]');
}

function insertAttachmentthumb(sId,nAttachmentid){
	$('#'+sId).val(nAttachmentid);
}

var oEditNewmusic='';
function addMusic(sFunction){
	oEditNewmusic=windsforceAjax(_ROOT_+'/index.php?app=home&c=misc&a=music&function='+sFunction,D.L('插入音乐','__COMMON_LANG__@Common'),'','','',500,100);
}

function insertMusic(editor,sContent){
	if(!sContent){
		windsforceAlert(D.L('音乐地址不能够为空','__COMMON_LANG__@Common'),'',3);
		return false;
	}

	sContent='[mp3]'+sContent+'[/mp3]';
	addEditorContent(editor,sContent);
	oEditNewmusic.close();
}

var oEditNewvideo='';
function addVideo(sFunction){
	oEditNewvideo=windsforceAjax(_ROOT_+'/index.php?app=home&c=misc&a=video&function='+sFunction,D.L('插入视频','__COMMON_LANG__@Common'),'','','',500,100);
}

function insertVideo(editor,sContent){
	if(!sContent){
		windsforceAlert(D.L('视频地址不能够为空','__COMMON_LANG__@Common'),'',3);
		return false;
	}

	sContent='[video]'+sContent+'[/video]';
	addEditorContent(editor,sContent);
	oEditNewvideo.close();
}

/** 普通文本框插入附件 && 需要Public/js/jquery/jquery.insertcontent.js */
function insertContentNormal(id,sContent){
	$('#'+id).insertAtCaret(sContent);
}

function insertAttachmentNormal(id,nAttachmentid){
	insertContentNormal(id,'[attachment]'+nAttachmentid+'[/attachment]');
}

function insertVideoNormal(id,sContent){
	if(!sContent){
		windsforceAlert(D.L('视频地址不能够为空','__COMMON_LANG__@Common'),'',3);
		return false;
	}

	insertContentNormal(id,'[video]'+sContent+'[/video]');
	oEditNewvideo.close();
}

function insertMusicNormal(id,sContent){
	if(!sContent){
		windsforceAlert(D.L('音乐地址不能够为空','__COMMON_LANG__@Common'),'',3);
		return false;
	}
	
	insertContentNormal(id,'[mp3]'+sContent+'[/mp3]');
	oEditNewmusic.close();
}
