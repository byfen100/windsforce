<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公用控制器($)*/

!defined('DYHB_PATH') && exit;

class InitController extends GlobalinitController{

	public function init__(){
		parent::init__();

		// 底部文章缓存
		Core_Extend::loadCache('shop_article');
	}

}
