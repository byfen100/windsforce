<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组搜索($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupController extends Controller{

	protected $_sKey='';

	public function index(){
		$sKey=urldecode(trim(G::getGpc('key')));

		if($sKey){
			if($GLOBALS['_option_']['show_search_result_message']){
				G::urlGoTo(Dyhb::U('group://search/groupresult?key='.urlencode($sKey),array(),true),1,nl2br($GLOBALS['_option_']['show_search_result_message']));
			}else{
				G::urlGoTo(Dyhb::U('group://search/groupresult?key='.urlencode($sKey),array(),true));
			}
		}

		$this->assign('sKey',$sKey);

		$this->display('search+group');
	}

	public function result(){
		$sKey=urldecode(trim(G::getGpc('key')));
		
		$sKey=htmlspecialchars($sKey);
		$sKey=str_replace('%','\%',$sKey);
		$sKey=str_replace('_','\_',$sKey);

		if($sKey){
			if($GLOBALS['_option_']['search_keywords_minlength']>0 && strlen($sKey)<$GLOBALS['_option_']['search_keywords_minlength']){
				$this->E(Dyhb::L('搜索的关键字最少为 %d 字节','Controller',null,$GLOBALS['_option_']['search_keywords_minlength']));
			}
			
			$this->_sKey=$sKey;
			
			// 小组列表
			$arrWhere=array();

			$arrWhere['group_status']=1;
			$arrWhere['group_isaudit']=1;
			$arrWhere['group_nikename']=array('like',"%{$sKey}%");

			$nTotalRecord=GroupModel::F()->where($arrWhere)->all()->getCounts();

			$oPage=Page::RUN($nTotalRecord,$GLOBALS['_option_']['search_list_num'],G::getGpc('page','G'));

			$arrGroups=GroupModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$GLOBALS['_option_']['search_list_num'])->getAll();
			
			$this->assign('arrGroups',$arrGroups);
			$this->assign('nTotalGroupnum',$nTotalRecord);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
			$this->assign('sKey',$sKey);
			
			$this->display('search+groupresult');
		}else{
			$this->U('group://search/group');
		}
	}

	public function groupresult_title_(){
		return $this->_sKey.' - '.Dyhb::L('搜索结果','Controller');
	}

	public function groupresult_keywords_(){
		return $this->groupresult_title_();
	}

	public function groupresult_description_(){
		return $this->groupresult_title_();
	}

	public function group_title_(){
		return Dyhb::L('小组搜索','Controller');
	}

	public function group_keywords_(){
		return $this->group_title_();
	}

	public function group_description_(){
		return $this->group_title_();
	}

}
