<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   访问推广控制器($)*/

!defined('DYHB_PATH') && exit;

class PromotionController extends Controller{

	public function index(){
		// URL地址解密
		if(empty($_GET['k'])){
			$bEncodeMethod=TRUE;
			@list($_GET['fromuid'],$_GET['k'],$_GET['t'],$_GET['sid'])=explode('|',base64_decode(G::getGpc('fromuid','G')));
		}else{
			$bEncodeMethod=FALSE;
		}

		$nUserid=intval(G::getGpc('fromuid','G'));
		if(empty($nUserid)){
			return;
		}

		$oUser=UserModel::F('user_id=? AND user_status=1',$nUserid)->getOne();
		if(empty($oUser['user_id'])){
			return;
		}

		if($GLOBALS['___login___']===false || ($nUserid!=$GLOBALS['___login___']['user_id'])){
			$sIp=G::getIp();
			$oTrypromotion=PromotionModel::F('promotion_ip=?',$sIp)->getOne();

			// 仅访问
			if(empty($oTrypromotion['promotion_ip'])){
				$oPromotion=new PromotionModel();
				$oPromotion->user_id=$nUserid;
				$oPromotion->promotion_username=$oUser['user_name'];
				$oPromotion->save(0);

				if($oPromotion->isError()){
					$this->E($oPromotion->getErrorMessage());
				}

				Core_Extend::updateCreditByAction('promotion_visit',$nUserid);
			}
			
			// 访问和注册
			$nCookiepromotion=Dyhb::cookie('_promotion_');
			if(!empty($nCookiepromotion)){
				$nUserid=intval($nCookiepromotion);
			}

			if($nUserid){
				Dyhb::cookie('_promotion_',$nUserid,1800);
			}
		}
	}

}
