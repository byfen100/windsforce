<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主题信息模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ThemeModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'theme',
			'check'=>array(
				'theme_name'=>array(
					array('require',Dyhb::L('模板名字不能为空','__COMMON_LANG__@Model/Theme')),
					array('max_length',32,Dyhb::L('模板名字最大长度为32个字符','__COMMON_LANG__@Model/Theme')),
				),
				'theme_dirname'=>array(
					array('require',Dyhb::L('模板目录不能为空','__COMMON_LANG__@Model/Theme')),
					array('max_length',32,Dyhb::L('模板目录最大长度为32个字符','__COMMON_LANG__@Model/Theme')),
					array('english',Dyhb::L('模板目录只能为英文字符','__COMMON_LANG__@Model/Theme')),
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

	public function saveThemeData($arrThemeData,$nThemeId=0){
		$bThemeExists=false;

		if(!empty($nThemeId)){
			$oTryTheme=ThemeModel::F('theme_id=?',$nThemeId)->getOne();

			if(!empty($oTryTheme['theme_id'])){
				$bThemeExists=true;
				$oTryTheme->changeProp($arrThemeData);
				$oTryTheme->save(0,'update');

				if($oTryTheme->isError()){
					$this->setErrorMessage($oTryTheme->getErrorMessage());
				}
			}
		}

		if($bThemeExists===false){
			$oTheme=new ThemeModel($arrThemeData);
			$oTheme->save(0);

			if($oTheme->isError()){
				$this->setErrorMessage($oTheme->getErrorMessage());
			}

			$nThemeId=$oTheme['theme_id'];
		}

		return $nThemeId;
	}

	static public function getThemenameById($nThemeId,$sField='theme_name'){
		$oTheme=ThemeModel::F('theme_id=?',$nThemeId)->query();

		if(empty($oTheme['theme_id'])){
			return Dyhb::L('模板套系不存在','__COMMON_LANG__@Model/Theme');
		}
		
		return $oTheme[$sField];
	}

}
