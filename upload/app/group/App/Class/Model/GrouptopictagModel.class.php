<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子标签模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopictagModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'grouptopictag',
			'props'=>array(
				'grouptopictag_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'hometag_id',
			'check'=>array(
				'grouptopictag_name'=>array(
					array('require',Dyhb::L('帖子标签不能为空','__APP_ADMIN_LANG__@Model/Grouptopictag')),
					array('max_length',32,Dyhb::L('帖子标签不能超过32个字符','__APP_ADMIN_LANG__@Model/Grouptopictag')),
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
		if(isset($_POST['grouptopictag_name'])){
			$_POST['grouptopictag_name']=G::html($_POST['grouptopictag_name']);
		}
	}

	public function addTag($nGrouptopicid,$sTags,$sOldTags=''){
		if($nGrouptopicid && $sTags){
			$sTags=str_replace('，',',',$sTags);
			$sTags=str_replace(' ',',',$sTags);

			$sOldTags=str_replace('，',',',$sOldTags);
			$sOldTags=str_replace(' ',',',$sOldTags);

			$arrTags=array_slice(Dyhb::normalize(explode(',',$sTags)),0,5);
			foreach($arrTags as $sTagName){
				$sTagName=G::text($sTagName);

				// 标签不存在，插入标签
				if($sTagName){
					$nTagCount=self::F('grouptopictag_name=?',$sTagName)->all()->getCounts();
					
					if(!$nTagCount){
						// 插入标签
						$oTag=new self();
						$oTag->grouptopictag_name=$sTagName;
						$oTag->save(0,'save');
						
						if($oTag->isError()){
							$this->setErrorMessage($oTag->getErrorMessage());
							return false;
						}
					}else{
						$oTag=self::F('grouptopictag_name=?',$sTagName)->getOne();
					}

					// 标签索引
					$nTagId=$oTag->grouptopictag_id;
					$nTagIndexCount=GrouptopictagindexModel::F('grouptopictag_id=? AND grouptopic_id=?',$nTagId,$nGrouptopicid)->all()->getCounts();

					if(!$nTagIndexCount){
						$oGrouptopictagindex=new GrouptopictagindexModel();
						$oGrouptopictagindex->grouptopic_id=$nGrouptopicid;
						$oGrouptopictagindex->grouptopictag_id=$nTagId;
						$oGrouptopictagindex->save(0,'save');

						if($oGrouptopictagindex->isError()){
							$this->setErrorMessage($oGrouptopictagindex->getErrorMessage());
							return false;
						}
					}

					// 更新标签中帖子数量
					$nTagIdCount=self::F('grouptopictag_id=?',$nTagId)->all()->getCounts();
					$oTag->grouptopictag_count=$nTagIdCount;

					if(isset($_POST['grouptopictag_name'])){
						unset($_POST['grouptopictag_name']);// 这里防止自动填充
					}

					$oTag->save(0,'update');

					if($oTag->isError()){
						$this->setErrorMessage($oTag->getErrorMessage());
						return false;
					}
				}

				// 对比旧标签删除不存在的标签
				if($sOldTags){
					$arrOldTags=Dyhb::normalize(explode(',',$sOldTags));
					foreach($arrOldTags as $nKey=>$sOldTagName){
						if(in_array($sOldTagName,$arrTags)){
							unset($arrOldTags[$nKey]);
						}
					}

					if(!empty($arrOldTags)){
						$arrGrouptopictags=GrouptopictagModel::F()->where(array('grouptopictag_name'=>array('in',$arrOldTags)))->getAll();

						if(is_array($arrGrouptopictags)){
							foreach($arrGrouptopictags as $oGrouptopictag){
								// 标签索引数据
								$oGrouptopictagindexMeta=GrouptopictagindexModel::M();
								$oGrouptopictagindexMeta->deleteWhere(array('grouptopictag_id'=>$oGrouptopictag['grouptopictag_id']));
								
								if($oGrouptopictagindexMeta->isError()){
									$this->setErrorMessage($oGrouptopictagindexMeta->getErrorMessage());
									return false;
								}

								// 更新标签数据
								$nTagIdCount=self::F('grouptopictag_id=?',$oGrouptopictag['grouptopictag_id'])->all()->getCounts();
								$oTag->grouptopictag_count=$nTagIdCount;
								
								if(isset($_POST['grouptopictag_name'])){
									unset($_POST['grouptopictag_name']);// 这里防止自动填充
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
	}

	public function getTagsByGrouptopicid($nGrouptopicid){
		$arrTagIndexs=GrouptopictagindexModel::F('grouptopic_id=?',$nGrouptopicid)->getAll();

		$arrTags=array();
		if(is_array($arrTagIndexs)){
			foreach($arrTagIndexs as $oTagIndex){
				$arrTags[]=$this->getOneTag($oTagIndex['grouptopictag_id']);
			}
		}
		
		return $arrTags;
	}

	public function getOneTag($nTagId){
		return self::F('grouptopictag_id=?',$nTagId)->getOne();
	}

}
