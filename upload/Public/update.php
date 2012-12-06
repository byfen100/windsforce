<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   系统服务端升级提醒(wym)*/

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
$sServerVersion='1.1';
$nServerRelease=20140821;
	
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

/** 比较版本取得更新信息 */
if($nServerRelease>$nRelease){
	echo<<<INFO
		parent.menu.document.getElementById("update_num").innerHTML="<span class=\"update_num\">3</span>";
INFO;

	if(empty($nInfolist)){
		echo<<<INFO
		document.getElementById("welcome_info").style.display="none";
		document.getElementById("newest_version").innerHTML="{$sServerVersion} Build {$nServerRelease}";
		document.getElementById("news_box").style.display="";
		document.getElementById("news_title").innerHTML="更新提示";
		document.getElementById("news_content").innerHTML="<span>{$sServerVersion} Build {$nServerRelease}已经发布。下载地址: <a href=\"http://www.baidu.com/\" target=\"_blank\">http://www.baidu.com/</a></span>";
INFO;
	}else{
		$arrUpdateContent=array(
			'新应用'=>'群组',
			'工具'=>'对于Web Developer来说，不可能不认识大名鼎鼎的FireBug。就连我这样的小菜，都天天要用到FireBug。 Firebug是Firefox下的一款浏览器调试开发类扩展，它集成HTML、CSS查看和编辑、Javascript控制台、网络状况监视器等功能，还可以加载评测网页效率的工具Yslow（这其中的关系是FireBug是Firefox的插件而Yslow是FireBug的扩展）。目前Firebug已经有了Chrome下的扩展，那IE呢？鉴于万恶的IE，我们有时候不得不做IE Hack，但是我们缺少一款强大的网页调试工具。'
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

		echo "document.getElementById(\"update_num\").innerHTML=\"<span class=\\\"update_num\\\">3</span>\";\n";
		echo "document.getElementById(\"update_list\").innerHTML=\"{$sContent}\";";
	}
}else{
	if($nInfolist==1){
		$sContent="
			<tr>
				<td colspan=\"2\">没有任何更新信息</td>
			</tr>";
		$sContent=trimNl($sContent);

		echo "document.getElementById(\"update_list\").innerHTML=\"{$sContent}\";";
	}
}

?>