<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   个人空间显示($)*/

!defined('DYHB_PATH') && exit;

class SpaceController extends InitController{

	public function index(){
		$sType=trim(G::getGpc('type','G'));
		$this->assign('sType',$sType);

		if(empty($sType)){
			Core_Extend::doControllerAction('Space@Base','index');
		}else{
			if(method_exists($this,$sType)){
				$this->{$sType}();
			}else{
				Dyhb::E(sprintf('method %s not exists',$sType));
			}
		}
	}

	public function rating(){
		Core_Extend::doControllerAction('Space@Rating','index');
	}

	public function avatar(){
		Core_Extend::doControllerAction('Space@Avatar','index');
	}

	public function feed(){
		Core_Extend::doControllerAction('Space@Feed','index');
	}

}
