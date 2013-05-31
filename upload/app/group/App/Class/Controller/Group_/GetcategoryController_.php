<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   AJAX取得分类控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GetcategoryController extends Controller{

	public function index(){
		$nGid=intval(G::getGpc('gid','P'));

		if(empty($nGid)){
			echo '';
		}

		echo "<option value=\"0\">".Dyhb::L('默认分类','Controller')."</option>";
		
		$arrGrouptopiccategorys=GrouptopiccategoryModel::F('group_id=?',$nGid)->order('grouptopiccategory_sort ASC')->getAll();

		if(is_array($arrGrouptopiccategorys)){
			foreach($arrGrouptopiccategorys as $key=>$oValue){
				echo "<option value=\"$oValue->grouptopiccategory_id\">".$oValue->grouptopiccategory_name."</option>";
			}
		}
	}

}