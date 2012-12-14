<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   取得分类控制器($)*/

!defined('DYHB_PATH') && exit;

class GetcategoryController extends Controller{

	public function index(){
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