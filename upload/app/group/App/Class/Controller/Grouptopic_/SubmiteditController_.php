<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   处理帖子编辑控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SubmiteditController extends Controller{

	public function index(){
		$nGid=intval(G::getGpc('group_id'));
		$nTid=intval(G::getGpc('grouptopic_id'));

		$oGrouptopic=GrouptopicModel::F('group_id=? AND grouptopic_id=?',$nGid,$nTid)->getOne();
		if(empty($oGrouptopic->group_id)){
			$this->E('主题编辑失败');
		}

		$oGrouptopic->grouptopic_updateusername=$GLOBALS['___login___']['user_name'];
		$oGrouptopic->save(0,'update');
		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		$sUrl=Dyhb::U('group://topic@?id='.$nTid);
		$this->A(array('url'=>$sUrl),'主题编辑成功',1);
	}

}
