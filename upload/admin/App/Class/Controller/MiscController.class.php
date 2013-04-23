<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   后台杂项控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MiscController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$sFiles=trim(G::getGpc('file','G'));
		$sType=trim(G::getGpc('type','G'));

		Admintheme_Extend::pathContent($sFiles,$sType);
	}

}
