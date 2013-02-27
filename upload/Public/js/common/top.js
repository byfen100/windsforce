/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce 回到顶部($Liu.XiangMin)*/

$(function(){
	var sBackToTopTxt=D.L('返回顶部','__COMMON_LANG__@Js/Common_Js'),oBackToTopEle=$('<div class="back-to-top"></div>').appendTo($("body"))
		.text(sBackToTopTxt).attr("title",sBackToTopTxt).click(function(){
			$("html, body").animate({ scrollTop: 0 },120);
	}),sBackToTopFun=function(){
		var st=$(document).scrollTop(),winh=$(window).height();
		(st>0)?oBackToTopEle.show():oBackToTopEle.hide();
			
		/* IE6下的定位 */
		if(!window.XMLHttpRequest){
			oBackToTopEle.css("top",st+winh-166);
		}
	};

	$(window).bind("scroll",sBackToTopFun);
	$(function(){
		sBackToTopFun();
	});
});
