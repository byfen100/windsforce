<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   URL美化控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UrloptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];
		
		$arrUrlModels=array(
			array('name'=>Dyhb::L('普通模式','Controller/Urloption'),'value'=>0),
			array('name'=>Dyhb::L('PATHINFO模式','Controller/Urloption'),'value'=>1),
			array('name'=>Dyhb::L('REWRITE模式','Controller/Urloption'),'value'=>2),
			array('name'=>Dyhb::L('兼容模式','Controller/Urloption'),'value'=>3),
		);
		
		$this->assign('arrOptions',$arrOptionData);
		$this->assign('arrUrlModels',$arrUrlModels);

		$this->display();
	}

	public function update_option(){
		$arrOptions=G::getGpc('options','P');
		$nUrlmodel=intval($arrOptions['url_model']);

		if(!in_array($nUrlmodel,array(0,1,2,3))){
			$nUrlmodel=1;
		}

		// 修改URL模式设置
		OptionModel::uploadOption('url_model',$nUrlmodel);
		Core_Extend::changeAppconfig('URL_MODEL',$nUrlmodel);

		// 需要删除导航缓存
		$bIsFilecache=$GLOBALS['_commonConfig_']['RUNTIME_CACHE_BACKEND']=='FileCache';
		$bAllowMem=Core_Extend::memory('check');

		$bAllowMem && self::memory('delete','nav');

		$sCachefile=WINDSFORCE_PATH.'/data/~runtime/cache_/data/~@nav.php';
		$bIsFilecache && (is_file($sCachefile) && @unlink($sCachefile));

		$this->S(Dyhb::L('修改URL模式成功','Controller/Urloption'));
	}

}
