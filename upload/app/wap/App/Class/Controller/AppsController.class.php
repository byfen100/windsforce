<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Wap应用($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppsController extends InitController{
	
	public function index(){
		$arrAppinfos=array();
		
		$arrApps=AppModel::F('app_status=?',1)->order('app_id DESC')->getAll();
		if(is_array($arrApps)){
			foreach($arrApps as $oApp){
				if(is_file(WINDSFORCE_PATH.'/app/'.$oApp['app_identifier'].'/App/Class/Controller/WapController.class.php')){
					$arrAppinfos[$oApp['app_identifier']]=$oApp->toArray();
					$arrAppinfos[$oApp['app_identifier']]['logo']=is_file(WINDSFORCE_PATH.'/app/'.$oApp['app_identifier'].'/logo.png')?
						__ROOT__.'/app/'.$oApp['app_identifier'].'/logo.png':
						__ROOT__.'/app/logo.png';
				}
			}
		}

		$this->assign('nApps',count($arrAppinfos));
		$this->assign('arrAppinfos',$arrAppinfos);

		$this->display('apps+index');
	}

	public function index_title_(){
		return Dyhb::L('应用列表','Controller/Apps');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
