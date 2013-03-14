<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   附件评论模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AttachmentcommentModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'attachmentcomment',
			'props'=>array(
				'attachmentcomment_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'attachment'=>array(Db::BELONGS_TO=>'AttachmentModel','source_key'=>'attachment_id','target_key'=>'attachment_id'),
			),
			'attr_protected'=>'attachmentcomment_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('attachmentcomment_ip','getIp','create','callback'),
			),
			'check'=>array(
				'attachmentcomment_name'=>array(
					array('require',Dyhb::L('评论名字不能为空','__COMMON_LANG__@Model/Commoncomment')),
					array('max_length',25,Dyhb::L('评论名字的最大字符数为25','__COMMON_LANG__@Model/Commoncomment'))
				),
				'attachmentcomment_email'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论Email 最大字符数为300','__COMMON_LANG__@Model/Commoncomment')),
					array('email',Dyhb::L('评论的邮件必须为正确的Email 格式','__COMMON_LANG__@Model/Commoncomment'))
				),
				'attachmentcomment_url'=>array(
					array('empty'),
					array('max_length',300,Dyhb::L('评论URL 最大字符数为300','__COMMON_LANG__@Model/Commoncomment')),
					array('url',Dyhb::L('评论的邮件必须为正确的URL 格式','__COMMON_LANG__@Model/Commoncomment'))
				),
				'attachmentcomment_content'=>array(
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
		if(isset($_POST['attachmentcomment_content'])){
			$_POST['attachmentcomment_content']=G::html($_POST['attachmentcomment_content']);
		}
	}

}
