<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户广场动态($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class FeedController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('sitefeed')){
			$this->E(Dyhb::L('你没有权限访问用户动态','Controller'));
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 动态列表
		$nTotalRecord=FeedModel::F()->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['feed_list_num'],G::getGpc('page','G'));

		$arrFeeds=FeedModel::F()->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['feed_list_num'])->getAll();

		// 最后处理结果
		$arrFeeddatas=array();
		if(is_array($arrFeeds)){
			foreach($arrFeeds as $nKey=>$oFeed){
				$arrData=@unserialize($oFeed['feed_data']);
		
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
					'feed_id'=>$oFeed['feed_id'],
					'user_id'=>$oFeed['user_id'],
					'feed_username'=>$oFeed['feed_username'],
					'feed_content'=>strtr($oFeed['feed_template'],$arrTempdata),
					'create_dateline'=>$oFeed['create_dateline'],
				);
			}
		}

		$this->assign('arrFeeddatas',$arrFeeddatas);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('stat+feed');
	}

	public function feed_title_(){
		return Dyhb::L('用户动态','Controller');
	}

	public function feed_keywords_(){
		return $this->feed_title_();
	}

	public function feed_description_(){
		return $this->feed_title_();
	}
	
}
