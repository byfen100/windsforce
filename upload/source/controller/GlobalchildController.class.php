<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台公用子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GlobalchildController extends Controller{

	public $_oParentcontroller=null;
	
	public function __construct($oParentcontroller=null){
		parent::__construct();
		
		$this->_oParentcontroller=$oParentcontroller;
	}

}
