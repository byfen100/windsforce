<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   模板全局恢复编译($)*/

!defined('DYHB_PATH') && exit;

class TemplateGlobalRevertCompiler{

	static private $_oGlobalInstance;

	protected function __construct(){}

	public function compile(TemplateObj $oObj){
		$sCompiled=$oObj->getContent();
		$sCompiled=base64_decode($sCompiled);
		$oObj->setCompiled($sCompiled);

		return $sCompiled;
	}

	static public function getGlobalInstance(){
		if(!self::$_oGlobalInstance){
			self::$_oGlobalInstance=new TemplateGlobalRevertCompiler();
		}

		return self::$_oGlobalInstance;
	}

}
