<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'group',
			'props'=>array(
				'group_id'=>array('readonly'=>true),
				'groupcategory'=>array('many_to_many'=>'GroupcategoryModel','mid_class'=>'GroupcategoryindexModel','mid_source_key'=>'group_id','mid_target_key'=>'groupcategory_id'),
				'grouptopiccategory'=>array(Db::HAS_MANY=>'GrouptopiccategoryModel','source_key'=>'group_id','target_key'=>'group_id'),
			),
			'attr_protected'=>'group_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
			),
			'check'=>array(
				'group_name'=>array(
					array('empty'),
					array('max_length',30,Dyhb::L('群组不能超过30个字符','__APPGROUP_COMMON_LANG__@Model')),
					array('number_underline_english',Dyhb::L('群组名只能是由数字，下划线，字母组成','__APPGROUP_COMMON_LANG__@Model')),
					array('groupName',Dyhb::L('群组名已经存在','__APPGROUP_COMMON_LANG__@Model'),'condition'=>'must','extend'=>'callback'),
				),
				'group_nikename'=>array(
					array('require',Dyhb::L('群组别名不能为空','__APPGROUP_COMMON_LANG__@Model')),
					array('max_length',30,Dyhb::L('群组别名不能超过30个字符','__APPGROUP_COMMON_LANG__@Model'))
				),
				'group_listdescription'=>array(
					array('require',Dyhb::L('群组列表简介不能为空','__APPGROUP_COMMON_LANG__@Model')),
					array('max_length',300,Dyhb::L('群组列表简介不能超过300个字符','__APPGROUP_COMMON_LANG__@Model'))
				),
				'group_description'=>array(
					array('require',Dyhb::L('群组简介不能为空','__APPGROUP_COMMON_LANG__@Model')),
				),
				'group_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__APPGROUP_COMMON_LANG__@Model'),'condition'=>'notempty','extend'=>'regex'),
				),
				'group_roleleader'=>array(
					array('require',Dyhb::L('自定义角色组长名字不能为空','__APPGROUP_COMMON_LANG__@Model')),
				),
				'group_roleadmin'=>array(
					array('require',Dyhb::L('自定义角色管理员名字不能为空','__APPGROUP_COMMON_LANG__@Model')),
				),				
				'group_roleuser'=>array(
					array('require',Dyhb::L('自定义角色成员名字不能为空','__APPGROUP_COMMON_LANG__@Model')),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function groupName(){
		$nId=G::getGpc('value','P');
		$sGroupName=G::getGpc('group_name','P');
		$sGroupInfo='';

		if($nId){
			$arrGroup=self::F('group_id=?',$nId)->asArray()->getOne();
			$sGroupInfo=trim($arrGroup['group_name']);
		}

		if($sGroupName!=$sGroupInfo){
			$arrResult=self::F()->getBygroup_name($sGroupName)->toArray();
			if(!empty($arrResult['group_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function afterInsert($nGroupId,$nCategoryId){
		if($nCategoryId<=0){
			return;
		}
		
		// 判断小组和分类索引是否存在
		$oExistGroupcategoryindex=GroupcategoryindexModel::F('group_id=? AND groupcategory_id=?',$nGroupId,$nCategoryId)->query();
		if(!empty($oExistGroupcategoryindex['group_id'])){
			return;
		}

		// 插入小组和分类的关联
		$oGroupcategoryindex=new GroupcategoryindexModel();
		$oGroupcategoryindex->group_id=$nGroupId;
		$oGroupcategoryindex->groupcategory_id=$nCategoryId;
		$oGroupcategoryindex->save(0);

		// 更新分类下的小组数量
		$nGroupNums=GroupcategoryindexModel::F('groupcategory_id=?',$nCategoryId)->all()->getCounts();
		$oGroupCategory=GroupcategoryModel::F('groupcategory_id=?',$nCategoryId)->query();
		$oGroupCategory->groupcategory_count=$nGroupNums;
		$oGroupCategory->save(0,'update');

		// 判断是否有顶级分类
		if($oGroupCategory['groupcategory_parentid']>0){
			// 更新顶级分类下小组数
			$nGroupTotal=GroupcategoryModel::F('groupcategory_parentid=?',$oGroupCategory['groupcategory_parentid'])->getSum('groupcategory_count');
			$oGroupCategory=GroupcategoryModel::F('groupcategory_id=?',$oGroupCategory['groupcategory_parentid'])->query();
			$oGroupCategory->groupcategory_count=$nGroupTotal;
			$oGroupCategory->save(0,'update');
		}
	}
	
	public function afterDelete($nGroupId,$nCategoryId){
		if($nCategoryId<=0){
			return;
		}
		
		// 解除群组和分类关联
		$oDb=Db::RUN();
		$sSql="DELETE FROM ".GroupcategoryindexModel::F()->query()->getTablePrefix()."groupcategoryindex WHERE group_id={$nGroupId} AND groupcategory_id={$nCategoryId}";
		$oDb->query($sSql);

		// 更新分类下的小组数量
		$nGroupNums=GroupcategoryindexModel::F('groupcategory_id=?',$nCategoryId)->all()->getCounts();
		$oGroupCategory=GroupcategoryModel::F('groupcategory_id=?',$nCategoryId)->query();
		$oGroupCategory->groupcategory_count=$nGroupNums;
		$oGroupCategory->save(0,'update');

		// 判断是否有顶级分类
		if($oGroupCategory['groupcategory_parentid']>0){
			// 更新顶级分类下小组数
			$nGroupTotal=GroupcategoryModel::F('groupcategory_parentid=?',$oGroupCategory['groupcategory_parentid'])->getSum('groupcategory_count');
			$oGroupCategory=GroupcategoryModel::F('groupcategory_id=?',$oGroupCategory['groupcategory_parentid'])->query();
			$oGroupCategory->groupcategory_count=$nGroupTotal;
			$oGroupCategory->save(0,'update');
		}
	}

	public function recommend($nId,$nStatus=0){
		if(!empty($nId)){
			$oModelMeta=self::M();
			$oModelMeta->updateDbWhere(array('group_isrecommend'=>$nStatus),array('group_id'=>$nId));
			return true;
		}else{
			$this->setErrorMessage(Dyhb::L('操作项不存在','__APPGROUP_COMMON_LANG__@Model'));
		}
	}

	public function safeInput(){
		if(isset($_POST['group_name'])){
			$_POST['group_name']=G::html($_POST['group_name']);
		}

		$_POST['group_nikename']=G::html($_POST['group_nikename']);
		$_POST['group_description']=G::cleanJs($_POST['group_description']);
	}

	protected function userId(){
		$nUserId=$GLOBALS['___login___']['user_id'];
		return $nUserId>0?$nUserId:0;
	}

	public function groupbyUserid($nUserid,$nNum=0){
		$arrGroupusers=GroupuserModel::F('user_id=?',$nUserid)->getAll();
		if(!is_array($arrGroupusers)){
			return false;
		}

		$arrGroupids=array();
		foreach($arrGroupusers as $oGroupuser){
			$arrGroupids[]=$oGroupuser['group_id'];
		}

		$oGroup=GroupModel::F(array('group_id'=>array('in',$arrGroupids)));
		if($nNum>0){
			$oGroup->limit(0,$nNum);
		}

		return $oGroup->getAll();
	}

	public static function isGroupuser($nGroupid,$nUserid){
		$oTrygroupuser=GroupuserModel::F('user_id=? AND group_id=?',$nUserid,$nGroupid)->getOne();
		
		if(empty($oTrygroupuser['user_id'])){
			return 0;
		}else{
			return 1;
		}
	}

	public function clearToday(){
		$oDb=Db::RUN();
		return $oDb->query("UPDATE ".$this->getTablePrefix()."group SET group_topiccommenttodaynum='0',group_topictodaynum='0',group_totaltodaynum='0'");
	}

	public function resetUser($nGroupid=0){
		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
				
		if(!empty($oGroup['group_id'])){
			$oGroup->group_usernum=GroupuserModel::F('group_id=?',$oGroup['group_id'])->getCounts();
			$oGroup->setAutofill(false);
			$oGroup->save(0,'update');
			
			if($oGroup->isError()){
				$this->setErrorMessage($oGroup->getErrorMessage());
				return false;
			}
		}

		return true;
	}

}
