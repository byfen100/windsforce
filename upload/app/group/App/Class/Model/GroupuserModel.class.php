<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组用户关联模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupuserModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'groupuser',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function userToGroup($nGroupId){
		$arrUsers=UserModel::F()->order('user_id DESC')->getAll();
		if(is_array($arrUsers)){
			foreach($arrUsers as $oUser){
				$nGroupNums=GroupuserModel::F('user_id=? AND group_id=?',$oUser['user_id'],$nGroupId)->all()->getCounts();
				if($nGroupNums==0){
					$oGroupuser=new GroupuserModel();
					$oGroupuser->user_id=$oUser['user_id'];
					$oGroupuser->group_id=$nGroupId;
					$oGroupuser->save(0);
				}
			}
		}

		$nUserNum=GroupuserModel::F('group_id=?',$nGroupId)->all()->getCounts();
		$oGroup=GroupModel::F('group_id=?',$nGroupId)->query();
		$oGroup->group_usernum=$nUserNum;
		$oGroup->save(0,'update');
	}

}
