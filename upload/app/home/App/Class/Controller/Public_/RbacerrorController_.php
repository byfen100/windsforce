<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   没有权限访问($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RbacerrorController extends Controller{

	public function index(){
		$sRbacerrorreferer=trim(Dyhb::cookie('_rbacerror_referer_'));

		if(empty($sRbacerrorreferer)){
			Dyhb::cookie('_rbacerror_referer_',null,-1);
			$this->U('home://public/index');
		}

		$this->assign('sRbacerrorreferer',$sRbacerrorreferer);
		
		$this->display('public+rbacerror');
	}

	public function rbacerror_title_(){
		return Dyhb::L('没有权限访问','Controller');
	}

	public function rbacerror_keywords_(){
		return $this->rbacerror_title_();
	}

	public function rbacerror_description_(){
		return $this->rbacerror_title_();
	}

}
