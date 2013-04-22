<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   后台模板相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Admintheme_Extend{
	
	static public function path($sFile,$sType='Images'){
		static $sAdmintemplate='';

		if($sAdmintemplate==''){
			$sAdmintemplate=Dyhb::cookie('admin_template');
		}
		
		if(is_file(WINDSFORCE_PATH.'/admin/Theme/'.$sAdmintemplate.'/Public/'.$sType.'/'.$sFile)){
			return __TMPLPUB__.'/'.$sType.'/'.$sFile;
		}else{
			return __TMPLPUB__DEFAULT__.'/'.$sType.'/'.$sFile;
		}
	}
	
}

/** 简化模板中的调用 */
class At extends Admintheme_Extend{}
