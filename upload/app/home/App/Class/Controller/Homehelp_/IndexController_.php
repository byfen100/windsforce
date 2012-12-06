<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   帮助首页($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends GlobalchildController{

	protected $_oHomehelpcategory=null;

	public function index(){
		$arrWhere=array();
		
		$nId=intval(G::getGpc('cid','G'));
		if(empty($nId)){
			$nId=0;
		}else{
			$arrWhere['homehelpcategory_id']=$nId;
			$this->_oHomehelpcategory=HomehelpcategoryModel::F('homehelpcategory_id=?',$nId)->getOne();
		}

		Core_Extend::loadCache('home_option');
		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$arrWhere['homehelp_status']=1;
		$nTotalRecord=HomehelpModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['homehelp_list_num'],G::getGpc('page','G'));
		$arrHomehelps=HomehelpModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['homehelp_list_num'])->getAll();

		$this->assign('arrHomehelps',$arrHomehelps);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nCid',$nId);

		$this->_oParentcontroller->homehelpcategory_($this);

		$this->display('homehelp+list');
	}

	public function index_title_(){
		return '帮助中心'.($this->_oHomehelpcategory?' - '.$this->_oHomehelpcategory['homehelpcategory_name']:'');
	}

	public function index_keywords_(){
		return $this->_oHomehelpcategory?$this->_oHomehelpcategory['homehelpcategory_name']:'帮助中心';
	}

	public function index_description_(){
		return $this->index_keywords_();
	}

}
