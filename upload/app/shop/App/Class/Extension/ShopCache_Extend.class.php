<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城缓存文件($)*/

!defined('DYHB_PATH') && exit;

class ShopCache_Extend{

	public static function updateCacheOption(){
		$arrData=array();

		$arrOptionData=ShopoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['shopoption_name']]=$arrValue['shopoption_value'];
			}
		}

		Core_Extend::saveSyscache('shop_option',$arrData);
	}

	public static function updateCacheArticle(){
		$arrData=array();
		
		$arrShopariclecategory=ShoparticlecategoryModel::F('shoparticlecategory_type=9')->getAll();

		foreach($arrShopariclecategory as $nKey=>$oShoparictecategory){

			$arrShoparicle=ShoparticleModel::F('shoparticlecategory_id=? AND shoparticle_status=1',$oShoparictecategory->shoparticlecategory_id)->getAll();

			if($oShoparictecategory->shoparticlecategory_id==1){

					$arrData['1']=self::buy($arrShoparicle);
					$arrData['1']['0']=$oShoparictecategory->shoparticlecategory_name;

			}else if($oShoparictecategory->shoparticlecategory_id==2){
					
					$arrData['2']=self::map($arrShoparicle);
					$arrData['2']['0']=$oShoparictecategory->shoparticlecategory_name;
			
			}else if($oShoparictecategory->shoparticlecategory_id==3){
					
					$arrData['3']=self::special($arrShoparicle);
					$arrData['3']['0']=$oShoparictecategory->shoparticlecategory_name;

			}else if($oShoparictecategory->shoparticlecategory_id==4){
					
					$arrData['4']=self::aboutus($arrShoparicle);
					$arrData['4']['0']=$oShoparictecategory->shoparticlecategory_name;
			}
			
		}
		Core_Extend::saveSyscache('shop_article',$arrData);
	}
	
	public static function buy($arrShoparicle){
		$arrData=array();
		foreach($arrShoparicle as $key=>$oShoparticle){
				if($oShoparticle->shoparticle_title=='意见反馈'){
					$arrData[$key+1][]=Dyhb::U('shop://shoparticle/feedback?tid='.$oShoparticle->shoparticle_id);
				}else if($oShoparticle->shoparticle_title!='意见反馈'){
					$arrData[$key+1][]=Dyhb::U('shop://shoparticle/index?tid='.$oShoparticle->shoparticle_id);
				}
				$arrData[$key+1][]=$oShoparticle->shoparticle_title;
			}
		return $arrData;
	}

	public static function map($arrShoparicle){
		$arrData=array();
	}

	public static function special($arrShoparicle){
		$arrData=array();
		foreach($arrShoparicle as $key=>$oShoparticle){
				$arrData[$key+1][]=Dyhb::U('shop://shoparticle/index?tid='.$oShoparticle->shoparticle_id);
				$arrData[$key+1][]=$oShoparticle->shoparticle_title;
			}
		return $arrData;
	}
	public static function aboutus($arrShoparicle){
		$arrData=array();
		foreach($arrShoparicle as $key=>$oShoparticle){
				$arrData[$key+1][]=Dyhb::U('shop://shoparticle/index?tid='.$oShoparticle->shoparticle_id);
				$arrData[$key+1][]=$oShoparticle->shoparticle_title;
			}
		return $arrData;
	}
}
