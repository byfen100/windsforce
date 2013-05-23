<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入附件扩展函数 */
if(!Dyhb::classExists('Attachment_Extend')){
	require_once(Core_Extend::includeFile('function/Attachment_Extend'));
}

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class IndexController extends Controller{

	public function index(){
		// 站点常用统计数据
		Core_Extend::loadCache('site');
		Core_Extend::loadCache('slide');
		Core_Extend::loadCache('link');
		Core_Extend::loadCache('sociatype');
		$sLogo=$GLOBALS['_option_']['site_logo']?$GLOBALS['_option_']['site_logo']:__PUBLIC__.'/images/common/logo.png';
		
		$this->assign('arrSite',$GLOBALS['_cache_']['site']);
		$this->assign('arrSlides',$GLOBALS['_cache_']['slide']);
		$this->assign('arrLinkDatas',$GLOBALS['_cache_']['link']);
		$this->assign('arrBindeds',$GLOBALS['_cache_']['sociatype']);
		$this->assign('sHomeDescription',Core_Extend::replaceSiteVar($GLOBALS['_option_']['home_description']));
		$this->assign('sLogo',$sLogo);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_login_status']);
		$this->assign('nRememberTime',$GLOBALS['_option_']['remember_time']);

		// 首页新鲜事
		$this->get_homefresh_();

		// 取得活跃会员
		$this->get_activeuser_();

		// 取得最新用户
		$this->get_newuser_();

		// 取得最新帮助
		$this->get_newhelp_();

		// 取得最新照片
		$this->get_newattachment_();

		// 取得在线用户数据
		if($GLOBALS['_option_']['online_on']==1 && $GLOBALS['_option_']['online_indexon']==1){
			$this->get_online_();
		}

		$this->display('public+index');
	}

	protected function get_homefresh_(){
		$nHomenewhomefreshnum=intval($GLOBALS['_option_']['home_newhomefresh_num']);
		if($nHomenewhomefreshnum<1){
			$nHomenewhomefreshnum=1;
		}
		
		$arrHomefreshs=HomefreshModel::F()->where('homefresh_status=?',1)->order('create_dateline DESC')->limit(0,$nHomenewhomefreshnum)->getAll();

		$sGoodCookie=Dyhb::cookie('homefresh_goodnum');
		$arrGoodCookie=explode(',',$sGoodCookie);

		$this->assign('arrGoodCookie',$arrGoodCookie);
		$this->assign('arrHomefreshs',$arrHomefreshs);
	}

	protected function get_activeuser_(){
		$arrActiveusers=Home_Extend::getActiveuser();
		$this->assign('arrActiveusers',$arrActiveusers);
	}

	protected function get_newuser_(){
		$arrNewusers=Home_Extend::getNewuser();
		$this->assign('arrNewusers',$arrNewusers);
	}

	protected function get_newhelp_(){
		$nHomenewhelpnum=intval($GLOBALS['_option_']['home_newhelp_num']);
		if($nHomenewhelpnum<1){
			$nHomenewhelpnum=1;
		}

		$arrNewhelps=HomehelpModel::F()->where('homehelp_status=?',1)->order('create_dateline DESC')->limit(0,$nHomenewhelpnum)->getAll();

		$this->assign('arrNewhelps',$arrNewhelps);
	}

	protected function get_newattachment_(){
		$nHomenewattachmentnum=intval($GLOBALS['_option_']['home_newattachment_num']);
		if($nHomenewattachmentnum<1){
			$nHomenewattachmentnum=1;
		}

		$arrNewattachments=AttachmentModel::F()->where(array('attachment_extension'=>array('in','gif,jpeg,jpg,png,bmp')))->order('create_dateline DESC')->limit(0,$nHomenewattachmentnum)->getAll();

		$this->assign('arrNewattachments',$arrNewattachments);
	}

	protected function get_online_(){
		// 读取在线数据
		$arrOnlinedata=Home_Extend::getOnlinedata();

		$this->assign('arrOnlinedata',$arrOnlinedata);

		// 用户在线列表
		if($GLOBALS['_option_']['online_indexmost']>0){
			if($GLOBALS['_option_']['online_indexgueston']==1){
				$arrOnlines=OnlineModel::F('online_isstealth=?',0)->order('create_dateline DESC')->limit(0,$GLOBALS['_option_']['online_indexmost'])->getAll();
			}else{
				$arrOnlines=OnlineModel::F('user_id>? AND online_isstealth=0',0)->order('create_dateline DESC')->limit(0,$GLOBALS['_option_']['online_indexmost'])->getAll();
			}

			$this->assign('arrOnlines',$arrOnlines);
		}
	}

	public function get_attachmentspan($nI){
		$nSpan=$nHeight=0;
		
		switch($nI){
			case 1:
				$nSpan=8;
				$nHeight=180;
				break;
			case 2:
				$nSpan=4;
				$nHeight=270;
				break;
			case 3:
			case 4:
				$nSpan=2;
				$nHeight=120;
				break;
			case 0:
				$nSpan=4;
				$nHeight=120;
				break;
		}
		
		return array($nSpan,$nHeight);
	}

	public function index_title_(){
		if($GLOBALS['_commonConfig_']['DEFAULT_APP']!='home'){
			return Dyhb::L('个人空间','Controller/Public');
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
