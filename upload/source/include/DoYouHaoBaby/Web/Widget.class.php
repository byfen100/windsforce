<?php
/* [DoYouHaoBaby!] (C)小牛哥Dyhb From 2010.10.04.
   Widget基类($)*/

!defined('DYHB_PATH') && exit;

abstract class Widget{

	protected $_sTemplate='';

	abstract public function render($arrData);

	protected function renderTpl($sTemplateFile='',$Var=''){
		ob_start();
		ob_implicit_flush(0);

		if(!is_file($sTemplateFile)){// 自动定位模板文件
			$sName=strtolower(substr(get_class($this),0,-6));
			$sFilename=empty($sTemplateFile)?$sName:$sTemplateFile;
			$sTemplateFile=APP_PATH.'/App/Class/View/Widget/'.ucfirst($sFilename).'.template'.$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX'];
			if(!is_file($sTemplateFile)){
				Dyhb::E(Dyhb::L( "挂件%s的模板文件%s不存在！",'__DYHB__@Dyhb',null,$sName,$sTemplateFile));
			}
		}

		$oTemplateHtml=new Template();
		$oTemplateHtml->setVar($Var);
		$oTemplateHtml->display($sTemplateFile);
		$sContent=ob_get_clean();

		return $sContent;
	}

}
