<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   群组Api接口控制器($)*/

!defined('DYHB_PATH') && exit;

class ApiController extends InitController{

	public function new_topic(){
		Core_Extend::doControllerAction('Api@Newtopic','index');
	}

	public function hot_topic(){
		Core_Extend::doControllerAction('Api@Hottopic','index');
	}

	public function recommend_group(){
		Core_Extend::doControllerAction('Api@Recommendgroup','index');
	}

}
