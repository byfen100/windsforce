<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   站点统计显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class OnlineController extends InitController{

	public function index(){
		if($GLOBALS['_option_']['online_on']==0){
			$this->E(Dyhb::L('用户在线功能没有开启','Controller/Online'));
		}

		if($GLOBALS['_option_']['online_detailon']==0){
			$this->E(Dyhb::L('系统关闭了用户在线详细页面','Controller/Online'));
		}
		
		// 读取在线数据
		$arrOnlinedata=Home_Extend::getOnlinedata();

		$this->assign('arrOnlinedata',$arrOnlinedata);

		// 用户列表数据
		$nTotalRecord=OnlineModel::F()->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$GLOBALS['_option_']['online_list_num'],G::getGpc('page','G'));

		$arrOnlineLists=OnlineModel::F()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$GLOBALS['_option_']['online_list_num'])->getAll();

		$this->assign('nTotalOnline',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrOnlineLists',$arrOnlineLists);
		
		$this->display('online+index');
	}

	public function index_title_(){
		return Dyhb::L('用户在线','Controller/Online');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}
	
}
