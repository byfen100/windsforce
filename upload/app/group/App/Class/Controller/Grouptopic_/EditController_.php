<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   编辑帖子控制器($)*/

!defined('DYHB_PATH') && exit;

class EditController extends Controller{

	public function index(){
		$nTid=intval(G::getGpc('tid','G'));
		$nUid=intval(G::getGpc('uid','G'));
		$nGroupid=intval(G::getGpc('gid','G'));

		if(Core_Extend::isAdmin()===false && $nUid!=$GLOBALS['___login___']['user_id']){
			$this->E("无法编辑他人的主题");
		}
		
		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nTid)->getOne();
		if(empty($oGrouptopic->grouptopic_id)){
			$this->E("不存在你要编辑的主题");
		}

		$this->assign('oGrouptopic',$oGrouptopic);
		
		$arrGrouptopiccategorys=array();
		$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
		$arrGrouptopiccategorys=$oGrouptopiccategory->grouptopiccategoryByGroupid($nGroupid);
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
		$this->assign('nGroupid',$nGroupid);

		$this->display('grouptopic+add');
	}
}
