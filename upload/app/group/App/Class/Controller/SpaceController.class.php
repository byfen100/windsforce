<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组个人空间显示($Liu.XiangMin)*/

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

	public function comment(){
		Core_Extend::doControllerAction('Space@Comment','index');
	}

	public function group(){
		Core_Extend::doControllerAction('Space@Group','index');
	}

}
