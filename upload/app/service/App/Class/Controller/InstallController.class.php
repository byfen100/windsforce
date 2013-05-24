<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Service统计用户安装($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class InstallController extends InitController{

	public function index(){
		// 传递参数
		$sIp=htmlspecialchars(trim(G::getGpc('ip')));
		$sDomain=htmlspecialchars(trim(G::getGpc('domain')));
		$sVersion=htmlspecialchars(trim(G::getGpc('version')));
		$sRelease=htmlspecialchars(trim(G::getGpc('release')));
		$sBug=htmlspecialchars(trim(G::getGpc('bug')));
		$nUpdate=intval(G::getGpc('update'));
		
		// 将数据保存到数据库
		$oServiceinstall=new ServiceinstallModel();
		$oServiceinstall->serviceinstall_ip=$sIp;
		$oServiceinstall->serviceinstall_domain=$sDomain;
		$oServiceinstall->serviceinstall_version=$sVersion;
		$oServiceinstall->serviceinstall_release=$sRelease;
		$oServiceinstall->serviceinstall_bug=$sBug;
		$oServiceinstall->serviceinstall_update=$nUpdate;
		$oServiceinstall->save(0);

		if($oServiceinstall->isError()){
			exit($oServiceinstall->getErrorMessage());
		}
	}

}
