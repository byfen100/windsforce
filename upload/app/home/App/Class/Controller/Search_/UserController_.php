<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户搜索($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserController extends Controller{

	public function index(){
		$sKey=urldecode(trim(G::getGpc('key')));
		
		$sKey=htmlspecialchars($sKey);
		$sKey=str_replace('%','\%',$sKey);
		$sKey=str_replace('_','\_',$sKey);

		if(!empty($sKey)){
			G::urlGoTo(Dyhb::U('home://friend/searchresult?user_name='.urlencode($sKey),array(),true));
		}

		$this->assign('sKey',$sKey);

		$this->display('search+user');
	}

	public function user_title_(){
		return Dyhb::L('用户搜索','Controller');
	}

	public function user_keywords_(){
		return $this->user_title_();
	}

	public function user_description_(){
		return $this->user_title_();
	}

}
