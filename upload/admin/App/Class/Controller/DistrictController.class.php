<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   地区控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息相关处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class DistrictController extends InitController{
	
	protected $_arrUpids;
	protected $_nTheId;
	
	public function index($sName=NULL,$bDisplay=true){
		$this->init_district_();

		$arrThevalues=array();
		foreach(Profile_Extend::getDistrictByUpid($this->_arrUpids) as $arrValue){
			if($arrValue['district_upid']==$this->_nTheId){
				$arrThevalues[]=array($arrValue['district_id'],$arrValue['district_name'],$arrValue['district_sort']);
			}
		}

		$this->assign('arrThevalues',$arrThevalues);
		$this->display();
	}

	protected function init_district_(){
		$arrValues=array(intval(G::getGpc('pid','G')),intval(G::getGpc('cid','G')),intval(G::getGpc('did','G')));
		$arrElems=array(G::getGpc('province','G'),G::getGpc('city','G'),G::getGpc('district','G'));

		$nLevel=1;
		$arrUpids=array(0);
		$nTheid=0;
		for($nI=0;$nI<3;$nI++){
			if(!empty($arrValues[$nI])){
				$nTheid=intval($arrValues[$nI]);
				$arrUpids[]=$nTheid;
				$nLevel++;
			}else{
				for($nJ=$nI;$nJ<3;$nJ++){
					$arrValues[$nJ]='';
				}
				break;
			}
		}

		$this->_arrUpids=$arrUpids;
		$this->_nTheId=$nTheid;

		$arrOptions=array(1=>array(),2=>array(),3=>array());
		foreach(Profile_Extend::getDistrictByUpid($arrUpids) as $arrValue){
			$arrOptions[$arrValue['district_level']][]=array($arrValue['district_id'],$arrValue['district_name']);
		}

		$arrNames=array('province','city','district');
		for($nI=0;$nI<3;$nI++){
			$arrElems[$nI]=!empty($arrElems[$nI])?$arrElems[$nI]:$arrNames[$nI];
		}
		$sHtml='';
		for($nI=0;$nI<3;$nI++){
			$nL=$nI+1;
			$sJscall=($nI==0?'this.form.city.value=\'\';this.form.district.value=\'\';':'')."refreshDistrict('{$arrElems[0]}','{$arrElems[1]}','{$arrElems[2]}')";
			$sHtml.='<select name="'.$arrElems[$nI].'" id="'.$arrElems[$nI].'" onchange="'.$sJscall.'">';
			$sHtml.='<option value="">'.Profile_Extend::getDistrictType($nL).'</option>';
			foreach($arrOptions[$nL] as $arrOption){
				$sSelected=$arrOption[0]==$arrValues[$nI]?' selected="selected"':'';
				$sHtml.='<option value="'.$arrOption[0].'"'.$sSelected.'>'.$arrOption[1].'</option>';
			}
			$sHtml.='</select>&nbsp;&nbsp;';
		}

		$this->assign('arrValues',$arrValues);
		$this->assign('sHtml',$sHtml);
		$this->assign('nUpid',array_pop($arrUpids));
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bAdd_(){
		$this->init_district_();
	}

	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_district($nId)){
			$this->E(Dyhb::L('系统地理数据无法编辑','Controller/District'));
		}

		$this->init_district_();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_district($nId)){
				$this->E(Dyhb::L('系统地理数据无法删除','Controller/District'));
			}

			$nDistricts=DistrictModel::F('district_upid=?',$nId)->all()->getCounts();
			$oDistrict=DistrictModel::F('district_id=?',$nId)->query();
			if($nDistricts>0){
				$this->E(Dyhb::L('地区%s存在子地区，你无法删除','Controller/District',null,$oDistrict->district_name));
			}
		}
	}

	public function is_system_district($nId){
		if($nId<=45051){
			return true;
		}

		return false;
	}

}
