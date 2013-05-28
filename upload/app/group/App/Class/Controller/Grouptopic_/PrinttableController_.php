<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子打印控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PrinttableController extends Controller{

	protected $_oGrouptopic=null;
	
	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('id','G'));
	
		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller/Grouptopic'));
		}

		// 判断帖子小组
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
		$this->_oGrouptopic=$oGrouptopic;

		// 更新点击量
		$oGrouptopic->grouptopic_views=$oGrouptopic->grouptopic_views+1;
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		$this->assign('oGrouptopic',$oGrouptopic);

		// 判断用户是否回复过帖子
		if($oGrouptopic['grouptopic_onlycommentview']==1){
			$bHavecomment=false;

			if($GLOBALS['___login___']!==false){
				if($oGrouptopic['user_id']==$GLOBALS['___login___']['user_id']){
					$bHavecomment=true;
				}else{
					$oTrygrouptopiccomment=GrouptopiccommentModel::F('user_id=? AND grouptopic_id=?',$GLOBALS['___login___']['user_id'],$oGrouptopic['grouptopic_id'])->getOne();

					if(!empty($oTrygrouptopiccomment['grouptopiccomment_id'])){
						$bHavecomment=true;
					}
				}
			}

			$this->assign('bHavecomment',$bHavecomment);
		}
		
		// 回复列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum'];

		$arrWhere['grouptopiccomment_status']=1;
		$arrWhere['grouptopic_id']=$oGrouptopic->grouptopic_id;

		if(!Groupadmin_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@auditcomment'))){
			$arrWhere['grouptopiccomment_auditpass']=1;
		}

		$arrComments=GrouptopiccommentModel::F()->where($arrWhere)->order('grouptopiccomment_auditpass ASC,grouptopiccomment_stickreply DESC,create_dateline '.($oGrouptopic['grouptopic_ordertype']==1?'DESC':'ASC'))->limit(0,$nEverynum)->getAll();

		$this->assign('arrComments',$arrComments);

		$this->display('grouptopic+printtable');
	}

	public function printtable_title_(){
		return $this->_oGrouptopic['grouptopic_title'].' - '.$this->_oGrouptopic->group->group_nikename;
	}

	public function printtable_keywords_(){
		return $this->printtable_title_();
	}

	public function printtable_description_(){
		return $this->printtable_title_();
	}

}
