<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   设置帖子浏览是否包含侧边栏控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SetgrouptopicsideController extends Controller{

	public function index(){
		$nSide=intval(G::getGpc('side','G'));

		if(!in_array($nSide,array(1,2))){
			$nSide=1;
		}

		Dyhb::cookie('group_grouptopicside',$nSide);

		$this->S('帖子侧边栏切换成功');
	}

}
