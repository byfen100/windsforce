<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   留言板评论模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserguestbookModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'userguestbook',
			'props'=>array(
				'userguestbook_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'userguestbook'=>array(Db::BELONGS_TO=>'UserguestbookModel','source_key'=>'userguestbook_id','target_key'=>'user_id','skip_empty'=>true),
			),
			'attr_protected'=>'userguestbook_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('userguestbook_ip','getIp','create','callback'),
			),
			'check'=>array(
				'userguestbook_name'=>array(
					array('require',Dyhb::L('评论名字不能为空','__COMMON_LANG__@Model/Commoncomment')),
					array('max_length',25,Dyhb::L('评论名字的最大字符数为25','__COMMON_LANG__@Model/Commoncomment'))
				),
				'userguestbook_email'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论Email 最大字符数为300','__COMMON_LANG__@Model/Commoncomment')),
					array('email',Dyhb::L('评论的邮件必须为正确的Email 格式','__COMMON_LANG__@Model/Commoncomment'))
				),
				'userguestbook_url'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论URL 最大字符数为300','__COMMON_LANG__@Model/Commoncomment')),
					array('url',Dyhb::L('评论的邮件必须为正确的URL 格式','__COMMON_LANG__@Model/Commoncomment'))
				),
				'userguestbook_content'=>array(
					array('require',Dyhb::L('评论的内容不能为空','__COMMON_LANG__@Model/Commoncomment'))
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
		if(isset($_POST['userguestbook_content'])){
			$_POST['userguestbook_content']=G::html($_POST['userguestbook_content']);
		}
	}

	static public function getParentCommentsPage($nFinecommentid,$nUserguestbookParentid=0,$nEveryCommentnum=1,$nUserguestbookid=0,$bAdminuser=false){
		$arrWhere['userguestbook_status']=1;
		$arrWhere['userguestbook_parentid']=$nUserguestbookParentid;
		$arrWhere['userguestbook_userid']=$nUserguestbookid;

		if($bAdminuser===false){
			$arrWhere['userguestbook_auditpass']=1;
		}
		
		// 查找当前评论的记录
		$nTheSearchKey='';

		$arrUserguestbookLists=self::F()->where($arrWhere)->all()->order('userguestbook_id DESC')->query();
		foreach($arrUserguestbookLists as $nKey=>$oUserguestbookList){
			if($oUserguestbookList['userguestbook_id']==$nFinecommentid){
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
		$oTryUserguestbook=UserguestbookModel::F('userguestbook_id=? AND userguestbook_status=1',$nCommentnumId)->getOne();
		if(empty($oTryUserguestbook['userguestbook_id'])){
			return false;
		}

		$bAdminuser=$GLOBALS['___login___']['user_id']!=$oTryUserguestbook->userguestbook->user_id?false:true;
		if($oTryUserguestbook['userguestbook_auditpass']==0 && $bAdminuser===false){
			return false;
		}

		// 分析出评论所在的分页值
		$nPage=self::getParentCommentsPage($nCommentnumId,0,$GLOBALS['_cache_']['home_option']['homefreshcomment_list_num'],$oTryUserguestbook['userguestbook_id'],$bAdminuser);

		return Dyhb::U('home://space@?id='.$oTryUserguestbook['userguestbook_userid'].'&type=guestbook'.($nPage>1?'&page='.$nPage:'')).'#comment-'.$nCommentnumId;
	}

}
