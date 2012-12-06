<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   地区获取控制器($)*/

!defined('DYHB_PATH') && exit;

class DistrictController extends Controller{

	public function index(){
		$sContainer=trim(G::getGpc('container','G'));
		$nShowlevel=intval(G::getGpc('level','G'));
		$nShowlevel=$nShowlevel>=1 && $nShowlevel<=4?$nShowlevel:4;
		$arrValues=array(intval(G::getGpc('pid','G')),intval(G::getGpc('cid','G')),intval(G::getGpc('did','G')),intval(G::getGpc('coid','G')));
		$sContainertype=in_array(trim(G::getGpc('containertype','G')),array('birth','reside'),true)?trim(G::getGpc('containertype','G')):'birth';
		$sDistrictprefix=trim(G::getGpc('districtprefix','G'));
	
		$nLevel=1;
		if($arrValues[0]){
			$nLevel++;
		}

		if($arrValues[1]){
			$nLevel++;
		}

		if($arrValues[2]){
			$nLevel++;
		}

		if($arrValues[3]){
			$nLevel++;
		}

		$nShowlevel=$nLevel;
		$arrElems=array();
		if(G::getGpc('province','G')){
			$arrElems=array(G::getGpc('province','G'),G::getGpc('city','G'),G::getGpc('district','G'),G::getGpc('community','G'));
		}

		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		echo Profile_Extend::showDistrict($arrValues,$arrElems,$sContainer,$nShowlevel,$sContainertype,$sDistrictprefix);
	}

}
