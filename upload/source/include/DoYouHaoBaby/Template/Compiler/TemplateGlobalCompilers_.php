<?php
/* [$DoYouHaoBaby] (C)WindsForce TEAM Since 2010.10.04.
   模板全局编译器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TemplateGlobalCompiler{

	static private $_oGlobalInstance;

	public function __construct(){}

	public function compile(TemplateObj $oObj){
		$sCompiled=TemplateGlobalParser::encode($oObj->getCompiled());
		$oObj->setCompiled($sCompiled);

		return $sCompiled;
	}

	static public function getGlobalInstance(){
		if(!self::$_oGlobalInstance){
			self::$_oGlobalInstance=new TemplateGlobalCompiler();
		}

		return self::$_oGlobalInstance;
	}

}
