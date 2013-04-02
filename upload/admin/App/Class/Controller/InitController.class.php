<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公用控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class InitController extends Controller{

	public function init__(){
		parent::init__();

		Core_Extend::loadCache('option');
		Core_Extend::loginInformation();

		UserModel::M()->checkRbac();
		if(UserModel::M()->isBehaviorError()){
			$this->E(UserModel::M()->getBehaviorErrorMessage());
		}

		Core_Extend::page404($this);
		
		// 记录后台操作记录
		if($GLOBALS['_option_']['adminlog_record']==1 && 
			G::getGpc('page')<2 && 
			!in_array(MODULE_NAME,array('public','index','adminctrlmenu','adminlog'))
		)
		{
			$sUrl=parse_url(__SELF__,PHP_URL_QUERY);

			// 重复记录判断
			$bRecord=true;

			$nAdminlogrecordtime=intval($GLOBALS['_option_']['adminlog_record_time']);
			if($nAdminlogrecordtime>0){
				$oTryadminlogModel=AdminlogModel::F('adminlog_info=?',$sUrl)->order('adminlog_id DESC')->getOne();
				if(!empty($oTryadminlogModel['adminlog_id']) && CURRENT_TIMESTAMP-$oTryadminlogModel['create_dateline']<=$nAdminlogrecordtime){
					$bRecord=false;
				}
			}

			if($bRecord===true){
				$oAdminlog=new AdminlogModel();
				$oAdminlog->adminlog_info=$sUrl;
				$oAdminlog->save(0);

				if($oAdminlog->isError()){
					$this->E($oAdminlog->getErrorMessage());
				}
			}
		}
	}

	public function page404(){
		$this->display('Public+404');
		exit();
	}

	public function index($sName=null,$bDisplay=true){
		if(empty($sName)){
			$sName=MODULE_NAME;
		}

		$arrMap=$this->map($sName);
		if(method_exists($this,'filter_')){
			$this->filter_($arrMap);
		}
		$this->get_list($arrMap,$sName);

		if($bDisplay===true){
			$this->display();
		}
	}

	protected function map($sName=null){
		if(empty($sName)){
			$sName=MODULE_NAME;
		}

		$sName=ucfirst($sName).'Model';
		$arrField=array();
		eval('$arrField='.$sName.'::M()->_arrFieldToProp;');
		$arrMap=array();
		foreach($arrField as $sField=>$sProp){
			if(isset($_REQUEST[$sField]) && !empty($_REQUEST[$sField])){
				$arrMap[$sField]=$_REQUEST[$sField];
			}
		}
		
		return $arrMap;
	}

	protected function get_list($arrMap,$sName=null){
		if(empty($sName)){
			$sName=MODULE_NAME;
		}

		$sParameter='';

		$arrSortUrl=array();
		$nTotalRecord=0;
		eval('$nTotalRecord='.ucfirst($sName).'Model::F()->where($arrMap)->all()->getCounts();');
		
		foreach($arrMap as $sKey=>$sVal){
			if(!is_array($sVal)){
				$sParameter.=$sKey.'='.urlencode($sVal).'&';
				$arrSortUrl[]='"'.$sKey.'='.urlencode($sVal).'"';
			}
		}

		$sSortBy=strtoupper(G::getGpc('sort_'))=='ASC'?'ASC':'DESC' ;
		$sOrder=G::getGpc('order_')?G::getGpc('order_'):$sName.'_id';
		$this->assign('sSortByUrl',strtolower($sSortBy)=='desc'? 'asc':'desc');
		$this->assign('sSortByDescription',strtolower($sSortBy)=='desc'?Dyhb::L('倒序','Controller/Common'):Dyhb::L('升序','Controller/Common'));
		$this->assign('sOrder',$sOrder);
		$this->assign('sSortUrl','new Array('.implode(',',$arrSortUrl).')');

		$nEverynum=$GLOBALS['_option_']['admin_list_num'];
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		$oPage->setParameter($sParameter);

		$arrLists=array();
		eval('$arrLists='.ucfirst($sName).'Model::F()->where($arrMap)->all()->order($sOrder.\' \'.$sSortBy)->limit($oPage->returnPageStart(),$nEverynum)->query();');
		$this->assign('sPageNavbar',$oPage->P());
		$this->assign('arrLists',$arrLists);
	}

	public function input_change_ajax($sName=null){
		if(empty($sName)){
			$sName=MODULE_NAME;
		}

		$oModelMeta=null;

		eval('$oModelMeta='.ucwords($sName).'Model::M();');
		$sPk=reset($oModelMeta->_arrIdName);
		$nInputAjaxId=G::getGpc('input_ajax_id');
		$sInputAjaxField=G::getGpc('input_ajax_field');
		$sInputAjaxVal=G::getGpc('input_ajax_val');
		$arrData=array(
			$sPk=>$nInputAjaxId,
			$sInputAjaxField=>$sInputAjaxVal,
		);

		if($sName=='badword' && $sInputAjaxField=='badword_find'){
			$arrData['badword_findpattern']='/'.$sInputAjaxVal.'/is';
		}

		if($sInputAjaxField=='badword_find'){
			$this->input_change_unique($sName);
		}

		if(method_exists($this,'BInput_change_ajax_data_')){
			$arrData=call_user_func(array($this,'BInput_change_ajax_data_'),$arrData);
		}

		$oModelMeta->updateDbWhere($arrData);
		if($oModelMeta->isError()){
			$this->E($oModelMeta->getErrorMessage());
		}else{
			$this->afterInputChangeAjax($sName);
			$arrVo=array(
				'id'=>$sInputAjaxField.'_'.$nInputAjaxId,
				'value'=>$sInputAjaxVal,
			);
			$this->A($arrVo,Dyhb::L('数据更新成功','Controller/Common'));
		}
	}

	public function afterInputChangeAjax($sName=null){}

	public function input_change_unique($sModel=null){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}

		$oModelMeta=null;

		eval('$oModelMeta='.ucfirst($sModel).'Model::M();');
		$nId=G::getGpc('input_ajax_id');
		$sField=G::getGpc('input_ajax_field');
		$sName=G::getGpc('input_ajax_val');
		$sInfo='';

		if($nId){
			$oModel=null;
			eval('$oModel='.ucfirst($sModel).'Model::F(\''.$sModel.'_id=?\','.$nId.')->query();');
			$arrInfo=$oModel->toArray();
			$sInfo=$arrInfo[ $sField ];
		}

		if($sName!=$sInfo){
			$oSelect=null;
			eval('$oSelect='.ucfirst($sModel).'Model::F();');
			$sFunc='getBy'.$sField;
			$arrResult=$oSelect->{$sFunc}($sName)->toArray();
			if(!empty($arrResult[$sField])){
				$this->E(Dyhb::L('该项数据已经存在','Controller/Common'));
			}
		}
	}

	public function insert($sModel=null,$nId=null){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}
		
		if(empty($nId)){
			$nId=G::getGpc('id','G');
		}

		$nId=$this->getPostInt($nId);
		
		$oModel=null;

		eval('$oModel=new '.ucfirst($sModel).'Model();');
		if(method_exists($this,'AInsertObject_')){
			call_user_func(array($this,'AInsertObject_'),$oModel);
		}

		$oModel->save(0);
		$sPrimaryKey=$sModel.'_id';
		
		if(!$oModel->isError()){
			$this->aInsert($oModel->{$sPrimaryKey});

			if(!in_array($sModel,array('user')) && !isset($_POST['no_ajax'])){
				$this->A($oModel->toArray(),Dyhb::L('数据保存成功','Controller/Common'),1);
			}else{
				$nId=$oModel->{$sPrimaryKey};

				if(G::getGpc('is_app','P')){
					$sUrl=Dyhb::U('app/config?controller='.trim(G::getGpc('controller','G')).'&action=edit&id='.intval(G::getGpc('id','G')).'&value='.$nId);
				}else{
					$sUrl=Dyhb::U($sModel.'/edit?id='.$nId);
				}

				$this->assign('__JumpUrl__',$sUrl);
				$this->S(Dyhb::L('数据保存成功','Controller/Common'));
			}
		}else{
			$this->E($oModel->getErrorMessage());
		}
	}

	protected function aInsert($nId=null){}

	public function add(){
		$this->display();
	}

	public function edit($sModel=null,$nId=null,$bDidplay=true){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}
		
		if(empty($nId)){
			$nId=G::getGpc('id','G');
		}

		$nId=$this->getPostInt($nId);

		if(!empty($nId)){
			$oModel=null;
			eval('$oModel='.ucfirst($sModel).'Model::F(\''.$sModel.'_id=?\','.$nId.')->query();');
			if(method_exists($this,'AEditObject_')){
				call_user_func(array($this,'AEditObject_'),$oModel);
			}

			if(!empty($oModel->{$sModel.'_id'})){
				$this->assign('oValue',$oModel);
				$this->assign('nId',$nId);
				
				if($bDidplay===true){
					$this->display($sModel.'+add');
				}
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function update($sModel=null,$nId=null){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}
		
		if(empty($nId)){
			$nId=G::getGpc('id');
		}

		$nId=$this->getPostInt($nId);
		
		$oModel=null;
		eval('$oModel='.ucfirst($sModel).'Model::F(\''.$sModel.'_id=?\','.$nId.')->query();');
		
		if(method_exists($this,'AUpdateObject_')){
			call_user_func(array($this,'AUpdateObject_'),$oModel);
		}

		$oModel->save(0,'update');
		$sPrimaryKey=$sModel.'_id';
		if(!$oModel->isError()){
			$this->aUpdate($oModel->{$sPrimaryKey});
			$this->S(Dyhb::L('数据更新成功','Controller/Common'));
		}else{
			$this->E($oModel->getErrorMessage());
		}
	}

	protected function aUpdate($nId=null){}

	public function foreverdelete($sModel=null,$sId=null){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}
		
		if(empty($sId)){
			$sId=G::getGpc('id');
		}

		if(!empty($sId)){
			$oModelMeta=null;
			eval('$oModelMeta='.ucfirst($sModel).'Model::M();');
			$sPk=reset($oModelMeta->_arrIdName);
			$oModelMeta->deleteWhere(array($sPk=>array('in',$sId)));
			
			if($oModelMeta->isError()){
				$this->E($oModelMeta->getErrorMessage());
			}else{
				$this->aForeverdelete($sId);
				if(G::isAjax()){
					$this->A('',Dyhb::L('删除记录成功','Controller/Common'),1);
				}else{
					$this->S(Dyhb::L('删除记录成功','Controller/Common'));
				}
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	protected function aForeverdelete($sId){}
	
	public function forbid($sModel=null,$sId=null,$bApp=false){
		$this->change_status_('status',0,$sModel,$sId,$bApp);
	}

	protected function aForbid(){}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$this->change_status_('status',1,$sModel,$sId,$bApp);
	}

	public function change_status_($sField='status',$nStatus=1,$sModel=null,$sId=null,$bApp=false){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}
		
		if(empty($sId)){
			$sId=G::getGpc('id');
		}

		$nPage=$this->get_referer_page();

		if(!empty($sId)){
			$oModelMeta=null;
			eval('$oModelMeta='.ucfirst($sModel).'Model::M();');
			$sPk=reset($oModelMeta->_arrIdName);

			$oModelMeta->updateDbWhere(array($sModel.'_'.$sField=>$nStatus),array($sPk=>$sId));
			if($oModelMeta->isError()){
				$this->E($oModelMeta->getErrorMessage());
			}else{
				if($bApp===false){
					$this->assign('__JumpUrl__',Dyhb::U($sModel.'/index'.($nPage?'?page='.$nPage:'')));
				}else{
					$this->assign('__JumpUrl__',$nPage?Admin_Extend::base(array('controller'=>$sModel,'page'=>$nPage)):Admin_Extend::base(array('controller'=>$sModel)));
				}
				
				if($nStatus){
					$this->aResume();
					$this->S(Dyhb::L('恢复成功','Controller/Common'));
				}else{
					$this->aForbid();
					$this->S(Dyhb::L('禁用成功','Controller/Common'));
				}
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}

	}

	protected function aResume(){}

	public function save_sort($sModel=null){
		if(empty($sModel)){
			$sModel=MODULE_NAME;
		}

		$sMoveResult=G::getGpc('moveResult','P');

		if(!empty($sMoveResult)){
			$oModel=null;
			eval('$oModel=new '.ucfirst($sModel).'Model();');
			$oDb=$oModel->getDb();
			$arrCol=explode(',',$sMoveResult);
			$oDb->getConnect()->startTransaction();
			$bResult=true;
			foreach($arrCol as $val){
				$val=explode(':',$val);
				$oModel=null;
				eval('$oModel='.ucfirst($sModel).'Model::F(\''.$sModel.'_id=?\','.$val[0].')->query();');
				$oModel->{$sModel.'_sort'}=$val[1];

				try{
					$oModel->save(0,'update');
				}catch(Exception $e){
					$bResult=false;
					$sErrorMessage=$e->getMessage();
					break;
				}

				if($oModel->isError()){
					$bResult=false;
					$sErrorMessage=$oModel->getErrorMessage();
					break;
				}
			}

			$oDb->getConnect()->commit();

			if($bResult!==false){
				$this->S(Dyhb::L('更新成功','Controller/Common'));
			}else{
				$oDb->getConnect()->rollback();
				$this->E($sErrorMessage);
			}
		}else{
			$this->E(Dyhb::L('没有可以排序的数据','Controller/Common'));
		}
	}

	public static function getPostInt($sValue){
		if(!empty($sValue) && !Core_Extend::isPostInt($sValue)){
			$sValue="'{$sValue}'";
		}

		return $sValue;
	}

	public function seccode(){
		Core_Extend::seccode();
	}

	public function check_seccode($bSubmit=false){
		$sSeccode=G::getGpc('seccode');

		if(empty($sSeccode)){
			$this->E(Dyhb::L('你验证码不能为空','__COMMON_LANG__@Common'));
		}

		$bResult=Core_Extend::checkSeccode($sSeccode);

		if(!$bResult){
			$this->E(Dyhb::L('你输入的验证码错误','__COMMON_LANG__@Common'));
		}

		if($bSubmit===false){
			$this->S(Dyhb::L('验证码正确','__COMMON_LANG__@Common'));
		}
	}

	public function get_referer_page(){
		$nPage=0;

		if(!empty($_SERVER['HTTP_REFERER'])){
			parse_str(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY),$arrUrldata);
			if(isset($arrUrldata['page']) && !empty($arrUrldata['page'])){
				$nPage=$arrUrldata['page'];
			}
		}

		return $nPage;
	}

}
