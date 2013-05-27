<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加新鲜事($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

class AddController extends GlobalchildController{

	public function index(){
		try{
			$arrData=array();

			$oLasthomefresh=HomefreshModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('create_dateline DESC')->getOne();
			if(!empty($oLasthomefresh['homefresh_id'])){
				$arrData['lasttime']=$oLasthomefresh['create_dateline'];
			}
			
			Core_Extend::checkSpam($arrData);
		}catch(Exception $e){
			$this->_oParentcontroller->wap_mes($e->getMessage(),'',Dyhb::U('wap://ucenter/index'),0);
		}

		$sMessage=trim(G::cleanJs(G::getGpc('homefresh_message','P')));
		if(empty($sMessage)){
			$this->_oParentcontroller->wap_mes(Dyhb::L('新鲜事内容不能为空','Controller/Homefresh'),Dyhb::U('wap://ucenter/index'),0);
		}

		// 新鲜事模型
		$oHomefresh=new HomefreshModel();
		$oHomefresh->safeInput();
		$oHomefresh->homefresh_message=$sMessage;
		$oHomefresh->homefresh_status=1;
		$oHomefresh->save(0,'save');

		if($oHomefresh->isError()){
			$this->_oParentcontroller->wap_mes($oHomefresh->getErrorMessage(),Dyhb::U('wap://ucenter/index'));
		}else{
			// 发送feed
			$sFeedtemplate='<div class="feed_addhomefresh"><span class="feed_title">'.Dyhb::L('发布了一条新鲜事','Controller/Homefresh').'&nbsp;<a href="{@homefresh_link}">'.Dyhb::L('查看','Controller/Homefresh').'</a></span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">'.Dyhb::L('回复','Controller/Homefresh').'</a></div></div>';

			$arrFeeddata=array(
				'@homefresh_link'=>'home://fresh@?id='.$oHomefresh['homefresh_id'],
				'homefresh_message'=>Core_Extend::subString($oHomefresh['homefresh_message'],100,false,1,false),
			);

			try{
				Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);
			}catch(Exception $e){
				$this->_oParentcontroller->wap_mes($e->getMessage(),Dyhb::U('wap://ucenter/index'),0);
			}

			// 保存home今日数据
			OptionModel::uploadOption('todayhomefreshnum',$GLOBALS['_option_']['todayhomefreshnum']+1);
			OptionModel::uploadOption('todaytotalnum',$GLOBALS['_option_']['todaytotalnum']+1);

			$this->cache_site_();

			// 更新积分
			Core_Extend::updateCreditByAction('posthomefresh',$GLOBALS['___login___']['user_id']);
		
			$this->_oParentcontroller->wap_mes(Dyhb::L('添加新鲜事成功','Controller/Homefresh'),Dyhb::U('wap://ucenter/index'));
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("site");
	}

}
