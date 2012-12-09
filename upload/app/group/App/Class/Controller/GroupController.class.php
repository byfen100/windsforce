<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组控制器($)*/

!defined('DYHB_PATH') && exit;

class GroupController extends InitController{

	public function show(){
		Core_Extend::doControllerAction('Group@Show','index');
	}

	public function joingroup(){
		Core_Extend::doControllerAction('Group@Joingroup','index');
	}

	public function leavegroup(){
		Core_Extend::doControllerAction('Group@Leavegroup','index');
	}

	public function getcategory(){
		$nGid=intval(G::getGpc('gid','P'));

		if(empty($nGid)){
			echo '';
		}

		echo "<option value=\"0\">"."默认分类</option>";
		
		$arrGrouptopiccategory=GrouptopiccategoryModel::F('group_id=?',$nGid)->getAll();
		foreach($arrGrouptopiccategory as $key=>$oValue){
			echo "<option value=\"$oValue->grouptopiccategory_id\">".$oValue->grouptopiccategory_name."</option>";
		}
	}

}
