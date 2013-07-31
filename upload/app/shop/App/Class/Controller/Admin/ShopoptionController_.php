<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城设置控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopoptionController extends InitController{
	
	public function index($sModel=null,$bDisplay=true){
		$this->get_option_();

		$this->display(Admin_Extend::template('shop','shopoption/index'));
	}

	public function shopgoods_img(){
		$this->get_option_();

		$this->display(Admin_Extend::template('shop','shopoption/shopgoodsimg'));
	}

	protected function get_option_(){
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

			if($oOptionModel->isError()){
				$this->E($oOptionModel->getErrorMessage());
			}
		}

		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('shop_option');

		$this->S(Dyhb::L('配置更新成功','__APPSHOP_COMMON_LANG__@Controller'));
	}

}
