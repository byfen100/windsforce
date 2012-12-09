<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   个人动态($)*/

!defined('DYHB_PATH') && exit;

class FeedController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		if(empty($nId)){
			$nId=$GLOBALS['___login___']['user_id'];
		}
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->assign('nId',$nId);

		// 取得用户动态
		$arrOptionData['feed_list_num']=20;

		// 动态列表
		$arrWhere['user_id']=$nId;

		$nTotalRecord=FeedModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['feed_list_num'],G::getGpc('page','G'));

		$arrFeeds=FeedModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['feed_list_num'])->getAll();

		// 最后处理结果
		$arrFeeddatas=array();
		if(is_array($arrFeeds)){
			foreach($arrFeeds as $nKey=>$oFeed){
				$arrData=unserialize($oFeed['feed_data']);
		
				$arrTempdata=array();
				if(is_array($arrData)){
					foreach($arrData as $nK=>$sValueTemp){
						$sTempkey='{'.$nK.'}';

						// @开头表示URL，调用Dyhb::U来生成地址
						if(strpos($nK,'@')===0){
							$sValueTemp=Dyhb::U($sValueTemp);
						}

						$arrTempdata[$sTempkey]=$sValueTemp;
					}
				}

				$arrFeeddatas[]=array(
					'user_id'=>$oFeed['user_id'],
					'feed_username'=>$oFeed['feed_username'],
					'feed_content'=>strtr($oFeed['feed_template'],$arrTempdata),
					'create_dateline'=>$oFeed['create_dateline'],
				);
			}
		}

		$this->assign('arrFeeddatas',$arrFeeddatas);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('space+feed');
	}

}
