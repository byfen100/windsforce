<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   系统安装程序初始化控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class InitsystemController extends Controller{

	public function index(){
		$nUpdate=intval(G::getGpc('update'));
		
		// 更新系统缓存
		require_once(Core_Extend::includeFile('function/Cache_Extend'));
		Cache_Extend::updateCache();

		// 将安装数据传回官方服务器以便于统计用户
		$sIp=G::getIp();
		$sDomain=$_SERVER['HTTP_HOST'];

		$sServUrl='http://doyouhaobaby.net/index.php?app=service&c=install&a=index&ip='.urlencode($sIp).'&domain='.urlencode($sDomain).'&version='.urlencode(WINDSFORCE_SERVER_VERSION).'&release='.urlencode(WINDSFORCE_SERVER_RELEASE).'&bug='.urlencode(WINDSFORCE_SERVER_BUG).'&update='.$nUpdate;

		echo<<<INFO
		<script type="text/javascript" src="{$sServUrl}"></script>
INFO;
	}

}
