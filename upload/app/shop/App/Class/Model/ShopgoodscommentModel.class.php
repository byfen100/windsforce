<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城商品评论模型($)*/

!defined('DYHB_PATH') && exit;

class ShopgoodscommentModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopgoodscomment',
			'props'=>array(
				'shopgoodscomment_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopgoodscomment_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('shopgoodscomment_ip','getIp','create','callback'),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	protected function userId(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_id']?$arrUserData['user_id']:0;
	}

	protected function getIp(){
		return G::getIp();
	}

}
