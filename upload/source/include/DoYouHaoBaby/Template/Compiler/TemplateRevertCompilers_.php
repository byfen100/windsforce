<?php
/* [$DoYouHaoBaby] (C)WindsForce TEAM Since 2010.10.04.
   模板恢复编译($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TemplateRevertCompiler{

	static private $_oGlobalInstance;

	protected function __construct(){}

	public function compile(TemplateObj $oObj){
		$sCompiled=$oObj->getCompiled();
		$sCompiled=base64_decode($sCompiled);
		$oObj->setCompiled($sCompiled);

		return $sCompiled;
	}

	static public function getGlobalInstance(){
		if(!self::$_oGlobalInstance){
			self::$_oGlobalInstance=new TemplateRevertCompiler();
		}

		return self::$_oGlobalInstance;
	}

}
