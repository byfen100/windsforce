<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户控制器($)*/

!defined('DYHB_PATH') && exit;

class UserController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['user_id']=array('gt',1);
		$arrMap['user_name']=array('like','%'.G::getGpc('user_name').'%');
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function checkusername(){
		$sUserName=G::getGpc('user_name','R');

		if(!empty($sUserName)){
			$arrUser=UserModel::F()->getByuser_name($sUserName)->toArray();
			if(!empty($arrUser['user_id'])){
				$this->E(Dyhb::L('用户名已经存在','Controller/User'));
			}else{
				$this->S(Dyhb::L('用户名可以使用','Controller/User'));
			}
		}else{
			$this->E(Dyhb::L('用户名必须','Controller/User'));
		}
	}

	public function resetpassword(){
		$nId=intval(G::getGpc('id'));
		$sPassword=G::getGpc('password');
		if(!empty($sPassword)){
			UserModel::M()->changePassword($nId,$sPassword,null,true);
			if(UserModel::M()->isBehaviorError()){
				$this->E(UserModel::M()->getBehaviorErrorMessage());
			}else{
				$oUser=UserModel::F('user_id=?',$nId)->query();
				if($oUser->isError()){
					$this->E($oUser->getErrorMessage());
				}
				$this->S(Dyhb::L('密码修改成功','Controller/User'));
			}
		}else{
			$this->E(Dyhb::L('密码不能为空','Controller/User'));
		}
	}

	public function bForbid_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_user($nId)){
			$this->E(Dyhb::L('系统用户无法禁用','Controller/User'));
		}
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_user($nId)){
				$this->E(Dyhb::L('系统用户无法删除','Controller/User'));
			}
		}
	}

	protected function aInsert($nId=null){
		// 写入注册用户的关联数据
		$oUserprofile=new UserprofileModel();
		$oUserprofile->user_id=$nId;
		$oUserprofile->save(0);

		$oUserCount=new UsercountModel();
		$oUserCount->user_id=$oUser->user_id;
		$oUserCount->save(0);

		$this->cache_site_();
	}

	public function aForeverdelete($sId){
		$arrIds=explode(',',$sId);

		foreach($arrIds as $nId){
			$oUserprofileMeta=UserprofileModel::M();
			$oUserprofileMeta->deleteWhere(array('user_id'=>$nId));

			if($oUserprofileMeta->isError()){
				$this->E($oUserprofileMeta->getErrorMessage());
			}

			$oUsercountMeta=UsercountModel::M();
			$oUsercountMeta->deleteWhere(array('user_id'=>$nId));
			
			if($oUsercountMeta->isError()){
				$this->E($oUsercountMeta->getErrorMessage());
			}
		}

		$this->cache_site_();
	}

	public function is_system_user($nId){
		if($nId==1){
			return true;
		}

		return false;
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("site");
	}

	protected function aUpdate($nId=null){
		$this->cache_site_();
	}

	public function show(){
		$nId=G::getGpc('id','G');

		if(!empty($nId)){
			$oUserModel=UserModel::F('user_id=?',$nId)->getOne();

			if(!empty($oUserModel['user_id'])){
				$this->assign('oValue',$oUserModel);
				$this->assign('nId',$nId);
				
				require_once(Core_Extend::includeFile('function/Credit_Extend'));
				
				$arrAvailableExtendCredits=array();
				$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();

				$arrData=array();
				foreach($arrAvailableExtendCredits as $nKey=>$arrValue){
					$arrData[$nKey]['title']="creditrule_extendcredit{$nKey} ({$arrValue['title']})";
					$arrData[$nKey]['value']=$oUserModel->usercount->{'usercount_extendcredit'.$nKey};
				}
				
				$this->assign('arrUserCounts',$arrData);

				$sDo=G::getGpc('do','G');
				if(!in_array($sDo,array('','base','contact','edu','work','info'))){
					$sDo='';
				}

				$this->assign('sDo',$sDo);

				require_once(Core_Extend::includeFile('function/Profile_Extend'));

				Core_Extend::loadCache('userprofilesetting');
				$this->assign('arrUserprofilesettingDatas',$GLOBALS['_cache_']['userprofilesetting']);

				$arrUserprofile=$oUserModel->userprofile;

				$this->assign('sDirthDistrict',Profile_Extend::getDistrict($arrUserprofile,'birth',false));
				$this->assign('sResideDistrict',Profile_Extend::getDistrict($arrUserprofile,'reside',false));

				// 视图
				$arrProfileSetting=Profile_Extend::getProfileSetting();

				$this->assign('arrBases',$arrProfileSetting[0]);
				$this->assign('arrContacts',$arrProfileSetting[1]);
				$this->assign('arrEdus',$arrProfileSetting[2]);
				$this->assign('arrWorks',$arrProfileSetting[3]);
				$this->assign('arrInfos',$arrProfileSetting[4]);

				$arrInfoMenus=Profile_Extend::getInfoMenu();

				$this->assign('arrInfoMenus',$arrInfoMenus);

				$this->display('user+show');
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

}
