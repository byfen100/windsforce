<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   应用模板相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Apptheme_Extend{
	
	static public function path($sFile,$sApp='home',$bAdmin=false){
		static $sApptemplate='';

		if($sApptemplate==''){
			$sApptemplate=Dyhb::cookie('template');
		}

		if(is_file(WINDSFORCE_PATH.'/app/'.$sApp.'/Theme/'.$sApptemplate.'/Public/Images/'.$sFile)){
			if($bAdmin===false){
				return __TMPLPUB__.'/Images/'.$sFile;
			}else{
				return __ROOT__.'/app/'.$sApp.'/Theme/'.$sApptemplate.'/Public/Images/'.$sFile;
			}
		}else{
			if($bAdmin===false){
				return __TMPLPUB__DEFAULT__.'/Images/'.$sFile;
			}else{
				return __ROOT__.'/app/'.$sApp.'/Theme/Default/Public/Images/'.$sFile;
			}
		}
	}
	
}

/** 简化模板中的调用 */
class Appt extends Apptheme_Extend{}
