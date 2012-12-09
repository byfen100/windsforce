<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   衔接缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheLink{

	public static function cache(){
		$sTightlinkContent=$sTightlinkText=$sTightlinkLogo='';

		$arrLinks=LinkModel::F('link_status=?',1)->order('link_sort DESC')->getAll();
		if(is_array($arrLinks)){
			foreach($arrLinks as $oLink){
				if($oLink['link_description']){
					if($oLink['link_logo']){
						$sTightlinkContent.='<li><div class="home-logo"><img src="'.$oLink['link_logo'].'" border="0" alt="'.$oLink['link_name'].'" /></div>
							<div class="home-content"><h5><a href="'.$oLink['link_url'].'" target="_blank">'.
							$oLink['link_name'].'</a></h5><p>'.$oLink['link_description'].'</p></div></li>';
					}else{
						$sTightlinkContent.='<li><div class="home-content"><h5><a href="'.$oLink['link_url'].'" target="_blank">'.
							$oLink['link_name'].'</a></h5><p>'.$oLink['link_description'].'</p></div></li>';
					}
				}else{
					if($oLink['link_logo']){
						$sTightlinkLogo.='<a href="'.$oLink['link_url'].'" target="_blank"><img src="'.$oLink['link_logo'].
							'" border="0" alt="'.$oLink['link_name'].'" /></a>';
					}else{
						$sTightlinkText.='<li><a href="'.$oLink['link_url'].'" target="_blank" title="'.
							$oLink['link_name'].'">'.$oLink['link_name'].'</a></li>';
					}
				}
			}
		}

		$arrData['link_content']=$sTightlinkContent;
		$arrData['link_text']=$sTightlinkText;
		$arrData['link_logo']=$sTightlinkLogo;

		Core_Extend::saveSyscache('link',$arrData);
	}

}
