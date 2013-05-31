<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   新鲜事话题模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HomefreshtagModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'homefreshtag',
			'props'=>array(
				'homefreshtag_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
			),
			'attr_protected'=>'homefreshtag_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('homefreshtag_username','userName','create','callback'),
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

	protected function userName(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_name']?$arrUserData['user_name']:'';
	}

	public function insertHomefreshtag($sHomefreshtag){
		if(empty($sHomefreshtag)){
			return;
		}

		// 判断话题是否存在
		$oHomefreshtag=self::F('homefreshtag_name=?',$sHomefreshtag)->getOne();
		if(!empty($oHomefreshtag['homefreshtag_id'])){
			// 更新统计数量
			$oHomefreshtag->homefreshtag_homefreshcount=$oHomefreshtag->homefreshtag_homefreshcount+1;
			if($GLOBALS['___login___']['user_id']!=$oHomefreshtag->user_id){
				$oHomefreshtag->homefreshtag_usercount=$oHomefreshtag->homefreshtag_usercount+1;
			}
			$oHomefreshtag->homefreshtag_totalcount=$oHomefreshtag->homefreshtag_usercount+$oHomefreshtag->homefreshtag_homefreshcount;
			$oHomefreshtag->save(0,'update');

			if($oHomefreshtag->isError()){
				$this->setErrorMessage($oHomefreshtag->getErrorMessage());
			}
		}else{
			$oHomefreshtag=new self();
			$oHomefreshtag->homefreshtag_name=$sHomefreshtag;
			$oHomefreshtag->homefreshtag_usercount=1;
			$oHomefreshtag->homefreshtag_homefreshcount=1;
			$oHomefreshtag->homefreshtag_totalcount=2;
			$oHomefreshtag->save(0);

			if($oHomefreshtag->isError()){
				$this->setErrorMessage($oHomefreshtag->getErrorMessage());
			}
		}
	}

}
