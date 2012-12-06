<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   社会化帐号模型($)*/

!defined('DYHB_PATH') && exit;

class SociatypeModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'sociatype',
			'props'=>array(
				'sociatype_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'sociatype_id',
			'check'=>array(
				'sociatype_title'=>array(
					array('require',Dyhb::L('社会化帐号名字不能为空','__COMMON_LANG__@Model/Sociatype')),
					array('max_length',35,Dyhb::L('社会化帐号名字最大长度为35个字符','__COMMON_LANG__@Model/Sociatype')),
				),
				'sociatype_identifier'=>array(
					array('require',Dyhb::L('社会化帐号唯一识别符不能为空','__COMMON_LANG__@Model/Sociatype')),
					array('english',Dyhb::L('社会化帐号唯一识别符只能为英文字符','__COMMON_LANG__@Model/Sociatype')),
					array('max_length',32,Dyhb::L('显社会化帐号唯一识别符为32个字符','__COMMON_LANG__@Model/Sociatype')),
					array('sociatypeIdentifier',Dyhb::L('社会化帐号唯一识别符已经存在','__COMMON_LANG__@Model/Sociatype'),'condition'=>'must','extend'=>'callback'),
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

	public function safeInput(){
		$_POST['sociatype_title']=G::html($_POST['sociatype_title']);
		$_POST['sociatype_appid']=G::html($_POST['sociatype_appid']);
		$_POST['sociatype_appkey']=G::html($_POST['sociatype_appkey']);
	}

	public function sociatypeIdentifier(){
		$nId=G::getGpc('id','P');

		$sSociatypeIdentifier=G::getGpc('sociatype_identifier','P');
		$sSociatypeIdentifierInfo='';
		if($nId){
			$arrSociatypeIdentifier=self::F('sociatype_id=?',$nId)->asArray()->getOne();
			$sSociatypeIdentifierInfo=trim($arrSociatypeIdentifier['sociatype_identifier']);
		}

		if($sSociatypeIdentifier!=$sSociatypeIdentifierInfo){
			$arrResult=self::F()->getBysociatype_identifier($sSociatypeIdentifier)->toArray();
			if(!empty($arrResult['sociatype_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

}
