<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   社会化帐号管理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 导入社会化登录组件
Dyhb::import(WINDSFORCE_PATH.'/source/extension/socialization');

class IndexController extends Controller{

	public function index(){
		Socia::clearCookie();
		Core_Extend::loadCache('sociatype');

		$arrBindeds=$GLOBALS['_cache_']['sociatype'];
		$arrBindedData=array();

		$arrSociausers=SociauserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
		if(is_array($arrSociausers)){
			foreach($arrSociausers as $oSociauser){
				if(isset($arrBindeds[$oSociauser['sociauser_vendor']])){
					$arrBindedData[]=$oSociauser['sociauser_vendor'];
				}
			}
		}

		$this->assign('arrBindedData',$arrBindedData);
		$this->assign('arrBindeds',$arrBindeds);

		$this->display('spaceadmin+socia');
	}

	public function socia_title_(){
		return Dyhb::L('社会化帐号','Controller');
	}

	public function socia_keywords_(){
		return $this->socia_title_();
	}

	public function socia_description_(){
		return $this->socia_title_();
	}

}
