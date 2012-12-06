<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城支付宝支付接口($)*/

!defined('DYHB_PATH') && exit;

class Alipay_Payment{

	public function __construct(){}

	public function paymentCode($oShoppayment,$oShoporderinfo){
		$arrShoppaymentconfig=unserialize($oShoppayment['shoppayment_config']);

		$nRealmethod=intval($arrShoppaymentconfig['alipay_pay_method']);
		switch($nRealmethod){
			case '0':
				$sService='trade_create_by_buyer';
				break;
			case '1':
				$sService='create_partner_trade_by_buyer';
				break;
			case '2':
				$sService='create_direct_pay_by_user';
				break;
		}

		$sExtendparam='isv^sh22';

		$arrParameter=array(
			'extend_param'=>$sExtendparam,
			'service'=>$sService,
			'partner'=>$arrShoppaymentconfig['alipay_partner'],
			'_input_charset'=>'utf-8',
			'notify_url'=>Shoppayment_Extend::getPaymentrespondurl('alipay'),
			'return_url'=>Shoppayment_Extend::getPaymentrespondurl('alipay'),
			// 业务参数
			'subject'=>$oShoporderinfo['shoporderinfo_sn'],
			'out_trade_no'=>$oShoporderinfo['shoporderinfo_sn'].G::randString(6),
			'price'=>$oShoporderinfo['shoporderinfo_goodsamount'],
			'quantity'=>1,
			'payment_type'=>1,
			// 物流参数
			'logistics_type'=>'EXPRESS',
			'logistics_fee'=>0,
			'logistics_payment'=>'BUYER_PAY_AFTER_RECEIVE',
			// 买卖双方信息
			'seller_email'=>$arrShoppaymentconfig['alipay_account'],
		);

		ksort($arrParameter);
		reset($arrParameter);

		$sParam='';
		$sSign='';
		if(is_array($arrParameter)){
			foreach($arrParameter AS $sKey=>$sVal){
				$sParam.="{$sKey}=".urlencode($sVal)."&";
				$sSign.="{$sKey}={$sVal}&";
			}
		}

		$sParam=substr($sParam,0,-1);
		$sSign=substr($sSign,0,-1).$arrShoppaymentconfig['alipay_key'];
		$sUrl='https://www.alipay.com/cooperate/gateway.do?'.$sParam.'&sign='.md5($sSign).'&sign_type=MD5';
		
		$sReturnhtml='<div style="text-align:center"><input type="button" class="btn btn-button" onclick="window.open(\''.$sUrl.'\')" value="'.'立即使用支付宝支付'.'"/></div>';

		return $sReturnhtml;
	}

	public function respond(){
		if(!empty($_POST)){
			foreach($_POST as $sKey=>$sData){
				$_GET[$sKey]=$sData;
			}
		}

		$oShoppayment=Shoppayment_Extend::getPayment($_GET['code']);
		$sSelleremail=rawurldecode($_GET['seller_email']);
		$nShopordersn=str_replace($_GET['subject'],'',$_GET['out_trade_no']);
		$nShopordersn=trim($nShopordersn);

		// 检查支付的金额是否相符
		if(!Shoppayment_Extend::checkMoney($nShopordersn,$_GET['total_fee'])){
			return false;
		}

		// 检查数字签名是否正确
		ksort($_GET);
		reset($_GET);

		$sSign='';
		if(!empty($_GET)){
			foreach($_GET AS $sKey=>$sVal){
				if($sKey!='sign' && $sKey!='sign_type' && $sKey!='code'){
					$sSign.="{$sKey}={$sVal}&";
				}
			}
		}

		$sSige=substr($sSign,0,-1).$oShoppayment['shoppayment_config']['alipay_key'];
		if(md5($sSign)!=$_GET['sign']){
			return false;
		}

		if($_GET['trade_status']=='WAIT_SELLER_SEND_GOODS'){
			//Shoporder_Extend::orderPaid($nShopordersn,2);
			return true;
		}elseif($_GET['trade_status']=='TRADE_FINISHED'){
			//Shoporder_Extend::orderPaid($nShopordersn);
			return true;
		}elseif ($_GET['trade_status']=='TRADE_SUCCESS'){
			//Shoporder_Extend::orderPaid($nShopordersn,2);
			return true;
		}else{
			return false;
		}
	}

}