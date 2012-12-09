<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城支付记录模型($)*/

!defined('DYHB_PATH') && exit;

class ShoppaylogModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shoppaylog',
			'props'=>array(
				'shoppaylog_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shoppaylog_id',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function insertShoppaylog($nShoporderinfoid,$nShoporderinfoorderamount){
		$oShoppaylog=new self();
		$oShoppaylog->shoporderinfo_id=$nShoporderinfoid;
		$oShoppaylog->shoppaylog_orderamount=$nShoporderinfoorderamount;
		$oShoppaylog->save(0);

		if($oShoppaylog->isError()){
			$this->setErrorMessage($oShoppaylog->getErrorMessage());
		}
	}

}
