<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['grouptopic_title']=array('like','%'.G::getGpc('grouptopic_title').'%');

		// 小组检索
		$nGid=intval(G::getGpc('gid','G'));
		if($nGid){
			$oGroup=GroupModel::F('group_id=?',$nGid)->getOne();

			if(!empty($oGroup['group_id'])){
				$arrMap['group_id']=$nGid;
				$this->assign('oGroup',$oGroup);
			}
		}

		// 分类检索
		if(isset($_GET['cid'])){
			$nCid=$_GET['cid'];

			if($nCid=='-1'){
				$arrMap['grouptopiccategory_id']='-1';
				$this->assign('nCid',$nCid);
			}else{
				$oGrouptopiccategory=GrouptopiccategoryModel::F('grouptopiccategory_id=?',$nCid)->getOne();

				if(!empty($oGrouptopiccategory['grouptopiccategory_id'])){
					$arrMap['grouptopiccategory_id']=$nCid;
					$this->assign('oGrouptopiccategory',$oGrouptopiccategory);
				}
			}
		}

		// 回收站检索
		if(isset($_GET['status']) && $_GET['status']==0){
			$arrMap['grouptopic_status']='0';
			$this->assign('bRecyclebin',true);
		}
	}
	
	public function index($sModel=null,$bDisplay=true){
		parent::index('grouptopic',false);

		$this->display(Admin_Extend::template('group','grouptopic/index'));
	}
	
	public function add(){
		$this->E(Dyhb::L('后台无法发布帖子','__APP_ADMIN_LANG__@Controller/Grouptopic').'<br/><a href="'.Core_Extend::windsforceOuter('app=group&c=grouptopic&a=add').'" target="_blank">'.Dyhb::L('前往发布','__APP_ADMIN_LANG__@Controller/Grouptopic').'</a>');
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('grouptopic',$nId,false);
		$this->display(Admin_Extend::template('group','grouptopic/add'));
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('grouptopic',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		parent::foreverdelete('grouptopic',$sId);
	}

	protected function aForeverdelete($sId){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		
		// 整理帖子相关信息
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				// 删除帖子回复
				$oGrouptopiccommentMeta=GrouptopiccommentModel::M();
				$oGrouptopiccommentMeta->deleteWhere(array('grouptopic_id'=>$nId));

				if($oGrouptopiccommentMeta->isError()){
					$this->E($oGrouptopiccommentMeta->getErrorMessage());
				}

				// 这里无法更新小组帖子数量，只有通过系统工具来修改小组主题总数
			}
		}
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::forbid('grouptopic',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::resume('grouptopic',$nId,true);
	}

}
