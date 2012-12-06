<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   短消息控制器($)*/

!defined('DYHB_PATH') && exit;

require_once(Core_Extend::includeFile('function/Pm_Extend'));

class PmController extends InitController{

	public function init__(){
		parent::init__();
		$this->is_login();
	}

	public function dialog_add(){
		$this->check_pm();
		
		$nUserid=intval(G::getGpc('uid','G'));

		$sUserName='';
		if(!empty($nUserid)){
			$oUser=UserModel::F('user_id=?',$nUserid)->getOne();
			if(!empty($oUser['user_id'])){
				$sUserName=$oUser['user_name'];
			}
		}

		$this->assign('sUserName',$sUserName);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['pmsend_seccode']);

		$this->display('pm+dialogadd');
	}

	public function pmnew(){
		$this->check_pm();
		
		$nUserId=intval(G::getGpc('uid'));
		$nPmId=intval(G::getGpc('pmid'));
		
		if(!empty($nUserId)){
			$oUser=UserModel::F('user_id=?',$nUserId)->query();
			$sUserName=$oUser['user_name'];
		}else{
			$sUserName='';
		}
		
		if(!empty($nPmId)){
			$oPm=PmModel::F('pm_id=? AND pm_status=1',$nPmId)->query();
			
			if(!empty($nUserId) && !empty($oPm['pm_id'])){
				$this->assign('oPm',$oPm);

				// 回复短消息的同时也查看了短消息，所以这里将短消息标记为已经阅读
				if($oPm->pm_isread==0){
					$oPm->pm_isread=1;
					$oPm->save(0,'update');

					if($oPm->isError()){
						$this->E($oPm->getErrorMessage());
					}
				}
			}
			
			if(!empty($oPm['pm_id'])){
				$sContent=" \r\n[hr][pm]".$oPm['pm_id']."[/pm]\r\n";
				$sContent.=$oPm['pm_message']."\r\n";
			}else{
				$sContent='';
			}
		}else{
			$sContent='';
		}
		
		$this->assign('sUserName',$sUserName);
		$this->assign('sContent',$sContent);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['pmsend_seccode']);
		$this->assign('sType','pmnew');
		
		$this->display('pm+new');
	}

	public function pmnew_title_(){
		return '新建短消息';
	}

	public function pmnew_keywords_(){
		return $this->pmnew_title_();
	}

	public function pmnew_description_(){
		return $this->pmnew_title_();
	}
	
	public function sendpm(){
		$this->check_pm();
		
		$arrOptionData=$GLOBALS['_option_'];
		
		if($arrOptionData['pmsend_seccode']==1){
			$this->check_seccode(true);
		}
		
		$sMessageto=trim(G::getGpc('messageto'));
		$sPmMessage=trim(G::getGpc('pm_message'));
		$sPmSubject=trim(G::getGpc('pm_subject'));
		
		if(empty($sMessageto)){
			$this->E(Dyhb::L('收件用户不能为空','Controller/Pm'));
		}
		
		$arrUsers=Core_Extend::segmentUsername($sMessageto);
		
		$oLastPmModel=null;
		foreach($arrUsers as $sUser){
			if(empty($sUser)){
				continue;
			}
				
			if($sUser==$GLOBALS['___login___']['user_name']){
				$this->E(Dyhb::L('收件用户中不能有自己','Controller/Pm'));
			}
			
			if(!preg_match("/[^\d-.,]/",$sUser)){
				$oTryUser=UserModel::F('user_id=? AND user_status=1',$sUser)->getOne();
			}else{
				$oTryUser=UserModel::F('user_name=? AND user_status=1',$sUser)->getOne();
			}

			if(empty($oTryUser['user_id'])){
				$this->E(Dyhb::L('用户 %s 不存在或者尚未审核通过','Controller/Pm',null,$sUser));
			}

			$arrUserInfo=$GLOBALS['___login___'];
		
			$oPmModel=Dyhb::instance('PmModel');
			$oLastPmModel=$oPmModel->sendAPm($sUser,$arrUserInfo['user_id'],$arrUserInfo['user_name'],$sPmSubject,'home');
		
			if($oPmModel->isError()){
				$this->E($oPmModel->getErrorMessage());
			}
		}
		
		if(G::getGpc('type')=='back'){
			$arrData=$oLastPmModel->toArray();
			$arrData['jumpurl']=($GLOBALS['_commonConfig_']['URL_MODEL'] && $GLOBALS['_commonConfig_']['URL_MODEL']!=3?'?':'&').
				'extra=new'.$arrData['pm_id'].'#pm-'.$arrData['pm_id'];

			$this->A($arrData,Dyhb::L('发送短消息成功','Controller/Pm'),1);
		}else{
			$arrData=$oLastPmModel->toArray();
			$arrData['jumpurl']=Dyhb::U('home://pm/show?id='.$arrData['pm_id'].'&muid='.$arrData['pm_msgfromid']);
			
			$this->A($arrData,Dyhb::L('发送短消息成功','Controller/Pm'),1);
		}
	}
	
	protected function check_pm(){
		$arrOptionData=$GLOBALS['_option_'];
		
		if($arrOptionData['pm_status']==0){
			$this->E(Dyhb::L('短消息功能尚未开启','Controller/Pm'));
		}
		
		if($arrOptionData['pmsend_regdays']>0){
			if(CURRENT_TIMESTAMP-$GLOBALS['___login___']['create_dateline']<86400*$arrOptionData['pmsend_regdays']){
				$this->E(Dyhb::L('只有注册时间超过 %d 天的用户才能够发送短消息','Controller/Pm',null,$arrOptionData['pmsend_regdays']));
			}
		}
		
		if($arrOptionData['pmflood_ctrl']>0){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$nPmSpace=intval($arrOptionData['pmflood_ctrl']);
			
			$oPm=PmModel::F("pm_msgfromid=? AND {$nCurrentTimeStamp}-create_dateline<{$nPmSpace}",$GLOBALS['___login___']['user_id'])->query();
			if(!empty($oPm['pm_id'])){
				$this->E(Dyhb::L('每 %d 秒你才能发送一次短消息','Controller/Pm',null,$nPmSpace));
			}
		}
		
		if($arrOptionData['pmlimit_oneday']>0){
			$arrNowDate=Core_Extend::getBeginEndDay();
			
			$nPms=PmModel::F("create_dateline<{$arrNowDate[1]} AND create_dateline>{$arrNowDate[0]} AND pm_msgfromid=?",$GLOBALS['___login___']['user_id'])->all()->getCounts();
			if($nPms>$arrOptionData['pmlimit_oneday']){
				$this->E(Dyhb::L('一个用户每天最多只能发送 %d 条消息','Controller/Pm',null,$arrOptionData['pmlimit_oneday']));
			}
		}
	}
	
	public function index(){
		$arrWhere=array();
		
		$sType=trim(G::getGpc('type','G'));

		if(in_array($sType,array('system','systemnew'))){
			$sFormAction=Dyhb::U('home://pm/readselect');
		}elseif($sType=='my'){
			$sFormAction=Dyhb::U('home://pm/delmyselect');
		}else{
			$sFormAction=Dyhb::U('home://pm/delselect');
		}

		if($sType=='new'){
			$arrWhere['pm_isread']=0;
			$arrWhere['pm_type']='user';
		}elseif($sType=='system' || $sType=='systemnew'){
			$arrWhere['pm_type']='system';
		}else{
			$arrWhere['pm_type']='user';
		}

		if($sType!='system' && $sType!='systemnew'){
			if($sType=='my'){
				// 我发送的消息如果被对方删除了，这里status=1的话就无法取出来 && 我的发件箱状态为1
				$arrWhere['pm_msgfromid']=$GLOBALS['___login___']['user_id'];
				$arrWhere['pm_mystatus']=1;
			}else{
				$arrWhere['pm_status']=1;
				$arrWhere['pm_msgtoid']=$GLOBALS['___login___']['user_id'];
			}

			$arrReadPms=array();
		}else{
			// 已删短消息
			$arrSystemdeleteMessages=PmsystemdeleteModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
			if(is_array($arrSystemdeleteMessages)){
				foreach($arrSystemdeleteMessages as $oSystemdeleteMessage){
					$arrDeletePms[]=$oSystemdeleteMessage['pm_id'];
				}
			}else{
				$arrDeletePms=array();
			}

			$arrNotinPms=$arrDeletePms;

			// 已读短消息
			$arrSystemreadMessages=PmsystemreadModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
			if(is_array($arrSystemreadMessages)){
				foreach($arrSystemreadMessages as $oSystemreadMessage){
					$arrReadPms[]=$oSystemreadMessage['pm_id'];
					if($sType=='systemnew'){
						$arrNotinPms[]=$oSystemreadMessage['pm_id'];
					}
				}
			}else{
				$arrReadPms=array();
			}
			
			if(!empty($arrNotinPms)){
				$arrWhere['pm_id']=array('NOT IN',$arrNotinPms);
			}
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$nTotalRecord=PmModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['pm_list_num'],G::getGpc('page','G'));

		$arrPmLists=PmModel::F()->where($arrWhere)->all()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$arrOptionData['pm_list_num'])->getAll();
		$this->assign('nTotalPm',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrPmLists',$arrPmLists);
		$this->assign('sPmType',$sType);
		$this->assign('arrReadPms',$arrReadPms);
		$this->assign('sType',($sType?$sType:'user'));
		$this->assign('sFormAction',$sFormAction);

		$this->display('pm+index');
	}

	public function index_title_(){
		$sType=trim(G::getGpc('type','G'));
		switch($sType){
			case 'new':
				return '未读短消息';
				break;
			case 'user':
				return '私人短消息';
				break;
			case 'my':
				return '已发短消息';
				break;
			case 'systemnew':
				return '未读公共短消息';
				break;
			case 'system':
				return '公共短消息';
				break;
			default:
				return '私人短消息';
				break;
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

	public function del_one_pm($nId='',$nUserId='',$nFromId=''){
		$nOldId=$nId;
		
		if(empty($nId)){
			$nId=G::getGpc('id');
		}
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定要删除的短消息','Controller/Pm'));
		}
		
		if(empty($nUserId)){
			$nUserId=$GLOBALS['___login___']['user_id'];
		}
		
		if(empty($nFromId) && $nFromId!==0){
			$nFromId=G::getGpc('uid');;
		}
		
		$oPmModel=PmModel::F("pm_id=? AND pm_type='user' AND pm_msgtoid=? ".($nFromId!==0?'AND pm_msgfromid='.$nFromId:''),$nId,$nUserId)->query();
		if(empty($oPmModel['pm_id'])){
			$this->E(Dyhb::L('待删除的短消息不存在','Controller/Pm'));
		}
		
		$oPmModel->pm_status=0;
		$oPmModel->save(0,'update');
		
		if($oPmModel->isError()){
			$this->E($oPmModel->getErrorMessage());
		}else{
			if(empty($nOldId)){
				$this->S(Dyhb::L('删除短消息成功','Controller/Pm'));
			}
		}
	}

	public function delselect(){
		$arrPmIds=G::getGpc('pmid','P');
		$arrUserId=G::getGpc('uid','P');
		
		if(empty($arrPmIds)){
			$this->E(Dyhb::L('你没有指定要删除的短消息','Controller/Pm'));
		}
		
		if(is_array($arrPmIds)){
			foreach($arrPmIds as $nPmId){
				$this->del_one_pm($nPmId,$GLOBALS['___login___']['user_id'],0);
			}
		}
		
		$this->S(Dyhb::L('删除短消息成功','Controller/Pm'));
	}

	public function del_my_one_pm($nId='',$nUserId=''){
		$nOldId=$nId;
		
		if(empty($nId)){
			$nId=G::getGpc('id');
		}
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定要删除的短消息','Controller/Pm'));
		}
		
		if(empty($nUserId)){
			$nUserId=$GLOBALS['___login___']['user_id'];
		}
		
		$oPmModel=PmModel::F("pm_id=? AND pm_msgfromid=? AND pm_type='user' AND pm_status=1",$nId,$nUserId)->query();
		if(empty($oPmModel['pm_id'])){
			$this->E(Dyhb::L('待删除的短消息不存在','Controller/Pm'));
		}
		
		$oPmModel->pm_mystatus=0;
		$oPmModel->save(0,'update');
		
		if($oPmModel->isError()){
			$this->E($oPmModel->getErrorMessage());
		}else{
			if(empty($nOldId)){
				$this->S(Dyhb::L('删除短消息成功','Controller/Pm'));
			}
		}
	}

	public function delmyselect(){
		$arrPmIds=G::getGpc('pmid','P');

		if(empty($arrPmIds)){
			$this->E(Dyhb::L('你没有指定要删除的短消息','Controller/Pm'));
		}
		
		if($arrPmIds){
			foreach($arrPmIds as $nPmId){
				$this->del_my_one_pm($nPmId,$GLOBALS['___login___']['user_id']);
			}
		}
		
		$this->S(Dyhb::L('删除短消息成功','Controller/Pm'));
	}

	public function readselect(){
		$arrPmIds=G::getGpc('pmid','P');
		
		if(empty($arrPmIds)){
			$this->E(Dyhb::L('你没有指定要标记的短消息','Controller/Pm'));
		}
		
		if($arrPmIds){
			foreach($arrPmIds as $nPmId){
				$this->read_system_message_($nPmId);
			}
		}
		
		$this->S(Dyhb::L('标记短消息已读成功','Controller/Pm'));
	}

	public function delete_systempm(){
		$arrPmIds=G::getGpc('pmid','P');

		if(is_array($arrPmIds)){
			foreach($arrPmIds as $nPmId){
					$this->delete_system_message_($nPmId);
			}
		}

		$this->S(Dyhb::L('删除系统短消息成功','Controller/Pm'));
	}

	public function show(){
		$arrWhere=array();
		
		$nPmId=G::getGpc('id');
		if($nPmId===null || $nPmId=='index'){
			$this->index();
			exit();
		}
		
		$oOnePm=PmModel::F('pm_id=?',$nPmId)->query();
		if(empty($oOnePm['pm_id'])){
			$this->page404();
		}
		if(!G::getGpc('muid','G') && $oOnePm['pm_status']==0){
			$this->page404();
		}
		
		// 系统消息
		if($oOnePm['pm_type']=='system'){
			$this->read_system_message_($oOnePm['pm_id']);
				
			$this->assign('oPm',$oOnePm);
			$this->assign('sType','system');
			$this->display('pm+singlesystem');
			
			exit();
		}

		$nUserId=intval(G::getGpc('uid'));

		// 读取用户发送的短消息
		if(empty($nUserId)){
			$nLoginUserId=intval(G::getGpc('uid'));
			
			if(empty($nLoginUserId)){
				$nLoginUserId=$GLOBALS['___login___']['user_id'];
			}
			
			$this->assign('oPm',$oOnePm);
			$this->assign('sType','my');
			$this->display('pm+singlemy');

			exit();
		}
		
		// 最近消息时间
		$sDate=G::getGpc('date','G');
		if(empty($sDate)){
			$sDate=3;
		}
		if($sDate!='all'){
			$arrWhere['create_dateline']=array('egt',(CURRENT_TIMESTAMP-$sDate*86400));
		}
		
		$arrWhere['pm_type']='user';
		$arrWhere['pm_status']=1;
		$arrWhere['pm_msgfromid']=array('exp',"in(".$oOnePm['pm_msgtoid'].",$nUserId)");
		$arrWhere['pm_id']=array('egt',$nPmId);
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];
	
		$nTotalRecord=PmModel::F()->where($arrWhere)->all()->getCounts();
		
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['pm_single_list_num'],G::getGpc('page','G'));
		
		$arrPms=PmModel::F()->where($arrWhere)->all()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$arrOptionData['pm_single_list_num'])->query();
		$this->assign('arrPms',$arrPms);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nTotalPm',$nTotalRecord);
		
		// 更新短消息状态
		$sReadPmids='';
		
		if(is_array($arrPms)){
			foreach($arrPms as $oPm){
				if($oPm['pm_isread']==0){
					$sReadPmids.=$oPm['pm_id'].',';
				}
			}

			if(!empty($sReadPmids)){
				$sReadPmids=rtrim($sReadPmids,',');
				$sReadPmids="AND `pm_id` IN ({$sReadPmids}) ";
			}
		}

		$oDb=Db::RUN();
		$sSql="UPDATE ".PmModel::F()->query()->getTablePrefix()."pm SET pm_isread=1 WHERE `pm_msgfromid`={$nUserId} AND `pm_status`=1 {$sReadPmids}".(!empty($arrWhere['create_dateline'])?" AND `create_dateline`>=".$arrWhere['create_dateline'][1]:'');
		$oDb->query($sSql);
	
		$this->assign('nUserId',$nUserId);
		$this->assign('sDate',$sDate);
		$this->assign('oPm',$oOnePm);
		$this->assign('sType','user');
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['pmsend_seccode']);
	
		// 导出数据
		if(G::getGpc('export')=='yes'){
			ob_end_clean();
			
			$sName='PM_'.$oOnePm['pm_msgfrom'].'_TO_'.UserModel::getUsernameById($oOnePm['pm_msgtoid']).'_'.date('Y_m_d_H_i_s',CURRENT_TIMESTAMP).'.html';
			
			header('Content-Encoding: none');
			header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')?'application/octetstream':'application/octet-stream'));
			header('Content-Disposition: attachment; filename="'.$sName.'"');
			header('Pragma: no-cache');
			header('Expires: 0');
			
			$this->assign('sCurrentTimestamp',date('Y-m-d H:i',CURRENT_TIMESTAMP));
			$this->assign('sVersion',NEEDFORBUG_SERVER_VERSION." Release ".NEEDFORBUG_SERVER_RELEASE);
			$this->assign('sBlogName',$GLOBALS['_option_']['site_name']);
			$this->assign('sBlogUrl',$GLOBALS['_option_']['site_url']);
			$this->display('pm+archive');
			
			exit;
		}
		
		$this->display('pm+message');
	}

	public function show_title_(){
		return '短消息聊天';
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function show_description_(){
		return $this->show_title_();
	}
	
	public function truncatepm(){
		$nUserId=G::getGpc('uid','G');
		
		$sDate=G::getGpc('date','G');
		if(empty($sDate)){
			$sDate=3;
		}
		
		$oDb=Db::RUN();
		$sSql="UPDATE ".PmModel::F()->query()->getTablePrefix().
			"pm SET pm_status=0 WHERE `pm_msgfromid`={$nUserId} AND `pm_status`=1 AND `pm_msgtoid`=".
			$GLOBALS['___login___']['user_id'].
			($sDate!='all'?" AND `create_dateline`>=".(CURRENT_TIMESTAMP-$sDate*86400):'');
		$oDb->query($sSql);
		
		$this->assign('__JumpUrl__',Dyhb::U('home://pm/index?type=user'));
		$this->S(Dyhb::L('短消息清空成功','Controller/Pm'));
	}

	protected function read_system_message_($nPmId){
		$oPmsystemread=PmsystemreadModel::F('user_id=? AND pm_id=?',$GLOBALS['___login___']['user_id'],$nPmId)->query();

		if(!empty($oPmsystemread['user_id'])){
			return true;
		}else{
			$oPmsystemread=new PmsystemreadModel();
			$oPmsystemread->user_id=$GLOBALS['___login___']['user_id'];
			$oPmsystemread->pm_id=$nPmId;
			$oPmsystemread->save(0);
				
			if($oPmsystemread->isError()){
				$this->E($oPmsystemread->getErrorMessage());
			}

			return $oPmsystemread;
		}
	}

	protected function delete_system_message_($nPmId){
		$oPmsystemdelete=PmsystemdeleteModel::F('user_id=? AND pm_id=?',$GLOBALS['___login___']['user_id'],$nPmId)->query();

		if(!empty($oPmsystemdelete['user_id'])){
			return true;
		}else{
			$oPmsystemdelete=new PmsystemdeleteModel();
			$oPmsystemdelete->user_id=$GLOBALS['___login___']['user_id'];
			$oPmsystemdelete->pm_id=$nPmId;
			$oPmsystemdelete->save(0);
				
			if($oPmsystemdelete->isError()){
				$this->E($oPmsystemdelete->getErrorMessage());
			}

			return $oPmsystemdelete;
		}
	}

}
