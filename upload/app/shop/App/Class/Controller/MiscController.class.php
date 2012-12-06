<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城杂项控制器($)*/

!defined('DYHB_PATH') && exit;

class MiscController extends InitController{

	public function payment(){
		$sType=trim(G::getGpc('type','G'));

		if(empty($sType)){
			$this->E('你没有指定支付方式');
		}

		// xxx
	}

}
