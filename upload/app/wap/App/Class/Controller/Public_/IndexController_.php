<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();
		$arrWhere['homefresh_status']=1;
		
		// 读取新鲜事
		$nTotalRecord=HomefreshModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$GLOBALS['_option_']['wap_baselist_num'],G::getGpc('page','G'));
		$arrHomefreshs=HomefreshModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$GLOBALS['_option_']['wap_baselist_num'])->getAll();

		$this->assign('nTotalRecord',$nTotalRecord);
		$this->assign('arrHomefreshs',$arrHomefreshs);
		$this->assign('sPageNavbar',$oPage->P('page'));
		
		$this->display('public+index');
	}

	public function index_title_(){
		if($GLOBALS['_commonConfig_']['DEFAULT_APP']!='wap'){
			return Dyhb::L('手机版','Controller');
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
