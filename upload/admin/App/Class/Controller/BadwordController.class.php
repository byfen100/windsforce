<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   词语过滤($)*/

!defined('DYHB_PATH') && exit;

class BadwordController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['badword_find']=array('like',"%".G::getGpc('badword_find')."%");
	}

	public function bIndex_(){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
	}

	public function muit_add(){
		$this->display();
	}

	public function update_option(){
		$oOptionController=new OptionController();

		$oOptionController->update_option();
	}

	public function muit_insert(){
		$sBadwords=G::getGpc('badwords','P');
		if($sBadwords==''){
			$this->E(Dyhb::L('导入词汇不能为空','Controller/Badword'));
		}

		$oBadword=Dyhb::instance('BadwordModel');
		
		$nType=G::getGpc('type','P');
		if($nType==0){
			$bResult=$oBadword->truncateBadword();
			if($bResult===false){
				$this->E(Dyhb::L('清空badword数据出错','Controller/Badword'));
			}

			$nType=1;
		}

		$arrValues=explode("\n",str_replace(array("\r","\n\n"),array("\r","\n"),$sBadwords));
		foreach($arrValues as $sValue){
			$arrValueTwo=explode("=",$sValue);
			if(!isset($arrValueTwo[1])){
				$arrValueTwo[1]='*';
			}

			$arrUserData=$GLOBALS['___login___'];
			$bResult=$oBadword->addBadword($arrValueTwo[0],$arrValueTwo[1],$arrUserData['user_name'],$nType);
			if($bResult===false){
				$this->E($oBadword->getErrorMessage());
			}
		}

		$this->S(Dyhb::L('导入数据成功','Controller/Badword'));
	}

	public function export(){
		$arrBadwords=BadwordModel::F()->all()->query();

		$sString='';
		if(is_array($arrBadwords)){
			foreach($arrBadwords as $oBadword){
				$sString.=$oBadword['badword_find'].'='.$oBadword['badword_replacement']."\r\n";
			}
		}

		$sName='BADWORD_'.date('Y_m_d_H_i_s',CURRENT_TIMESTAMP).'.txt';

		header('Content-Type: text/plain');
		header('Content-Disposition: attachment;filename="'.$sName.'"');
		if(preg_match("/MSIE([0-9].[0-9]{1,2})/",$_SERVER['HTTP_USER_AGENT'])){
			header('Cache-Control: must-revalidate,post-check=0,pre-check=0');
			header('Pragma: public');
		}else{
			header('Pragma: no-cache');
		}

		echo $sString;
	}

	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("badword");
	}

	protected function aUpdate($nId=null){
		$this->aInsert();
	}

	public function aForeverdelete($sId){
		$this->aInsert();
	}

}
