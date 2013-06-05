/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Group应用基础Javascript($Liu.XiangMin)*/

/** 加入和退出小组 */
function joinGroup(gid,id){
	var sUrl=D.U('group://group/joingroup');
	Dyhb.AjaxSend(sUrl,'gid='+gid,'',function(data,status){
		if(status==1){
			if(id){
				$('#'+id).html(D.L('加入成功','Template'));
			}
			setTimeout("window.location.replace(_SELF_);",1000);
		}
	});
}

function leaveGroup(gid,id){
	windsforceConfirm(D.L('你确定要退出小组吗？如果，你是小组的管理人员包括创始人，退出小组意味着你的职务将会被自动解除，请慎重！','Template'),function(){
		var sUrl=D.U('group://group/leavegroup');
		Dyhb.AjaxSend(sUrl,'gid='+gid,'',function(data,status){
			if(status==1){
				if(id){
					$('#'+id).html(D.L('退出成功','Template'));
				}
				setTimeout("window.location.replace(_SELF_);",1000);
			}
		});
	});
}
