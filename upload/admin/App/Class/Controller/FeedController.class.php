<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户动态控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class FeedController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}

	public function show(){
		$nId=G::getGpc('id','G');

		if(!empty($nId)){
			$oModel=FeedModel::F('feed_id=?',$nId)->query();

			if(!empty($oModel->feed_id)){
				$arrData=@unserialize($oModel['feed_data']);
		
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

				$arrFeeddata=array(
					'user_id'=>$oModel['user_id'],
					'feed_username'=>$oModel['feed_username'],
					'feed_content'=>strtr($oModel['feed_template'],$arrTempdata),
					'create_dateline'=>$oModel['create_dateline'],
				);

				$this->assign('oValue',$oModel);
				$this->assign('nId',$nId);
				$this->assign('arrFeeddata',$arrFeeddata);
				
				$this->display('feed+show');
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function feedContent($oFeed){
		$arrData=@unserialize($oFeed['feed_data']);

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

		return strtr($oFeed['feed_template'],$arrTempdata);
	}

}
