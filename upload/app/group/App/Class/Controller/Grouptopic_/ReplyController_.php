<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子回复控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ReplyController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		if(empty($nId)){
			$this->E('无法找到该主题');
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nId)->getOne();
		if(empty($oGrouptopic->grouptopic_id)){
			$this->E('你访问的主题不存在');
		}
		$this->assign('oGrouptopic',$oGrouptopic);

		$this->display('grouptopic+reply');
	}
}
