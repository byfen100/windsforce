<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户留言审核($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AudituserguestbookController extends GlobalchildController{

	public function index(){
		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('你没有登录，无法留言','Controller'));
		}

		$nId=intval(G::getGpc('id','G'));
		$nStatus=intval(G::getGpc('status','G'));

		$oUserguestbook=UserguestbookModel::F('userguestbook_id=? AND userguestbook_status=1',$nId)->getOne();
		if(empty($oUserguestbook['userguestbook_id'])){
			$this->E(Dyhb::L('待操作的留言不存在或者已被系统屏蔽','Controller'));
		}

		$oUserguestbook->userguestbook_auditpass=$nStatus;
		$oUserguestbook->save(0,'update');

		if($oUserguestbook->isError()){
			$this->E($oUserguestbook->getErrorMessage());
		}

		$this->S(Dyhb::L('留言操作成功','Controller'));
	}

}
