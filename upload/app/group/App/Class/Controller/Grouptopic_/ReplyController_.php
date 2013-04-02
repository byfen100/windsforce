<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子回复控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ReplyController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定主题的ID','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller/Grouptopic'));
		}

		$this->assign('oGrouptopic',$oGrouptopic);

		$this->display('grouptopic+reply');
	}

}
