/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台短消息提醒($Liu.XiangMin)*/

function getNewpms(userid){
	$.ajax({
		type:"GET",
		url:_ROOT_+"/index.php?app=home&c=misc&a=newpmnum&uid="+userid,
		success: function(data){
			var dataJson=eval('('+data+')');

			if(dataJson.total>0){
				// 显示消息框
				var sMessage='<a href="'+D.U('home://pm/index?'+(dataJson.user?'type=new':'type=systemnew'))+'" title="'+D.L('私人消息','__COMMON_LANG__@Js/Pm_Js')+'('+dataJson.user+') '+D.L('系统消息','__COMMON_LANG__@Js/Pm_Js')+'('+dataJson.system+')"><img src="'+_ROOT_+'/Public/images/common/notice_newpm.gif"/> '+D.L('新消息','__COMMON_LANG__@Js/Pm_Js')+'+'+dataJson.total+'</a>';
				if(pm_sound_on==1){
					sMessage+='<div id="pmsound" style="position:absolute;top:-100000px">&nbsp;</div>';
				}
				$('#new-message-box').html(sMessage);
				document.getElementById('pmsound').innerHTML=AC_FL_RunContent('id','pmsoundplayer','name','pmsoundplayer','width','0','height','0','src',_ROOT_+'/Public/images/common/sound/player.swf','FlashVars','sFile='+sound_outurl,'menu','false','allowScriptAccess','sameDomain','swLiveConnect','true');

				// 赋值
				if(document.getElementById('usernew-pm-num')){
					if(dataJson.user){
						document.getElementById('usernew-pm-num').innerHTML='<span class="badge badge-important">'+dataJson.user+'</span>';
					}else{
						document.getElementById('usernew-pm-num').innerHTML='<span class="badge">'+dataJson.user+'</span>';
					}
				}
				if(document.getElementById('systemnew-pm-num')){
					if(dataJson.system){
						document.getElementById('systemnew-pm-num').innerHTML='<span class="badge badge-important">'+dataJson.system+'</span>';
					}else{
						document.getElementById('systemnew-pm-num').innerHTML='<span class="badge>'+dataJson.system+'</span>';
					}
				}
				
				// 闪烁标题
				var titleState=0;
				var promptState=0;
				var oldTitle=document.title;
				flashTitle=function(){
					document.title=(titleState?'\u3010\u3000\u3000\u3000\u3011':'【'+D.L('新消息','__COMMON_LANG__@Js/Pm_Js')+'】'+'('+dataJson.total+') ')+oldTitle;
					titleState=!titleState;
				}

				window.setInterval('flashTitle();',500);
			}
		}
	});
}
