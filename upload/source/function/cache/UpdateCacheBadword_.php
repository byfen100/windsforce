<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   过滤词语缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheBadword{

	public static function cache(){
		$arrBadwordDatas=BadwordModel::F()->order('badword_id ASC')->all()->query();

		$arrSaveData=array();
		if(is_array($arrBadwordDatas)){
			foreach($arrBadwordDatas as $nKey=>$oBadwordData){
				$arrSaveData[$oBadwordData['badword_id']]['regex']=$oBadwordData['badword_findpattern'];
				$arrSaveData[$oBadwordData['badword_id']]['value']=$oBadwordData['badword_replacement'];
			}
		}

		Core_Extend::saveSyscache('badword',$arrSaveData);
	}

}
