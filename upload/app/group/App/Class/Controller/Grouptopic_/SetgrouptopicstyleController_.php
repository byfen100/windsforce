<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   设置帖子浏览风格控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SetgrouptopicstyleController extends Controller{

	public function index(){
		$nStyle=intval(G::getGpc('style','G'));

		if(!in_array($nStyle,array(1,2))){
			$nStyle=1;
		}

		Dyhb::cookie('group_grouptopicstyle',$nStyle);

		$this->S(Dyhb::L('帖子样式切换成功','Controller'));
	}

}
