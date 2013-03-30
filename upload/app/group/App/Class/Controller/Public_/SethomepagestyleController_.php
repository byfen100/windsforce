<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SethomepagestyleController extends Controller{

	public function index(){
		$nStyle=intval(G::getGpc('style','G'));

		if(!in_array($nStyle,array(1,2))){
			$nStyle=1;
		}

		Dyhb::cookie('group_homepagestyle',$nStyle);

		$this->S('主页样式切换成功');
	}

}
