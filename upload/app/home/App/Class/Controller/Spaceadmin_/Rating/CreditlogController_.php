<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分变更记录($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditlogController extends Controller{

	public function index(){
		$nBaselistnum=$GLOBALS['_option_']['baselistnum'];

		// 列表数据
		$nTotalRecord=CreditlogModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$nBaselistnum,G::getGpc('page','G'));

		$arrCreditlogs=CreditlogModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('create_dateline DESC')->limit($oPage->returnPageStart(),$nBaselistnum)->getAll();
		$this->assign('arrCreditlogs',$arrCreditlogs);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		$this->display('spaceadmin+creditlog');
	}

	public function creditlog_title_(){
		return Dyhb::L('积分记录','Controller/Spaceadmin');
	}

	public function creditlog_keywords_(){
		return $this->creditlog_title_();
	}

	public function creditlog_description_(){
		return $this->creditlog_title_();
	}

}
