<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   其他应用个人空间($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppsController extends Controller{

	public $_oUserInfo=null;

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->_oUserInfo=$oUserInfo;

		$arrAppinfos=array();

		$arrApps=AppModel::F('app_status=?',1)->order('app_id DESC')->getAll();
		if(is_array($arrApps)){
			foreach($arrApps as $oApp){
				if(is_file(WINDSFORCE_PATH.'/app/'.$oApp['app_identifier'].'/App/Class/Controller/SpaceController.class.php')){
					$arrAppinfos[$oApp['app_identifier']]=$oApp->toArray();
					$arrAppinfos[$oApp['app_identifier']]['logo']=is_file(WINDSFORCE_PATH.'/app/'.$oApp['app_identifier'].'/logo.png')?
						__ROOT__.'/app/'.$oApp['app_identifier'].'/logo.png':
						__ROOT__.'/app/logo.png';
				}
			}
		}

		$this->assign('nApps',count($arrAppinfos));
		$this->assign('arrAppinfos',$arrAppinfos);

		$this->assign('nId',$nId);

		$this->display('space+apps');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('应用个人空间','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
