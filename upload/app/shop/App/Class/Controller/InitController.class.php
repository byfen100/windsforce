<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   公用控制器($)*/

!defined('DYHB_PATH') && exit;

class InitController extends GlobalinitController{

	public function init__(){
		parent::init__();
		ShopCache_Extend::updateCacheArticle();
		Core_Extend::loadCache('shop_article');
	}

}
