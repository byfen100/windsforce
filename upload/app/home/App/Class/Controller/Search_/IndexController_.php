<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   搜索首页($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	protected $_sKey='';
	
	public function index(){
		$sKey=urldecode(trim(G::getGpc('key')));

		if($sKey){
			if($GLOBALS['_option_']['show_search_result_message']){
				G::urlGoTo(Dyhb::U('home://search/result?key='.urlencode($sKey),array(),true),1,nl2br($GLOBALS['_option_']['show_search_result_message']));
			}else{
				G::urlGoTo(Dyhb::U('home://search/result?key='.urlencode($sKey),array(),true));
			}
		}

		$this->assign('sKey',$sKey);

		$this->display('search+index');
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
			
			// 赞
			$sGoodCookie=Dyhb::cookie('homefresh_goodnum');
			$arrGoodCookie=explode(',',$sGoodCookie);
			$this->assign('arrGoodCookie',$arrGoodCookie);

			$this->_sKey=$sKey;
			
			// 新鲜事列表
			$arrWhere=array();

			$arrWhere['homefresh_status']=1;
			$arrWhere['homefresh_message']=array('like',"%{$sKey}%");

			$nTotalRecord=HomefreshModel::F()->where($arrWhere)->all()->getCounts();

			$oPage=Page::RUN($nTotalRecord,$GLOBALS['_option_']['search_list_num'],G::getGpc('page','G'));

			$arrHomefreshs=HomefreshModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$GLOBALS['_option_']['search_list_num'])->getAll();
			
			$this->assign('arrHomefreshs',$arrHomefreshs);
			$this->assign('nTotalHomefreshnum',$nTotalRecord);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
			$this->assign('sKey',$sKey);
			
			$this->display('search+result');
		}else{
			$this->U('home://search/index');
		}
	}

	public function index_title_(){
		return Dyhb::L('搜索引擎','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

	public function result_title_(){
		return $this->_sKey.' - '.Dyhb::L('搜索结果','Controller');
	}

	public function result_keywords_(){
		return $this->result_title_();
	}

	public function result_description_(){
		return $this->result_title_();
	}

}
