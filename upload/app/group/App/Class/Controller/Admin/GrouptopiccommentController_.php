<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组回帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class GrouptopiccommentController extends InitController{

	protected $_arrTopics=array();
	
	public function filter_(&$arrMap){
		$arrMap['grouptopiccomment_title']=array('like','%'.G::getGpc('grouptopiccomment_title').'%');

		// 帖子检索
		$nTid=intval(G::getGpc('tid','G'));
		if($nTid){
			$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nTid)->getOne();

			if(!empty($oGrouptopic['grouptopic_id'])){
				$arrMap['grouptopic_id']=$nTid;
				$this->assign('oGrouptopic',$oGrouptopic);
			}
		}

		// 回收站检索
		if(isset($_GET['status']) && $_GET['status']==0){
			$arrMap['grouptopiccomment_status']='0';
			$this->assign('bRecyclebin',true);
		}
	}
	
	public function index($sModel=null,$bDisplay=true){
		parent::index('grouptopiccomment',false);

		$this->display(Admin_Extend::template('group','grouptopiccomment/index'));
	}
	
	public function add(){
		$this->E(Dyhb::L('后台无法发布回帖','__APP_ADMIN_LANG__@Controller/Grouptopiccomment'));
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('grouptopiccomment',$nId,false);
		$this->display(Admin_Extend::template('group','grouptopiccomment/add'));
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('grouptopiccomment',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrGroups=array();
		
		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			// 读取所有待删除的回帖，提取相关主题
			$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nId)->getOne();
			if(!empty($oGrouptopiccomment['grouptopiccomment_id'])){
				$arrTopics[]=$oGrouptopiccomment['grouptopic_id'];
			}
		}

		$arrTopics=array_unique($arrTopics);

		$this->_arrTopics=$arrTopics;
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();

		parent::foreverdelete('grouptopiccomment',$sId);
	}

	protected function aForeverdelete($sId){
		// 重新统计相关主题的回帖数量
		$arrTopics=$this->_arrTopics;

		if(is_array($arrTopics)){
			foreach($arrTopics as $nTid){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nTid)->getOne();
				
				if(!empty($oGrouptopic['grouptopic_id'])){
					// 更新主题回帖数量
					$nCommentnum=GrouptopiccommentModel::F('grouptopic_id=?',$nTid)->all()->getCounts();
					$oGrouptopic->grouptopic_comments=$nCommentnum;
					$oGrouptopic->save(0,'update');

					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::forbid('grouptopiccomment',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::resume('grouptopiccomment',$nId,true);
	}

}
