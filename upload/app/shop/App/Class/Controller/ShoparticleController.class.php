<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城文章控制器($)*/

!defined('DYHB_PATH') && exit;

class ShoparticleController extends InitController{

	public function index(){
		$nTid=intval(G::getGpc('tid'));
		$nCid=intval(G::getGpc('cid'));

		if(empty($nTid) && empty($nCid)){
			$this->E('对不起，你查询的文章或分类不存在');
		}else if(!empty($nTid) && empty($nCid)){
			$this->articleshow();
		}else if(!empty($nCid) && empty($nTid)){
			$this->articlelist();
		}else{
			$this->E('你访问的页面有误');
		}
	}

	public function articleshow(){
		$nTid=intval(G::getGpc('tid','G'));

		$Shoparticle=ShoparticleModel::F('shoparticle_status=1 AND shoparticle_id=?',$nTid)->getOne();

		$this->assign('Shoparticle',$Shoparticle);
		$this->display('shoparticle+help');
	}
	
	public function articlelist(){
		$nCid=intval(G::getGpc('cid'));
		$sText=trim(G::getGpc('string'));

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
		$this->display('shoparticle+help');
	}

	public function feedback(){
		$nId=intval(G::getGpc('id','G'));

		$oShoparticle=ShoparticleModel::F('shoparticlecategory_id=?',$nId)->limit(0,1)->getOne();

		$this->assign('oShoparticle',$oShoparticle);
		$this->display('shoparticle+feedback');
	}

	public function addfeedback(){
		$sUsername=trim(G::getGpc('username','P'));
		$sEmail=trim(G::getGpc('email','P'));
		$sContent=trim(G::getGpc('content','P'));

		$oShoparticle=new ShoparticleModel();
		$oShoparticle->shoparticlecategory_id=6;/*cid=6 默认为意见反馈分类*/
		$oShoparticle->shoparticle_username=$sUsername;
		$oShoparticle->shoparticle_useremail=$sEmail;
		$oShoparticle->shoparticle_content=$sContent;
		$oShoparticle->shoparticle_status=0;/*默认不在前台显示的*/
		$oShoparticle->save(0);

		if(!$oShoparticle->isError()){
			$this->S('提交意见反馈成功!');
		}else{
			$this->E($oShoparticle->getErrorMessage());
		}
	}
}
