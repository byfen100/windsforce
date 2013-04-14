<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   系统服务端升级提醒($WYM)*/

/** 防止乱码 */
header("Content-type:text/html;charset=utf-8");

/**
 * 去掉字符串的换行符
 */
function trimNl($sContent){
	$sContent=trim($sContent);

	if(empty($sContent)){
		return '';
	}

	if($sContent!=''){
		$arrLine=explode("\n",$sContent);
		$sContent='';
		foreach($arrLine as $sLine){
			if(substr($sLine,strlen($sLine)-1,strlen($sLine))=="\n"){
				$sLine=substr($sLine,0,strlen($sLine)-1);
			}
			$sContent.=addslashes(trim($sLine));
		}

		return $sContent;
	}
}

/**
 * 输出Javascript错误消息
 */
function errorMessage($sContent){
	echo "throw new Error('{$sContent}');";
	exit;
}

/** 服务端版本 */
$sServerVersion='1.0';
$nServerRelease='20120104';
	
/** 获取客户端信息 & 验证 */
$sVersion=isset($_GET['version'])?trim($_GET['version']):'';
$nRelease=isset($_GET['release'])?intval($_GET['release']):'';
$sHostname=isset($_GET['hostname'])?trim($_GET['hostname']):'';
$sUrl=isset($_GET['url'])?trim($_GET['url']):'';
$nInfolist=isset($_GET['infolist']) && $_GET['infolist']==1?intval($_GET['infolist']):'';

if(empty($sVersion)){
	errorMessage('无法获取版本信息');
}

if(empty($nRelease)){
	errorMessage('无法获取版本发布日期');
}

if(empty($sHostname)){
	errorMessage('无法获取域名信息');
}

if(empty($sUrl)){
	errorMessage('无法获取程序安装地址');
}

echo<<<INFO
function \$WF(id){
	return document.getElementById(id);
}
INFO;

/** 比较版本取得更新信息 */
if($nServerRelease>$nRelease){
	echo<<<INFO
		parent.menu.\$WF("update_num").innerHTML="<span class=\"update_num\">3</span>";
INFO;

	if(empty($nInfolist)){
		echo<<<INFO
		\$WF("welcome_info").style.display="none";
		\$WF("newest_version").innerHTML="{$sServerVersion} Build {$nServerRelease}";
		\$WF("news_box").style.display="";
		\$WF("news_title").innerHTML="更新提示";
		\$WF("news_content").innerHTML="<span>{$sServerVersion} Build {$nServerRelease}已经发布。下载地址: <a href=\"http://www.windsforce.net/\" target=\"_blank\">http://www.windsforce.net/</a></span>";
INFO;
	}else{
		$arrUpdateContent=array(
			'title1'=>'content1',
			'title2'=>'content2',
		);

		$sContent='';
		foreach($arrUpdateContent as $sKey=>$sValue){
			$sContent.="
				<tr>
					<td>{$sKey}</td>
					<td>{$sValue}</td>
				</tr>";
		}
		$sContent=trimNl($sContent);

		echo "\$WF(\"update_num\").innerHTML=\"<span class=\\\"update_num\\\">3</span>\";\n";
		echo "\$WF(\"update_list\").innerHTML=\"{$sContent}\";";
	}
}else{
	if($nInfolist==1){
		$sContent="
			<tr>
				<td colspan=\"2\">没有任何更新信息</td>
			</tr>";
		$sContent=trimNl($sContent);

		echo "\$WF(\"update_list\").innerHTML=\"{$sContent}\";";
	}else{
		echo<<<INFO
		\$WF("newest_version").innerHTML="{$sServerVersion} Build {$nServerRelease}";
INFO;
	}
}

?>