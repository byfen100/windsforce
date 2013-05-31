<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   站点新鲜事评论控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class HomefreshcommentController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['homefreshcomment_name']=array('like','%'.G::getGpc('homefreshcomment_name').'%');

		// 新鲜事检索
		$nFid=intval(G::getGpc('fid','G'));
		if($nFid){
			$oHomefresh=HomefreshModel::F('homefresh_id=?',$nFid)->getOne();

			if(!empty($oHomefresh['homefresh_id'])){
				$arrMap['homefresh_id']=$nFid;
				$this->assign('oHomefresh',$oHomefresh);
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

	public function index($sModel=null,$bDisplay=true){
		parent::index('homefreshcomment',false);

		$this->display(Admin_Extend::template('home','homefreshcomment/index'));
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::forbid('homefreshcomment',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::resume('homefreshcomment',$nId,true);
	}

	public function add(){
		$this->E(Dyhb::L('后台无法添加新鲜事评论','__APPHOME_COMMON_LANG__@Controller'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('homefreshcomment',$nId,false);
		$this->display(Admin_Extend::template('home','homefreshcomment/add'));
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('homefreshcomment',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);

		// 将新鲜事评论子评论的父级ID改为当前的评论的父级ID(节点移位)
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oHomefreshcomment=HomefreshcommentModel::F('homefreshcomment_id=?',$nId)->getOne();

				if(!empty($oHomefreshcomment['homefreshcomment_id'])){
					$arrHomefreshchildcomments=HomefreshcommentModel::F('homefreshcomment_parentid=?',$nId)->getAll();

					if(is_array($arrHomefreshchildcomments)){
						foreach($arrHomefreshchildcomments as $oHomefreshchildcomment){
							$oHomefreshchildcomment->homefreshcomment_parentid=$oHomefreshcomment['homefreshcomment_parentid'];
							$oHomefreshchildcomment->save(0,'update');

							if($oHomefreshchildcomment->isError()){
								$this->E($oHomefreshchildcomment->getErrorMessage());
							}
						}
					}
				}
			}
		}
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();
		
		parent::foreverdelete('homefreshcomment',$sId);
	}

	protected function aForeverdelete($sId){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		
		// 更新新鲜事评论数量
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oHomefreshcomment=HomefreshcommentModel::F('homefreshcomment_id=?',$nId)->getOne();
				
				if(!empty($oHomefreshcomment['homefreshcomment_id'])){
					// 更新评论数量
					$oHomefresh=Dyhb::instance('HomefreshModel');
					$oHomefresh->updateHomefreshcommentnum(intval($oHomefreshcomment['homefresh_id']));

					if($oHomefresh->isError()){
						$oHomefresh->getErrorMessage();
					}
				}
			}
		}
	}
	
}
