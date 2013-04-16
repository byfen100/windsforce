<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入群组模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/group/App/Class/Model');

class GroupController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['group_name']=array('like','%'.G::getGpc('group_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('group',false);

		$this->display(Admin_Extend::template('group','group/index'));
	}

	public function bAdd_(){
		$oGroupcategory=Dyhb::instance('GroupcategoryModel');
		$oGroupcategoryTree=$oGroupcategory->getGroupcategoryTree();

		$this->assign('oGroupcategoryTree',$oGroupcategoryTree);
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		$this->bAdd_();
		
		parent::edit('group',$nId,false);
		$this->display(Admin_Extend::template('group','group/add'));
	}
	
	public function bEdit_(){
		$this->bAdd_();
	}
	
	public function add(){
		$this->bAdd_();
		
		$this->display(Admin_Extend::template('group','group/add'));
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		$_POST['group_description']=rtrim($_POST['group_description'],'<br />');
		
		parent::update('group',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		$_POST['group_description']=rtrim($_POST['group_description'],'<br />');
		
		parent::insert('group',$nId);
	}

	protected function aInsert($nId=null){
		$oGroup=Dyhb::instance('GroupModel');
		$oGroup->afterInsert($nId,intval(G::getGpc('group_categoryid','P')));
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			// 小组有帖子不能删除
			$nTotalgrouptopic=GrouptopicModel::F('group_id=?',$nId)->all()->getCounts();
			if($nTotalgrouptopic>0){
				$this->E(Dyhb::L('小组存在帖子，请先删除帖子','__APP_ADMIN_LANG__@Controller/Group'));
			}

			// 删除小组的帖子分类
			$oGrouptopiccategoryMeta=GrouptopiccategoryModel::M();
			$oGrouptopiccategoryMeta->deleteWhere(array('group_id'=>$nId));

			if($oGrouptopiccategoryMeta->isError()){
				$this->E($oGrouptopiccategoryMeta->getErrorMessage());
			}

			// 删除小组用户
			$oGroupuserMeta=GroupuserModel::M();
			$oGroupuserMeta->deleteWhere(array('group_id'=>$nId));

			if($oGroupuserMeta->isError()){
				$this->E($oGroupuserMeta->getErrorMessage());
			}
		}
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');
		
		$this->bForeverdelete_();
		
		parent::foreverdelete('group',$sId);
	}
	
	public function input_change_ajax($sName=null){
		parent::input_change_ajax('group');
	}

	public function recommend(){
		$nId=intval(G::getGpc('value','G'));

		$oGroup=Dyhb::instance('GroupModel');
		$oGroup->recommend($nId,1);
		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}else{
			$this->S(Dyhb::L('推荐成功','__APP_ADMIN_LANG__@Controller/Group'));
		}
	}

	public function unrecommend(){
		$nId=intval(G::getGpc('value','G'));

		$oGroup=Dyhb::instance('GroupModel');
		$oGroup->recommend($nId,0);
		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}else{
			$this->S(Dyhb::L('取消推荐成功','__APP_ADMIN_LANG__@Controller/Group'));
		}
	}

	public function upuser(){
		$nId=intval(G::getGpc('value','G'));

		if(!empty($nId)){
			$oGroup=Dyhb::instance('GroupuserModel');
			$oGroup->userToGroup($nId);
			$this->S(Dyhb::L('用户推送成功','__APP_ADMIN_LANG__@Controller/Group'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function icon(){
		$nId=intval(G::getGpc('value','G'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			$this->assign('oGroup',$oGroup);
			
			// 取得ICON
			$sGroupIcon=Group_Extend::getGroupIcon($oGroup['group_icon']);
			$this->assign('sGroupIcon',$sGroupIcon);

			$arrOptionData=$GLOBALS['_cache_']['group_option'];
			$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['group_icon_uploadfile_maxsize']));
			
			$this->display(Admin_Extend::template('group','group/icon'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function icon_add(){
		$nId=intval(G::getGpc('value','P'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			require_once(Core_Extend::includeFile('function/Upload_Extend'));
			try{
				// 上传前删除早前的icon
				if(!empty($oGroup['group_icon'])){
					require_once(Core_Extend::includeFile('function/Upload_Extend'));
					Upload_Extend::deleteIcon('group',$oGroup['group_icon']);
			
					$oGroup->group_icon='';
					$oGroup->save(0,'update');
					if($oGroup->isError()){
						$this->E($oGroup->getErrorMessage());
					}
				}

				// 执行上传
				$sPhotoDir=Upload_Extend::uploadIcon('group');
			
				$oGroup->group_icon=$sPhotoDir;
				$oGroup->save(0,'update');
				if($oGroup->isError()){
					$this->E($oGroup->getErrorMessage());
				}
			
				$this->S(Dyhb::L('图标设置成功','__APP_ADMIN_LANG__@Controller/Group'));
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function delete_icon(){
		$nId=intval(G::getGpc('value','G'));

		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			if(!empty($oGroup['group_icon'])){
				require_once(Core_Extend::includeFile('function/Upload_Extend'));
				Upload_Extend::deleteIcon('group',$oGroup['group_icon']);
			
				$oGroup->group_icon='';
				$oGroup->save(0,'update');
				if($oGroup->isError()){
					$this->E($oGroup->getErrorMessage());
				}
				
				$this->S(Dyhb::L('图标删除成功','__APP_ADMIN_LANG__@Controller/Group'));
			}else{
				$this->E(Dyhb::L('图标不存在','__APP_ADMIN_LANG__@Controller/Group'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function headerbg(){
		$nId=intval(G::getGpc('value','G'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			$this->assign('oGroup',$oGroup);
			
			// 读取系统背景
			$arrSystembgs=G::listDir(WINDSFORCE_PATH.'/app/group/Static/Images/groupbg',false,true);

			// 取得当前背景
			$sGroupHeaderbg=Group_Extend::getGroupHeaderbg($oGroup['group_headerbg']);
			$this->assign('sGroupHeaderbg',$sGroupHeaderbg);
			
			$arrOptionData=$GLOBALS['_cache_']['group_option'];
			$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['group_headbg_uploadfile_maxsize']));
			
			$this->display(Admin_Extend::template('group','group/headerbg'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function headerbg_add(){
		$nId=intval(G::getGpc('value','P'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			if($_FILES['headerbg']['error'][0]=='4'){
				if(isset($_POST['group_headerbg'])){
					$oGroup->group_headerbg=intval($_POST['group_headerbg']);
					$oGroup->save(0,'update');

					if($oGroup->isError()){
						$this->E($oGroup->getErrorMessage());
					}
				}
			}else{
				require_once(Core_Extend::includeFile('function/Upload_Extend'));
				try{
					// 上传前删除早前的icon
					if(!empty($oGroup['group_headerbg']) && !Core_Extend::isPostInt($oGroup['group_headerbg'])){
						require_once(Core_Extend::includeFile('function/Upload_Extend'));
						Upload_Extend::deleteIcon('group',$oGroup['group_headerbg']);
				
						$oGroup->group_headerbg='';
						$oGroup->save(0,'update');
						if($oGroup->isError()){
							$this->E($oGroup->getErrorMessage());
						}
					}

					// 执行上传
					$sPhotoDir=Upload_Extend::uploadIcon('group',array('width'=>1170,'height'=>150,'uploadfile_maxsize'=>$GLOBALS['_cache_']['group_option']['group_headbg_uploadfile_maxsize']));
				
					$oGroup->group_headerbg=$sPhotoDir;
					$oGroup->save(0,'update');
					if($oGroup->isError()){
						$this->E($oGroup->getErrorMessage());
					}
				}catch(Exception $e){
					$this->E($e->getMessage());
				}
			}
			
			$this->S(Dyhb::L('群组背景设置成功','__APP_ADMIN_LANG__@Controller/Group'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function delete_headerbg(){
		$nId=intval(G::getGpc('value','G'));

		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			if(!empty($oGroup['group_headerbg'])){
				if(!Core_Extend::isPostInt($oGroup['group_headerbg'])){
					require_once(Core_Extend::includeFile('function/Upload_Extend'));
					Upload_Extend::deleteIcon('group',$oGroup['group_headerbg']);
				}
			
				$oGroup->group_headerbg='';
				$oGroup->save(0,'update');
				if($oGroup->isError()){
					$this->E($oGroup->getErrorMessage());
				}
				
				$this->S(Dyhb::L('小组背景删除成功','__APP_ADMIN_LANG__@Controller/Group'));
			}else{
				$this->E(Dyhb::L('小组背景不存在','__APP_ADMIN_LANG__@Controller/Group'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function groupcategory(){
		$nId=intval(G::getGpc('value','G'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			$this->assign('oGroup',$oGroup);
			
			$this->display(Admin_Extend::template('group','group/groupcategory'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function delete_category(){
		$nId=intval(G::getGpc('value','G'));
		$nCategoryId=intval(G::getGpc('category_id','G'));
		
		$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCategoryId)->query();
		if(!empty($oGroupcategory['groupcategory_id']) || $nCategoryId){
			$oGroup=Dyhb::instance('GroupModel');
			$oGroup->afterDelete($nId,$nCategoryId);
			
			$this->S(Dyhb::L('删除群组分类成功','__APP_ADMIN_LANG__@Controller/Group'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function add_category(){
		$nId=intval(G::getGpc('value','G'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			$this->bAdd_();
			
			$this->assign('nValue',$nId);
			
			// 获取当前分类
			$arrCategorys=array();
			$arrTemps=$oGroup->groupcategory;
			if(is_array($arrTemps)){
				foreach($arrTemps as $oTemp){
					$arrCategorys[]=$oTemp['groupcategory_id'];
				}
				unset($arrTemps);
			}
			$this->assign('arrCategorys',$arrCategorys);
			
			$this->display(Admin_Extend::template('group','group/add_category'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function add_category_id(){
		$nId=intval(G::getGpc('value'));
		$nCategoryId=intval(G::getGpc('category_id'));
		
		$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCategoryId)->query();
		if(!empty($oGroupcategory['groupcategory_id']) || $nCategoryId){
			$oExistGroupcategoryindex=GroupcategoryindexModel::F('group_id=? AND groupcategory_id=?',$nId,$nCategoryId)->query();
			if(!empty($oExistGroupcategoryindex['group_id'])){
				$this->E(Dyhb::L('群组分类已经存在','__APP_ADMIN_LANG__@Controller/Group'));
			}
			
			$oGroup=Dyhb::instance('GroupModel');
			$oGroup->afterInsert($nId,$nCategoryId);
				
			$this->S(Dyhb::L('添加群组分类成功','__APP_ADMIN_LANG__@Controller/Group'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function topiccategory(){
		$nId=intval(G::getGpc('value'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			$arrGrouptopiccategorys=GrouptopiccategoryModel::F('group_id=?',$nId)->getAll();
			$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
			
			$this->assign('nValue',$nId);
			
			$this->display(Admin_Extend::template('group','group/topiccategory'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function add_topiccategory(){
		$nId=intval(G::getGpc('value'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->query();
		if(!empty($oGroup['group_id'])){
			$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
			$oGrouptopiccategory->insertGroupcategory($nId);

			if($oGrouptopiccategory->isError()){
				$this->E($oGrouptopiccategory->getErrorMessage());
			}else{
				$this->S(Dyhb::L('添加帖子分类成功','__APP_ADMIN_LANG__@Controller/Group'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function delete_topic_category(){
		$nId=intval(G::getGpc('value'));
		$nGroupId=intval(G::getGpc('group_id'));
		
		$oGroupcategory=GrouptopiccategoryModel::F('grouptopiccategory_id=? AND group_id=?',$nId,$nGroupId)->query();
		if(!empty($oGroupcategory['grouptopiccategory_id'])){
			$oGrouptopiccategoryMeta=GrouptopiccategoryModel::M();
			$oGrouptopiccategoryMeta->deleteWhere(array('grouptopiccategory_id'=>$nId));
			
			if($oGrouptopiccategoryMeta->isError()){
				$this->E($oGrouptopiccategoryMeta->getErrorMessage());
			}
			
			$this->S(Dyhb::L('删除帖子分类成功','__APP_ADMIN_LANG__@Controller/Group'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function update_topic_category(){
		$nId=intval(G::getGpc('value'));
		$nGroupId=intval(G::getGpc('group_id'));
		
		$oGroupcategory=GrouptopiccategoryModel::F('grouptopiccategory_id=? AND group_id=?',$nId,$nGroupId)->query();
		if(!empty($oGroupcategory['grouptopiccategory_id'])){
			$this->assign('oGroupcategory',$oGroupcategory);
			$this->assign('nValue',$nGroupId);
			$this->assign('nCategoryId',$nId);
			
			$this->display(Admin_Extend::template('group','group/update_topic_category'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}
	
	public function update_topiccategory(){
		$nId=intval(G::getGpc('value'));
		
		$oGroupcategory=GrouptopiccategoryModel::F('grouptopiccategory_id=?',$nId)->order('grouptopiccategory_sort DESC')->query();
		if(!empty($oGroupcategory['grouptopiccategory_id'])){
			$oGroupcategory->save(0,'update');
			
			if($oGroupcategory->isError()){
				$this->E($oGroupcategory->getErrorMessage());
			}else{
				$this->S(Dyhb::L('更新帖子分类成功','__APP_ADMIN_LANG__@Controller/Group'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function user(){
		$nId=intval(G::getGpc('value'));
		
		$oGroup=GroupModel::F('group_id=?',$nId)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在','__APP_ADMIN_LANG__@Controller/Group'));
		}
		
		$this->assign('oGroup',$oGroup);
		
		// 读取小组创始人
		$arrGroupleaders=GroupuserModel::F('group_id=? AND groupuser_isadmin=2',$nId)->order('create_dateline DESC')->getAll();

		$arrTemp=array();
		if(is_array($arrGroupleaders)){
			foreach($arrGroupleaders as $oGroupleader){
				$arrTemp[]=$oGroupleader['user_id'];
			}
		}

		$this->assign('sGroupleader',implode(',',$arrTemp));
		$this->assign('arrGroupleaders',$arrGroupleaders);

		// 读取小组管理员
		$arrGroupadmins=GroupuserModel::F('group_id=? AND groupuser_isadmin=1',$nId)->order('create_dateline DESC')->getAll();

		$arrTemp=array();
		if(is_array($arrGroupadmins)){
			foreach($arrGroupadmins as $oGroupadmin){
				$arrTemp[]=$oGroupadmin['user_id'];
			}
		}

		$this->assign('sGroupadmin',implode(',',$arrTemp));
		$this->assign('arrGroupadmins',$arrGroupadmins);

		// 读取成员列表
		$nEverynum=$GLOBALS['_option_']['admin_list_num'];
		$arrWhere['group_id']=$nId;
		$arrWhere['groupuser_isadmin']=0;

		$nTotalRecord=GroupuserModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		
		$arrGroupusers=GroupuserModel::F()->where($arrWhere)->order("create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGroupusers',$arrGroupusers);
		$this->assign('sPageNavbar',$oPage->P());
		
		$this->display(Admin_Extend::template('group','group/user'));
	}

	public function user_add(){
		$nGroupid=intval(G::getGpc('value'));

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在','__APP_ADMIN_LANG__@Controller/Group'));
		}
		
		// 保存小组组长
		$sLeaderUid=trim(G::getGpc('leader_userid','P'));

		$arrLeaderUserid=explode(',',$sLeaderUid);
		$arrLeaderUserid=Dyhb::normalize($arrLeaderUserid,',',false);

		// 保存前清除旧的用户
		$oGroupuserMeta=GroupuserModel::M();
		$oGroupuserMeta->deleteWhere(array('group_id'=>$nGroupid,'groupuser_isadmin'=>2));

		if($oGroupuserMeta->isError()){
			$this->E($oGroupuserMeta->getErrorMessage());
		}
		
		if(!empty($arrLeaderUserid)){
			foreach($arrLeaderUserid as $nLeaderUserid){
				$oUser=UserModel::F('user_id=? AND user_status=1',$nLeaderUserid)->getOne();
				if(empty($oUser['user_id'])){
					$this->E('用户不存在或者被禁用');
				}
				
				$oGroupuser=new GroupuserModel();
				$oGroupuser->user_id=$nLeaderUserid;
				$oGroupuser->group_id=$nGroupid;
				$oGroupuser->groupuser_isadmin=2;

				$oGroupuser->save(0);

				if($oGroupuser->isError()){
					$this->E($oGroupuser->getErrorMessage());
				}
			}
		}

		// 保存管理员
		$sAdminUid=trim(G::getGpc('admin_userid','P'));

		$arrAdminUserid=explode(',',$sAdminUid);
		$arrAdminUserid=Dyhb::normalize($arrAdminUserid,',',false);

		// 保存前清除旧的用户
		$oGroupuserMeta=GroupuserModel::M();
		$oGroupuserMeta->deleteWhere(array('group_id'=>$nGroupid,'groupuser_isadmin'=>1));

		if($oGroupuserMeta->isError()){
			$this->E($oGroupuserMeta->getErrorMessage());
		}
		
		if(!empty($arrAdminUserid)){
			foreach($arrAdminUserid as $nAdminUserid){
				$oUser=UserModel::F('user_id=? AND user_status=1',$nAdminUserid)->getOne();
				if(empty($oUser['user_id'])){
					$this->E(Dyhb::L('用户不存在或者被禁用','__APP_ADMIN_LANG__@Controller/Group'));
				}
				
				$oGroupuser=new GroupuserModel();
				$oGroupuser->user_id=$nAdminUserid;
				$oGroupuser->group_id=$nGroupid;
				$oGroupuser->groupuser_isadmin=1;

				$oGroupuser->save(0);

				if($oGroupuser->isError()){
					$this->E($oGroupuser->getErrorMessage());
				}
			}
		}

		// 保存管理员
		$sUserUid=trim(G::getGpc('user_userid','P'));

		$arrUserUserid=explode(',',$sUserUid);
		$arrUserUserid=Dyhb::normalize($arrUserUserid,',',false);

		// 保存成员
		if(!empty($arrUserUserid)){
			foreach($arrUserUserid as $nUserUserid){
				$oUser=UserModel::F('user_id=? AND user_status=1',$nUserUserid)->getOne();
				if(empty($oUser['user_id'])){
					$this->E(Dyhb::L('用户不存在或者被禁用','__APP_ADMIN_LANG__@Controller/Group'));
				}

				$oTryGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$nUserUserid,$nGroupid)->getOne();

				if(!empty($oTryGroupuser['user_id'])){
					continue;
				}
				
				$oGroupuser=new GroupuserModel();
				$oGroupuser->user_id=$nUserUserid;
				$oGroupuser->group_id=$nGroupid;
				$oGroupuser->groupuser_isadmin='0';
				$oGroupuser->save(0);

				if($oGroupuser->isError()){
					$this->E($oGroupuser->getErrorMessage());
				}
			}
		}

		$this->S(Dyhb::L('用户设置成功','__APP_ADMIN_LANG__@Controller/Group'));
	}

	public function delete_groupuser(){
		$nGroupid=intval(G::getGpc('gid'));
		$nUserid=intval(G::getGpc('uid'));
		
		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在','__APP_ADMIN_LANG__@Controller/Group'));
		}

		$oUser=UserModel::F('user_id=?',$nUserid)->getOne();
		if(empty($oUser['user_id'])){
			$this->E(Dyhb::L('用户不存在','__APP_ADMIN_LANG__@Controller/Group'));
		}
		
		$oGroupuserMeta=GroupuserModel::M();
		$oGroupuserMeta->deleteWhere(array('group_id'=>$nGroupid,'user_id'=>$nUserid));

		if($oGroupuserMeta->isError()){
			$this->E($oGroupuserMeta->getErrorMessage());
		}
		
		$this->S(Dyhb::L('用户删除成功','__APP_ADMIN_LANG__@Controller/Group'));
	}

}
