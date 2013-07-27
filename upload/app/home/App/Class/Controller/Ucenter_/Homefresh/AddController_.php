<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加新鲜事($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

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
			$this->E($e->getMessage());
		}
		
		if($GLOBALS['_option_']['seccode_publish_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		$sMessage=trim(G::cleanJs(G::getGpc('homefresh_message','P')));
		if(empty($sMessage)){
			$this->E(Dyhb::L('新鲜事内容不能为空','Controller'));
		}

		// 解析新鲜事内容
		$arrParsemessage=Core_Extend::contentParsetag($sMessage);
		$sMessage=$arrParsemessage['content'];

		// 话题功能
		if(!empty($arrParsemessage['tags'])){
			foreach($arrParsemessage['tags'] as $sHomefreshtag){
				$oHomefreshtag=Dyhb::instance('HomefreshtagModel');
				$oHomefreshtag->insertHomefreshtag($sHomefreshtag);

				if($oHomefreshtag->isError()){
					$this->E($oHomefreshtag->getErrorMessage());
				}
			}
		}
		
		// 新鲜事模型
		$oHomefresh=new HomefreshModel();
		$oHomefresh->safeInput();
		$oHomefresh->homefresh_message=$sMessage;
		$oHomefresh->homefresh_status=1;
		$oHomefresh->save(0,'save');

		if($oHomefresh->isError()){
			$this->E($oHomefresh->getErrorMessage());
		}else{
			// 判断是否将新鲜事更新到签名
			if(G::getGpc('synchronized-to-sign','P')==1){
				$sMessage=trim(strip_tags($sMessage));
				$sMessage=preg_replace('/\s(?=\s)/','',$sMessage);// 接着去掉两个空格以上的
				$sMessage=preg_replace('/[\n\r\t]/','',$sMessage);// 最后将非空格替换为一个空格
				$sMessage=G::subString($sMessage,0,500);
				
				// 更新到前用户的签名信息
				$oUser=UserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
				$oUser->user_sign=$sMessage;
				$oUser->save(0,'update');
		
				if($oUser->isError()){
					$this->E($oUser->getErrorMessage());
				}
			}

			// 发送feed
			$sFeedtemplate='<div class="feed_addhomefresh"><span class="feed_title">'.Dyhb::L('发布了一条新鲜事','Controller').'&nbsp;<a href="{@homefresh_link}">'.Dyhb::L('查看','Controller').'</a></span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">'.Dyhb::L('回复','Controller').'</a></div></div>';

			$arrFeeddata=array(
				'@homefresh_link'=>'home://fresh@?id='.$oHomefresh['homefresh_id'],
				'homefresh_message'=>Core_Extend::subString($oHomefresh['homefresh_message'],100,false,1,false),
			);

			try{
				Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}

			// 发送提醒
			if($arrParsemessage['atuserids']){
				foreach($arrParsemessage['atuserids'] as $nAtuserid){
					if($nAtuserid!=$GLOBALS['___login___']['user_id']){
						$sHomefreshmessage=Core_Extend::subString($oHomefresh['homefresh_message'],100,false,1,false);
						
						$sNoticetemplate='<div class="notice_credit"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在新鲜事中提到了你','Controller').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@homefresh_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@homefresh_link'=>'home://fresh@?id='.$oHomefresh['homefresh_id'],
							'content_message'=>$sHomefreshmessage,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'athomefresh',$oHomefresh['homefresh_id']);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}
				}
			}

			$nCutnum=intval(G::getGpc('cutnum'));
			if(!$nCutnum){
				$nCutnum=$GLOBALS['_cache_']['home_option']['homefresh_list_substring_num'];
			}

			$arrHomefreshData=$oHomefresh->toArray();
			$arrHomefreshData['space']=Dyhb::U('home://space@?id='.$oHomefresh['user_id']);
			$arrHomefreshData['avatar']=Core_Extend::avatar($oHomefresh['user_id'],'small');
			$arrHomefreshData['user_name']=$oHomefresh->user->user_name;
			$arrHomefreshData['create_dateline']=Core_Extend::timeFormat($oHomefresh['create_dateline']);
			$arrHomefreshData['homefresh_message']=Core_Extend::subString($oHomefresh['homefresh_message'],$nCutnum,false,1);
			$arrHomefreshData['url']=Dyhb::U('home://fresh@?id='.$oHomefresh['homefresh_id']);
			$arrHomefreshData['usericon']=Core_Extend::getUsericon($oHomefresh['user_id']);

			// 保存home今日数据
			OptionModel::uploadOption('todayhomefreshnum',$GLOBALS['_option_']['todayhomefreshnum']+1);
			OptionModel::uploadOption('todaytotalnum',$GLOBALS['_option_']['todaytotalnum']+1);

			$this->cache_site_();

			// 更新积分
			Core_Extend::updateCreditByAction('posthomefresh',$GLOBALS['___login___']['user_id']);
			
			$arrHomefreshData['homefresh_count']=Homefresh_Extend::getMyhomefreshnum($GLOBALS['___login___']['user_id']);
			$this->A($arrHomefreshData,Dyhb::L('添加新鲜事成功','Controller'),1);
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("site");
	}

}
