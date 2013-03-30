<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

require_once(Core_Extend::includeFile('function/Attachment_Extend'));

class IndexController extends Controller{

	public function index(){
		$nCid=intval(G::getGpc('cid','G'));
		$nStyle=intval(Dyhb::cookie('group_homepagestyle'));
		
		if(!in_array($nStyle,array(1,2))){
			$nStyle=1;
		}

		// 取得小组分类
		$arrWhere=array();
		if($nCid){
			$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCid)->getOne();
			if(empty($oGroupcategory['groupcategory_id'])){
				$this->U('group://public/index');
			}

			$this->assign('oParentGroupcategory',$oGroupcategory);
			$arrWhere['groupcategory_parentid']=$nCid;
		}else{
			$arrWhere['groupcategory_parentid']=0;
		}
		
		// 小组分类赋值，根据小组分类来取得小组
		$arrGroupcategorys=GroupcategoryModel::F()->where($arrWhere)->getAll();
		$this->assign('arrGroupcategorys',$arrGroupcategorys);

		// 热门帖子
		$arrGrouphottopics=Group_Extend::getGrouphottopic();
		$this->assign('arrGrouphottopics',$arrGrouphottopics);

		// 首页幻灯片帖子
		$arrGroupthumbtopics=Group_Extend::getGroupthumbtopic();
		$this->assign('arrGroupthumbtopics',$arrGroupthumbtopics);

		// 推荐小组
		$arrRecommendgroups=GroupModel::F('group_isrecommend=? AND group_status=1',1)->order('create_dateline DESC')->limit(0,10)->getAll();
		$this->assign('arrRecommendgroups',$arrRecommendgroups);

		// 最新小组
		$arrNewgroups=GroupModel::F()->order('create_dateline DESC')->limit(0,10)->getAll();
		$this->assign('arrNewgroups',$arrNewgroups);

		// 24小时热门小组
		$arrHotgroups=GroupModel::F()->order('group_totaltodaynum DESC')->limit(0,10)->getAll();
		$this->assign('arrHotgroups',$arrHotgroups);

		// 小组长
		$arrGroupleaders=GroupuserModel::F('groupuser_isadmin=?',2)->order('create_dateline DESC')->limit(0,6)->getAll();
		$this->assign('arrGroupleaders',$arrGroupleaders);

		$this->assign('nStyle',$nStyle);

		if($nStyle==2){
			$this->display('public+indexnew');
		}else{
			$this->display('public+index');
		}
	}

	public function get_childGroupcategory($nParentid){
		$arrGroupcategorys=GroupcategoryModel::F()->where(array('groupcategory_parentid'=>$nParentid))->getAll();
		
		return $arrGroupcategorys;
	}

}
