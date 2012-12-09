<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城首页控制器($)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){

		$this->newlist();
		$this->populargoodslist();
		$this->handwritinglist();
		$this->picturelist();
		$this->bestgoodslist();
		$this->originalgoodslist();
		$this->upcominglist();
		$this->display('public+index');
	}

	public function newlist(){

		$arrNew=ShoparticleModel::F('shoparticlecategory_id=5 AND shoparticle_status=1')->order('create_dateline DESC')->limit(0,10)->getAll();/*cid=5 默认为商城公告分类*/
		$this->assign('arrNew',$arrNew);
	}

	public function populargoodslist(){
	
		$arrPopularshopgoods=ShopgoodsModel::F('shopgoods_status=0')->order('shopgoods_views DESC,create_dateline DESC')->limit(0,4)->getAll();
		$this->assign('arrPopularshopgoods',$arrPopularshopgoods);
	}
	
	public function handwritinglist(){

		$arrHandwritingshopgoods=ShopgoodsModel::F('shopcategory_id=? AND shopgoods_status=0',2)->order('shopgoods_views DESC,create_dateline DESC')->limit(0,4)->getAll();
		$this->assign('arrHandwritingshopgoods',$arrHandwritingshopgoods);
	}

	public function picturelist(){

		$arrpictureshopgoods=ShopgoodsModel::F('shopcategory_id=? AND shopgoods_status=0',8)->order('shopgoods_views DESC,create_dateline DESC')->limit(0,4)->getAll();
		$this->assign('arrpictureshopgoods',$arrpictureshopgoods);
	}

	public function bestgoodslist(){
	
		$arrBestshopgoods=ShopgoodsModel::F('shopgoods_isbest=? AND shopgoods_status=0',1)->order('create_dateline DESC')->limit(0,4)->getAll();
		$this->assign('arrBestshopgoods',$arrBestshopgoods);
	}

	public function originalgoodslist(){
	
		$arrOriginalshopgoods=ShopgoodsModel::F('shopgoods_status=0')->order('create_dateline DESC')->limit(0,5)->getAll();
		$this->assign('arrOriginalshopgoods',$arrOriginalshopgoods);
	}

	public function upcominglist(){

		$arrUpcomingshopgoods=ShopgoodsModel::F('shopgoods_isupcoming=? AND shopgoods_status=0',1)->order('create_dateline DESC')->limit(0,6)->getAll();
		$this->assign('arrUpcomingshopgoods',$arrUpcomingshopgoods);
	}
}
