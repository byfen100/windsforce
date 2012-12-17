<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   帖子标签模型($)*/

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

	public function addTag($nGrouptopicid,$sTags){
		if($nGrouptopicid && $sTags){
			$sTags=str_replace('，',',',$sTags);
			$sTags=str_replace(' ',',',$sTags);

			$arrTags=Dyhb::normalize(explode(',',$sTags));
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
						}
					}else{
						$oTag=self::F('grouptopictag_name=?',$sTagName)->getOne();
					}

					// 标签索引
					$nTagId=$oTag->grouptopictag_id;
					$nTagIndexCount=GrouptopictagindexModel::F('grouptopictag_id=? AND grouptopic_id=?',$nTagId,$nGrouptopicid)->all()->getCounts();

					if(!$nTagIndexCount){
						$oHometagindex=new GrouptopictagindexModel();
						$oHometagindex->grouptopic_id=$nGrouptopicid;
						$oHometagindex->grouptopictag_id=$nTagId;
						$oHometagindex->save(0,'save');

						if($oHometagindex->isError()){
							$this->setErrorMessage($oHometagindex->getErrorMessage());
						}
					}

					// 更新标签中用户数量
					$nTagIdCount=self::F('grouptopictag_id=?',$nTagId)->all()->getCounts();
					$oTag->grouptopictag_count=$nTagIdCount;
					unset($_POST['grouptopictag_name']);// 这里放置自动填充
					$oTag->save(0,'update');

					if($oTag->isError()){
						$this->setErrorMessage($oTag->getErrorMessage());
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
