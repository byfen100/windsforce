<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城商品函数库文件($)*/

!defined('DYHB_PATH') && exit;

class Shopgoods_Extend{

	public static function getShopgoodspath($sImgpath){
		return __ROOT__.'/data/upload/app/shop/shopgoods/'.$sImgpath;
	}

	static public function getAttributevalue($arrShopattributevalueData,$nShopattributeid,$nShopattributeinputtype){
		$sShopattributevalue='';

		if(isset($arrShopattributevalueData[$nShopattributeid])){
			$oShopattributevalue=$arrShopattributevalueData[$nShopattributeid];
			$sShopattributevalue=$oShopattributevalue->shopattribute_value;
		}
		
		if($nShopattributeinputtype=='number'){
			$sShopattributevalue=intval($sShopattributevalue);
		}
		
		if($nShopattributeinputtype=='selects'){
			$sShopattributevalue=unserialize($sShopattributevalue);
		}

		return $sShopattributevalue;
	}


	static public function parserSelect($sValue){
		$arrData=array();
		
		$arrValue=explode("\n",$sValue);
		if(is_array($arrValue)){
			foreach($arrValue as $sKey=>$sOption){
				$sOption=trim($sOption);
			
				if(strpos($sOption,'=')===FALSE){
					$sKey=$sOption;
				}else{
					$arrTemp=explode('=',$sOption);
					$sKey=trim($arrTemp[0]);
					$sOption=trim($arrTemp[1]);
				}
				
				$sKey=htmlspecialchars($sKey);
				
				$arrData[$sKey]=$sOption;
			}
		}
		
		return $arrData;
	}

	static public function getShopattributevalue($oShopgoods){
		if(empty($oShopgoods['shopgoods_id'])){
			return false;
		}else{
			if($oShopgoods['shopgoodstype_id']<1){
				return false;
			}

			$arrDatareturn=$arrShopattributevalueData=array();

			$arrShopattributes=ShopattributeModel::F('shopgoodstype_id=?',$oShopgoods['shopgoodstype_id'])->getAll();
			if(is_array($arrShopattributes)){
				$arrShopattributevalues=ShopattributevalueModel::F('shopgoods_id=?',$oShopgoods['shopgoods_id'])->getAll();
				if(is_array($arrShopattributevalues)){
					foreach($arrShopattributevalues as $oShopattributevalue){
						$arrShopattributevalueData[$oShopattributevalue['shopattribute_id']]=$oShopattributevalue;
					}
				}

				foreach($arrShopattributes as $oShopattribute){
					$sShopattributevalue=Shopgoods_Extend::getAttributevalue($arrShopattributevalueData,$oShopattribute['shopattribute_id'],$oShopattribute['shopattribute_inputtype']);
					$arrDatareturn[htmlspecialchars($oShopattribute['shopattribute_name'])]=$sShopattributevalue;
				}
			}

			return $arrDatareturn;
		}
	}

}
