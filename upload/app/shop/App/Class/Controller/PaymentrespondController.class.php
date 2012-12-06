<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城在线支付响应控制器($)*/

!defined('DYHB_PATH') && exit;

class PaymentrespondController extends InitController{

	public function index(){
		// 支付方式代码
		$sPaycode=!empty($_REQUEST['code'])?trim($_REQUEST['code']):'';

		// 获取首信支付方式
		if(empty($sPaycode) && !empty($_REQUEST['v_pmode']) && !empty($_REQUEST['v_pstring'])){
			$sPaycode='cappay';
		}

		// 获取快钱神州行支付方式
		if(empty($sPaycode) && ($_REQUEST['ext1']=='shenzhou') && ($_REQUEST['ext2']=='needforbug')){
			$sPaycode='shenzhou';
		}
		
		if(empty($sPaycode)){
			$this->E('支付方式不存在');
		}else{
			if(strpos($sPaycode,'?')!==false){
				$arrValue1=explode('?',$sPaycode);
				$arrValue2=explode('=',$arrValue1[1]);

				$_REQUEST['code']=$arrValue1[0];
				$_REQUEST[$arrValue2[0]]=$arrValue2[1];
				$_GET['code']=$arrValue1[0];
				$_GET[$arrValue2[0]]=$arrValue2[1];
				$sPaycode=$arrValue1[0];
			}

			$oTryshoppayment=ShoppaymentModel::F('shoppayment_code=? AND shoppayment_status=1',$sPaycode)->getOne();
			if(empty($oTryshoppayment['shoppayment_id'])){
				$this->E('支付方式尚未启用或者不存在');
			}else{
				$sShoppaymentfile=APP_PATH.'/App/Class/Extension/Payment/'.strtolower($sPaycode).'/'.ucfirst($sPaycode).'_.php';
				if(is_file($sShoppaymentfile)){
					require($sShoppaymentfile);

					$oShoppaymentapi=Dyhb::instance(ucfirst($sPaycode).'_Payment');
					$sMessage=($oShoppaymentapi->respond())?'支付成功':'支付失败';

					$this->S($sMessage);
				}else{
					$this->E('支付方式不存在');
				}
			}
		}
	}

}
