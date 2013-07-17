<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动详情控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	protected $_oEvent=null;
	
	public function index(){
		$nEventid=intval(G::getGpc('id','G'));
		$sType=trim(G::getGpc('type','G'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要浏览的活动不存在','Controller'));
		}

		if(!in_array($sType,array('user','attentionuser'))){
			$sType='';
		}

		$this->assign('oEvent',$oEvent);
		$this->assign('sType',$sType);

		$this->_oEvent=$oEvent;

		// 取得活动发起者
		$oAdminuser=EventuserModel::F('eventuser_status=1 AND event_id=? AND eventuser_admin=1',$nEventid)->getOne();

		$this->assign('oAdminuser',$oAdminuser);

		// 取得最新参加的成员
		$arrNeweventusers=EventuserModel::F('eventuser_status=1 AND event_id=? AND eventuser_admin=0',$nEventid)->order('create_dateline DESC')->limit(0,8)->getAll();

		$this->assign('arrNeweventusers',$arrNeweventusers);

		// 取得最新感兴趣的成员
		$arrNeweventattentionusers=EventattentionuserModel::F('event_id=?',$nEventid)->order('create_dateline DESC')->limit(0,8)->getAll();

		$this->assign('arrNeweventattentionusers',$arrNeweventattentionusers);

		if(!empty($sType)){
			$this->display('event+'.$sType);
		}else{
			$this->display('event+show');
		}
	}

	public function show_title_(){
		return $this->_oEvent->event_title;
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function add_description_(){
		return $this->show_title_();
	}

}
