/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   WindsForce 新鲜事评论AJAX提交($)*/

/** 前端验证 */
function commentCheckForm(comment_content,customContent){
	var comment_name=$.trim($('#homefreshcomment_name').val());
	var comment_email=$.trim($("#homefreshcomment_email").val());
	var comment_url=$.trim($("#homefreshcomment_url").val());
	if(customContent==1){
		comment_content=$.trim($("#homefreshcomment_content").val());
	}

	if(comment_name==""){
		windsforceAlert(D.L('评论名字不能为空','Js/Comment_Js'),D.L('评论发生错误','Js/Comment_Js'),3);
		return false;
	}

	if(comment_name.length>25){
		windsforceAlert(D.L('评论名字长度只能小于等于25个字符串','Js/Comment_Js'),D.L('评论发生错误','Js/Comment_Js'),3);
		return false;
	}

	if(comment_email!='' && !checkEmail(comment_email)){
		windsforceAlert(D.L('评论E-mail 格式错误','Js/Comment_Js'),D.L('评论发生错误','Js/Comment_Js'),3);
		return false;
	}

	if(comment_url!='' && !checkUrl(comment_url)){
		windsforceAlert(D.L('评论Url 格式错误','Js/Comment_Js'),D.L('评论发生错误','Js/Comment_Js'),3);
		return false;
	}

	if(comment_content == ""){
		windsforceAlert(D.L('评论内容不能为空','Js/Comment_Js'),D.L('评论发生错误','Js/Comment_Js'),3);
		return false;
	}

	return true;
}

/** 评论浏览页面&提交评论 */
function commentSubmit(){
	var bResult=commentCheckForm();
	if(bResult===false){
		return false;
	}

	$("#comment-submit").val(D.L('正在提交评论','Js/Comment_Js'));
	$("#comment-submit").attr("disabled", "disabled");
	Dyhb.AjaxSubmit('homefresh-commentform',D.U('home://ucenter/add_homefreshcomment'),'',commentComplete);
}

function commentComplete(data,status){
	$("#comment-submit").attr("disabled", false);
	$("#comment-submit").val(D.L('提交评论','Js/Comment_Js'));
	if(status==1){
		window.location.href=data.jumpurl;
	}
}

/** 我也来说一句&提交评论 */
function goodnum(id){
	Dyhb.AjaxSend(D.U('home://ucenter/update_homefreshgoodnum'),'ajax=1&id='+id,'',function(data,status,info){
		if(status==1){
			$('#goodnum_'+id).text(data.num);
			$('#goodnum_'+id).addClass('goodnum_click');
		}
	});
}

var nCurrentHomefreshid='';
var nCurrentHomefreshcommentid='';
var nCurrentHomefreshchildcommentid='';
var bCurrentHomefreshcommentopen=false;
var sCommentSeccode='';

function commentForm(id){
	if($("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val()){
		windsforceConfirm(D.L('你确定要放弃正在编辑的评论?','Js/Comment_Js'),function(){
			$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
			$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
			$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val('');
			$("#homefreshcommentform_"+nCurrentHomefreshid).html('');
			homefreshcommentSwitchform(id);
			return true;
		},function(){
			$(".homefreshcommentform_area").focus();
			return true;
		});

		return false;
	}

	if($("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val()){
		windsforceConfirm(D.L('你确定要放弃正在编辑的评论?','Js/Comment_Js'),function(){
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).css("display","none");
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val('');
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).html('');
			bCurrentHomefreshcommentopen=false;
			homefreshcommentSwitchform(id);
			return true;
		},function(){
			$(".homefreshcommentform_area").focus();
			return true;
		});

		return false;
	}

	if(bCurrentHomefreshcommentopen===true){
		homefreshchildcommentCancel();
	}

	$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
	$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
	$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val('');
	$("#homefreshcommentform_"+nCurrentHomefreshid).html('');
	homefreshcommentSwitchform(id);

	return true;
}

function setSeccode(sValue){
	sCommentSeccode=sValue;
}

function homefreshcommentSwitchform(id){
	$("#homefreshcommentdiv_"+id).css("display","none");
	$("#homefreshcommentform_"+id).css("display","block");

	$("#homefreshcommentform_"+id).html($("#homefreshcommentform_box").html());
		
	$('.homefreshcommentform_area').autoResize({
		onResize:function(){
			$(this).css({opacity:0.8});
		},
		animateCallback:function(){
			$(this).css({opacity:1});
		},
		animateDuration:300,
		extraSpace:0,
		min:'80px'
	});

	$(".homefreshcommentform_area").focus();

	nCurrentHomefreshid=id;
}

function homefreshcommentCancel(){
	if(bCurrentHomefreshcommentopen===true){
		homefreshchildcommentCancel();
		return false;
	}
	
	if($("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val()){
		windsforceConfirm(D.L('你确定要放弃正在编辑的评论?','Js/Comment_Js'),function(){
			$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
			$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
			$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val('');
			return true;
		},function(){
			$(".homefreshcommentform_area").focus();
			return true;
		});

		return false;
	}

	$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
	$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
	$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val('');

	return true;
}

function homefreshcommentSubmit(){
	var comment_name=$.trim($('#homefreshcomment_name').val());
	var comment_email=$.trim($("#homefreshcomment_email").val());
	var comment_url=$.trim($("#homefreshcomment_url").val());
	var comment_parentid=$.trim($("#homefreshcomment_parentid").val());
	var comment_isreplymail=$("#homefreshcomment_isreplymail:checked").val();

	if(typeof(comment_isreplymail)!="undefined" || comment_isreplymail!=null){
		comment_isreplymail=0;
	}
	
	if(comment_parentid>0){
		var value=$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val();
	}else{
		var value=$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val();
	}
	
	var bResult=commentCheckForm(value);
	if(bResult===false){
		return false;
	}

	var sUrlParameter="ajax=1&quick=1&homefreshcomment_content="+encodeURIComponent(value)+
		"&homefresh_id="+nCurrentHomefreshid+'&homefreshcomment_name='+encodeURIComponent(comment_name)+
		'&homefreshcomment_email='+encodeURIComponent(comment_email)+'&homefreshcomment_url='+
		encodeURIComponent(comment_url)+'&homefreshcomment_parentid='+comment_parentid+'homefreshcomment_isreplaymail='+comment_isreplymail+'&seccode='+sCommentSeccode;

	Dyhb.AjaxSend(D.U('home://ucenter/add_homefreshcomment'),sUrlParameter,'',function(data,status){
		if(status==1){
			var sCommentReply='<div class="homefreshcomment_item">'+
					'<div class="homefreshcomment_avatar">'+
						'<img src="'+data.avatar+'" class="thumbnail"/>'+
					'</div>'+
					'<div class="homefreshcomment_content">'+
						'<a href="'+data.url+'">'+data.comment_name+'</a>:'+data.homefreshcomment_content+'<br/>'+
						'<em class="homefreshcomment_date">'+data.create_dateline+'</em>'+
						'<span class="pipe">|</span>';

			sCommentReply+='<a href="'+data.viewurl+'">'+D.L('查看','Js/Comment_Js')+'</a>&nbsp;';
			
			if(comment_parentid>0){
				sCommentReply+='<a href="javascript:void(0);" onclick="childcommentForm(\''+data.homefresh_id+'\',\''+comment_parentid+'\',\'1\',\''+data.comment_name+'\');">'+D.L('回复','Js/Comment_Js')+'</a>';
			}else{
				sCommentReply+='<a href="javascript:void(0);" onclick="childcommentForm(\''+data.homefresh_id+'\',\''+data.homefreshcomment_id+'\');">'+D.L('回复','Js/Comment_Js')+'</a>'+
					'<div id="homefreshchildcommentlist_'+data.homefreshcomment_id+'" class="homefreshchildcommentlist_box">'+
					'</div>'+
					'<div id="homefreshchildcommentform_'+data.homefreshcomment_id+'" class="homefreshcomment_form">'+
					'</div>';
			}
			
			sCommentReply+='</div>'+
				'</div>'+
				'<div class="clear"></div>';
			
			if(comment_parentid>0){
				$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).css("display","none");
				$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val('');
				$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).html('');

				$("#homefreshchildcommentlist_"+nCurrentHomefreshcommentid).append(sCommentReply);

				bCurrentHomefreshcommentopen=false;
			}else{
				$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
				$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
				$("#homefreshcommentform_"+nCurrentHomefreshid).html("");

				$("#homefreshcommentlist_"+nCurrentHomefreshid).append(sCommentReply);
			}

			$("#homefreshcomment_"+nCurrentHomefreshid).html(data.num);
		}
	});
}

/** 子评论提交 */
function childcommentForm(id,commentid,childComment,username,childcommentid){
	if(childcommentid==nCurrentHomefreshchildcommentid && commentid==nCurrentHomefreshcommentid && bCurrentHomefreshcommentopen===true){
		homefreshchildcommentCancel();
		return false;
	}

	if($("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val()){
		windsforceConfirm(D.L('你确定要放弃正在编辑的评论?','Js/Comment_Js'),function(){
			$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
			$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
			$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val('');
			$("#homefreshcommentform_"+nCurrentHomefreshid).html('');
			homefreshchildcommentSwitchform(id,commentid,username,childcommentid);
			return true;
		},function(){
			$(".homefreshcommentform_area").focus();
			return true;
		});

		return false;
	}
	
	if($("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val()){
		windsforceConfirm(D.L('你确定要放弃正在编辑的评论?','Js/Comment_Js'),function(){
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).css("display","none");
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val('');
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).html('');
			homefreshchildcommentSwitchform(id,commentid,username,childcommentid);
			return true;
		},function(){
			$(".homefreshcommentform_area").focus();
			return true;
		});

		return false;
	}

	$("#homefreshcommentdiv_"+nCurrentHomefreshid).css("display","block");
	$("#homefreshcommentform_"+nCurrentHomefreshid).css("display","none");
	$("#homefreshcommentform_"+nCurrentHomefreshid+' .homefreshcommentform_area').val('');
	$("#homefreshcommentform_"+nCurrentHomefreshid).html('');
	
	$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).css("display","none");
	$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val('');
	$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).html('');
	
	homefreshchildcommentSwitchform(id,commentid,username,childcommentid);

	return true;
}

function childcommentAt(commentid,username){
	$("#homefreshchildcommentform_"+commentid+' .homefreshcommentform_area').insertAtCaret('@'+username+' ');
}

function homefreshchildcommentSwitchform(id,commentid,username,childcommentid){
	$("#homefreshcommentform_box").css("display","none");

	$("#homefreshchildcommentform_"+commentid).css("display","block");
	$("#homefreshchildcommentform_"+commentid).html($("#homefreshcommentform_box").html());
		
	$('.homefreshcommentform_area').autoResize({
		onResize:function(){
			$(this).css({opacity:0.8});
		},
		animateCallback:function(){
			$(this).css({opacity:1});
		},
		animateDuration:300,
		extraSpace:0,
		min:'80px'
	});

	$(".homefreshcommentform_area").focus();

	nCurrentHomefreshid=id;
	nCurrentHomefreshcommentid=commentid;
	nCurrentHomefreshchildcommentid=childcommentid;

	bCurrentHomefreshcommentopen=true;

	$('#homefreshcomment_parentid').val(commentid);
	
	if(username){
		childcommentAt(commentid,username);
	}
}

function homefreshchildcommentCancel(){
	if($('#homefreshchildcommentform_'+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val()){
		windsforceConfirm(D.L('你确定要放弃正在编辑的评论?','Js/Comment_Js'),function(){
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).css("display","none");
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val('');
			$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).html('');
			if(nHomefreshviewcomment==1){
				$("#homefreshcommentform_box").css("display","block");
			}
			bCurrentHomefreshcommentopen=false;
			return true;
		},function(){
			$(".homefreshcommentform_area").focus();
			return true;
		});

		return false;
	}
	
	$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).css("display","none");
	$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid+' .homefreshcommentform_area').val('');
	$("#homefreshchildcommentform_"+nCurrentHomefreshcommentid).html('');

	if(nHomefreshviewcomment==1){
		$("#homefreshcommentform_box").css("display","block");
	}
	
	bCurrentHomefreshcommentopen=false;

	return true;
}

function homefreshcommentAudit(nCommentid,nStatus){
	Dyhb.AjaxSend(D.U('home://ucenter/audit_homefreshcomment?id='+nCommentid+'&status='+nStatus),'ajax=1','',function(data,status){
		if(status==1){
			window.location.reload();
		}
	});
}

/** 子评论分页 */
$oGlobalBody=(window.opera)?(document.compatMode=="CSS1Compat"?$('html'):$('body')):$('html,body');

function homefreshcommentAjaxpage(nHomefreshcomentId){
	$('#pagination_'+nHomefreshcomentId+' a').live('click',function(e){
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: $(this).attr('href'),
			beforeSend: function(){
				$('#pagination_'+nHomefreshcomentId).remove();
				$('#homefreshchildcommentlist_'+nHomefreshcomentId).remove();
				$('#loadinghomefreshchildcomments_'+nHomefreshcomentId).slideDown();
				$oGlobalBody.animate({scrollTop: $('#homefreshchildcommentlistheader_'+nHomefreshcomentId).offset().top-65},800);
			},
			dataType: "html",
			success: function(out){
				oResult=$(out).find('#homefreshchildcommentlist_'+nHomefreshcomentId);
				sNextlink = $(out).find('#pagination_'+nHomefreshcomentId);
				$('#loadinghomefreshchildcomments_'+nHomefreshcomentId).slideUp('fast');
				$('#loadinghomefreshchildcomments_'+nHomefreshcomentId).after(oResult.fadeIn(500));
				$('#pagination_'+nHomefreshcomentId).html(sNextlink);
			}
		});
	});	
}
