<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动评论模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventcommentModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'eventcomment',
			'props'=>array(
				'eventcomment_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'event'=>array(Db::BELONGS_TO=>'EventModel','source_key'=>'eventcomment_id','target_key'=>'event_id','skip_empty'=>true),
			),
			'attr_protected'=>'eventcomment_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('eventcomment_ip','getIp','create','callback'),
			),
			'check'=>array(
				'eventcomment_name'=>array(
					array('require',Dyhb::L('评论名字不能为空','__COMMON_LANG__@Common')),
					array('max_length',25,Dyhb::L('评论名字的最大字符数为25','__COMMON_LANG__@Common'))
				),
				'eventcomment_email'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论Email 最大字符数为300','__COMMON_LANG__@Common')),
					array('email',Dyhb::L('评论的邮件必须为正确的Email 格式','__COMMON_LANG__@Common'))
				),
				'eventcomment_url'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论URL 最大字符数为300','__COMMON_LANG__@Common')),
					array('url',Dyhb::L('评论的邮件必须为正确的URL 格式','__COMMON_LANG__@Common'))
				),
				'eventcomment_content'=>array(
					array('require',Dyhb::L('评论的内容不能为空','__COMMON_LANG__@Common'))
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

	protected function userId(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_id']?$arrUserData['user_id']:0;
	}

	protected function getIp(){
		return G::getIp();
	}

	public function safeInput(){
		if(isset($_POST['eventcomment_content'])){
			$_POST['eventcomment_content']=G::html($_POST['eventcomment_content']);
		}
	}

	static public function getParentCommentsPage($nFinecommentid,$nEventcommentParentid=0,$nEveryCommentnum=1,$nEventcommentid=0,$bAdminuser=false){
		$arrWhere['eventcomment_status']=1;
		$arrWhere['eventcomment_parentid']=$nEventcommentParentid;
		$arrWhere['event_id']=$nEventcommentid;

		if($bAdminuser===false){
			$arrWhere['eventcomment_auditpass']=1;
		}
		
		// 查找当前评论的记录
		$nTheSearchKey='';

		$arrEventcommentLists=self::F()->where($arrWhere)->all()->order('create_dateline ASC')->query();
		foreach($arrEventcommentLists as $nKey=>$oEventcommentList){
			if($oEventcommentList['eventcomment_id']==$nFinecommentid){
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
		$oTryEventcomment=EventcommentModel::F('eventcomment_id=? AND eventcomment_status=1',$nCommentnumId)->getOne();
		if(empty($oTryEventcomment['eventcomment_id'])){
			return false;
		}

		$bAdminuser=$GLOBALS['___login___']['user_id']!=$oTryEventcomment->event->user_id?false:true;
		if($oTryEventcomment['eventcomment_auditpass']==0 && $bAdminuser===false){
			return false;
		}

		// 分析出评论所在的分页值
		$nPage=self::getParentCommentsPage($nCommentnumId,0,$GLOBALS['_cache_']['home_option']['homefreshcomment_list_num'],$oTryEventcomment['event_id'],$bAdminuser);

		return Dyhb::U('event://e@?id='.$oTryEventcomment['event_id'].($nPage>1?'&page='.$nPage:'')).'#comment-'.$nCommentnumId;
	}

}
