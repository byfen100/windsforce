<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动详情控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入Home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

/** 定义Home的语言包 */
define('__APPHOME_COMMON_LANG__',WINDSFORCE_PATH.'/app/home/App/Lang/Admin');

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
		
		Core_Extend::loadCache('home_option');
		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 判断邮件等外部地址过来的查找评论地址
		$nIsolationCommentid=intval(G::getGpc('isolation_commentid','G'));
		if($nIsolationCommentid){
			$result=EventcommentModel::getCommenturlByid($nIsolationCommentid);
			if($result===false){
				$this->E(Dyhb::L('该条评论已被删除、屏蔽或者尚未通过审核','Controller'));
			}

			G::urlGoTo($result);
			exit();
		}

		if(!in_array($sType,array('user','attentionuser'))){
			$sType='';
		}

		$this->assign('oEvent',$oEvent);
		$this->assign('sType',$sType);

		$this->_oEvent=$oEvent;

		// 判断用户是否已经参加 && 已经感兴趣过 && 参加人数是否已满
		$oTryjoin=EventuserModel::F('event_id=? AND user_id=?',$oEvent['event_id'],$GLOBALS['___login___']['user_id'])->getOne();

		$oTryattention=EventattentionuserModel::F('event_id=? AND user_id=?',$oEvent['event_id'],$GLOBALS['___login___']['user_id'])->getOne();

		$bLimituser=false;
		if($oEvent['event_limitcount']){
			if($oEvent['event_limitcount']-$oEvent['event_joincount']<=0){
				$bLimituser=true;
			}
		}

		$this->assign('bEventend',$oEvent['event_endtime']<CURRENT_TIMESTAMP);
		$this->assign('bJoinuser',$GLOBALS['___login___']['user_id']==$oEvent['user_id'] || !empty($oTryjoin['event_id'])?true:false);
		$this->assign('bAttentionuser',!empty($oTryattention['event_id'])?true:false);
		$this->assign('bLimituser',$bLimituser);
		
		// 读取评论列表
		if(empty($sType)){
			$arrWhere=array();
			$arrWhere['eventcomment_parentid']=0;
			$arrWhere['eventcomment_status']=1;
			$arrWhere['event_id']=$nEventid;
	
			if($GLOBALS['___login___']['user_id']!=$oEvent['user_id']){
				$arrWhere['eventcomment_auditpass']=1;
				$this->assign('bAuditpass',false);
			}else{
				$this->assign('bAuditpass',true);
			}
	
			$nTotalRecord=EventcommentModel::F()->where($arrWhere)->all()->getCounts();
			$oPage=Page::RUN($nTotalRecord,$arrOptionData['homefreshcomment_list_num'],G::getGpc('page','G'));
			$arrEventcommentLists=EventcommentModel::F()->where($arrWhere)->all()->order('create_dateline ASC')->limit($oPage->returnPageStart(),$arrOptionData['homefreshcomment_list_num'])->getAll();
			
			$this->assign('sUsersite',UserprofileModel::getUserprofileById($GLOBALS['___login___']['user_id']));
			$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);
			$this->assign('nTotalEventcomment',$nTotalRecord);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
			$this->assign('arrEventcommentLists',$arrEventcommentLists);
		}

		// 读取成员
		if($sType=='user'){
			$arrWhere=array();
			$arrWhere['event_id']=$nEventid;

			$nTotalRecord=EventuserModel::F()->where($arrWhere)->all()->getCounts();

			$oPage=Page::RUN($nTotalRecord,36,G::getGpc('page','G'));

			$arrEventuserLists=EventuserModel::F()->where($arrWhere)->all()->order('eventuser_status ASC,create_dateline DESC')->limit($oPage->returnPageStart(),36)->getAll();

			$this->assign('nTotalEventuser',$nTotalRecord);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
			$this->assign('arrEventuserLists',$arrEventuserLists);
		}

		// 读取感兴趣用户
		if($sType=='attentionuser'){
			$nTotalRecord=EventattentionuserModel::F('event_id=?',$nEventid)->all()->getCounts();

			$oPage=Page::RUN($nTotalRecord,36,G::getGpc('page','G'));

			$arrEventuserLists=EventattentionuserModel::F('event_id=?',$nEventid)->all()->order('create_dateline DESC')->limit($oPage->returnPageStart(),36)->getAll();

			$this->assign('nTotalEventuser',$nTotalRecord);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
			$this->assign('arrEventuserLists',$arrEventuserLists);
		}

		// 取得最新参加的成员
		$arrNeweventusers=EventuserModel::F('eventuser_status=1 AND event_id=?',$nEventid)->order('create_dateline DESC')->limit(0,8)->getAll();

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
