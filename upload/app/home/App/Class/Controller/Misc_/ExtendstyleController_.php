<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主题样式切换控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ExtendstyleController extends Controller{

	public function index(){
		if($GLOBALS['_option_']['extendstyle_switch_on']==0){
			$this->E(Dyhb::L('系统已经关闭了主题扩展切换功能','Controller/Misc'));
		}

		$sStyleId=trim(G::getGpc('id','G'));

		// 发送主题COOKIE
		Dyhb::cookie('extend_style_id',$sStyleId);

		if($GLOBALS['___login___']!==false){
			$oUser=UserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
			$oUser->user_extendstyle=$sStyleId;
			$oUser->setAutofill(false);
			$oUser->save(0,'update');

			if($oUser->isError()){
				$this->E($oUser->getErrorMessage());
			}
		}

		$this->S(Dyhb::L('主题样式切换成功','Controller/Misc'));
	}

}
