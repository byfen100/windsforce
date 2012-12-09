<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   新鲜事评论模型($)*/

!defined('DYHB_PATH') && exit;

class HomefreshcommentModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'homefreshcomment',
			'props'=>array(
				'homefreshcomment_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'homefresh'=>array(Db::BELONGS_TO=>'HomefreshModel','source_key'=>'homefresh_id','target_key'=>'homefresh_id'),
			),
			'attr_protected'=>'homefreshcomment_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('homefreshcomment_ip','getIp','create','callback'),
			),
			'check'=>array(
				'homefreshcomment_name'=>array(
					array('require',Dyhb::L('评论名字不能为空','__APP_ADMIN_LANG__@Model/Homefreshcomment')),
					array('max_length',25,Dyhb::L('评论名字的最大字符数为25','__APP_ADMIN_LANG__@Model/Homefreshcomment'))
				),
				'homefreshcomment_email'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论Email 最大字符数为300','__APP_ADMIN_LANG__@Model/Homefreshcomment')),
					array('email',Dyhb::L('评论的邮件必须为正确的Email 格式','__APP_ADMIN_LANG__@Model/Homefreshcomment'))
				),
				'homefreshcomment_url'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论URL 最大字符数为300','__APP_ADMIN_LANG__@Model/Homefreshcomment')),
					array('url',Dyhb::L('评论的邮件必须为正确的URL 格式','__APP_ADMIN_LANG__@Model/Homefreshcomment'))
				),
				'homefreshcomment_content'=>array(
					array('require',Dyhb::L('评论的内容不能为空','__APP_ADMIN_LANG__@Model/Homefreshcomment'))
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
		if(isset($_POST['homefreshcomment_content'])){
			$_POST['homefreshcomment_content']=G::html($_POST['homefreshcomment_content']);
		}
	}

	static public function getParentCommentsPage($nFinecommentid,$nHomefreshcommentParentid=0,$nEveryCommentnum=1,$nHomefreshid=0,$bAdminuser=false){
		$arrWhere['homefreshcomment_status']=1;
		$arrWhere['homefreshcomment_parentid']=$nHomefreshcommentParentid;
		$arrWhere['homefresh_id']=$nHomefreshid;

		if($bAdminuser===false){
			$arrWhere['homefreshcomment_auditpass']=1;
		}
		
		// 查找当前评论的记录
		$nTheSearchKey='';

		$arrHomefreshcommentLists=self::F()->where($arrWhere)->all()->order('`homefreshcomment_id` DESC')->query();
		foreach($arrHomefreshcommentLists as $nKey=>$oHomefreshcommentList){
			if($oHomefreshcommentList['homefreshcomment_id']==$nFinecommentid){
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
		$oTryHomefreshcomment=HomefreshcommentModel::F('homefreshcomment_id=? AND homefreshcomment_status=1',$nCommentnumId)->getOne();
		if(empty($oTryHomefreshcomment['homefreshcomment_id'])){
			return false;
		}

		$bAdminuser=$GLOBALS['___login___']['user_id']!=$oTryHomefreshcomment->homefresh->user_id?false:true;
		if($oTryHomefreshcomment['homefreshcomment_auditpass']==0 && $bAdminuser===false){
			return false;
		}

		// 分析出父级评论所在的分页值
		$nPage=self::getParentCommentsPage($oTryHomefreshcomment['homefreshcomment_parentid']==0?$nCommentnumId:$oTryHomefreshcomment['homefreshcomment_parentid'],0,$GLOBALS['_cache_']['home_option']['homefreshcomment_list_num'],$oTryHomefreshcomment['homefresh_id'],$bAdminuser);

		// 分析出子评论所在分页值
		if($oTryHomefreshcomment['homefreshcomment_parentid']>0){
			$nCommentPage=self::getParentCommentsPage($nCommentnumId,$oTryHomefreshcomment['homefreshcomment_parentid'],$GLOBALS['_cache_']['home_option']['homefreshchildcomment_list_num'],$oTryHomefreshcomment['homefresh_id'],$bAdminuser);
		}else{
			$nCommentPage=1;
		}

		return Dyhb::U('home://fresh@?id='.$oTryHomefreshcomment['homefresh_id'].($nPage>1?'&page='.$nPage:'').($nCommentPage>1?'&commentpage_'.$oTryHomefreshcomment['homefreshcomment_parentid'].'='.$nCommentPage:'')).'#comment-'.$nCommentnumId;
	}

}
