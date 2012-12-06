<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台站点信息($)*/

!defined('DYHB_PATH') && exit;

class HomesiteController extends InitController{

	public function site(){
		$sName=G::text(G::getGpc('id','G'));
		
		$this->site_($sName);
	}
	
	public function aboutus(){
		$this->site_('aboutus');
	}

	public function aboutus_title_(){
		return '关于我们';
	}

	public function aboutus_keywords_(){
		return $this->aboutus_title_();
	}

	public function aboutus_description_(){
		return $this->aboutus_title_();
	}

	public function contactus(){
		$this->site_('contactus');
	}

	public function contactus_title_(){
		return '联系我们';
	}

	public function contactus_keywords_(){
		return $this->contactus_title_();
	}

	public function contactus_description_(){
		return $this->contactus_title_();
	}

	public function agreement(){
		$this->site_('agreement');
	}

	public function agreement_title_(){
		return '用户协议';
	}

	public function agreement_keywords_(){
		return $this->agreement_title_();
	}

	public function agreement_description_(){
		return $this->agreement_title_();
	}

	public function privacy(){
		$this->site_('privacy');
	}

	public function privacy_title_(){
		return '隐私声明';
	}

	public function privacy_keywords_(){
		return $this->privacy_title_();
	}

	public function privacy_description_(){
		return $this->privacy_title_();
	}

	protected function site_($sName){
		$oHomesite=HomesiteModel::F("homesite_name='{$sName}'")->getOne();
		$this->assign('sContent',Core_Extend::replaceSiteVar($oHomesite['homesite_content']));
		$this->assign('sTitle',$oHomesite['homesite_nikename']);
		
		$arrHomesites=HomesiteModel::F()->getAll();
		$this->assign('arrHomesites',$arrHomesites);

		$this->display('homesite+index');
	}

}
