<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户提醒控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NoticeController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}

	public function show(){
		$nId=G::getGpc('id','G');

		if(!empty($nId)){
			$oModel=NoticeModel::F('notice_id=?',$nId)->query();

			if(!empty($oModel->notice_id)){
				$arrData=@unserialize($oModel['notice_data']);
		
				$arrTempdata=array();
				if(is_array($arrData)){
					foreach($arrData as $nK=>$sValueTemp){
						$sTempkey='{'.$nK.'}';

						// @开头表示URL，调用Dyhb::U来生成地址
						if(strpos($nK,'@')===0){
							$sValueTemp='Dyhb::U('.$sValueTemp.')';
							$sValueTemp='javascript:alert(\''.$sValueTemp.'\');';
						}

						$arrTempdata[$sTempkey]=$sValueTemp;
					}
				}

				$arrNoticedata=array(
					'user_id'=>$oModel['notice_authorid'],
					'notice_username'=>$oModel['notice_authorusername'],
					'notice_content'=>strtr($oModel['notice_template'],$arrTempdata),
					'create_dateline'=>$oModel['notice_fromnum']>1?$oModel['update_dateline']:$oModel['create_dateline'],
					'notice_fromnum'=>$oModel['notice_fromnum'],
					'notice_type'=>$oModel['notice_type'],
				);

				$this->assign('oValue',$oModel);
				$this->assign('nId',$nId);
				$this->assign('arrNoticedata',$arrNoticedata);
				
				$this->display('notice+show');
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

}
