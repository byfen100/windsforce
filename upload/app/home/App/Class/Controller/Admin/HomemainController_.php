<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主页入口控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入主页模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

class HomemainController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$sType=trim(G::getGpc('type','G'));

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$this->assign('nId',intval(G::getGpc('id','G')));
		$this->assign('arrOptions',$arrOptionData);
		
		$this->display(Admin_Extend::template('home','homeoption/'.($sType?$sType:'index')));
	}

	public function update_option(){
		$arrOptions=G::getGpc('options','P');

		foreach($arrOptions as $sKey=>$val){
			$val=trim($val);
			
			if(in_array($sKey,array('homefreshcomment_limit_num','homefreshchildcomment_limit_num','homefreshchildcomment_list_num'))){
				if($val<1){
					$val=4;
				}
			}
			
			$oOptionModel=HomeoptionModel::F('homeoption_name=?',$sKey)->getOne();
			$oOptionModel->homeoption_value=G::html($val);
			$oOptionModel->save(0,'update');
		}

		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('home_option');

		$this->S(Dyhb::L('配置更新成功','__APP_ADMIN_LANG__@Controller/Homeoption'));
	}

}
