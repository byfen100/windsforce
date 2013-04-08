<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子回复入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddreplyController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('tid'));
		$nSimple=intval(G::getGpc('simple_comment'));

		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('你没有登录无法回帖','Controller/Grouptopic'));
		}

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定回复主题的ID','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你回复的主题不存在','Controller/Grouptopic'));
		}

		$oGroup=GroupModel::F('group_id=?',$oGrouptopic->group_id)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('你回复的帖子所在小组不存在','Controller/Grouptopic'));
		}

		// 快捷回复兼容性
		if($nSimple==1){
			foreach(array('message','name','email','url') as $sTemp){
				if(isset($_POST['simple_grouptopiccomment_'.$sTemp])){
					$_POST['grouptopiccomment_'.$sTemp]=$_POST['simple_grouptopiccomment_'.$sTemp];
					unset($_POST['simple_grouptopiccomment_'.$sTemp]);
				}
			}
		}

		if(isset($_POST['grouptopiccomment_message'])){
			$sContent=trim($_POST['grouptopiccomment_message']);
		}else{
			$sContent=trim($_GET['grouptopiccomment_message']);
		}

		$sContent=trim($sContent,'<br />');

		// 保存回复数据
		$oGrouptopiccomment=new GrouptopiccommentModel();	
		$oGrouptopiccomment->grouptopiccomment_content=$sContent;
		$oGrouptopiccomment->grouptopic_id=$nId;
		$oGrouptopiccomment->save(0);

		if($oGrouptopiccomment->isError()){
			$this->E($oGrouptopiccomment->getErrorMessage());
		}

		// 更新帖子的最后更新回复
		$arrLatestData=array(
			'commenttime'=>$oGrouptopiccomment->create_dateline,
			'commentid'=>$oGrouptopiccomment->grouptopiccomment_id,
			'tid'=>$oGrouptopic->grouptopic_id,
			'commentuserid'=>$GLOBALS['___login___']['user_id']
		);

		$oGrouptopic->grouptopic_latestcomment=serialize($arrLatestData);
		$oGrouptopic->setAutoFill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 更新小组的最后更新数据
		$arrLatestData['commenttitle']=$oGrouptopic->grouptopic_title;
		$nCommnum=GrouptopicModel::F('group_id=?',$oGrouptopic->group_id)->getSum('grouptopic_comments');
		
		$oGroup->group_latestcomment=serialize($arrLatestData);
		$oGroup->group_topiccomment=$nCommnum;
		$oGroup->save(0,'update');

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		// 更新保存帖子的评论数量
		$oGrouptopic->grouptopic_comments=GrouptopiccommentModel::F('grouptopic_id=?',$nId)->all()->getCounts();
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		$nTotalComment=GrouptopiccommentModel::F('grouptopic_id=?',$oGrouptopic->grouptopic_id)->getCounts();
		$nPage=ceil($nTotalComment/$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum']);
		
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic->grouptopic_id.($nPage>1?'&page='.$nPage:'').'&extra=new'.$oGrouptopiccomment->grouptopiccomment_id).'#grouptopiccomment-'.($oGrouptopiccomment->grouptopiccomment_id);

		$this->A(array('url'=>$sUrl),Dyhb::L('回复成功','Controller/Grouptopic'),1);
	}

}
