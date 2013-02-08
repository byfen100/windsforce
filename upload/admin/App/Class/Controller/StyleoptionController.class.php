<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   界面设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StyleoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$sLogo=$GLOBALS['_option_']['site_logo']?$GLOBALS['_option_']['site_logo']:__PUBLIC__.'/images/common/logo.png';

		$this->assign('arrOptions',$arrOptionData);
		$this->assign('sLogo',$sLogo);

		$this->display();
	}

}
