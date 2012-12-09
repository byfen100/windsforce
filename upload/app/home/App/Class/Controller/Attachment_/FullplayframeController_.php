<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   全屏播放($)*/

!defined('DYHB_PATH') && exit;

class FullplayframeController extends Controller{

	public function index(){
		$sFlashpath=trim(G::getGpc('url','G'));
	
		if(empty($sFlashpath)){
			$this->E('没有指定播放的flash');
		}
	
		$this->assign('sFlashpath',$sFlashpath);
	
		$this->display('attachment+fullplayframe');
	}
}
