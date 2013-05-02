<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   应用模板相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Apptheme_Extend{
	
	static public function path($sFile,$sApp='home',$sType='images'){
		static $sApptemplate='';

		if($sApptemplate==''){
			$sApptemplate=Dyhb::cookie('template');
		}

		$sType=ucfirst(strtolower($sType));
		
		if(is_file(WINDSFORCE_PATH.'/app/'.$sApp.'/Theme/'.$sApptemplate.'/Public/'.$sType.'/'.$sFile)){
			return __TMPLPUB__.'/'.$sType.'/'.$sFile;
		}else{
			return __TMPLPUB__DEFAULT__.'/'.$sType.'/'.$sFile;
		}
	}
	
}

/** 简化模板中的调用 */
class Appt extends Apptheme_Extend{}
