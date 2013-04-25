<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   最新用户Api控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;
!defined('IN_API') && !defined('IN_APISELF') && exit;

class NewuserController extends Controller{

	public function index(){
		// 获取参数
		$nNum=intval(G::getGpc('num','G'));
		$sType=strtolower(trim(G::getGpc('type','G')));

		// 基本处理
		if($nNum<1){
			$nNum=1;
		}

		if($nNum>USER_NEW_MAXNUM){
			$nNum=USER_NEW_MAXNUM;
		}

		if(empty($sType) || !in_array($sType,array('xml','json'))){
			$sType=USER_NEW_DEFAULTRETURNTYPE;
		}

		// 获取最新用户
		$arrData=array();
		
		$arrUsers=UserModel::F('user_status=?',1)->order('create_dateline DESC')->limit(0,$nNum)->getAll();
		if(is_array($arrUsers)){
			foreach($arrUsers as $oUser){
				$nUserid=$oUser['user_id'];

				// 基本信息
				$arrData['user_'.$nUserid]['user_name']=$oUser['user_name'];
				$arrData['user_'.$nUserid]['user_nikename']=$oUser['user_nikename'];
				$arrData['user_'.$nUserid]['user_email']=$oUser['user_email'];
				
				// 用户头像
				$arrData['user_'.$nUserid]['user_avatarorigin']=Core_Extend::avatar($nUserid,'origin',true);
				$arrData['user_'.$nUserid]['user_avatarbig']=Core_Extend::avatar($nUserid,'big',true);
				$arrData['user_'.$nUserid]['user_avatarmiddle']=Core_Extend::avatar($nUserid,'middle',true);
				$arrData['user_'.$nUserid]['user_avatarsmall']=Core_Extend::avatar($nUserid,'small',true);
			}
		}

		Core_Extend::api($arrData,$sType,true);
	}

}
