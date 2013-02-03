<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   数据库备份处理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入文件相关函数 */
require_once(Core_Extend::includeFile('function/File_Extend'));

class DatabaseController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}

	public function index($sModel=null,$bDisplay=true){
		$oDb=Db::RUN();

		$arrTables=$oDb->getConnect()->getCol("SHOW TABLES LIKE '".$GLOBALS['_commonConfig_']['DB_PREFIX']."%'");
		$nAllowMaxSize=G::returnBytes(ini_get('upload_max_filesize'));// 单位为字节
		$nAllowMaxSize=$nAllowMaxSize/1024;// 转换单位为 KB

		$nMask=File_Extend::fileModeInfo(WINDSFORCE_PATH.'/data/backup');
		if($nMask===false){
			$this->assign('sWarning',Dyhb::L('备份目录不存在%s','Controller/Database',null,WINDSFORCE_PATH.'/data/backup'));
		}elseif($nMask!=15){
			$sWarning=Dyhb::L('文件夹 %s 权限警告：','Controller/Database',null,WINDSFORCE_PATH.'/data/backup');
			if(($nMask&1)<1){
				$sWarning.=Dyhb::L('不可读','Controller/Database');
			}

			if(($nMask&2)<1){
				$sWarning.=Dyhb::L('不可写','Controller/Database');
			}

			if(($nMask&4)< 1){
				$sWarning.=Dyhb::L('不可增加','Controller/Database');
			}

			if(($nMask&8)<1){
				$sWarning.=Dyhb::L('不可修改','Controller/Database');
			}
			$this->assign('sWarning',$sWarning);
		}

		$this->assign('arrTables',$arrTables);
		$this->assign('nVolSize',$nAllowMaxSize);
		$this->assign('sSqlName',Backup::getRandomName().'.sql');

		$this->display();
	}

	public function dumpsql(){
		$oDb=Db::RUN();

		$nMask=File_Extend::fileModeInfo(WINDSFORCE_PATH.'/data/backup');
		if($nMask===false){
			$this->assign('sWarning',Dyhb::L('备份目录不存在%s','Controller/Database',null,WINDSFORCE_PATH.'/data/backup'));
		}else if($nMask!=15){
			$sWarning=Dyhb::L('文件夹 %s 权限警告：','Controller/Database',null,WINDSFORCE_PATH.'/data/backup');
			if(($nMask&1)<1){
				$sWarning.=Dyhb::L('不可读','Controller/Database');
			}

			if(($nMask&2)<1){
				$sWarning.=Dyhb::L('不可写','Controller/Database');
			}

			if(($nMask&4)<1){
				$sWarning.=Dyhb::L('不可追加','Controller/Database');
			}

			if(($nMask&8)<1){
				$sWarning.=Dyhb::L('不可修改','Controller/Database');
			}
			$this->assign('sWarning',$sWarning);
		}

		@set_time_limit(300);
		$oConnect=$oDb->getConnect();
		$oBackup=new Backup($oConnect);
		$sRunLog=WINDSFORCE_PATH.'/data/backup/run.log';
		$sSqlFileName=G::getGpc('sql_file_name');
		if(empty($sSqlFileName)){
			$sSqlFileName=BackUp::getRandomName();
		}else{
			$sSqlFileName=str_replace("0xa",'',trim($sSqlFileName));// 过滤 0xa 非法字符
			$nPos=strpos($sSqlFileName,'.sql');
			if($nPos!==false){
				$sSqlFileName=substr($sSqlFileName,0,$nPos);
			}
		}

		$nMaxSize=G::getGpc('vol_size');
		$nMaxSize=empty($nMaxSize)?0:intval($nMaxSize);
		$nVol=G::getGpc('vol');
		$nVol=empty($nVol)?1:intval($nVol);
		$bIsShort=G::getGpc('ext_insert');
		$bIsShort=$bIsShort==0?false:true;
		$oBackup->setIsShort($bIsShort);
		
		$nAllowMaxSize=intval(@ini_get('upload_max_filesize'));//单位M
		if($nAllowMaxSize>0 && $nMaxSize>($nAllowMaxSize*1024)){
			$nMaxSize=$nAllowMaxSize*1024;//单位K
		}

		if($nMaxSize>0){
			$oBackup->setMaxSize($nMaxSize*1024);
		}

		$sType=G::getGpc('type');
		$sType=empty($sType)?'full':trim($sType);
		$arrTables=array();
		switch($sType){
			case 'full':
				$arrTemp=$oDb->getConnect()->getCol("SHOW TABLES LIKE '".$GLOBALS['_commonConfig_']['DB_PREFIX']."%'");
				foreach($arrTemp as $sTable){
					$arrTables[$sTable]=-1;
				}
				$oBackup->putTablesList($sRunLog,$arrTables);
				break;
			case 'custom':
				foreach(G::getGpc('customtables') as $sTable){
					$arrTables[$sTable]=-1;
				}
				$oBackup->putTablesList($sRunLog,$arrTables);
				break;
		}

		$arrTables=$oBackup->dumpTable($sRunLog,$nVol);
		if($arrTables===false){
			$this->E($oBackup->getErrorMessage());
		}

		if(empty($arrTables)){
			if($nVol>1){
				if(!@file_put_contents(WINDSFORCE_PATH.'/data/backup/'.$sSqlFileName.'_'.$nVol.'.sql',$oBackup->getDumpSql())){
					$this->E(Dyhb::L('备份文件写入失败%s','Controller/Database',null,$sSqlFileName.'_'.$nVol.'.sql'));
				}

				$arrList=array();
				for($nI=1;$nI<=$nVol;$nI++){
					$arrList[]=array(
						'name'=>$sSqlFileName.'_'.$nI.'.sql',
						'href'=>__ROOT__.'/data/backup/'.$sSqlFileName.'_'.$nI.'.sql'
					);
				}

				$arrMessage=array(
					'list'=>$arrList
				);

				@unlink($sRunLog);

				$this->sql_dump_message($arrMessage);
			}else{
				if(!@file_put_contents(WINDSFORCE_PATH.'/data/backup/'.$sSqlFileName. '.sql',$oBackup->getDumpSql())){
					$this->E(Dyhb::L('备份文件写入失败%s','Controller/Database',null,$sSqlFileName.'_'.$nVol.'.sql'));
				};

				$arrList=array(
					array('name'=>$sSqlFileName.'.sql',
						'href'=>__ROOT__.'/data/backup/'. $sSqlFileName.'.sql'
					)
				);
				$arrMessage=array(
					'list'=>$arrList
				);

				@unlink($sRunLog);

				$this->sql_dump_message($arrMessage);
			}
		}else{
			if(!@file_put_contents(WINDSFORCE_PATH.'/data/backup/'.$sSqlFileName.'_'.$nVol.'.sql',$oBackup->getDumpSql())){
				$this->E(Dyhb::L('备份文件写入失败%s','Controller/Database',null,$sSqlFileName.'_'.$nVol.'.sql'));
			}

			$arrList=array(
				'sql_file_name'=>$sSqlFileName,
				'vol_size'=>$nMaxSize,
				'vol'=>$nVol+1,
				'type'=>$sType,
			);

			$sLink=Dyhb::U('database/dumpsql',$arrList);

			$arrMessage=array(
				'auto_link'=>$sLink,
				'auto_redirect'=>1,
				'done_file'=>$sSqlFileName.'_'.$nVol.'.sql',
				'list'=>$arrList
			);

			$this->sql_dump_message($arrMessage);
		}
	}

	private function sql_dump_message($arrMessage){
		$sBackMsg="";

		if(isset($arrMessage['auto_redirect'])&& $arrMessage['auto_redirect']){
			$this->assign('__JumpUrl__',$arrMessage['auto_link']);
			$this->assign('__WaitSecond__',3);
		}else{
			if(is_array($arrMessage['list'])){
				foreach($arrMessage['list'] as $arrFile){
					$sBackMsg.="<a href=\"{$arrFile['href']}\">{$arrFile['name']}</a><br/>";
				}
			}
			$this->assign('__JumpUrl__',Dyhb::U('database/restore'));
			$this->assign('__WaitSecond__',5);
		}

		$this->S($sBackMsg);
	}

	public function runsql(){
		$sSql=G::getGpc('sql');

		if(!empty($sSql)){
			$this->assign('sSql',$sSql);
			$this->assign_sql($sSql);
		}

		$this->display();
	}

	private function assign_sql($sSql){
		$oDb=Db::RUN();

		$sSql=stripslashes($sSql);
		$sSql=str_replace("\r",'',$sSql);
		$arrQueryItems=explode(";\n",$sSql);
		$arrQueryItems=array_filter($arrQueryItems,'strlen');
		if(count($arrQueryItems)>1){
			foreach($arrQueryItems as $sKey=>$sValue){
				if($oDb->getConnect()->query($sValue)){
					$this->assign('nType',1);
				}else{
					$this->assign('nType',0);
					return;
				}
			}
			return;
		}

		if(preg_match("/^(?:UPDATE|DELETE|TRUNCATE|ALTER|DROP|FLUSH|INSERT|REPLACE|SET|CREATE)\\s+/i",$sSql)){// 执行，但不返回结果型
			try{
				$oDb->getConnect()->query($sSql);
				$this->assign('nType',1);
			}catch(Exception $e){
				$this->assign('nType',-1);
				$this->assign('sError',$e->getMessage());
			}
		}else{
			try{
				$arrData=$oDb->getConnect()->getAllRows($sSql);

				$sResult='';
				if(is_array($arrData) && isset($arrData[0])){
					$sResult="<table class=\"tablesorter\" id=\"checkList\"> \n<thead> \n<tr>";
					$arrKeys=array_keys($arrData[0]);
					$nNum=count($arrKeys);
					for($nI=0;$nI < $nNum;$nI++){
						$sResult.="<th>".$arrKeys[$nI]."</th>\n";
					}
					$sResult.="</tr> \n</thead>\n<tbody>\n";
					foreach($arrData as $arrData1){
						$sResult.="<tr>\n";
						foreach($arrData1 as $sValue){
							$sResult.="<td>".$sValue."</td>";
						}
						$sResult.="</tr>\n";
					}
					$sResult.="</tbody></table>\n";
				}else{
					$sResult="<center><h3>".Dyhb::L('没有发现任何记录','Controller/Database')."</h3></center>";
				}
				$this->assign('nType',2);
				$this->assign('sResult',$sResult);
			}catch(Exception $e){
				$this->assign('nType',-1);
				$this->assign('sError',$e->getMessage());
			}
		}
	}

	public function optimize(){
		$oDb=Db::RUN();

		$nDbVer=$oDb->getConnect()->getVersion();
		$sSql="SHOW TABLE STATUS LIKE '" .$GLOBALS['_commonConfig_']['DB_PREFIX']. "%'";
		$nNum=0;
		$arrList=array();
		$arrReuslt=$oDb->getConnect()->getAllRows($sSql);
		foreach($arrReuslt as $arrRow){
			if(strpos($arrRow['Name'],'_session')!==false){
				$arrRes['Msg_text']='Ignore';
				$arrRow['Data_free']='Ignore';
			}else{
				$arrRes=$oDb->getConnect()->getRow('CHECK TABLE '.$arrRow['Name'],null,false);
				$nNum+=$arrRow['Data_free'];
			}
			$sType=$nDbVer >='4.1'?$arrRow['Engine']:$arrRow['Type'];
			$sCharset=$nDbVer >='4.1'?$arrRow['Collation']:'N/A';
			$arrList[]=array('table'=>$arrRow['Name'],'type'=>$sType,'rec_num'=>$arrRow['Rows'],'rec_size'=>sprintf(" %.2f KB",$arrRow['Data_length'] / 1024),'rec_index'=>$arrRow['Index_length'], 'rec_chip'=>$arrRow['Data_free'],'status'=>$arrRes['Msg_text'],'charset'=>$sCharset);
		}
		unset($arrReuslt,$sCharset,$sType);

		$this->assign('arrList',$arrList);
		$this->assign('nNum',$nNum);

		$this->display();
	}

	public function run_optimize(){
		$oDb=Db::RUN();

		$arrTables=$oDb->getConnect()->getCol("SHOW TABLES LIKE '".$GLOBALS['_commonConfig_']['DB_PREFIX']."%'");
		$sResult='';
		foreach($arrTables as $sTable){
			if(($arrRow=$oDb->getConnect()->getRow('OPTIMIZE TABLE '.$sTable,null,false))!==false){
				if($arrRow['Msg_type']=='error' && strpos($arrRow['Msg_text'],'repair')!==false){
					$sResult.=Dyhb::L('优化数据库表%s失败','Controller/Database',null,$sTable).'<br/>';
					if($oDb->getConnect()->query('REPAIR TABLE '.$sTable)){
						$sResult.=Dyhb::L('优化失败后，尝试修复数据库%s成功','Controller/Database',null,$sTable).'<br/>';
					}else{
						$sResult.=Dyhb::L('优化失败后，尝试修复数据库%s失败','Controller/Database',null,$sTable).'<br/>';
					}
				}else{
					$sResult.=Dyhb::L('优化数据库表%s成功','Controller/Database',null,$sTable).'<br/>';
				}

				foreach(G::getGpc('do','P') as $sDo){
					if($oDb->query($sDo.' TABLE '.$sTable)){
						$sResult.=Dyhb::L('数据库表%s成功','Controller/Database',null,$sTable).'<br/>';
					}else{
						$sResult.=Dyhb::L('数据库表%s失败','Controller/Database',null,$sTable).'<br/>';
					}
				}
				$sResult.='<br/><br/>';
			}
		}
		$this->assign('__WaitSecond__',10);

		$this->S(Dyhb::L('数据表优化成功，共清理碎片%d','Controller/Database',null,G::getGpc('num','P'))."<br/><br/>".Dyhb::L('附加信息','Controller/Database').": ".$sResult);
	}

	public function restore(){
		$arrList=array();

		$nMask=File_Extend::fileModeInfo(WINDSFORCE_PATH.'/data/backup');
		if($nMask===false){
			$this->assign('sWarning',Dyhb::L('备份目录不存在%s','Controller/Database',null,WINDSFORCE_PATH.'/data/backup'));
		}elseif(($nMask&1)<1){
			$this->assign('sWarning',Dyhb::L('不可读','Controller/Database'));
		}else{
			$arrRealList=array();

			$hFolder=opendir(WINDSFORCE_PATH.'/data/backup');
			while(($sFile=readdir($hFolder))!==false){
				if(strpos($sFile,'.sql')!==false){
					$arrRealList[]=$sFile;
				}
			}

			natsort($arrRealList);
			$arrMatch=array();
			foreach($arrRealList as $sFile){
				if(preg_match('/_([0-9])+\.sql$/',$sFile,$arrMatch)){
					if($arrMatch[1]==1){
						$nMark=1;
					}else{
						$nMark=2;
					}
				}else{
					$nMark=0;
				}

				$nFileSize=filesize(WINDSFORCE_PATH.'/data/backup/'.$sFile);
				$arrInfo=Backup::getHead(WINDSFORCE_PATH.'/data/backup/'.$sFile);

				$arrList[]=array(
					'name'=>$sFile,
					'add_time'=>$arrInfo['date'],
					'vol'=>$arrInfo['vol'],
					'file_size'=>G::changeFileSize($nFileSize),
					'mark'=>$nMark
				);
			}
		}
		$this->assign('arrList',$arrList);

		$this->display();
	}

	public function remove(){
		$arrFile=G::getGpc('file');

		if(!empty($arrFile)){
			$arrMFile=array();//多卷文件
			$arrSFile=array();//单卷文件
			foreach($arrFile as $sFile){
				if(preg_match('/_[0-9]+\.sql$/',$sFile)){
					$arrMFile[]=substr($sFile,0,strrpos($sFile,'_'));
				}else{
					$arrSFile[]=$sFile;
				}
			}

			if($arrMFile){
				$arrMFile=array_unique($arrMFile);
				$arrRealFile=array();
				$hFolder=opendir(WINDSFORCE_PATH.'/data/backup');
				while(($sFile=readdir($hFolder))!==false){
					if(preg_match('/_[0-9]+\.sql$/',$sFile)&& is_file(WINDSFORCE_PATH.'/data/backup/'.$sFile)){
						$arrRealFile[]=$sFile;
					}
				}

				foreach($arrRealFile as $sFile){
					$sShortFile=substr($sFile,0,strrpos($sFile,'_'));
					if(in_array($sShortFile,$arrMFile)){
						@unlink(WINDSFORCE_PATH.'/data/backup/'.$sFile);
					}
				}
			}

			if($arrSFile){
				foreach($arrSFile as $sFile){
					@unlink(WINDSFORCE_PATH.'/data/backup/'. $sFile);
				}
			}

			$this->S(Dyhb::L('删除备份文件成功','Controller/Database'));
		}else{
			$this->E(Dyhb::L('你没有选择任何文件','Controller/Database'));
		}
	}

	public function import(){
		$oDb=Db::RUN();

		$bIsContrim=G::getGpc('confirm');
		$bIsConfirm=empty($bIsContrim)?false:true;
		$sFileName=G::getGpc('file_name');
		$sFileName=empty($sFileName)?'':trim($sFileName);

		@set_time_limit(300);
		if(preg_match('/_[0-9]+\.sql$/',$sFileName)){
			if($bIsConfirm==false){
				$sUrl=Dyhb::U('database/import?confirm=1&file_name='. $sFileName);
				$this->assign("__JumpUrl__",$sUrl);
				$this->assign('__WaitSecond__',60);
				$this->S(Dyhb::L('你确定要导入?','Controller/Database')."&nbsp;<a href='".$sUrl."'>".Dyhb::L('确定','Controller/Database')."</a>");
			}

			$sShortName=substr($sFileName,0,strrpos($sFileName,'_'));

			$arrRealFile=array();
			$hFolder=opendir(WINDSFORCE_PATH.'/data/backup');
			while(($sFile=readdir($hFolder))!==false){
				if(is_file(WINDSFORCE_PATH.'/data/backup/'.$sFile) && preg_match('/_[0-9]+\.sql$/',$sFile)){
					$arrRealFile[]=$sFile;
				}
			}

			$arrPostList=array();
			foreach($arrRealFile as $sFile){
				$sTmpName=substr($sFile,0,strrpos($sFile,'_'));
				if($sTmpName==$sShortName){
					$arrPostList[]=$sFile;
				}
			}

			natsort($arrPostList);
			foreach($arrPostList as $sFile){
				$arrInfo=Backup::getHead(WINDSFORCE_PATH.'/data/backup/'. $sFile);
				if(!$this->sql_import(WINDSFORCE_PATH.'/data/backup/'. $sFile)){
					$this->E(Dyhb::L('导入数据库备份文件失败','Controller/Database'));
				}
			}
			$this->assign("__JumpUrl__",Dyhb::U('database/restore'));
			$this->S(Dyhb::L('数据导入成功','Controller/Database'));
		}else{
			$arrInfo=Backup::getHead(WINDSFORCE_PATH.'/data/backup/'. $sFileName);
			if($this->sql_import(WINDSFORCE_PATH.'/data/backup/'. $sFileName)){
				$this->assign("__JumpUrl__",Dyhb::U('database/restore'));
				$this->S(Dyhb::L('数据导入成功','Controller/Database'));
			}else{
				$this->E(Dyhb::L('导入数据库备份文件失败','Controller/Database'));
			}
		}
	}

	public function upload_sql(){
		$oDb=Db::RUN();
		$sSqlFile=WINDSFORCE_PATH.'/data/backup/upload_database_bak.sql';
		$sSqlVerConfirm=G::getGpc('sql_ver_confirm');
		if(empty($sSqlVerConfirm)){
			$arrSqlfile=G::getGpc('sqlfile','F');
			if(empty($arrSqlfile)){
				$this->E(Dyhb::L('你没有选择任何文件','Controller/Database'));
			}

			if((isset($arrSqlfile['error'])
				&& $arrSqlfile['error'] > 0)
				||(!isset($arrSqlfile['error'])
				&& $arrSqlfile['tmp_name']=='none')){
				$this->E(Dyhb::L('上传文件失败','Controller/Database'));
			}

			if($arrSqlfile['type']=='application/x-zip-compressed'){
				$this->E(Dyhb::L('不能是zip格式','Controller/Database'));
			}

			if(!preg_match("/\.sql$/i",$arrSqlfile['name'])){
				$this->E(Dyhb::L('不是sql格式','Controller/Database'));
			}

			if(is_file($sSqlFile)){
				unlink($sSqlFile);
			}

			if(!move_uploaded_file($arrSqlfile['tmp_name'],$sSqlFile)){
				$this->E(Dyhb::L('文件移动失败','Controller/Database'));
			}
		}

		// 获取sql文件头部信息
		$arrSqlInfo=Backup::getHead($sSqlFile);
		if(empty($sSqlVerConfirm)){// 检查数据库版本是否正确
			if(empty($arrSqlInfo['database_ver'])){
				$this->E(Dyhb::L('没有确定数据库版本','Controller/Database'));
			}else{
				$nSqlVer=$oDb->getConnect()->getVersion();
				if($arrSqlInfo['database_ver'] !=$nSqlVer){
					$sMessage="<a href='".Dyhb::U('database/upload_sql?sql_ver_confrim=1')."'>".Dyhb::L('重试','Controller/Database')."</a></br>< href='".Dyhb::U('database/restore')."'>".Dyhb::L('返回','Controller/Database')."</a>";
					$this->E($sMessage);
				}
			}
		}

		@set_time_limit(300);
		if($this->sql_import($sSqlFile)){
			if(is_file($sSqlFile)){
				unlink($sSqlFile);
			}

			$this->S(Dyhb::L('数据库导入成功','Controller/Database'));
		}else{
			if(is_file($sSqlFile)){
				unlink($sSqlFile);
			}

			$this->E(Dyhb::L('数据库导入失败','Controller/Database'));
		}
	}

	private function sql_import($sSqlFile){
		$oDb=Db::RUN();

		$nDbVer=$oDb->getConnect()->getVersion();
		$sSqlStr=array_filter(file($sSqlFile),'removeComment');
		$sSqlStr=str_replace("\r",'',implode('',$sSqlStr));
		$arrRet=explode(";\n",$sSqlStr);
		$nRetCount=count($arrRet);
		if($nDbVer>'4.1'){
			for($nI=0;$nI<$nRetCount;$nI++){
				$arrRet[$nI]=trim($arrRet[$nI]," \r\n;");//剔除多余信息
				if(!empty($arrRet[$nI])){
					if((strpos($arrRet[$nI],'CREATE TABLE')!==false) && (strpos($arrRet[$nI],'DEFAULT CHARSET='. str_replace('-','',$GLOBALS['_commonConfig_']['DB_CHAR']))===false)){
						// 建表时缺 DEFAULT CHARSET=utf8
						$arrRet[$nI]=$arrRet[$nI].'DEFAULT CHARSET='. str_replace('-','',$GLOBALS['_commonConfig_']['DB_CHAR']);
					}

					$oDb->getConnect()->query($arrRet[$nI]);
				}
			}
		}else{
			for($nI=0;$nI<$nRetCount;$nI++){
				$arrRet[$nI]=trim($arrRet[$nI]," \r\n;");//剔除多余信息
				if((strpos($arrRet[$nI],'CREATE TABLE')!==false)&&(strpos($arrRet[$nI],'DEFAULT CHARSET='. str_replace('-','',$GLOBALS['_commonConfig_']['DB_CHAR']))!==false)){
					$arrRet[$nI]=str_replace('DEFAULT CHARSET='. str_replace('-','',$GLOBALS['_commonConfig_']['DB_CHAR']),'',$arrRet[$nI]);
				}

				if(!empty($arrRet[$nI])){
					$oDb->getConnect()->query($arrRet[$nI]);
				}
			}
		}
		return true;
	}

}

function removeComment($sVar){
	return(substr($sVar,0,2)!='--');
}
