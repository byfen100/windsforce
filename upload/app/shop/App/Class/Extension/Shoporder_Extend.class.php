<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城订单函数库文件($)*/

!defined('DYHB_PATH') && exit;

class Shoporder_Extend{

	public static function getShopaddressto($arrShopaddressData){
		if(!empty($arrShopaddressData['shopaddress_handaddress'])){
			$sShopaddressto=$arrShopaddressData['shopaddress_handaddress'];
		}else{
			$sShopaddressto=$arrShopaddressData['shopaddress_province'].' '.
				(!empty($arrShopaddressData['shopaddress_city'])?$arrShopaddressData['shopaddress_city'].' ':'').
				(!empty($arrShopaddressData['shopaddress_district'])?$arrShopaddressData['shopaddress_district'].' ':'').
				(!empty($arrShopaddressData['shopaddress_community'])?$arrShopaddressData['shopaddress_community'].' ':'');
		}

		return $sShopaddressto;
	}

	public static function getShopaddressdistrict($sName,$arrCookiedata){
		if(isset($_POST['shopaddress'.$sName])){
			$sDistrict=trim(G::getGpc('shopaddress'.$sName,'P'));
		}else{
			$sDistrict=!empty($arrCookiedata['shopaddress_'.$sName])?$arrCookiedata['shopaddress_'.$sName]:'';
		}

		return $sDistrict;
	}

	public static function getShopaddressdistrictTwo($arrShopaddressData){
		$arrDistrictdata=array();
		foreach(array('province','city','district','community') as $sDistrict){
			if(!empty($arrShopaddressData['shopaddress_'.$sDistrict])){
				$arrDistrictdata['shopaddress'.$sDistrict]=$arrShopaddressData['shopaddress_'.$sDistrict];
			}
		}

		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		$sDistrict=Profile_Extend::getDistrict($arrDistrictdata,'shopaddress',true,'',false);

		return $sDistrict;
	}

	public static function getShoppaymentreturnhtml($oShoppayment){
		$sShoppaymentcode=strtolower($oShoppayment['shoppayment_code']);

		require(APP_PATH.'/App/Class/Extension/Payment/'.$sShoppaymentcode.'/'.ucfirst($sShoppaymentcode).'_.php');
			
		$oShoppaymentapi=Dyhb::instance(ucfirst($sShoppaymentcode).'_Payment');
		$sShoppaymentReturnhtml=$oShoppaymentapi->paymentCode($oShoppayment,$oShoporderinfo);

		return $sShoppaymentReturnhtml;
	}

	public static function getShopcartdata(){
		$oCart=Dyhb::instance('Cart');
		$arrCarts=$oCart->view();

		$arrShopcartsData=array();
		if(isset($arrCarts['goods_id']) && is_array($arrCarts['goods_id'])){
			foreach($arrCarts['goods_id'] as $nShopgoodsid){
				$oShopgoods=ShopgoodsModel::F('shopgoods_id=? AND shopgoods_isonsale=1',$nShopgoodsid)->getOne();
				if(empty($oShopgoods['shopgoods_id'])){
					// 商品不存在或者下架等等 && 删除购物车中的商品
					$oCart->edit($nShopgoodsid,0,3);
				}else{
					if($oShopgoods['shopgoods_thumb']){
						$arrShopcartsData[$nShopgoodsid]['goods_img']=Shopgoods_Extend::getShopgoodspath($oShopgoods['shopgoods_thumb']);
					}else{
						$arrShopcartsData[$nShopgoodsid]['goods_img']=__APPPUB__.'/Images/shopgoods/default_1.jpg';
					}

					$arrShopcartsData[$nShopgoodsid]['goods_id']=$nShopgoodsid;
					$arrShopcartsData[$nShopgoodsid]['goods_name']=$arrCarts['goods_name'][$nShopgoodsid];
					$arrShopcartsData[$nShopgoodsid]['goods_price']=$arrCarts['goods_price'][$nShopgoodsid];
					$arrShopcartsData[$nShopgoodsid]['goods_count']=$arrCarts['goods_count'][$nShopgoodsid];
				}
			}
		}

		// 计算商品总价格
		$arrCarts=$oCart->view();
		$arrShopcartsTotal=$oCart->countPrice();

		return array($arrShopcartsData,$arrShopcartsTotal);
	}

	public static function getShoporderinfosn(){
		return date('Ymd',CURRENT_TIMESTAMP).G::randString(10);
	}

}
