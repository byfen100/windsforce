<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台首页显示($)*/

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

		// 取得最新用户
		$this->get_newuser_();

		// 取得最新帮助
		$this->get_newhelp_();

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

	protected function get_newuser_(){
		$nHomenewusernum=intval($GLOBALS['_option_']['home_newuser_num']);
		if($nHomenewusernum<1){
			$nHomenewusernum=1;
		}
		
		$arrNewusers=UserModel::F()->where('user_status=?',1)->order('create_dateline DESC')->limit(0,$nHomenewusernum)->getAll();

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

}
