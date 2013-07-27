<?php
/**

 //  [Websetup!] 图像界面工具
 //  +---------------------------------------------------------------------
 //
 //  “Copyright”
 //  +---------------------------------------------------------------------
 //  | (C) 2010 - 2099 http://doyouhaobaby.net All rights reserved.
 //  | This is not a free software, use is subject to license terms
 //  +---------------------------------------------------------------------
 //
 //  “About This File”
 //  +---------------------------------------------------------------------
 //  | websetup Create控制器 ( 代码创建工具 )
 //  +---------------------------------------------------------------------

*/

!defined('DYHB_PATH') && exit;

class CreateController extends InitController{

	private $_sClassName='';

	public function controllers(){
		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');

		if(!is_file($sDyhbWebsetupApp."/App/Config/Config.php")){
			$this->E(Dyhb::L("项目%s的配置文件%s不存在！",'app',null,$sDyhbWebsetupApp,$sDyhbWebsetupApp."/App/Config/Config.php"));
		}

		$arrControllers=array();
		$arrControllerFiles=includeDirPhpfile($sDyhbWebsetupApp.'/App/Class/Controller');
		if(is_array($arrControllerFiles)){
			foreach($arrControllerFiles as $sKey=>$sVal){
				$sPath=$sVal;
				$sVal=basename($sVal);
				$sVal=str_replace('Controller','',$sVal);
				$sVal=str_replace('.class.php','',$sVal);
				$sClass=ucfirst($sVal).'Controller';
				$arrControllers[$sKey]['class']=$sClass;
				$arrControllers[$sKey]['module']=$sVal;
				$arrControllers[$sKey]['control']=$sVal;
				$arrControllers[$sKey]['path']=$sPath;
			}
		}
		$this->assign('arrControllers',$arrControllers);
		$this->assign('sHelp',Dyhb::L('查看已有的控制器，并能够创建新控制器。'));

		$this->display();
	}

	public function newcontroller($bModel=false){
		$sPostName=G::getGpc('name');
		$sPostAttribute=G::getGpc('attribute');
		$sPostAction=G::getGpc('action');
		$sTableName=G::getGpc('table_name');
		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');

		if(!is_file($sDyhbWebsetupApp."/App/Config/Config.php")){
			$this->E(Dyhb::L("项目%s的配置文件%s不存在！",'app',null,$sDyhbWebsetupApp,$sDyhbWebsetupApp."/App/Config/Config.php"));
		}

		$sTypeName=$bModel ? Dyhb::L("模型"):Dyhb::L("控制器");
		if($sPostName==''){
			$this->E(Dyhb::L('%s不能为空！','app',null,$sTypeName));
		}

		if($sPostAction==''){
			$sPostAction='public|index|*|我是一个测试方法';
		}

		$sGroup='';
		$sName=$sPostName;
		$arrAttribute=array_filter(explode("\n", $sPostAttribute));
		$arrAction=array_filter(explode("\n", $sPostAction));

		Check::RUN();
		if(!Check::C($sName,'number_underline_english')){
			$this->E(Dyhb::L('%s名字格式不正确！','app',null,$sTypeName));
		}

		$this->_sClassName=$sName;
		$sPath=$sDyhbWebsetupApp.'/App/Class/'.($bModel ?'Model' : 'Controller').'/'.ucfirst($sName).($bModel ?'Model' : 'Controller').'.class.php';
		$sCode=$this->makeCode_(
			array(
				'name'=>strtolower(trim($sName)),
				'attribute'=>$arrAttribute,
				'action'=>$arrAction,
			),!$bModel
		);

		if(is_file($sPath)){
			$this->E(Dyhb::L('%s:%s已经存在！','app',null,$sTypeName,$sPath));
		}

		if(file_put_contents($sPath,$sCode)){
			$this->S(Dyhb::L('创建%s，路径：%s成功！','app',null,$sTypeName.$sName,$sPath));
		}else{
			$this->E(Dyhb::L('创建%s，路径：%s失败！','app',null,$sTypeName.$sName,$sPath));
		}
	}

	public function getcolumns(){
		$sTableName=G::getGpc('table','P');
		$oDb=Db::RUN();
		$this->assign('arrColumns',$oDb->getConnect()->getColumnNameList($sTableName));
		$this->assign('sTableName',$sTableName);

		$this->display();
	}

	public function models(){
		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');

		if(!is_file($sDyhbWebsetupApp."/App/Config/Config.php")){
			$this->E(Dyhb::L("项目%s的配置文件%s不存在！",'app',null,$sDyhbWebsetupApp,$sDyhbWebsetupApp."/App/Config/Config.php"));
		}

		$arrModels=array();
		$arrModelFiles=includeDirPhpfile($sDyhbWebsetupApp.'/App/Class/Model');
		if(is_array($arrModelFiles)){
			foreach($arrModelFiles as $sKey=>$sVal){
				$sPath=$sVal;
				$sVal=basename($sVal);
				$sVal=str_replace('Model','',$sVal);
				$sVal=str_replace('.class.php','',$sVal);
				$arrModels[$sKey]['class']=$sVal;
				$arrModels[$sKey]['module']=$sVal;
				$arrModels[$sKey]['tablename']=$sVal;
				$arrModels[$sKey]['path']=$sPath;
			}
		}

		$oDb=Db::RUN();
		$arrTables=$oDb->getConnect()->getTableNameList();
		$this->assign('arrTables',$arrTables);
		$this->assign('arrModels',$arrModels);
		$this->assign('sHelp',Dyhb::L('查看已有的模型，并能够创建新模型。'));

		$this->display();
	}

	public function newmodel(){
		$this->newcontroller(true);
	}

	private function makeCode_(array $arrayData,$isController=true){
		$sControllerName=ucwords($arrayData['name']);
		$arrAttribute=$arrayData['attribute'];

		$arrAction=array();
		if($isController===TRUE){
			$arrAction[]='public|__contruct|*|构造函数|parent++__construct();';
		}

		$arrAction=array_merge($arrAction,$arrayData['action']);
		$sTypeValue=array();
		if($isController===true){
			$sTypeValue=array(
				'Controller',
				Dyhb::L('控制器')
			);
		}else{
			$sTypeValue=array(
				'Model',
				Dyhb::L('模型')
			);
		}

		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');
		$arrApps=Dyhb::normalize($sDyhbWebsetupApp,'/');
		$sAppName=array_pop($arrApps);
		$sStart="<?php
/**

 //  [DoYouHaoBaby!] Init APP - {$sAppName}
 //  +---------------------------------------------------------------------
 //
 //  “Copyright”
 //  +---------------------------------------------------------------------
 //  |(C)2010 - 2099 http://doyouhaobaby.net All rights reserved.
 //  | This is not a free software, use is subject to license terms
 //  +---------------------------------------------------------------------
 //
 //  “About This File”
 //  +---------------------------------------------------------------------
 //  | {$sControllerName} {$sTypeValue[1]}
 //  +---------------------------------------------------------------------

*/

!defined('DYHB_PATH') && exit;

class {$sControllerName}{$sTypeValue[0]} extends {$sTypeValue[0]}{
";
		if(!$isController){
			$sPostThin=G::getGpc('thin','P');
			if($sPostThin==0){
				$sStart.=(string)(include APP_PATH.'/Static/Template/Full.template.php');
			}else{
				$sStart.=(string)(include APP_PATH.'/Static/Template/Thin.template.php');
			}
		}
		$sContent='';
		if($arrAttribute){
			foreach($arrAttribute as $value){
				$sContent.=$this->makeAttribute_($value);
			}
		}

		if($arrAction){
			foreach($arrAction as $value){
				$sContent.=$this->makeAction_($value);
			}
		}

		$sEnd="
}

";
		return $sStart.$sContent.$sEnd;
	}

	private function makeAttribute_($value){
		$arrAttribute=explode('|',trim($value));
		return "
	/**
	 * {$arrAttribute[3]}
	 *
	 * @var string
	 */
	{$arrAttribute[0]} {$arrAttribute[1]}={$arrAttribute[2]};
";
	}

	private function makeAction_($value){
		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');

		if(!is_file($sDyhbWebsetupApp."/App/Config/Config.php")){
			$this->E(Dyhb::L("项目%s的配置文件%s不存在！",'app',null,$sDyhbWebsetupApp,$sDyhbWebsetupApp."/App/Config/Config.php"));
		}

		$arrAction=explode('|',trim($value));
		$sArgs=$arrAction[2]=='*'?'':$arrAction[2];
		$sContent='';
		$sActionBodyValue='';
		$sActionBody=isset($arrAction[4])?$arrAction[4]:'';
		if($sActionBody){
			$sBlock='';
			$sBlockItem='';
			if(strpos($sActionBody,':')){
				$arrayValue =explode(':',$sActionBody);
				$sBlockItem=trim(array_pop($arrayValue));
				$sBlock=implode('.',$arrayValue);
			}else{
				$sBlock=$sActionBody;
			}

			$sBlockPath=APP_PATH.'/Static/Blocks/block_'.$sBlock.'.php';
			if(is_file($sBlockPath)){
				G::includeFile($sBlockPath);
				$sFunction='block_'.$sBlock;
				if(function_exists($sFunction)){
					$sActionBodyValue=$sFunction($this->_sClassName);
					if($sBlockItem){
						$sActionBodyValue=$sActionBodyValue[$sBlockItem];
					}
					else{
						$sActionBodyValue=$sFunction($this->_sClassName);
					}
				}
			}else{
				$sActionBodyValue=str_replace('+',':',$sActionBody);
			}
		}

		$arrArgs=array();
		if($sArgs){
			$arrArgs=explode(',',$sArgs);
		}

		if($arrArgs){
			foreach($arrArgs as $value){
				$sContent.='
	* @param string '.$value;
			}
		}
		return "
	/**
	 * {$arrAction[3]}{$sContent}
	 */
	{$arrAction[0]} function {$arrAction[1]}({$sArgs}){
		{$sActionBodyValue}
	}
";
	}

}
