<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城文章缓存文件($)*/

!defined('DYHB_PATH') && exit;

class ShopArticleCache_Extend{

	public static function updateCacheArticle(){
		$arrData=array();

		$arrShoparticlecategory=ShoparticlecategoryModel::F('shoparticlecategory_type=9')->getAll();
		G::dump("ddddd");
		exit;
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['shopoption_name']]=$arrValue['shopoption_value'];
			}
		}

		Core_Extend::saveSyscache('shop_option',$arrData);
	}

}
