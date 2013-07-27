<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Service系统升级提醒($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateController extends InitController{

	public function index(){
		// 服务端版本
		$sServerVersion='1.1';
		$nServerRelease='20130604';
		$nServerBug='1.0';
		$sFrameworkServerVersion='2.5.1';
		$nFrameworkServerRelease='20130604';
		
		// 获取客户端信息 & 验证
		$sVersion=isset($_GET['version'])?htmlspecialchars(trim($_GET['version'])):'';
		$nRelease=isset($_GET['release'])?intval($_GET['release']):'';
		$sBug=isset($_GET['bug'])?htmlspecialchars(trim($_GET['bug'])):'';
		$sHostname=isset($_GET['hostname'])?htmlspecialchars(trim($_GET['hostname'])):'';
		$sUrl=isset($_GET['url'])?htmlspecialchars(trim($_GET['url'])):'';
		$nInfolist=isset($_GET['infolist']) && $_GET['infolist']==1?intval($_GET['infolist']):'';

		if(empty($sVersion)){
			$this->errorMessage('无法获取版本信息');
		}

		if(empty($nRelease)){
			$this->errorMessage('无法获取版本发布日期');
		}

		if(empty($sHostname)){
			$this->errorMessage('无法获取域名信息');
		}

		if(empty($sUrl)){
			$this->errorMessage('无法获取程序安装地址');
		}

		$sBuginfo=$nServerBug?' Bug-'.$nServerBug:'';

		// 比较版本取得更新信息
		if($nServerRelease>$nRelease || $nServerBug>$sBug){
			echo<<<INFO
				parent.menu.document.getElementById("update_num").innerHTML="<span class=\"update_num\">3</span>";
INFO;

			$sNewcontent='';
			if($nServerRelease>$nRelease){
				$sNewcontent.="<span>{$sServerVersion} Build {$nServerRelease}已经发布。下载地址: <a href=\"https://needforbug.googlecode.com/files/WindsForce-1.1_release20130604.tar.gz\" target=\"_blank\">https://needforbug.googlecode.com/files/WindsForce-1.1_release20130604.tar.gz</a></span>";
			}

			if($nServerBug>$sBug){
				$sNewcontent.=($nServerRelease>$nRelease?'<br/>':'')."<span>注意，本次还包含一个补丁文件{$sServerVersion} Build {$nServerRelease} Bug {$nServerBug} 已经发布。下载地址: <a href=\"http://www.windsforce.net/\" target=\"_blank\">http://www.windsforce.net/</a></span>";
			}

			$sNewcontent=$this->trimNl($sNewcontent);

			if(empty($nInfolist)){
				echo<<<INFO
					\$WF("welcome_info").style.display="none";
					\$WF("newest_version").innerHTML="{$sServerVersion} Build {$nServerRelease}{$sBuginfo}";
					\$WF("news_box").style.display="";
					\$WF("news_title").innerHTML="更新提示";
					\$WF("news_content").innerHTML="{$sNewcontent}";
INFO;

				if($sVersion>'1.0'){
					echo<<<INFO
						\$WF("newest_frameworkversion").innerHTML="{$sFrameworkServerVersion} Build {$nFrameworkServerRelease}";
INFO;
				}
			}else{
				$arrUpdateContent=array(
					'BUG修正'=>'修复了1.0.1的BUG',
					'新功能'=>'本次增加了搜索，WAP，小组个人中心以及新模板等等。',
				);

				$sContent='';
				foreach($arrUpdateContent as $sKey=>$sValue){
					$sContent.="
						<tr>
							<td>{$sKey}</td>
							<td>{$sValue}</td>
						</tr>";
				}
				$sContent=$this->trimNl($sContent);

				echo "\$WF(\"update_num\").innerHTML=\"<span class=\\\"update_num\\\">3</span>\";\n";
				echo "\$WF(\"update_list\").innerHTML=\"{$sContent}\";";
			}
		}else{
			if($nInfolist==1){
				$sContent="
					<tr>
						<td colspan=\"2\">没有任何更新信息</td>
					</tr>";
				$sContent=$this->trimNl($sContent);

				echo "\$WF(\"update_list\").innerHTML=\"{$sContent}\";";
			}else{
				echo<<<INFO
					\$WF("newest_version").innerHTML="{$sServerVersion} Build {$nServerRelease}{$sBuginfo}";
					\$WF("newest_frameworkversion").innerHTML="{$sFrameworkServerVersion} Build {$nFrameworkServerRelease}";
INFO;
			}
		}
	}

	/**
	 * 去掉字符串的换行符
	 */
	protected function trimNl($sContent){
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
	protected function errorMessage($sContent){
		echo "throw new Error('{$sContent}');";
		exit;
	}

}
