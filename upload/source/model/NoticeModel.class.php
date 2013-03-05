<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   提醒模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NoticeModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'notice',
			'props'=>array(
				'notice_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'notice_id',
			'check'=>array(
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

	public function addNotice($sTemplate,$arrData,$nTouserid,$sType,$nFromid,$nUserid,$sUsername){
		$nUserid=intval($nUserid);

		if(is_array($arrData)){
			$sData=serialize($arrData);

			$bNewnotice=true;
			
			if($nFromid){
				$oNotice=self::F()->where(array('user_id'=>$nTouserid,'notice_type'=>$sType,'notice_authorid'=>$nUserid,'notice_fromid'=>$nFromid))->getOne();

				if(!empty($oNotice['notice_id'])){
					$bNewnotice=false;
					
					$oNotice->notice_fromnum=$oNotice->notice_fromnum+1;
					$oNotice->save(0,'update');
					
					if($oNotice->isError()){
						$this->setErrorMessage($oNotice->getErrorMessage());
						return false;
					}

					return true;
				}
			}
			
			if($bNewnotice===true){
				$oNotice=new self(
					array(
						'user_id'=>$nTouserid,
						'notice_type'=>$sType,
						'notice_authorid'=>$nUserid,
						'notice_authorusername'=>$sUsername,
						'notice_template'=>$sTemplate,
						'notice_data'=>$sData,
						'notice_fromnum'=>1,
						'notice_fromid'=>$nFromid,
					)
				);

				$oNotice->save(0);
				
				if($oNotice->isError()){
					$this->setErrorMessage($oNotice->getErrorMessage());
					return false;
				}
			}
		}

		return true;
	}

}
