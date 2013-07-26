<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   我感兴趣的活动列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AttentionController extends Controller{

	public function index(){
		// 活动
		$nTotalEventnum=EventattentionuserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->all()->getCounts();

		$oPage=Page::RUN($nTotalEventnum,12,G::getGpc('page','G'));

		$arrEventattentionusers=EventattentionuserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->limit($oPage->returnPageStart(),12)->order('create_dateline DESC')->getAll();

		$this->assign('arrEventattentionusers',$arrEventattentionusers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('ucenterevent+attention');
	}

	public function attention_title_(){
		return Dyhb::L('我感兴趣的活动','Controller');
	}

	public function attention_keywords_(){
		return $this->attention_title_();
	}

	public function attention_description_(){
		return $this->attention_title_();
	}

}
