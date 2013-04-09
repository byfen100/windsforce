<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑帖子回复控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SubmitreplyController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('editcid'));

		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('你没有登录无法编辑回帖','Controller/Grouptopic'));
		}

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定编辑回帖的ID','Controller/Grouptopic'));
		}

		$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nId)->getOne();
		if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
			$this->E(Dyhb::L('你编辑的回帖不存在','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$oGrouptopiccomment['grouptopic_id'])->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你要编辑的回帖的主题不存在','Controller/Grouptopic'));
		}

		$sContent=trim($_POST['grouptopiccomment_message']);
		$sContent=rtrim($sContent,'<br />');

		// 保存回复数据
		$oGrouptopiccomment->grouptopiccomment_content=$sContent;
		$oGrouptopiccomment->save(0,'update');

		if($oGrouptopiccomment->isError()){
			$this->E($oGrouptopiccomment->getErrorMessage());
		}

		$nTotalComment=GrouptopiccommentModel::F('grouptopic_id=?',$oGrouptopic->grouptopic_id)->getCounts();
		$nPage=ceil($nTotalComment/$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum']);
		
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic->grouptopic_id.($nPage>1?'&page='.$nPage:'').'&extra=new'.$oGrouptopiccomment->grouptopiccomment_id).'#grouptopiccomment-'.($oGrouptopiccomment->grouptopiccomment_id);

		$this->A(array('url'=>$sUrl),Dyhb::L('编辑回帖成功','Controller/Grouptopic'),1);
	}

}
