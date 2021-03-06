<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户标签模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HometagModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'hometag',
			'props'=>array(
				'hometag_id'=>array('readonly'=>true),
				'user'=>array('many_to_many'=>'UserModel','mid_class'=>'HometagindexModel','mid_source_key'=>'hometag_id','mid_target_key'=>'user_id'),
			),
			'attr_protected'=>'hometag_id',
			'check'=>array(
				'hometag_name'=>array(
					array('require',Dyhb::L('用户标签不能为空','__APPHOME_COMMON_LANG__@Model')),
					array('max_length',32,Dyhb::L('用户标签不能超过32个字符','__APPHOME_COMMON_LANG__@Model')),
				),
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

	public function safeInput(){
		if(isset($_POST['hometag_name'])){
			$_POST['hometag_name']=G::html($_POST['hometag_name']);
		}
	}

	public function addTag($nUserId,$sTags,$sOldTags=''){
		if($nUserId && $sTags){
			$sTags=str_replace('，',',',$sTags);
			$sTags=str_replace(' ',',',$sTags);

			$sOldTags=str_replace('，',',',$sOldTags);
			$sOldTags=str_replace(' ',',',$sOldTags);

			$arrTags=array_slice(Dyhb::normalize(explode(',',$sTags)),0,5);
			foreach($arrTags as $sTagName){
				$sTagName=G::text($sTagName);

				// 标签不存在，插入标签
				if($sTagName){
					$nTagCount=self::F('hometag_name=?',$sTagName)->all()->getCounts();
					
					if(!$nTagCount){
						// 插入标签
						$oTag=new self();
						$oTag->hometag_name=$sTagName;
						$oTag->save(0,'save');
						
						if($oTag->isError()){
							$this->setErrorMessage($oTag->getErrorMessage());
							return false;
						}
					}else{
						$oTag=self::F('hometag_name=?',$sTagName)->getOne();
					}

					// 标签索引
					$nTagId=$oTag->hometag_id;
					$nTagIndexCount=HometagindexModel::F('hometag_id=? AND user_id=?',$nTagId,$nUserId)->all()->getCounts();

					if(!$nTagIndexCount){
						$oHometagindex=new HometagindexModel();
						$oHometagindex->user_id=$nUserId;
						$oHometagindex->hometag_id=$nTagId;
						$oHometagindex->save(0,'save');

						if($oHometagindex->isError()){
							$this->setErrorMessage($oHometagindex->getErrorMessage());
							return false;
						}
					}

					// 更新标签中用户数量
					$nTagIdCount=HometagindexModel::F('hometag_id=?',$nTagId)->all()->getCounts();
					$oTag->hometag_count=$nTagIdCount;

					if(isset($_POST['hometag_name'])){
						unset($_POST['hometag_name']);// 这里防止自动填充
					}

					$oTag->save(0,'update');

					if($oTag->isError()){
						$this->setErrorMessage($oTag->getErrorMessage());
						return false;
					}
				}
				
				if(!empty($arrOldTags)){
					$arrHometags=HometagModel::F()->where(array('hometag_name'=>array('in',$arrOldTags)))->getAll();

					if(is_array($arrHometags)){
						foreach($arrHometags as $oHometag){
							// 标签索引数据
							$oHometagindexMeta=HometagindexModel::M();
							$oHometagindexMeta->deleteWhere(array('hometag_id'=>$oHometag['hometag_id']));
							
							if($oHometagindexMeta->isError()){
								$this->setErrorMessage($oHometagindexMeta->getErrorMessage());
								return false;
							}

							// 更新标签数据
							$nTagIdCount=self::F('hometag_id=?',$oHometag['hometag_id'])->all()->getCounts();
							$oTag->hometag_count=$nTagIdCount;
							
							if(isset($_POST['hometag_name'])){
								unset($_POST['hometag_name']);// 这里防止自动填充
							}

							$oTag->save(0,'update');

							if($oTag->isError()){
								$this->setErrorMessage($oTag->getErrorMessage());
								return false;
							}
						}
					}
				}
			}
		}
	}

	public function getTagsByUserid($nUserid){
		$arrTagIndexs=HometagindexModel::F('user_id=?',$nUserid)->getAll();

		$arrTags=array();
		if(is_array($arrTagIndexs)){
			foreach($arrTagIndexs as $oTagIndex){
				$arrTags[]=$this->getOneTag($oTagIndex['hometag_id']);
			}
		}
		
		return $arrTags;
	}

	public function getOneTag($nTagId){
		return self::F('hometag_id=?',$nTagId)->getOne();
	}

}
