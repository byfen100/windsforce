/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Group应用基础Javascript($Liu.XiangMin)*/

/** 登录回调 */
function login_handle(data,status){
	if(status==1){
		sUrl=D.U('group://public/index');
		setTimeout("window.location=sUrl;",1000);
	}
}

/** 加入和退出小组 */
function joinGroup(gid){
	var sUrl=D.U('group://group/joingroup');
	Dyhb.AjaxSend(sUrl,'gid='+gid,'',function(data,status){
		if(status==1){
			$('#joingroup_'+gid).html(D.L('加入成功','Js/Group_Js'));
			
			window.location.reload();
		}
	});
}

function leaveGroup(gid){
	var sUrl=D.U('group://group/leavegroup');
	Dyhb.AjaxSend(sUrl,'gid='+gid,'',function(data,status){
		if(status==1){
			$('#leavegroup_'+gid).html(D.L('退出成功','Js/Group_Js'));

			window.location.reload();
		}
	});
}
