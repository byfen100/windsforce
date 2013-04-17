<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组标签查找帖子($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	protected $_oGrouptopictag=null;
	
	public function index(){
		$sTag=trim(G::getGpc('tag','G'));

		// 不存在则提示没有标签
		if(!empty($sTag)){
			$oGrouptopictag=GrouptopictagModel::F('grouptopictag_name=?',$sTag)->getOne();
			if(empty($oGrouptopictag['grouptopictag_id'])){
				$this->E(Dyhb::L('用户标签不存在','Controller/Tag'));
			}

			$this->assign('oGrouptopictag',$oGrouptopictag);

			$this->_oGrouptopictag=$oGrouptopictag;
			
			// 读取帖子列表
			$arrWhere=array();
			$nEverynum=$GLOBALS['_cache_']['group_option']['group_tag_listtopicnum'];

			$arrWhere['grouptopic_status']=1;
			$arrWhere['grouptopic_isaudit']=1;

			$nTotalRecord=GrouptopictagindexModel::F('grouptopictag_id=?',$oGrouptopictag['grouptopictag_id'])->all()->getCounts();
				
			$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));

			$arrGrouptopictagindexs=GrouptopictagindexModel::F('grouptopictag_id=?',$oGrouptopictag['grouptopictag_id'])->limit($oPage->returnPageStart(),$nEverynum)->getAll();

			if(is_array($arrGrouptopictagindexs)){
				$arrTempdata=array();
				foreach($arrGrouptopictagindexs as $oGrouptopictagindex){
					$arrTempdata[]=$oGrouptopictagindex['grouptopic_id'];
				}
				
				$arrWhere['grouptopic_id']=array('in',$arrTempdata);
				
				$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->limit($oPage->returnPageStart(),$nEverynum)->order('create_dateline DESC')->getAll();
			}else{
				$arrGrouptopics=null;
			}

			// 热门标签
			$arrHottags=Groupdata_Extend::getGrouphotag();
			$this->assign('arrHottags',$arrHottags);

			$this->assign('arrGrouptopics',$arrGrouptopics);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		}else{
			$this->U('group://tag/index');
		}
		
		$this->display('tag+show');
	}

	public function show_title_(){
		return $this->_oGrouptopictag['grouptopictag_name'].' - '.Dyhb::L('标签','Controller/Tag');
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function show_description_(){
		return $this->show_title_();
	}

}
