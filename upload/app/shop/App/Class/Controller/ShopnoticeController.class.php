<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城公告控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopnoticeController extends InitController{

	public function index(){
		$nId=intval(G::getGpc('tid','G'));

		if(empty($nId)){
			$this->E("你查找的公告不存在或已经删除");
		}

		$oShopnotice=ShoparticleModel::F('shoparticle_id=? AND shoparticle_status=1',$nId)->getOne();
		if(empty($oShopnotice->shoparticle_id)){
			$this->E("你查找的公告不存在或已经删除");
		}
		$this->assign('oShopnotice',$oShopnotice);
		$this->display('shopnotice+index');
	}
	
	public function noticelist(){
		$sText=trim(G::getGpc('string'));

		$nCid=5;/*cid=5 默认为商城公告分类*/
		$arrWhere=array();
		$condition='';
		if(!empty($sText)){
			$condition='shoparticle_title like '.'\'%'.$sText.'%\' AND ';
		}
		$arrWhere['shoparticle_status']=1;
		$arrWhere['shoparticlecategory_id']=$nCid;
		$nEverynum=6;
		$nCount=ShoparticleModel::F("{$condition} shoparticle_status=1 AND shoparticlecategory_id=?",$nCid)->getCounts();
		$oPage=Page::RUN($nCount,$nEverynum,G::getGpc('page','G'));
		$arrShoparticle=ShoparticleModel::F($condition)->where($arrWhere)->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrShoparticle',$arrShoparticle);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$oShoparticlecategory=ShoparticlecategoryModel::F('shoparticlecategory_id=?',$nCid)->getOne();
		$this->assign('oShoparticlecategory',$oShoparticlecategory);

		$this->display('shopnotice+list');
	}
}
