<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入附件函数库 */
require_once(Core_Extend::includeFile('function/Attachment_Extend'));

class IndexController extends Controller{

	public function index(){
		/** 小组分类ID */
		$nCid=intval(G::getGpc('cid','G'));

		// 站点统计数据
		Core_Extend::loadCache('group_site');

		$nStyle=Dyhb::cookie('group_homepagestyle')?intval(Dyhb::cookie('group_homepagestyle')):$GLOBALS['_cache_']['group_option']['group_homepagestyle'];
		
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
		$arrGrouphottopics=Groupdata_Extend::getGrouphottopic();
		
		$this->assign('arrGrouphottopics',$arrGrouphottopics);

		// 首页幻灯片帖子
		$arrGroupthumbtopics=Groupdata_Extend::getGroupthumbtopic();
		
		$this->assign('arrGroupthumbtopics',$arrGroupthumbtopics);

		// 推荐小组
		$arrRecommendgroups=GroupModel::F('group_isrecommend=? AND group_status=1 AND group_isaudit=1',1)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['index_recommendgroupnum'])->getAll();

		$this->assign('arrRecommendgroups',$arrRecommendgroups);

		// 最新小组
		$arrNewgroups=GroupModel::F('group_status=? AND group_isaudit=1',1)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['index_newgroupnum'])->getAll();
		
		$this->assign('arrNewgroups',$arrNewgroups);

		// 24小时热门小组
		$arrHotgroups=GroupModel::F('group_status=? AND group_isaudit=1',1)->order('group_totaltodaynum DESC')->limit(0,$GLOBALS['_cache_']['group_option']['index_hotgroupnum'])->getAll();
		
		$this->assign('arrHotgroups',$arrHotgroups);

		// 小组长
		$arrGroupleaders=GroupuserModel::F('groupuser_isadmin=?',2)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['index_groupleadernum'])->getAll();
		
		$this->assign('arrGroupleaders',$arrGroupleaders);

		// 组长推荐帖子 && 系统推荐
		$arrGroupadminRecommendtopics=GrouptopicModel::F('grouptopic_status=1 AND grouptopic_isaudit=? AND grouptopic_isrecommend=1',1)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['index_groupadminretopic_num'])->getAll();
		$arrSystemRecommendtopics=GrouptopicModel::F('grouptopic_status=1 AND grouptopic_isaudit=? AND grouptopic_isrecommend=2',1)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['index_systemrecommendtopic_num'])->getAll();
		
		$this->assign('arrGroupadminRecommendtopics',$arrGroupadminRecommendtopics);
		$this->assign('arrSystemRecommendtopics',$arrSystemRecommendtopics);
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
	
	public function index_title_(){
		if($GLOBALS['_commonConfig_']['DEFAULT_APP']!='group'){
			return Dyhb::L('小组','Controller/Public');
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
