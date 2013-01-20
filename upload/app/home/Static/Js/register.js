/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce 用户注册验证($Liu.XiangMin)*/

/* 注册数据处理 */
function registerSubmit(){
	$("#register_submit").attr("disabled", "disabled");
	$("#register_submit").val('add...');
	Dyhb.AjaxSubmit('register_form',Dyhb.U('home://public/register_user'),'result',registerComplete);
};

function registerComplete(data,status){
	$("#register_submit").attr("disabled", false);
	$("#register_submit").val(D.L('注册','__COMMON_LANG__@Js/Register_Js'));
	if(status==1){
		sUrl=data.jumpurl?data.jumpurl:Dyhb.U('home://public/login');
		setTimeout(function(){window.location.href=sUrl;},1000);
	}
};

/* 注册验证 */
$(document).ready(function(){
	var validator=$("#register_form").validate({
		rules: {
			user_name: {
				required: true,
				maxlength: 50, 
				remote: Dyhb.U('home://public/check_user')
			},
			user_nikename: {
				maxlength: 50
			},
			user_password: {
				required: true,
				minlength: 6,
				maxlength: 32
			},
			user_password_confirm: {
				required: true,
				minlength: 6,
				maxlength: 32,
				equalTo: "#user_password"
			},
			user_email: {
				required: true,
				email: true,
				maxlength: 150,
				remote: Dyhb.U('home://public/check_email')
			},
			user_terms: "required"
		},
		messages: {
			user_name: {
				required: D.L("请输入你的注册用户名",'__COMMON_LANG__@Js/Register_Js'),
				maxlength: jQuery.format(D.L("注册用户名最多 {0} 字符",'__COMMON_LANG__@Js/Register_Js')),
				remote: jQuery.format(D.L("{0} 该用户已经被占用了",'__COMMON_LANG__@Js/Register_Js'))
			},
			user_nikename: {
				maxlength: jQuery.format(D.L("用户昵称最多 {0} 字符",'__COMMON_LANG__@Js/Register_Js'))
			},
			user_password: {
				required: D.L("请输入你的用户密码",'__COMMON_LANG__@Js/Register_Js'),
				minlength: jQuery.format(D.L("用户密码最少 {0} 字符",'__COMMON_LANG__@Js/Register_Js')),
				maxlength: jQuery.format(D.L("用户密码最多 {0} 字符",'__COMMON_LANG__@Js/Register_Js'))
			},
			user_password_confirm: {
				required: D.L("请输入你的确认密码",'__COMMON_LANG__@Js/Register_Js'),
				minlength: jQuery.format(D.L("确认密码最少 {0} 字符",'__COMMON_LANG__@Js/Register_Js')),
				maxlength: jQuery.format(D.L("确认密码最多 {0} 字符",'__COMMON_LANG__@Js/Register_Js')),
				equalTo: D.L("两次填写的密码不一致",'__COMMON_LANG__@Js/Register_Js')
			},
			user_email: {
				required: D.L("E-mail 地址不能为空",'__COMMON_LANG__@Js/Register_Js'),
				email: D.L("请输入一个正确的E-mail 地址",'__COMMON_LANG__@Js/Register_Js'),
				maxlength: jQuery.format(D.L("E-mail 地址最多 {0} 字符",'__COMMON_LANG__@Js/Register_Js')),
				remote: jQuery.format(D.L("{0} 该E-mail 地址已经被占用",'__COMMON_LANG__@Js/Register_Js'))
			},
			user_terms: " "
		},
		errorPlacement: function(error,element){
			error.appendTo(element.next());
		},
		submitHandler: function(){
			registerSubmit();
		},
		success: function(label){
			label.html("&nbsp;").addClass("checked");
		}
	});
});
