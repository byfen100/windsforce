<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   处理帖子编辑控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SubmiteditController extends GlobalchildController{

	public function index(){
		$nGid=intval(G::getGpc('group_id'));
		$nTid=intval(G::getGpc('grouptopic_id'));

		$oGrouptopic=GrouptopicModel::F('group_id=? AND grouptopic_id=?',$nGid,$nTid)->getOne();
		if(empty($oGrouptopic->group_id)){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if(!Groupadmin_Extend::checkTopicedit($oGrouptopic)){
			$this->E(Dyhb::L('你没有权限编辑帖子','Controller/Grouptopic'));
		}

		// 处理checkbox
		$arrCheckbox=array(
			'grouptopic_usesign','grouptopic_isanonymous','grouptopic_hiddenreplies',
			'grouptopic_ordertype','grouptopic_allownoticeauthor','grouptopic_iscomment',
			'grouptopic_sticktopic','grouptopic_addtodigest','grouptopic_isrecommend',
			'grouptopic_onlycommentview',
		);

		foreach($arrCheckbox as $sCheckbox){
			if(!isset($_POST[$sCheckbox])){
				$_POST[$sCheckbox]=0;
			}
		}

		if($GLOBALS['_option_']['seccode_publish_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		$nIsrecommend=$oGrouptopic->grouptopic_isrecommend;
		$nAddtodigest=$oGrouptopic->grouptopic_addtodigest;
		$nSticktopic=$oGrouptopic->grouptopic_sticktopic;
		
		$oGrouptopic->grouptopic_updateusername=$GLOBALS['___login___']['user_name'];
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 更新积分
		if($oGrouptopic->grouptopic_addtodigest>0 && $nAddtodigest<$oGrouptopic->grouptopic_addtodigest){
			Core_Extend::updateCreditByAction('group_topicdigest'.$oGrouptopic['grouptopic_addtodigest'],$oGrouptopic['user_id']);
		}

		if($oGrouptopic->grouptopic_sticktopic>0 && $nSticktopic<$oGrouptopic->grouptopic_sticktopic){
			Core_Extend::updateCreditByAction('group_topicstick'.$oGrouptopic['grouptopic_sticktopic'],$oGrouptopic['user_id']);
		}

		if($oGrouptopic->grouptopic_isrecommend>0 && $nIsrecommend<$oGrouptopic->grouptopic_isrecommend){
			Core_Extend::updateCreditByAction('group_trecommend'.$oGrouptopic['grouptopic_isrecommend'],$oGrouptopic['user_id']);
		}

		// 保存帖子标签
		$sTags=trim(G::getGpc('tags','P'));
		$sOldTags=trim(G::getGpc('old_tags','P'));

		$oGrouptopictag=Dyhb::instance('GrouptopictagModel');
		$oGrouptopictag->addTag($oGrouptopic->grouptopic_id,$sTags,$sOldTags);

		if($oGrouptopictag->isError()){
			$this->E($oGrouptopictag->getErrorMessage());
		}

		// 发送提醒
		if($GLOBALS['___login___']['user_id']!=$oGrouptopic['user_id']){
			$sGrouptopicmessage=G::subString(strip_tags($oGrouptopic['grouptopic_content']),0,100);
			
			$sNoticetemplate='<div class="notice_editgrouptopic"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('编辑了你的主题','Controller/Grouptopic').'&nbsp;<a href="{@grouptopic_link}">'.$oGrouptopic['grouptopic_title'].'</a></span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller/Grouptopic').'</div><div class="notice_action"><a href="{@grouptopic_link}">'.Dyhb::L('查看','Controller/Grouptopic').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@grouptopic_link'=>'group://grouptopic/view?id='.$oGrouptopic['grouptopic_id'],
				'content_message'=>$sGrouptopicmessage,
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oGrouptopic['user_id'],'editgrouptopic',$oGrouptopic['grouptopic_id']);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$sUrl=Dyhb::U('group://topic@?id='.$nTid);
		$this->A(array('url'=>$sUrl),Dyhb::L('主题编辑成功','Controller/Grouptopic'),1);
	}

}
