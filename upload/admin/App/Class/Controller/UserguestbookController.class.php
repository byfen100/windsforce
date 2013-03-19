<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户留言控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserguestbookController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['userguestbook_name']=array('like',"%".G::getGpc('userguestbook_name')."%");

		// 附件检索
		$nTuid=intval(G::getGpc('tuid','G'));
		if($nTuid){
			$oTouser=UserModel::F('user_id=?',$nTuid)->getOne();

			if(!empty($oTouser['user_id'])){
				$arrMap['userguestbook_userid']=$nTuid;
				$this->assign('oTouser',$oTouser);
			}
		}

		// 用户检索
		$nUid=intval(G::getGpc('uid','G'));
		if($nUid){
			$oUser=UserModel::F('user_id=?',$nUid)->getOne();

			if(!empty($oUser['user_id'])){
				$arrMap['user_id']=$nUid;
				$this->assign('oUser',$oUser);
			}
		}
	}

	public function add(){
		$this->E(Dyhb::L('后台无法添加用户留言','Controller/Userguestbook'));
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);

		// 将用户留言子留言的父级ID改为当前的留言的父级ID(节点移位)
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oUserguestbook=UserguestbookModel::F('userguestbook_id=?',$nId)->getOne();

				if(!empty($oUserguestbook['userguestbook_id'])){
					$arrUserchildguestbooks=UserguestbookModel::F('userguestbook_parentid=?',$nId)->getAll();

					if(is_array($arrUserchildguestbooks)){
						foreach($arrUserchildguestbooks as $oUserchildguestbook){
							$oUserchildguestbook->userguestbook_parentid=$oUserguestbook['userguestbook_parentid'];
							$oUserchildguestbook->save(0,'update');

							if($oUserchildguestbook->isError()){
								$this->E($oUserchildguestbook->getErrorMessage());
							}
						}
					}
				}
			}
		}
	}

}
