<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   添加新鲜事($)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	public function index(){
		if($GLOBALS['_option_']['seccode_publish_status']==1){
			$this->check_seccode(true);
		}

		$sMessage=trim(G::cleanJs(G::getGpc('homefresh_message','P')));
		if(empty($sMessage)){
			$this->E(Dyhb::L('新鲜事内容不能为空','Controller/Homefresh'));
		}
		
		$oHomefresh=new HomefreshModel();
		$oHomefresh->safeInput();
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
			$sFeedtemplate='<div class="feed_addhomefresh"><span class="feed_title">'.Dyhb::L('发布了一条新鲜事','Controller/Homefresh').'&nbsp;<a href="{@homefresh_link}">'.Dyhb::L('查看','Controller/Homefresh').'</a></span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">'.Dyhb::L('回复','Controller/Homefresh').'</a></div></div>';

			$arrFeeddata=array(
				'@homefresh_link'=>'home://fresh@?id='.$oHomefresh['homefresh_id'],
				'homefresh_message'=>G::subString(strip_tags($oHomefresh['homefresh_message']),0,100),
			);

			Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);

			$arrHomefreshData=$oHomefresh->toArray();
			$arrHomefreshData['space']=Dyhb::U('home://space@?id='.$oHomefresh['user_id']);
			$arrHomefreshData['avatar']=Core_Extend::avatar($oHomefresh['user_id'],'small');
			$arrHomefreshData['user_name']=$oHomefresh->user->user_name;
			$arrHomefreshData['create_dateline']=Core_Extend::timeFormat($oHomefresh['create_dateline']);
			$arrHomefreshData['homefresh_message']=Core_Extend::ubb(G::subString(strip_tags($oHomefresh['homefresh_message']),0,$GLOBALS['_cache_']['home_option']['homefresh_list_substring_num']));
			$arrHomefreshData['url']=Dyhb::U('home://fresh@?id='.$oHomefresh['homefresh_id']);

			$this->cache_site_();

			$arrHomefreshData['homefresh_count']=Homefresh_Extend::getMyhomefreshnum($GLOBALS['___login___']['user_id']);
			
			$this->A($arrHomefreshData,Dyhb::L('添加新鲜事成功','Controller/Homefresh'),1);
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("site");
	}

}
