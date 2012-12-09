<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户动态模型($)*/

!defined('DYHB_PATH') && exit;

class FeedModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'feed',
			'props'=>array(
				'feed_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'feed_id',
		);
	}

	public function addFeed($sTemplate,$arrData,$nUserid,$nUsername){
		$nUserid=intval($nUserid);

		if(is_array($arrData)){
			$sData=serialize($arrData);

			$oFeed=new self(
				array(
					'user_id'=>$nUserid,
					'feed_username'=>$nUsername,
					'feed_template'=>$sTemplate,
					'feed_data'=>$sData,
				)
			);

			$oFeed->save(0);
			if($oFeed->isError()){
				$this->setErrorMessage($oFeed->getErrorMessage());
			}
		}
		
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

}
