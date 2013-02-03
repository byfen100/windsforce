<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   个人空间显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SpaceController extends InitController{

	public function index(){
		$sType=trim(G::getGpc('type','G'));
		$this->assign('sType',$sType);

		if(empty($sType)){
			$sId=trim(G::getGpc('id','G'));

			if(!in_array($sId,array('ratings'))){
				Core_Extend::doControllerAction('Space@Base','index');
			}else{
				$this->{$sId}();
			}
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
	
	public function ratings(){
		Core_Extend::doControllerAction('Space@Ratings','index');
	}

}
