<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   网站联系信息模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HomesiteModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'homesite',
			'props'=>array(
				'homesite_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'homesite_id',
			'check'=>array(
				'homesite_name'=>array(
					array('require',Dyhb::L('信息名字不能为空','__APP_ADMIN_LANG__@Model/Homesite')),
					array('number_underline_english',Dyhb::L('信息名字只能是由数字，下划线，字母组成','__APP_ADMIN_LANG__@Model/Homesite')),
					array('max_length',32,Dyhb::L('信息名字不能超过32个字符','__APP_ADMIN_LANG__@Model/Homesite')),
					array('homesiteName',Dyhb::L('信息名字已经存在','__APP_ADMIN_LANG__@Model/Homesite'),'condition'=>'must','extend'=>'callback'),
				),
				'homesite_nikename'=>array(
					array('require',Dyhb::L('信息别名不能为空','__APP_ADMIN_LANG__@Model/Homesite')),
					array('max_length',32,Dyhb::L('信息别名不能超过32个字符','__APP_ADMIN_LANG__@Model/Homesite')),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function homesiteName(){
		$nId=G::getGpc('value','P');

		$sHomesiteName=G::getGpc('homesite_name','P');
		$sHomesiteInfo='';
		if($nId){
			$arrHomesite=self::F('homesite_id=?',$nId)->asArray()->getOne();
			$sHomesiteInfo=trim($arrHomesite['homesite_name']);
		}

		if($sHomesiteName!=$sHomesiteInfo){
			$arrResult=self::F()->getByhomesite_name($sHomesiteName)->toArray();
			if(!empty($arrResult['homesite_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function safeInput(){
		if(isset($_POST['homesite_name'])){
			$_POST['homesite_name']=G::html($_POST['homesite_name']);
		}

		$_POST['homesite_nikename']=G::html($_POST['homesite_nikename']);
	}

}
