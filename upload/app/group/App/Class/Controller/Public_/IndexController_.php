<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   小组首页控制器($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){	
		$nCid=intval(G::getGpc('cid','G'));

		$arrWhere=array();
		if($nCid){
			$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCid)->getOne();
			if(empty($oGroupcategory['groupcategory_id'])){
				$this->U('group://public/index');
			}

			$this->assign('oGroupcategory',$oGroupcategory);
			$arrWhere['groupcategory_parentid']=$nCid;
		}else{
			$arrWhere['groupcategory_parentid']=0;
		}
		
		$arrGroupcategorys=GroupcategoryModel::F()->where($arrWhere)->getAll();

		$this->assign('arrGroupcategorys',$arrGroupcategorys);

		$this->display('public+index');
	}

}
