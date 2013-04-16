<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组帖子评论模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopiccommentModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'grouptopiccomment',
			'props'=>array(
				'grouptopiccomment_id'=>array('readonly'=>true),
				'grouptopic'=>array(Db::BELONGS_TO =>'GrouptopicModel','source_key'=>'grouptopic_id','target_key'=>'grouptopic_id'),
				'user'=>array(Db::BELONGS_TO =>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'userprofile'=>array(Db::BELONGS_TO=>'UserprofileModel','source_key'=>'user_id','target_key'=>'user_id'),
				'usercount'=>array(Db::BELONGS_TO=>'UsercountModel','source_key'=>'user_id','target_key'=>'user_id'),
			),
			'attr_protected'=>'grouptopiccomment_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
			),
			'check'=>array(
				'grouptopiccomment_title'=>array(
					array('max_length',300,Dyhb::L('回帖标题不能超过300个字符','__APP_ADMIN_LANG__@Model/Grouptopiccomment')),
				),
				'grouptopiccomment_content'=>array(
					array('require',Dyhb::L('帖子评论内容不能为空','__APP_ADMIN_LANG__@Model/Grouptopiccomment')),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function safeInput(){
	}

	protected function userId(){
		$nUserId=$GLOBALS['___login___']['user_id'];

		return $nUserId>0?$nUserId:0;
	}	
	
	protected function userName(){
		$sUserName=$GLOBALS['___login___']['user_name'];

		return $sUserName?$sUserName:'';
	}

	static public function getGrouptopiccommentById($nCommentId,$sField='grouptopiccomment_name',$bAll=false){
		$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nCommentId)->query();

		if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
			return '';
		}

		if($bAll===true){
			return $oGrouptopiccomment;
		}
		
		return $oGrouptopiccomment[$sField];
	}

	static public function getParentCommentsPage($nFinecommentid,$nGrouptopiccommentParentid=0,$nEveryCommentnum=1,$nGrouptopicid=0,$sOrdertype='ASC',$nAutopass=0){
		$arrWhere['grouptopiccomment_status']=1;
		$arrWhere['grouptopiccomment_parentid']=$nGrouptopiccommentParentid;
		$arrWhere['grouptopic_id']=$nGrouptopicid;

		if($nAutopass==1){
			$arrWhere['grouptopiccomment_auditpass']=1;
		}
		
		// 查找当前评论的记录
		$nTheSearchKey='';

		$arrGrouptopiccommentLists=self::F()->where($arrWhere)->all()->order('grouptopiccomment_auditpass ASC,grouptopiccomment_stickreply DESC,grouptopiccomment_id '.$sOrdertype)->query();
		foreach($arrGrouptopiccommentLists as $nKey=>$oGrouptopiccommentList){
			if($oGrouptopiccommentList['grouptopiccomment_id']==$nFinecommentid){
				$nTheSearchKey=$nKey+1;
			}
		}

		$nPage=ceil($nTheSearchKey/$nEveryCommentnum);
		if($nPage<1){
			$nPage=1;
		}

		return $nPage;
	}

	static public function getCommenturlByid($nCommentnumId){
		// 判断评论是否存在
		$oTryGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=? AND grouptopiccomment_status=1',$nCommentnumId)->getOne();
		if(empty($oTryGrouptopiccomment['grouptopiccomment_id'])){
			return false;
		}

		$sOrdertype=$oTryGrouptopiccomment->grouptopic->grouptopic_ordertype?'DESC':'ASC';

		if(!Groupadmin_Extend::checkCommentadminRbac($oTryGrouptopiccomment->grouptopic->group,array('group@grouptopicadmin@auditcomment'))){
			$nAutopass=1;
		}else{
			$nAutopass=0;
		}

		// 分析出父级评论所在的分页值
		$nPage=self::getParentCommentsPage($nCommentnumId,0,$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum'],$oTryGrouptopiccomment['grouptopic_id'],$sOrdertype,$nAutopass);

		return Dyhb::U('group://topic@?id='.$oTryGrouptopiccomment['grouptopic_id'].($nPage>1?'&page='.$nPage:'')).'#grouptopiccomment-'.$nCommentnumId;
	}

}
