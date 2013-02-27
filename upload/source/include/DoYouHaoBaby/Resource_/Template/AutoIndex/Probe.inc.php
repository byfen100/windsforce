<?php
/* [$DoYouHaoBaby] (C)WindsForce TEAM Since 2010.10.04.
	 DoYouHaoBaby探针文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 是否允许探针执行 */
if($GLOBALS['_commonConfig_']['ALLOWED_PROBE']){
	header("content-Type: text/html; charset=utf-8");
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ob_start();

	$bIoncube=extension_loaded('ionCube Loader');
	$bFfmpeg=extension_loaded("ffmpeg");
	$bImagick=extension_loaded("imagick");

	define("YES","<span class='resYes'>YES</span>");
	define("NO","<span class='resNo'>NO</span>");

	$sPhpSelf=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	define("PHPSELF",preg_replace("/(.{0,}?\/+)/","",$sPhpSelf));

	if(isset($_GET['act']) && $_GET['act'] == "phpinfo"){// php系统消息
		phpinfo();
		exit();
	}

	function isFun($sFunName){
		return(false!==function_exists($sFunName))?YES:NO;
	}

	function getCon($sVarName){
		switch($sRes=get_cfg_var($sVarName)){
			case 0:
				return NO;
				break;
			case 1:
				return YES;
				break;
			default:
				return $sRes;
				break;
		}
	}

	function sysLinux(){
		if(false===($str=@file("/proc/cpuinfo"))){
			return false;
		}

		// cpu
		$str=implode("",$str);
		@preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(.]+)[\r\n]+/",$str,$arrModel);
		@preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/",$str,$arrCache);
		if(false!==is_array($arrModel[1])){
			$arrRes['cpu']['num']=sizeof($arrModel[1]);
			for($nI=0;$nI<$arrRes['cpu']['num'];$nI++){
				$arrRes['cpu']['detail'][]=Dyhb::L('类型：','__DYHB__@ProdeDyhb').$arrModel[1][$nI].Dyhb::L('缓存：','__DYHB__@ProdeDyhb').$arrCache[1][$nI];
			}

			if(false!==is_array($arrRes['cpu']['detail'])){
				$arrRes['cpu']['detail']=implode("<br />",$arrRes['cpu']['detail']);
			}
		}

		// updatetime
		if(false===($str=@file("/proc/uptime"))){
			return false;
		}
		$str=explode(" ",implode("",$str));
		$str=trim($str[0]);
		$nMin=$str/60;
		$nHours=$nMin/60;
		$nDays=floor($nHours/24);
		$nHours=floor($nHours-($nDays*24));
		$nMin=floor($nMin-($nDays*60*24)-($nHours*60));
		if($nDays!==0){
			$arrRes['uptime']=$nDays.Dyhb::L('天','__DYHB__@ProdeDyhb');
		}
		if($nHours!==0){
			$arrRes['uptime'].=$nHours.Dyhb::L('小时','__DYHB__@ProdeDyhb');
		}
		$arrRes['uptime'].=$nMin.Dyhb::L('分钟','__DYHB__@ProdeDyhb');

		// MEMORY
		if(false ===($str=@file("/proc/meminfo"))){
			return false;
		}
		$str=implode("",$str);
		preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s",$str,$arrBuf);
		$arrRes['memTotal']=round($arrBuf[1][0]/1024,2);
		$arrRes['memFree']=round($arrBuf[2][0]/1024,2);
		$arrRes['memUsed']=($arrRes['memTotal']-$arrRes['memFree']);
		$arrRes['memPercent']=(floatval($arrRes['memTotal'])!=0)?round($arrRes['memUsed']/$arrRes['memTotal']*100,2):0;
		$arrRes['swapTotal']=round($arrBuf[3][0]/1024,2);
		$arrRes['swapFree']=round($arrBuf[4][0]/1024,2);
		$arrRes['swapUsed']=($arrRes['swapTotal']-$arrRes['swapFree']);
		$arrRes['swapPercent']=(floatval($arrRes['swapTotal'])!=0)?round($arrRes['swapUsed']/$arrRes['swapTotal']*100,2):0;

		// LOAD AVG
		if(false===($str=@file("/proc/loadavg"))){
			return false;
		}
		$str=explode(" ",implode("",$str));
		$str=array_chunk($str,3);
		$arrRes['loadAvg']=implode(" ",$str[0]);

		return $arrRes;
	}

	function sysFreebsd(){
		// CPU
		if(false ===($arrRes['cpu']['num']=getKey("hw.ncpu"))){
			return false;
		}
		$arrRes['cpu']['detail']=getKey("hw.model");

		// LOAD AVG
		if(false ===($arrRes['loadAvg']=getKey("vm.loadavg"))){
			return false;
		}
		$arrRes['loadAvg']=str_replace("{","",$arrRes['loadAvg']);
		$arrRes['loadAvg']=str_replace("}","",$arrRes['loadAvg']);

		// UPTIME
		if(false ===($buf=getKey("kern.boottime"))){
			return false;
		}
		$buf=explode(' ',$buf);
		$nSysTicks=time()-intval($buf[3]);
		$nMin=$nSysTicks/60;
		$nHours=$nMin/60;
		$nDays=floor($nHours/24);
		$nHours=floor($nHours-($nDays*24));
		$nMin=floor($nMin-($nDays*60*24)-($nHours*60));
		if($nDays!==0){
			$arrRes['uptime']=$nDays.Dyhb::L('天','__DYHB__@ProdeDyhb');
		}
		if($nHours!==0){
			$arrRes['uptime'].=$nHours.Dyhb::L('小时','__DYHB__@ProdeDyhb');
		}
		$arrRes['uptime'].=$nMin.Dyhb::L('分钟','__DYHB__@ProdeDyhb');

		// MEMORY
		if(false===($buf=getKey("hw.physmem"))){
			return false;
		}
		$arrRes['memTotal']=round($buf/1024/1024,2);
		$buf=explode("\n",doCommand("vmstat",""));
		$buf=explode(" ",trim($buf[2]));
		$arrRes['memFree']=round($buf[5]/1024,2);
		$arrRes['memUsed'] =($arrRes['memTotal']-$arrRes['memFree']);
		$arrRes['memPercent'] =(floatval($arrRes['memTotal'])!=0)?round($arrRes['memUsed']/$arrRes['memTotal']*100,2):0;
		$buf=explode("\n",doCommand("swapinfo","-k"));
		$buf=$buf[1];
		preg_match_all("/([0-9]+)\s+([0-9]+)\s+([0-9]+)/",$buf,$arrBuf);
		$arrRes['swapTotal']=round($arrBuf[1][0]/1024,2);
		$arrRes['swapUsed']=round($arrBuf[2][0]/1024,2);
		$arrRes['swapFree']=round($arrBuf[3][0]/1024,2);
		$arrRes['swapPercent'] =(floatval($arrRes['swapTotal'])!=0)?round($arrRes['swapUsed']/$arrRes['swapTotal']*100,2):0;

		return $arrRes;
	}

	function getKey($skeyName){
		return doCommand('sysctl',"-n $sKeyName");
	}

	function findCommand($sCommandName){
		$arrPath=array('/bin','/sbin','/usr/bin','/usr/sbin','/usr/local/bin','/usr/local/sbin');
		foreach($arrPath as $sP){
			if(@is_executable("$sP/$sCommandName"))return "$p/$sCommandName";
		}

		return false;
	}

	function doCommand($sCommandName,$sArgs){
		$sBuffer="";

		if(false===($sCommand=findCommand($sCommandName))){
			return false;
		}

		if($hFp=@popen("$sCommand $sArgs",'r')){
			while(!@feof($hFp)){
				$sBuffer.=@fgets($hFp,4096);
			}
			return trim($sBuffer);
		}

		return false;
	}

	// 系統參數
	switch(PHP_OS){
		 case "Linux":
			$sSysReShow=(false!==($arrSysInfo=syLinux()))?"show":"none";
			 break;
		case "FreeBSD":
			$sSysReShow=(false!==($arrSysInfo=sysFreebsd()))?"show":"none";
			break;
		default:
			$sSysReShow='none';
			break;
	 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DoYouHaoBaby - <?php echo Dyhb::L('PHP探针','__DYHB__@ProdeDyhb');?></title>
<style type="text/css">
<!--
body,div,p,ul,form,h1 { margin:0px; padding:0px; }
body { background:#F7F7F7; }
div,a,input { font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 14px; color:#000000; }
div { margin-left:auto; margin-right:auto; width:720px; }
a,#t3 a.arrow,#f1 a.arrow { text-decoration:none; color:#ccc; }
a:hover { text-decoration:underline; }
a.arrow { font-family:Webdings,sans-serif; color:#fff; font-size:10px; }
a.arrow:hover { color:red; text-decoration:none; }
.resYes { font-size: 12px; font-weight: bold; color: green; }
.resNo { font-size: 12px; font-weight: bold; color: red; }
.bar { border:1px solid #2D2F2C; background:#6C6754; height:8px; font-size:2px; }
.bar li { background:#979179; height:8px; font-size:2px; list-style-type:none; }
table { clear:both; background:#CCC; border:3px solid #E0E0E0; margin-bottom:10px; }
td,th { padding:4px; background:#fff; }
th { background:#00CACA; color:#fff; text-align:left; }
th span { font-family:Webdings,sans-serif; font-weight:normal; padding-right:4px; }
th p { float:right; line-height:10px; text-align:right; }
th a { color:#343525; }
h1 { color:#003D79; font-size:35px; width:600px; float:left;padding:30px 0; }
h1 b { color:#cc3300; font-size:50px; font-family: Webdings,sans-serif; font-weight:normal; }
h1 span { font-size:10px; padding-left:10px; color:#7D795E;  }
#t3 td{ line-height:30px; height:30px; text-align:center; background:#003D79; border:1px solid #000; border-right:none; border-bottom:none; }
#t3 th,#t3 th a { font-weight:normal; }
#m4 td {text-align:center;}
.th2 th,.th3 { background:#232522; text-align:center; color:#7D795E; font-weight:normal;  }
.th3 { font-weight:bold; text-align:left; }
#footer table { clear:none; }
#footer td { text-align:center; padding:1px 3px; font-size:9px; }
#footer a { font-size:9px; }
#f1 { text-align:right; padding:15px; }
#f2 { text-align:center;margin-bottom:20px;font-size:10px;}
-->
</style>
</head>
<body>
	<div>
		 <!-- 头部 -->
		<h1>DoYouHaoBaby <?php echo Dyhb::L('PHP探针','__DYHB__@ProdeDyhb');?></h1>
		<a name="top"></a>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" id="t3">
			<tr>
				<td><a href="#sec1"><?php echo Dyhb::L('服务器特性','__DYHB__@ProdeDyhb');?></a></td>
				<td><a href="#sec2"><?php echo Dyhb::L('PHP基本特性','__DYHB__@ProdeDyhb');?></a></td>
				<td><a href="#sec3"><?php echo Dyhb::L('PHP组件支持狀況','__DYHB__@ProdeDyhb');?></a></td>
				<td><a href="<?php echo PHPSELF; ?>" class="t211"><?php echo Dyhb::L('刷新','__DYHB__@ProdeDyhb');?></a></td>
				<td><a href="<?php echo __APP__; ?>" class="t211"><?php echo Dyhb::L('返回首页','__DYHB__@ProdeDyhb');?></a></td>
				<td><a href="#bottom" class="arrow">66</a></td>
			</tr>
		</table>

		<!-- 服务器特性  -->
		<table width="100%" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<th colspan="2"><p>
						<a href="#top" class="arrow">5</a>
						<br />
						<a href="#bottom" class="arrow">6</a>
					</p>
					<span>8</span><?php echo Dyhb::L('服务器特性','__DYHB__@ProdeDyhb');?>
					<a name="sec1" id="sec1"></a>
				</th>
			</tr>
			<?php if("show"==$sSysReShow): ?>
			<tr>
				<td><?php echo Dyhb::L('服务器处理器 CPU','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo Dyhb::L('CPU个数：','__DYHB__@ProdeDyhb');?>
					<?php echo $arrSysInfo['cpu']['num'];?>
					<br />
					<?php echo $arrSysInfo['cpu']['detail'];?></td>
			</tr>
			<?php endif;?>
			<tr>
				<td><?php echo Dyhb::L('服务器时间','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo date("Y-n-j H:i:s");?>
					&nbsp;<?php echo Dyhb::L('北京时间：','__DYHB__@ProdeDyhb');?>
					<?php echo gmdate("Y-n-j H:i:s",time()+8*3600);?></td>
			</tr>
			<?php if("show"==$sSysReShow): ?>
			<tr>
				<td><?php echo Dyhb::L('服务器运行时间','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $arrSysInfo['uptime'];?></td>
			</tr>
			<?php endif;?>
			<tr>
				<td><?php echo Dyhb::L('服务器域名/IP位址','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $_SERVER['SERVER_NAME'];?>
					(
					<?php echo @gethostbyname($_SERVER['SERVER_NAME']);?>
					)</td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('服务器操作系统','__DYHB__@ProdeDyhb');?>
					<?php $arrOs=explode(" ",php_uname());?></td>
				<td><?php echo $arrOs[0];?>
					&nbsp;<?php echo Dyhb::L('內核版本：','__DYHB__@ProdeDyhb');?>
					<?php echo $arrOs[2]?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('主机名称','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $arrOs[1];?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('服务器解释引擎','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('Web服务端口','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $_SERVER['SERVER_PORT'];?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('服务器管理员','__DYHB__@ProdeDyhb');?></td>
				<td><a href="mailto:<?php echo $_SERVER['SERVER_ADMIN'];?>">
					<?php echo $_SERVER['SERVER_ADMIN'];?>
					</a></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('本地路径','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $_SERVER['PATH_TRANSLATED'];?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('目前还有空余','__DYHB__@ProdeDyhb');?>&nbsp;diskfreespace</td>
				<td><?php echo round((@disk_free_space(".")/(1024*1024)),2);?>
					M</td>
			</tr>
			<?php if("show"==$sSysReShow):?>
			<tr>
				<td><?php echo Dyhb::L('内存使用狀況','__DYHB__@ProdeDyhb');?></td>
				<td> <?php echo Dyhb::L('实际内存：共','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['memTotal'];?>
					M,<?php echo Dyhb::L('已使用','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['memUsed'];?>
					M,<?php echo Dyhb::L('空间','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['memFree'];?>
					M,<?php echo Dyhb::L('使用率','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['memPercent'];?>
					%
					<?php echo bar($arrRysInfo['memPercent']);?>
					<?php echo Dyhb::L('SWAP区：共','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['swapTotal'];?>
					M,<?php echo Dyhb::L('已使用','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['swapUsed'];?>
					M,<?php echo Dyhb::L('空间','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['swapFree'];?>
					M,<?php echo Dyhb::L('使用率','__DYHB__@ProdeDyhb');?>
					<?php echo $arrRysInfo['swapPercent'];?>
					%
					<?php echo bar($arrRysInfo['swapPercent']);?>
				</td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('系統平均负载','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo $arrRysInfo['loadAvg'];?></td>
			</tr>
			<?php  endif;?>
		</table>

		<!-- PHP基本特性 -->
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
			<tr>
				<th colspan="2"><p>
						<a href="#top" class="arrow">5</a>
						<br />
						<a href="#bottom" class="arrow">6</a>
					</p>
					<span>8</span><?php echo Dyhb::L('PHP基本特性','__DYHB__@ProdeDyhb');?>
					<a name="sec2" id="sec2"></a>
				</th>
			</tr>
			<tr>
				<td width="49%"><?php echo Dyhb::L('PHP运行方式','__DYHB__@ProdeDyhb');?></td>
				<td width="51%"><?php echo strtoupper(php_sapi_name());?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('PHP版本','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo PHP_VERSION;?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('运行于安全模式','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo getCon("safe_mode");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('支持ZEND编译运行','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo(get_cfg_var("zend_optimizer.optimization_level")||get_cfg_var("zend_extension_manager.optimizer_ts")||get_cfg_var("zend_extension_ts"))?YES:NO;?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('支持ioncube编译运行','__DYHB__@ProdeDyhb');?></td>
				<td><?php if($bIoncube){ echo("<span class='resYes'>YES</span>");}
						 else { echo("<span class='resNo'>NO</span>"); } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('支持Eaccelerator加速','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo(get_cfg_var("eaccelerator.allowed_admin_path")||get_cfg_var("eaccelerator.enable")||get_cfg_var("eaccelerator.optimizer"))?YES:NO;?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('支持FFmpeg组件','__DYHB__@ProdeDyhb');?></td>
				<td><?php if($bFfmpeg){ echo("<span class='resYes'>YES</span>");}
						 else { echo("<span class='resNo'>NO</span>"); } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('支持Imagick组件','__DYHB__@ProdeDyhb');?></td>
				<td><?php if($bImagick){ echo("<span class='resYes'>YES</span>");}
						 else { echo("<span class='resNo'>NO</span>"); } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('允许使用URL打开','__DYHB__@ProdeDyhb');?>&nbsp;allow_url_fopen</td>
				<td><?php echo getCon("allow_url_fopen");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('允许动态载入程式库','__DYHB__@ProdeDyhb');?>&nbsp;enable_dl</td>
				<td><?php echo getCon("enable_dl");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('输出错误信息','__DYHB__@ProdeDyhb');?>&nbsp;display_errors</td>
				<td><?php echo getCon("display_errors");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('自动定义总体变数','__DYHB__@ProdeDyhb');?>&nbsp;register_globals</td>
				<td><?php echo getCon("register_globals");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('程序最多允许使用的内存','__DYHB__@ProdeDyhb');?>&nbsp;memory_limit</td>
				<td><?php echo getCon("memory_limit");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('POST最大位原组数','__DYHB__@ProdeDyhb');?>&nbsp;post_max_size</td>
				<td><?php echo getCon("post_max_size");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('允许最大上传长度','__DYHB__@ProdeDyhb');?>&nbsp;upload_max_filesize</td>
				<td><?php echo getCon("upload_max_filesize");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('程式最长运行时间','__DYHB__@ProdeDyhb');?>&nbsp;max_execution_time</td>
				<td><?php echo getCon("max_execution_time");?>
					<?php echo Dyhb::L('秒','__DYHB__@ProdeDyhb');?></td>
			</tr>
			<tr>
				<td>magic_quotes_gpc</td>
				<td><?php echo(1===get_magic_quotes_gpc())?YES:NO;?></td>
			</tr>
			<tr>
				<td>magic_quotes_runtime</td>
				<td><?php echo(1===get_magic_quotes_runtime())?YES:NO;?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('被禁用的函数','__DYHB__@ProdeDyhb');?>&nbsp;disable_functions</td>
				<td><?php echo(""==($sDisFuns=get_cfg_var("disable_functions")))?"无":str_replace(",","<br />",$sDisFuns)?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('PHP信息','__DYHB__@ProdeDyhb');?>&nbsp;PHPINFO</td>
				<td><?php echo(false!==preg_match("/phpinfo/i",$sDisFuns))?NO:"<a href='$sPhpSelf?act=phpinfo' target='_blank' class='static'>PHPINFO</a>"?></td>
			</tr>
		</table>

		<!-- PHP组件支持 -->
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
			<tr>
				<th colspan="4"><p>
						<a href="#top" class="arrow">5</a>
						<br />
						<a href="#bottom" class="arrow">6</a>
					</p>
					<span>8</span><?php echo Dyhb::L('PHP组建支持','__DYHB__@ProdeDyhb');?>
					<a name="sec3" id="sec3"></a>
				</th>
			</tr>
			<tr>
				<td width="38%"><?php echo Dyhb::L('拼写检查','__DYHB__@ProdeDyhb');?> ASpell Library</td>
				<td width="12%"><?php echo isFun("aspell_check_raw");?></td>
				<td width="38%"><?php echo Dyhb::L('高精度数学运算','__DYHB__@ProdeDyhb');?> BCMath</td>
				<td width="12%"><?php echo isFun("bcadd");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('历法运算','__DYHB__@ProdeDyhb');?> Calendar</td>
				<td><?php echo isFun("cal_days_in_month");?></td>
				<td><?php echo Dyhb::L('DBA资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("dba_close");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('dBase资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("dbase_close");?></td>
				<td><?php echo Dyhb::L('DBM资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("dbmclose");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('FDF表单资料格式','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("fdf_get_ap");?></td>
				<td><?php echo Dyhb::L('FilePro资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("filepro_fieldcount");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('Hyperwave资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("hw_close");?></td>
				<td><?php echo Dyhb::L('图形处理','__DYHB__@ProdeDyhb');?> GD Library</td>
				<td><?php echo isFun("gd_info");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('IMAP电子邮件系統','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("imap_close");?></td>
				<td><?php echo Dyhb::L('Informix资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("ifx_close");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('LDAP目录协定','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("ldap_close");?></td>
				<td><?php echo Dyhb::L('MCrypt加密处理','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("mcrypt_cbc");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('哈希计算 MHash','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("mhash_count");?></td>
				<td><?php echo Dyhb::L('mSQL资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("msql_close");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('SQL Server资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("mssql_close");?></td>
				<td><?php echo Dyhb::L('MySQL资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("mysql_close");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('SyBase资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("sybase_close");?></td>
				<td><?php echo Dyhb::L('Yellow Page系統','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("yp_match");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('Oracle资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("ora_close");?></td>
				<td><?php echo Dyhb::L('Oracle 8 资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("OCILogOff");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('PREL相容语法 PCRE','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("preg_match");?></td>
				<td><?php echo Dyhb::L('PDF文档支持','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("pdf_close");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('Postgre SQL资料库','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("pg_close");?></td>
				<td><?php echo Dyhb::L('SNMP网络管理协定','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("snmpget");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('VMailMgr邮件处理','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("vm_adduser");?></td>
				<td><?php echo Dyhb::L('WDDX支持','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("wddx_add_vars");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('压缩支持(Zlib)','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("gzclose");?></td>
				<td><?php echo Dyhb::L('XML解析','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("xml_set_object");?></td>
			</tr>
			<tr>
				<td>FTP</td>
				<td><?php echo isFun("ftp_login");?></td>
				<td><?php echo Dyhb::L('ODBC资料库连接','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("odbc_close");?></td>
			</tr>
			<tr>
				<td><?php echo Dyhb::L('Session支持','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("session_start");?></td>
				<td><?php echo Dyhb::L('Socket支持','__DYHB__@ProdeDyhb');?></td>
				<td><?php echo isFun("socket_accept");?></td>
			</tr>
		</table>

		<!-- 底部 -->
		<div id="footer">
			<p id="f1">
				<a name="bottom"></a>
				<a href="#top" class="arrow">55</a>
			</p>
			<div id="f2">
				 Powered by <a href="http://doyouhaobaby.net" title="<?php echo Dyhb::L('DoYouHaoBaby官方站','__DYHB__@ProdeDyhb');?>"><b>DoYouHaoBaby</b></a>
			</div>
	</div>
</body>
</html>

<?php
// php代码执行结束
}else{
	die('<p style="color:#fff;font-weight:bold;margin-top:30px;width:500px;padding:10px;background:red;">'.Dyhb::L('I am sorry,系统禁用了探针!','__DYHB__@ProdeDyhb').'</p>');
}
