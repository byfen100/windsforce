<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城支付函数库文件($)*/

!defined('DYHB_PATH') && exit;

class Shoppayment_Extend{

	static public function getPaymentrespondurl($sCode){
		return $GLOBALS['_option_']['site_url'].'/index.php?app=shop&c=paymentrespond&a=index&type='.$sCode;
	}

	static public function getPayment($sCode){
		$oShoppayment=ShoppaymentModel::F('shoppayment_code=? AND shoppayment_status=1',$sCode)->getOne();
		$oShoppayment->shoppayment_config=unserialize($oShoppayment->shoppayment_config);

		return $oShoppayment;
	}

	static public function checkMoney($nPaylogid,$nMoney){
		$oShoppaylog=ShoppaylogModel::F('shoppaylog_id=?',$nLogid)->getOne();

		if($nMoney==$oShoppaylog['shoppaylog_orderamount']){
			return true;
		}else{
			return false;
		}
	}

	static public function getShippinglistData(){
		// 已安装的配送方式
		$arrShippinglist=array();

		$arrShippings=ShopshippingModel::F('shopshipping_status=?',1)->order('shopshipping_sort DESC')->getAll();
		if(is_array($arrShippings)){
			foreach($arrShippings as $oShipping){
				$arrShippinglist[$oShipping['shopshipping_code']]=$oShipping;
			}
		}
		
		// 读取配送方式数据
		$arrShippinglistData=array();

		$sShippingpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Shipping';
		$arrShippingdir=G::listDir($sShippingpath);
		if(is_array($arrShippingdir)){
			foreach($arrShippingdir as $sShippingdir){
				$sConfigfile=$sShippingpath.'/'.$sShippingdir.'/Config.php';

				if(!is_file($sConfigfile)){
					continue;
				}else{
					$sShippingcode=$sShippingdir;

					$arrShippinglistData[$sShippingcode]=(array)(include $sConfigfile);

					if(isset($arrShippinglist[$sShippingcode])){
						$arrShippinglistData[$sShippingcode]=array_merge($arrShippinglistData[$sShippingcode],$arrShippinglist[$sShippingcode]->toArray());
					}
				}
			}
		}

		return $arrShippinglistData;
	}

}
