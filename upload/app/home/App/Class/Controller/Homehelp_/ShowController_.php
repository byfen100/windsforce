<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帮助显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends GlobalchildController{

	protected $_oHomehelp=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定帮助ID','Controller/Homehelp'));
		}

		$oHomehelp=HomehelpModel::F('homehelp_id=?',$nId)->getOne();
		if(!empty($oHomehelp['homehelp_id'])){
			$this->assign('oHomehelp',$oHomehelp);
			$this->assign('sContent',Core_Extend::replaceSiteVar($oHomehelp['homehelp_content']));

			$this->_oParentcontroller->homehelpcategory_($this);

			// 更新点击量
			$oHomehelp->homehelp_viewnum=$oHomehelp->homehelp_viewnum+1;
			$oHomehelp->setAutofill(false);
			$oHomehelp->save(0,'update');

			$this->_oHomehelp=$oHomehelp;

			if($oHomehelp->isError()){
				$this->E($oHomehelp->getErrorMessage());
			}
			
			$this->display('homehelp+show');
		}else{
			$this->E(Dyhb::L('你指定的帮助不存在','Controller/Homehelp'));
		}
	}

	public function show_title_(){
		return $this->_oHomehelp['homehelp_title'].($this->_oHomehelp->homehelpcategory_id>0?' - '.$this->_oHomehelp->homehelpcategory->homehelpcategory_name:'');
	}

	public function show_description_(){
		return $this->_oHomehelp['homehelp_content'];
	}

	public function show_keywords_(){
		return $this->_oHomehelp['homehelp_title'];
	}

}
