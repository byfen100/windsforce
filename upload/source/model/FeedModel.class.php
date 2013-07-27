<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户动态模型($Liu.XiangMin)*/

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

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
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
					'feed_application'=>APP_NAME,
				)
			);

			$oFeed->save(0);
			if($oFeed->isError()){
				$this->setErrorMessage($oFeed->getErrorMessage());
				return false;
			}
		}

		return true;
	}

	public function deleteAllCreatedateline($nTime){
		$oDb=Db::RUN();

		$oDb->query("DELETE FROM ".$this->getTablePrefix()."feed WHERE create_dateline<".(CURRENT_TIMESTAMP-$nTime));
	}

}
