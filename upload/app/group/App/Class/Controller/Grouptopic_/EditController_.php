<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   编辑帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EditController extends Controller{

	public function index(){
		$nTid=intval(G::getGpc('tid','G'));
		$nUid=intval(G::getGpc('uid','G'));
		$nGroupid=intval(G::getGpc('gid','G'));

		// 编辑权限检测
		if(Core_Extend::isAdmin()===false && $nUid!=$GLOBALS['___login___']['user_id']){
			$this->E("无法编辑他人的主题");
		}
		
		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nTid)->getOne();
		if(empty($oGrouptopic->grouptopic_id)){
			$this->E("不存在你要编辑的主题");
		}

		// 取得小组分类
		$arrGrouptopiccategorys=array();
		$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
		$arrGrouptopiccategorys=$oGrouptopiccategory->grouptopiccategoryByGroupid($nGroupid);

		// 获取帖子标签
		$sTag='';

		$arrTags=GrouptopictagindexModel::F('grouptopic_id=?',$nTid)->getAll();
		if(is_array($arrTags)){
			$arrTemptag=array();
			foreach($arrTags as $oTag){
				$arrTemptag[]=$oTag['grouptopictag_id'];
			}

			// 取得标签
			$arrWhere['grouptopictag_id']=array('in',$arrTemptag);
			$arrGrouptopictags=GrouptopictagModel::F($arrWhere)->all()->get();
			if(is_array($arrGrouptopictags)){
				foreach($arrGrouptopictags as $oGrouptopictag){
					$sTag.=','.$oGrouptopictag['grouptopictag_name'];
				}
			}

			$sTag=trim($sTag,',');
		}

		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sTag',$sTag);

		$this->display('grouptopic+add');
	}
}
