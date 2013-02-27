<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   全屏播放($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class FullplayframeController extends Controller{

	public function index(){
		$sFlashpath=trim(G::getGpc('url','G'));
	
		if(empty($sFlashpath)){
			$this->E(Dyhb::L('没有指定播放的flash','Controller/Attachment'));
		}
	
		$this->assign('sFlashpath',$sFlashpath);
	
		$this->display('attachment+fullplayframe');
	}
}
