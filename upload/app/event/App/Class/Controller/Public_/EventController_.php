<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动分类控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventController extends Controller{

	protected $_oEventcategory=null;
	
	public function index(){
		$nCid=intval(G::getGpc('cid','G'));

		$oEventcategory=EventcategoryModel::F('eventcategory_id=?',$nCid)->getOne();
		if(!empty($oEventcategory['eventcategory_id'])){
			$this->assign('oEventcategory',$oEventcategory);
			$this->_oEventcategory=$oEventcategory;
		}else{
			$this->U('event://public/index');
		}
		
		// 读取活动列表
		$arrWhere['event_status']=1;
		$arrWhere['eventcategory_id']=$nCid;

		$nEverynum=12;
			
		$nTotalRecord=EventModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));

		$arrEvents=EventModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrEvents',$arrEvents);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		// 活动类型赋值，根据活动类型来取得活动
		$arrEventcategorys=EventcategoryModel::F('eventcategory_parentid=?',0)->order('eventcategory_sort ASC')->getAll();
		
		$this->assign('arrEventcategorys',$arrEventcategorys);

		$this->display('public+event');
	}

	public function get_childEventcategory($nParentid){
		$arrEventcategorys=EventcategoryModel::F()->where(array('eventcategory_parentid'=>$nParentid))->getAll();
		
		return $arrEventcategorys;
	}

	public function event_title_(){
		return $this->_oEventcategory['eventcategory_name'].' - '.Dyhb::L('活动','Controller');
	}

	public function event_keywords_(){
		return $this->event_title_();
	}

	public function event_description_(){
		return $this->event_title_();
	}

}
