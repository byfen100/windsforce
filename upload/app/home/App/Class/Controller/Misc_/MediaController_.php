<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   媒体控制器($)*/

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
