<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台公用子控制器($)*/

!defined('DYHB_PATH') && exit;

class GlobalchildController extends Controller{

	public $_oParentcontroller=null;
	
	public function __construct($oParentcontroller=null){
		parent::__construct();
		
		$this->_oParentcontroller=$oParentcontroller;
	}

}
