<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   添加标签($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	public function index(){
		$nUserId=$GLOBALS['___login___']['user_id'];
		$sHometagName=G::getGpc('hometag_name');

		$oTag=Dyhb::instance('HometagModel');
		$oTag->addTag($nUserId,$sHometagName);

		if($oTag->isError()){
			$this->E($oTag->getErrorMessage());
		}

		$this->S(Dyhb::L('添加用户标签成功','Controller/Spaceadmin'));
	}

}
