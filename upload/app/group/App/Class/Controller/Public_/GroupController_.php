<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组列表控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupController extends Controller{

	public function index(){
		$nCid=intval(G::getGpc('cid','G'));
		$nType=intval(G::getGpc('t','G'));
		
		$arrWhere=$arrGroupWhere=array();
		
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_grouplistnum'];
		$arrGroupWhere['group_status']=1;
		$arrGroupWhere['group_isaudit']=1;

		// 取得小组分类
		if($nCid){
			$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCid)->getOne();
			if(empty($oGroupcategory['groupcategory_id'])){
				$this->U('group://public/group');
			}

			$this->assign('oParentGroupcategory',$oGroupcategory);
			$arrWhere['groupcategory_parentid']=$nCid;
			
			$nTotalRecord=GroupcategoryindexModel::F('groupcategory_id=?',$nCid)->all()->getCounts();
			$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));

			$arrGroupcategoryindexs=GroupcategoryindexModel::F('groupcategory_id=?',$nCid)->limit($oPage->returnPageStart(),$nEverynum)->getAll();

			if(is_array($arrGroupcategoryindexs)){
				$arrTempdata=array();
				foreach($arrGroupcategoryindexs as $oGroupcategoryindex){
					$arrTempdata[]=$oGroupcategoryindex['group_id'];
				}
				
				$arrGroupWhere['group_id']=array('in',$arrTempdata);
			}else{
				$arrGroups='';
			}
		}else{
			$arrWhere['groupcategory_parentid']=0;
			
			$nTotalRecord=GroupModel::F()->where($arrGroupWhere)->all()->getCounts();
			$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		}
		
		if(!isset($arrGroups)){
			if($nType==2){
				$sOrder='group_usernum DESC';
			}elseif($nType==1){
				$sOrder='group_totaltodaynum DESC';
			}else{
				$sOrder='group_isrecommend DESC,create_dateline DESC';
			}
			
			$arrGroups=GroupModel::F()->where($arrGroupWhere)->order($sOrder)->limit($oPage->returnPageStart(),$nEverynum)->getAll();
		}

		$this->assign('arrGroups',$arrGroups);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nType',$nType);

		// 小组分类赋值，根据小组分类来取得小组
		$arrGroupcategorys=GroupcategoryModel::F()->where($arrWhere)->getAll();
		$this->assign('arrGroupcategorys',$arrGroupcategorys);

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
		
		$this->display('public+group');
	}

	public function get_childGroupcategory($nParentid){
		$arrGroupcategorys=GroupcategoryModel::F()->where(array('groupcategory_parentid'=>$nParentid))->getAll();
		
		return $arrGroupcategorys;
	}	
	
	public function group_title_(){
		return Dyhb::L('小组列表','Controller');
	}

	public function group_keywords_(){
		return $this->group_title_();
	}

	public function group_description_(){
		return $this->group_title_();
	}

}
