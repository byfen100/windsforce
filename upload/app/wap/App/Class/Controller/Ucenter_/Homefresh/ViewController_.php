<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   新鲜事查看($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

class ViewController extends GlobalchildController{

	protected $_oHomefresh=null;
	protected $_sHomefreshtitle='';

	public function index(){
		if(!Core_Extend::checkRbac('home@ucenter@view')){
			$this->_oParentcontroller->wap_mes(Dyhb::L('你没有权限查看新鲜事','Controller'),'',0);
		}
		
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->_oParentcontroller->wap_mes(Dyhb::L('你没有指定要阅读的新鲜事','Controller'),'',0);
		}

		$oHomefresh=HomefreshModel::F('homefresh_id=? AND homefresh_status=1',$nId)->getOne();
		if(empty($oHomefresh['homefresh_id'])){
			$this->_oParentcontroller->wap_mes(Dyhb::L('新鲜事不存在或者被屏蔽了','Controller'),'',0);
		}
		
		if($oHomefresh->isError()){
			$this->_oParentcontroller->wap_mes($oHomefresh->getErrorMessage(),'',0);
		}

		$sHomefreshtitle=$oHomefresh->homefresh_title?$oHomefresh->homefresh_title:'Title Not Found!';

		$sHomefreshwebtitle=G::subString($oHomefresh->homefresh_title?$oHomefresh->homefresh_title:strip_tags(Core_Extend::ubb($oHomefresh->homefresh_message)),0,30);
		$this->_sHomefreshtitle=$sHomefreshwebtitle;

		$this->assign('oHomefresh',$oHomefresh);
		$this->assign('sHomefreshtitle',$sHomefreshtitle);
		
		$this->display('homefresh+view');
	}
	
	public function view_title_(){
		return $this->_sHomefreshtitle;
	}

	public function view_keywords_(){
		return $this->view_title_();
	}

	public function view_description_(){
		if(G::getGpc('page','G')>1){
			return $this->view_title_();
		}else{
			return G::subString(strip_tags($this->_oHomefresh['homefresh_message']),0,30);
		}
	}

}
