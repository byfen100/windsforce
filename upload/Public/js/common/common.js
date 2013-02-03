/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   WindsForce 前台公用($Liu.XiangMin)*/

/** 浏览器复制变量 */
var clipboardswfdata;

function addFavorite(url,title){
	try{
		window.external.addFavorite(url,title);
	}catch(e){
		try{
			window.sidebar.addPanel(title,url,'');
		}catch(e){
			windsforceAlert(D.L('请按 Ctrl+D 键添加到收藏夹','__COMMON_LANG__@Js/Common_Js'),'',3);
		}
	}
}

function setHomepage(sURL){
	if(Dyhb.Browser.Ie){
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(sURL);
	}else{
		windsforceAlert(D.L('非 IE 浏览器请手动将本站设为首页','__COMMON_LANG__@Js/Common_Js'),'',3);
		return false;
	}
}

function AC_DetectFlashVer(reqMajorVer,reqMinorVer,reqRevision){
	var versionStr=-1;

	if(navigator.plugins!=null && navigator.plugins.length>0 && (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"])){
		var swVer2=navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
		var flashDescription=navigator.plugins["Shockwave Flash" + swVer2].description;var descArray=flashDescription.split(" ");
		var tempArrayMajor=descArray[2].split(".");
		var versionMajor=tempArrayMajor[0];
		var versionMinor=tempArrayMajor[1];
		var versionRevision=descArray[3];

		if(versionRevision==""){
			versionRevision=descArray[4];
		}

		if(versionRevision[0]=="d"){
			versionRevision=versionRevision.substring(1);
		}else if(versionRevision[0]=="r"){
			versionRevision=versionRevision.substring(1);
			if(versionRevision.indexOf("d")>0){
				versionRevision=versionRevision.substring(0,versionRevision.indexOf("d"));
			}
		}

		versionStr=versionMajor+"."+versionMinor+"."+versionRevision;
	}else if(Dyhb.Browser.Ie && !Dyhb.Browser.Opera){
		try{
			var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
			versionStr=axo.GetVariable("$version");
		}
		catch(e){}
	}

	if(versionStr==-1){
		return false;
	}else if(versionStr!=0){
		if(Dyhb.Browser.Ie && !Dyhb.Browser.Opera){
			tempArray=versionStr.split(" ");
			tempString=tempArray[1];
			versionArray=tempString.split(",");
		}else{
			versionArray=versionStr.split(".");
		}

		var versionMajor=versionArray[0];
		var versionMinor=versionArray[1];
		var versionRevision=versionArray[2];

		return versionMajor>parseFloat(reqMajorVer)||(versionMajor == parseFloat(reqMajorVer)) && (versionMinor>parseFloat(reqMinorVer) || versionMinor==parseFloat(reqMinorVer) && versionRevision>=parseFloat(reqRevision));
	}
}

function AC_GetArgs(args,classid,mimeType){
	var ret=new Object();

	ret.embedAttrs=new Object();
	ret.params=new Object();
	ret.objAttrs=new Object();

	for(
		var i=0;i<args.length;i=i+2){
		var currArg=args[i].toLowerCase();
			switch(currArg){
					case "classid":break;
					case "pluginspage":
						ret.embedAttrs[args[i]]='http://www.macromedia.com/go/getflashplayer';break;
					case "src":
						ret.embedAttrs[args[i]]=args[i+1];
						ret.params["movie"]=args[i+1];
						break;
					case "codebase":
						ret.objAttrs[args[i]]='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0';
						break;
					case "onafterupdate":
					case "onbeforeupdate":
					case "onblur":
					case "oncellchange":
					case "onclick":
					case "ondblclick":
					case "ondrag":
					case "ondragend":
					case "ondragenter":
					case "ondragleave":
					case "ondragover":
					case "ondrop":
					case "onfinish":
					case "onfocus":
					case "onhelp":
					case "onmousedown":
					case "onmouseup":
					case "onmouseover":
					case "onmousemove":
					case "onmouseout":
					case "onkeypress":
					case "onkeydown":
					case "onkeyup":
					case "onload":
					case "onlosecapture":
					case "onpropertychange":
					case "onreadystatechange":
					case "onrowsdelete":
					case "onrowenter":
					case "onrowexit":
					case "onrowsinserted":
					case "onstart":
					case "onscroll":
					case "onbeforeeditfocus":
					case "onactivate":
					case "onbeforedeactivate":
					case "ondeactivate":
					case "type":
					case "id":
						ret.objAttrs[args[i]]=args[i+1];
						break;
					case "width":
					case "height":
					case "align":
					case "vspace":
					case "hspace":
					case "class":
					case "title":
					case "accesskey":
					case "name":
					case "tabindex":
						ret.embedAttrs[args[i]]=ret.objAttrs[args[i]]=args[i+1];
						break;
					default:ret.embedAttrs[args[i]]=ret.params[args[i]]=args[i+1];
			 }
		}

		ret.objAttrs["classid"]=classid;
		if(mimeType){
			ret.embedAttrs["type"]=mimeType;
		}

		return ret;
}

function AC_FL_RunContent(){
	var str='';

	if(AC_DetectFlashVer(9,0,124)){
		var ret=AC_GetArgs(arguments,"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000", "application/x-shockwave-flash");
		if(Dyhb.Browser.Ie && !Dyhb.Browser.Opera){
			str+='<object ';
			for(var i in ret.objAttrs){
				str+=i+'="'+ret.objAttrs[i]+'" ';
			}

			str+='>';

			for(var i in ret.params){
				str+='<param name="'+i+'" value="'+ret.params[i]+'" /> ';
			}

			str+='</object>';
		}else{
			str+='<embed ';
			for(var i in ret.embedAttrs){
				str+=i+ '="'+ret.embedAttrs[i]+'" ';
			}

			str+='></embed>';
		}
	}else{
		str=D.L('此内容需要 Adobe Flash Player 9.0.124 或更高版本','__COMMON_LANG__@Js/Common_Js')+'<br /><a href="http://www.adobe.com/go/getflashplayer/" target="_blank"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt='+D.L('下载 Flash Player','__COMMON_LANG__@Js/Common_Js')+' /></a>';
	}

	return str;
}

function goTop(acceleration,time){
	acceleration=acceleration || 0.1;
	time=time || 16;
 
	var x1=0;
	var y1=0;
	var x2=0;
	var y2=0;
	var x3=0;
	var y3=0;
 
	if(document.documentElement){
		x1=document.documentElement.scrollLeft || 0;
		y1=document.documentElement.scrollTop || 0;
	}

	if(document.body){
		x2=document.body.scrollLeft || 0;
		y2=document.body.scrollTop || 0;
	}

	var x3=window.scrollX || 0;
	var y3=window.scrollY || 0;
 
	// 滚动条到页面顶部的水平距离
	var x=Math.max(x1,Math.max(x2,x3));

	// 滚动条到页面顶部的垂直距离
	var y=Math.max(y1,Math.max(y2,y3));
 
	// 滚动距离=目前距离/速度, 因为距离原来越小,速度是大于1的数, 所以滚动距离会越来越小
	var speed=1+acceleration;
	window.scrollTo(Math.floor(x/speed),Math.floor(y/speed));
 
	// 如果距离不为零, 继续调用迭代本函数
	if(x>0 || y>0){
		var invokeFunction="goTop("+acceleration+","+time+")";
		window.setTimeout(invokeFunction,time);
	}
}

/** 复制数据到剪切板 */
function getClipboardData(){
	window.document.clipboardswf.SetVariable('str',clipboardswfdata);
}

var oCopyToClipboard='';

function hideMenu(attr,mtype){
	oCopyToClipboard.close();
}

function copyText(id,title){
	if(document.getElementById(id)){
		var tocopy=document.getElementById(id).innerHTML;
		tocopy=tocopy.replace(/&amp;/g,"&");

		copy(tocopy,title);
	}
}

function copy(text2copy,title){
	if(!title){
		title=D.L('地址已经复制到剪贴板','__COMMON_LANG__@Js/Common_Js');
	}
	
	if(window.clipboardData){
		copyToClipboard(text2copy,title);
	}else{
		var divinfo=AC_FL_RunContent('id','clipboardswf','name','clipboardswf','devicefont','false','width','200','height','40','src',_ROOT_+'/Public/images/common/clipboard.swf','menu','false','allowScriptAccess','sameDomain','swLiveConnect','true','wmode','transparent','style','margin-top:-20px');

		divinfo='<div><div style="width: 200px; text-align: center; text-decoration:underline;">'+D.L('点此复制到剪贴板','__COMMON_LANG__@Js/Common_Js')+'</div>'+divinfo+'</div>';

		text2copy=text2copy.replace(/[\xA0]/g,' ');
		clipboardswfdata=text2copy;

		oCopyToClipboard=windsforceAlert(divinfo,title,9);
	}
}

function copyToClipboard(meintext,title){
	if(window.clipboardData){
		windsforceAlert("ie",title,3);
			/* the IE-manier */
			window.clipboardData.setData("Text", meintext);
			/* waarschijnlijk niet de beste manier om Moz/NS te detecteren;
			   het is mij echter onbekend vanaf welke versie dit precies werkt: */
		}else if(window.netscape){
			/* dit is belangrijk maar staat nergens duidelijk vermeld:
			   you have to sign the code to enable this, or see notes below */
			netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');

			/* maak een interface naar het clipboard */
			var clip=Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
			if(!clip){
				return
			};
			windsforceAlert("mozilla",title,3);

			/* maak een transferable*/
			var trans=Components.classes['@mozilla.org/widget/transferable;1']
						.createInstance(Components.interfaces.nsITransferable);
			if(!trans){
				return
			};

			/* specificeer wat voor soort data we op willen halen; text in dit geval */
			trans.addDataFlavor('text/unicode');

			/* om de data uit de transferable te halen hebben we 2 nieuwe objecten
			   nodig om het in op te slaan */
			var str=new Object();
			var len=new Object();

			var str=Components.classes["@mozilla.org/supports-string;1"]
					.createInstance(Components.interfaces.nsISupportsString);
			var copytext=meintext;
			str.data=copytext;
			trans.setTransferData("text/unicode",str,copytext.length*2);
			
			var clipid=Components.interfaces.nsIClipboard;
			if(!clip){
				return false
			};
			clip.setData(trans,null,clipid.kGlobalClipboard);
		}

		windsforceAlert(meintext,title,3);

		return false;
}

function showYearmonthday(day,month,year){
	var oEl=document.getElementById(day);
	var sBirthday=oEl.value;

	oEl.length=0;
	oEl.options.add(new Option(D.L('日','__COMMON_LANG__@Js/Common_Js'),''));
	for(var nI=0;nI<28;nI++){
		oEl.options.add(new Option(nI+1,nI+1));
	}

	if(document.getElementById(month).value!="2"){
		oEl.options.add(new Option(29,29));
		oEl.options.add(new Option(30,30));
		switch(document.getElementById(month).value){
			case "1":
			case "3":
			case "5":
			case "7":
			case "8":
			case "10":
			case "12":{
				oEl.options.add(new Option(31,31));
			}
		}
	}else if(document.getElementById(year).value!=""){
		var nBirthyear=document.getElementById(year).value;
		if(nBirthyear%400==0 || (nBirthyear%4==0 && nBirthyear%100!=0)){
			oEl.options.add(new Option(29,29));
		}
	}

	oEl.value=sBirthday;
}

function setStyle(nStyle){
	Dyhb.AjaxSend(D.U('home://misc/style?id='+nStyle),'ajax=1','',function(data,status){
		if(status==1){
			setTimeout("window.location.reload();",1000);
		}
	});
}

function setExtendstyle(sExtendstyle,sCss){
	Dyhb.AjaxSend(D.U('home://misc/extendstyle?id='+sExtendstyle),'ajax=1','',function(data,status){
		if(status==1){
			document.getElementById('extend_style').href=sCss?sCss:_ROOT_+'/Public/images/common/none.css';
		}
	});
}

/** 登陆相关 */
function rememberme(close){
	var sStyle='block';
	if(close==1){
		sStyle='none';
	}

	document.getElementById('remember_time').style.display=sStyle;
}

function showSocialogin(){
	$('#socailogin_more').toggle('fast');
}

function ajaxLogin(){
	var sHtml = $.ajax({
		url: D.U('home://public/login?inajax=1'),
		async: false
	}).responseText;

	windsforceAlert(sHtml,D.L('用户登录','__COMMON_LANG__@Js/Common_Js'),'','','',600,200);
}

function ajaxRegister(refer){
	var sHtml = $.ajax({
		url: D.U('home://public/register?inajax=1'+(refer?'&refer='+encodeURIComponent(refer):'')),
		async: false
	}).responseText;

	windsforceAlert(sHtml,D.L('用户注册','__COMMON_LANG__@Js/Common_Js'),'','','',600,200);
}

/** 播放器 */
function playmedia(strID,strType,strURL,intWidth,intHeight,sBgColor){
	var objDiv=document.getElementById(strID);
	if(!objDiv)return false;
	if(objDiv.style.display!='none'){
		objDiv.innerHTML='';
		objDiv.style.display='none';
	} else {
		objDiv.innerHTML=makemedia(strType,strURL,intWidth,intHeight,strID,sBgColor);
		objDiv.style.display='block';
	}
}

function flashResize(id,width,height,reload,url,idbox){
	if(reload==1){
		var objDiv=document.getElementById(idbox);
		objDiv.innerHTML='';
		objDiv.style.display='none';

		playmedia(idbox,'flv',url,width,height);
	}else{
		document.getElementById(id).height=height;
		document.getElementById(id).width=width;
	}
}

function flashResizeUp(obj,width,height,reload,url,idbox){
	var newheight=parseInt(document.getElementById(obj).height,10)+height;
	var newwidth=parseInt(document.getElementById(obj).width,10)+width;
	if(reload==1){
		var objDiv=document.getElementById(idbox);
		objDiv.innerHTML='';
		objDiv.style.display='none';

		playmedia(idbox,'flv',url,newwidth,newheight);
	}else{
		document.getElementById(obj).height=newheight+'px';
		document.getElementById(obj).width=newwidth+'px';
	}
}

function flashResizeDown(obj,width,height,reload,url,idbox){
	var newheight=parseInt(document.getElementById(obj).height,10)-height;
	var newwidth=parseInt(document.getElementById(obj).width,10)-width;
	if(reload==1){
		var objDiv=document.getElementById(idbox);
		objDiv.innerHTML='';
		objDiv.style.display='none';

		if(newheight>0 && newwidth>0){
			playmedia(idbox,'flv',url,newwidth,newheight);
		}
	}else{
		if(newheight>0){
			document.getElementById(obj).height=newheight+'px';
		}	
		if(newwidth>0){
			document.getElementById(obj).width=newwidth+'px';
		}
	}
}

function fullplayFrame(sUrl,id){
	window.open(D.U('home://attachment/fullplay_frame?url='+encodeURIComponent(sUrl)),"WindsForce","menubar=no,toolbar=no,location=no,status=no,fullscreen=yes");
	window.opener =null;
	window.close();

	objDiv=document.getElementById(id);
	objDiv.innerHTML='';
	objDiv.style.display='none';
}

function playout(sUrl,id){
	var sHtml = $.ajax({
		url: D.U('home://attachment/playout?url='+encodeURIComponent(sUrl)),
		async: false
	}).responseText;

	oEditNewattachmentcategory=windsforceAlert(sHtml,D.L('Flash播放','__COMMON_LANG__@Js/Common_Js'),'','','',600,200);

	objDiv=document.getElementById(id);
	objDiv.innerHTML='';
	objDiv.style.display='none';
}

function makemedia(strType,strURL,intWidth,intHeight,strID,sBgColor){
	var strHtml;
	var strBg=sBgColor!='' && typeof(sBgColor)!='underfined' ? 'bgcolor="'+sBgColor+'"' :'';
	switch(strType){
		case 'wmp':
			strHtml="<object width='"+intWidth+"' height='"+intHeight+"' classid='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'><param name='url' value='"+strURL+"'/><embed width='"+intWidth+"' height='"+intHeight+"' type='application/x-mplayer2' src='"+strURL+"' ></embed></object>";
			strHtml+='<div><a href="'+strURL+'">'+D.L('下载','__COMMON_LANG__@Js/Common_Js')+'</a></div>';
			break;
		case 'swf':
			strHtml="<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='"+intWidth+"' height='"+intHeight+"''><param name='movie' value='"+strURL+"'/><param name='quality' value='high' /><embed src='"+strURL+"' quality='high' type='application/x-shockwave-flash' width='"+intWidth+"' height='"+intHeight+"' "+strBg+" id='size_"+strID+"'></embed></object>";
			strHtml+='<div><a href="javascript:void(0);" onclick="javascript:flashResizeDown(\'size_'+strID+'\',50,34);">'+D.L('缩小','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResizeUp(\'size_'+strID+'\',50,34);">'+D.L('放大','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',600,405);">'+D.L('原始','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',800,540);">'+D.L('小屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',920,621);">'+D.L('大屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="fullplayFrame(\''+strURL+'\',\''+strID+'\');">'+D.L('全屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="playout(\''+strURL+'\',\''+strID+'\');">'+D.L('弹出','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="'+strURL+'">'+D.L('下载','__COMMON_LANG__@Js/Common_Js')+'</a></div>';
			break;
		case 'flv':
			var sFlvurl=_ROOT_+"/Public/js/mediaplayer/player.swf?file="+encodeURIComponent(strURL);
			strHtml="<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='"+intWidth+"' height='"+intHeight+"'><param name='movie' value='"+sFlvurl+"&bufferlength=10'/><param name='quality' value='high' /><param name='allowFullScreen' value='true' /><embed src='"+sFlvurl+"&bufferlength=10' quality='high' allowFullScreen='true' type='application/x-shockwave-flash' width='"+intWidth+"' height='"+intHeight+"' id='size_"+strID+"' ></embed></object>";
			strHtml+='<div><a href="javascript:void(0);" onclick="javascript:flashResizeDown(\'size_'+strID+'\',50,34,1,\''+strURL+'\',\''+strID+'\');">'+D.L('缩小','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResizeUp(\'size_'+strID+'\',50,34,1,\''+strURL+'\',\''+strID+'\');">'+D.L('放大','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',600,405,1,\''+strURL+'\',\''+strID+'\');">'+D.L('原始','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',800,540,1,\''+strURL+'\',\''+strID+'\');">'+D.L('小屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',920,621,1,\''+strURL+'\',\''+strID+'\');">'+D.L('大屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="fullplayFrame(\''+sFlvurl+'\',\''+strID+'\');">'+D.L('全屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="playout(\''+sFlvurl+'\',\''+strID+'\');">'+D.L('弹出','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="'+strURL+'">'+D.L('下载','__COMMON_LANG__@Js/Common_Js')+'</a></div>';
			break;
		case 'qvod':
			strHtml='<iframe id="iframe_down" name="iframe_down" scrolling="no" frameborder="0" style="margin: 0;width: 635px; height: 500px; display: none;" src=""></iframe><object classid="clsid:F3D0D36F-23F8-4682-A195-74C92B03D4AF" width="'+intWidth+'" height="'+intHeight+'" id="QvodPlayer" name="QvodPlayer" onerror="document.getElementById(\'QvodPlayer\').style.display=\'none\';document.getElementById(\'iframe_down\').style.display=\'\';document.getElementById(\'iframe_down\').src=\'http://error2.qvod.com/error2.htm\';"><param name="url" value="'+strURL+'"><param name="Autoplay" value="1"><embed url="'+strURL+'" type="application/qvod-plugin" width="'+intWidth+'" height="'+intHeight+'" id="size_'+strID+'"></embed></object>';
			strHtml+='<div><a href="javascript:void(0);" onclick="javascript:flashResizeDown(\'size_'+strID+'\',50,34);">'+D.L('缩小','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResizeUp(\'size_'+strID+'\',50,34);">'+D.L('放大','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',600,405);">'+D.L('原始','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',800,540);">'+D.L('小屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="javascript:void(0);" onclick="javascript:flashResize(\'size_'+strID+'\',920,621);">'+D.L('大屏','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="'+strURL+'">'+D.L('下载','__COMMON_LANG__@Js/Common_Js')+'</a></div>';
			break;
		case 'mp3':
			var sMp3playurl=_ROOT_+"/Public/js/dewplayer/dewplayer-vol.swf?mp3="+encodeURIComponent(strURL);
			var sMp3url='showtime=true&autoreplay=true&autostart=1';
			strHtml="<object type='application/x-shockwave-flash' data='"+sMp3playurl+"' width='"+intWidth+"' height='"+intHeight+"'><param name='wmode' value='transparent' /><param name='movie' value='"+sMp3playurl+"' /><param name='flashvars' value='showtime=true&autoreplay=true&autostart=1' /></object>";
			strHtml+='<div><a href="'+sMp3playurl+'&'+sMp3url+'" target="_blank">'+D.L('新窗口','__COMMON_LANG__@Js/Common_Js')+'</a><span class="pipe">|</span><a href="'+strURL+'">'+D.L('下载','__COMMON_LANG__@Js/Common_Js')+'</a></div>';
			break;
		case 'mp3list':
			var sMp3playurl=_ROOT_+"/Public/js/dewplayer/dewplayer-playlist.swf";
			var sMp3listurl='showtime=true&autoreplay=true&autostart=1&xml='+encodeURIComponent(strURL);
			strHtml="<object type='application/x-shockwave-flash' data='"+sMp3playurl+"' width='"+intWidth+"' height='"+intHeight+"'><param name='wmode' value='transparent' /><param name='movie' value='"+sMp3playurl+"' /><param name='flashvars' value='"+sMp3listurl+"' /></object>";
			strHtml+='<div><a href="'+sMp3playurl+'?'+sMp3listurl+'" target="_blank">'+D.L('新窗口','__COMMON_LANG__@Js/Common_Js')+'</a></div>';
			break;
	}

	return strHtml;
}

/** 更新媒体下载量 */
function updateDownload(nAttachmentid){
	Dyhb.AjaxSend(D.U('home://attachmentdownload/index?id='+nAttachmentid),'','',function(data,status){
		if(status==0){
			alert(data.info);
		}
	});
}

/** 更换背景 */
var nCurrentBgindex=0;
function changeGlobalbg(){
	if(typeof(globalImgbgs)=='undefined'){
		return false;
	}

	if(nCurrentBgindex>=globalImgbgs.length){
		nCurrentBgindex=0;
	}

	var oObj=document.getElementsByTagName("BODY")[0];
	oObj.style.backgroundImage="url("+globalImgbgs[nCurrentBgindex]+")";
	oObj.style.backgroundRepeat=sBgextendRepeat; 
	nCurrentBgindex+=1;
}

/** 发送短消息 */
function addMessage(nUid){
	var sHtml = $.ajax({
		url: D.U('home://pm/dialog_add?uid='+nUid),
		async: false
	}).responseText;

	try{
		arrReturn=eval('('+sHtml+')');
		alert(arrReturn.info);
	}catch(ex){
		oEditNewmessage=windsforceAlert(sHtml,D.L('发送短消息','__COMMON_LANG__@Js/Common_Js'),'',addMessageok,'',500,100);
	};
}

function addMessageok(){
	Dyhb.AjaxSubmit('sendpmform',D.U('home://pm/sendpm'),'result',function(data,status){
		if(status==1){
			$('#pm_subject').val('');
			$('#pm_message').val('');
			return true;
		}else{
			return false;
		}
	});
	return false;
}

/** 添加和删除好友 */
function addFriend(userid){
	Dyhb.AjaxSend(D.U('home://friend/add'),'ajax=1&uid='+userid,'',function(data,status){
		if(status==1){
			window.location.reload();
		}
	});
}

function deleteFriend(friendid,fan){
	windsforceConfirm(D.L('确实要永久删除选择项吗？','__COMMON_LANG__@Admin/Common_Js'),function(){
		Dyhb.AjaxSend(D.U('home://friend/delete?friendid='+friendid+(fan=='1'?'&fan=1':'')),'','',function(data,status){
			if(status==1){
				window.location.reload();
			}
		});
	});
}

/** URL && Email格式检测 */
function checkUrl(sUrl){
	var strRegex="^((https|http|ftp|rtsp|mms)?://)"
		+ "?(([0-9a-z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+@)?"
		+ "(([0-9]{1,3}\.){3}[0-9]{1,3}"
		+ "|"
		+ "([0-9a-zA-Z_!~*'()-]+\.)*"
		+ "([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-z]\."
		+ "[a-zA-Z]{2,6})"
		+ "(:[0-9]{1,4})?"
		+ "((/?)|"
		+ "(/[0-9a-zA-Z_!~*'().;?:@&=+$,%#-]+)+/?)$";

	var re=new RegExp(strRegex);

	if(re.test(sUrl)){
		return true;
	}else{
		return false;
	}
}

function checkEmail(sEmail){
	var emailRegExp=new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
	
	if(!emailRegExp.test(sEmail)||sEmail.indexOf('.')==-1){
		return false;
	}else{
		return true;
	}
}
