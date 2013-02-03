<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

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
