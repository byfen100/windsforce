<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   群组Api接口控制器($Liu.XiangMin)*/

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
