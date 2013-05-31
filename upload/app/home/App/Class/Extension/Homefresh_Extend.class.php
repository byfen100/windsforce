<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主页新鲜事函数库文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Homefresh_Extend{

	public static function getMyhomefreshnum($nUserid){
		$oHomefresh=Dyhb::instance('HomefreshModel');
		return $oHomefresh->getHomefreshnumByUserid($nUserid);
	}

	public static function getNewcomment($nId,$nUserid){
		if($GLOBALS['___login___']['user_id']!=$nUserid){
			$sHomefreshcommentAuditpass=' AND homefreshcomment_auditpass=1 ';
		}else{
			$sHomefreshcommentAuditpass='';
		}
		
		return HomefreshcommentModel::F(
				'homefresh_id=? AND homefreshcomment_status=1 '.$sHomefreshcommentAuditpass.' AND homefreshcomment_parentid=0',$nId
			)->limit(0,$GLOBALS['_cache_']['home_option']['homefreshcomment_limit_num'])->order('homefreshcomment_id DESC')->getAll();
	}

	public static function getNewchildcomment($nId,$nCommentid,$nUserid,$bAll=false,$nCommentpage=1){
		if($GLOBALS['___login___']['user_id']!=$nUserid){
			$sHomefreshcommentAuditpass=' AND homefreshcomment_auditpass=1 ';
		}else{
			$sHomefreshcommentAuditpass='';
		}
		
		$oHomefreshcommentSelect=HomefreshcommentModel::F(
				'homefresh_id=? AND homefreshcomment_status=1 '.$sHomefreshcommentAuditpass.' AND homefreshcomment_parentid=?',$nId,$nCommentid
			)->order('homefreshcomment_id DESC');

		if($bAll===true){
			if($nCommentpage<1){
				$nCommentpage=1;
			}

			$nTotalHomefreshcommentNum=$oHomefreshcommentSelect->All()->getCounts();

			$oPage=Page::RUN($nTotalHomefreshcommentNum,$GLOBALS['_cache_']['home_option']['homefreshchildcomment_list_num'],$nCommentpage,false);

			$arrHomefreshcomments=HomefreshcommentModel::F(
				'homefresh_id=? AND homefreshcomment_status=1 '.$sHomefreshcommentAuditpass.' AND homefreshcomment_parentid=?',$nId,$nCommentid
				)->order('homefreshcomment_id DESC')->limit($oPage->returnPageStart(),$GLOBALS['_cache_']['home_option']['homefreshchildcomment_list_num'])->getAll();

			return array($arrHomefreshcomments,$oPage->P('pagination_'.$nCommentid.'@pagenav','span','current','disabled','commentpage_'.$nCommentid),$nTotalHomefreshcommentNum<$GLOBALS['_cache_']['home_option']['homefreshchildcomment_list_num']?false:true);
		}else{
			return $oHomefreshcommentSelect->limit(0,$GLOBALS['_cache_']['home_option']['homefreshchildcomment_limit_num'])->getAll();
		}
	}

	public static function getHomefreshtagBydate($nDate,$nNum){
		$nData=intval($nDate);
		$nNum=intval($nNum);

		if($nNum<1){
			$nNum=1;
		}

		if($nDate<3600){
			$nDate=3600;
		}

		return HomefreshtagModel::F('homefreshtag_status=? AND create_dateline>?',1,(CURRENT_TIMESTAMP-$nDate))->order('homefreshtag_totalcount DESC')->limit(0,$nNum)->getAll();
	}

}
