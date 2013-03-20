<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   新鲜事话题列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();

		// 类型
		$sType=trim(G::getGpc('type','G'));
		if(empty($sType)){
			$sType='';
		}
		$this->assign('sType',$sType);

		if(!$sType){
			$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 动态列表
		$arrWhere['homefreshtag_status']=1;
		$nTotalRecord=HomefreshtagModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['homefreshtag_list_num'],G::getGpc('page','G'));

		$arrHomefreshtags=HomefreshtagModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['homefreshtag_list_num'])->getAll();

		$this->assign('arrHomefreshtags',$arrHomefreshtags);
		$this->assign('nTotalHomefreshtagnum',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		$this->display('homefreshtag+index');
	}

	public function tag_title_(){
		return Dyhb::L('新鲜事话题','Controller/Homefreshtag');
	}

	public function tag_keywords_(){
		return $this->tag_title_();
	}

	public function tag_description_(){
		return $this->tag_title_();
	}

}
