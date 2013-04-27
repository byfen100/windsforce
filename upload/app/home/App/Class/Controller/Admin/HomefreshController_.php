<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   站点新鲜事控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class HomefreshController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['homefresh_title']=array('like','%'.G::getGpc('homefresh_title').'%');

		$nUid=intval(G::getGpc('uid','G'));
		if($nUid){
			$oUser=UserModel::F('user_id=?',$nUid)->getOne();

			if(!empty($oUser['user_id'])){
				$arrMap['user_id']=$nUid;
				$this->assign('oUser',$oUser);
			}
		}
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('homefresh',false);

		$this->display(Admin_Extend::template('home','homefresh/index'));
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::forbid('homefresh',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::resume('homefresh',$nId,true);
	}
	
	public function add(){
		$this->E(Dyhb::L('后台无法添加新鲜事','__APP_ADMIN_LANG__@Controller/Homefresh'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('homefresh',$nId,false);
		$this->display(Admin_Extend::template('home','homefresh/add'));
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');

		parent::update('homefresh',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);

		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oHomefreshcommentMeta=HomefreshcommentModel::M();
				$oHomefreshcommentMeta->deleteWhere(array('homefresh_id'=>$nId));

				if($oHomefreshcommentMeta->isError()){
					$this->E($oHomefreshcommentMeta->getErrorMessage());
				}
			}
		}
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();
		
		parent::foreverdelete('homefresh',$sId);
	}
	
}
