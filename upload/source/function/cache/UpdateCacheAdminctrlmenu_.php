<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   后台快捷菜单缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheAdminctrlmenu{

	public static function cache(){
		$arrData=array();

		$arrAdminctrlmenus=AdminctrlmenuModel::F('adminctrlmenu_status=?',1)->order('adminctrlmenu_sort ASC,create_dateline ASC')->getAll();
		if(is_array($arrAdminctrlmenus)){
			foreach($arrAdminctrlmenus as $oAdminctrlmenu){
				$arrData[]=array(
					'adminctrlmenu_id'=>$oAdminctrlmenu['adminctrlmenu_id'],
					'adminctrlmenu_title'=>$oAdminctrlmenu['adminctrlmenu_title'],
					'adminctrlmenu_url'=>$oAdminctrlmenu['adminctrlmenu_url'],
				);
			}
		}

		Core_Extend::saveSyscache('adminctrlmenu',$arrData);
	}

}
