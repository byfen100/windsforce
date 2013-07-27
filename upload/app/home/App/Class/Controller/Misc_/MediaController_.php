<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   媒体控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MediaController extends Controller{

	public function index(){
		$sFunction=trim(G::getGpc('function','G'));
		$this->assign('sFunction',$sFunction);

		$this->display('misc+music');
	}

	public function video(){
		$sFunction=trim(G::getGpc('function','G'));
		$this->assign('sFunction',$sFunction);

		$this->display('misc+video');
	}

}
