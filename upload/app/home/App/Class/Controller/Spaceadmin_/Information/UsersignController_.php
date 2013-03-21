<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户签名预览($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UsersignController extends Controller{

	public function index(){
		$sContent=trim(G::getGpc('content'));

		echo Core_Extend::usersign($sContent);
	}

}
