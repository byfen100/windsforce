<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   弹出播放($)*/

!defined('DYHB_PATH') && exit;

class PlayoutController extends Controller{

	public function index(){
		$sFlashpath=trim(G::getGpc('url','G'));
		
		if(empty($sFlashpath)){
			Dyhb::E('没有指定播放的flash');
		}
		
		$this->assign('sFlashpath',$sFlashpath);
		
		$this->display('attachment+playout');
	}
}