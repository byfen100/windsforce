<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城设置控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShopoptionController extends InitController{

	public function filter_(&$arrMap){
	}
	
	public function index($sModel=null,$bDisplay=true){
		$this->get_option_();

		$this->display(Admin_Extend::template('shop','shopoption/index'));
	}

	public function shopgoods_img(){
		$this->get_option_();

		$this->display(Admin_Extend::template('shop','shopoption/shopgoodsimg'));
	}

	protected function get_option_(){
		Core_Extend::loadCache("shop_option");
		$arrOptionData=$GLOBALS['_cache_']['shop_option'];
		
		$this->assign("nId",intval(G::getGpc("id",'G')));
		$this->assign('arrOptions',$arrOptionData);
	}

	public function update_option(){
		$arrOptions=G::getGpc('options','P');

		foreach($arrOptions as $sKey=>$val){
			$val=trim($val);
			
			$oOptionModel=ShopoptionModel::F('shopoption_name=?',$sKey)->getOne();
			$oOptionModel->shopoption_value=G::html($val);
			$oOptionModel->save(0,'update');
		}

		ShopCache_Extend::updateCacheOption();

		$this->S(Dyhb::L('配置更新成功','__APP_ADMIN_LANG__@Controller/Shopoption'));
	}

}
