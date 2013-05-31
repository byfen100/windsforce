<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户广场用户标签列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class HometagController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('siteuserlist')){
			$this->E(Dyhb::L('你没有权限访问用户标签列表','Controller'));
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$nTotalRecord=HometagModel::F()->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['hometag_list_num'],G::getGpc('page','G'));

		$arrHometags=HometagModel::F()->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['hometag_list_num'])->getAll();

		$this->assign('arrHometags',$arrHometags);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		$this->display('stat+hometag');
	}

	public function hometag_title_(){
		return Dyhb::L('用户标签列表','Controller');
	}

	public function hometag_keywords_(){
		return $this->hometag_title_();
	}

	public function hometag_description_(){
		return $this->hometag_title_();
	}
	
}
