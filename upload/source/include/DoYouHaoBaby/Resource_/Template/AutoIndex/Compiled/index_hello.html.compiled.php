<?php  /* DoYouHaoBaby Framework 模板缓存文件生成时间：2012-07-09 09:25:16  */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="author" content="DoYouHaoBaby - <www.doyouhaobaby.net>" />
<meta name="Copyright" content="DoYouHaoBaby - <www.doyouhaobaby.net>" />
<meta name="description" content="<?php print Dyhb::L("DoYouHaoBaby启动成功欢迎页",'__DYHB__@HelloworldDyhb',null); ?>" />
<title>^_^  <?php print Dyhb::L("Hello , 欢迎使用DoYouHaoBaby!",'__DYHB__@HelloworldDyhb',null); ?></title>
<style>
body{font:400 14px/25px '微软雅黑',Tahoma,sans-serif;background:#F7F7F7;}
a{text-decoration:none; color:#007979; padding:0 5px;}
a:hover{color:#FF0000;}
img{border:0}
.clear{ clear:both; }
.lang_list{ border:1px solid #000;background:#ccc;padding-left:5px;padding-bottom:5px;padding-right:5px; }
.lang_title{ font-weight:bold;margin-left:10px;color:#000;margin-top:10px; }
em,i {font-style:normal;}
h1{padding:10px 0;}
h2{font-size:14px;padding:10px;color:#000;border:1px solid #e0e0e0;background:#ffd;}
em{color:#6C6C6C}
.hello{width:600px;height:100%;padding:10px; margin:50px auto 0; border:1px solid #DFDFDF;overflow:hidden;background:#fff;}
.link{float:right;}
.copyright{clear:both;text-align:center;margin-top:10px;}
.copyright i{color:silver;}
.copyright sup{font-size:9pt;color:#666;}
</style>
</head>
<body>
	<div class="hello">
		<h1><a href="http://doyouhaobaby.net" title=""><img src="<?php echo __LIBCOM__;?>/Images/Logo.gif" title="DoYouHaoBaby官方站"/></a></h1>

		<?php $arrLangs=G::listDir(DYHB_PATH.'/Resource_/Lang'); ?>
		<div class="lang_list">
			<div class="lang_title">Please select your language !</div>
			<?php $sLangCookieName=$GLOBALS['_commonConfig_']['COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME']===true?APP_NAME.'_language':'language'; ?>
			<select name="lang-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
				<option value="">-- Please select your language --</option>
				<?php $i=1;?>
<?php if(is_array($arrLangs)): foreach($arrLangs as $key=>$value): ?>

				<option value="?l=<?php echo($value); ?>" <?php if(strtolower( Dyhb::cookie($sLangCookieName) )==strtolower($value)) :?>selected<?php endif; ?>><?php echo($value); ?></option>
				
<?php $i++;?>
<?php endforeach;endif; ?>
			</select>
		</div>

		<h2><em>^_^</em> <?php print Dyhb::L("Hello ,欢迎使用",'__DYHB__@HelloworldDyhb',null); ?> <span style='font-weight:bold;color:red'>DoYouHaoBaby</span> ,<?php print Dyhb::L("当你看到这个页面的时候,你成功了一半!",'__DYHB__@HelloworldDyhb',null); ?>
			<br>
			<font color="#ADADAD"><?php print Dyhb::L("说明:你可以使用框架目录中Tools/Websetup.php图形化工具初始化应用程序设置,当然也可以手动修改配置文件。",'__DYHB__@HelloworldDyhb',null); ?></font>
		</h2>
		<div class="link">
			[<a href="<?php echo(Dyhb::U('index/check_probe'));?>" title=""><?php print Dyhb::L("PHP探针",'__DYHB__@HelloworldDyhb',null); ?></a>][<a href="http://doyouhaobaby.net/document" title=""><?php print Dyhb::L("在线手册",'__DYHB__@HelloworldDyhb',null); ?></a>][<a href="http://doyouhaobaby.net/" title="<?php print Dyhb::L("DoYouHaoBaby官方站",'__DYHB__@HelloworldDyhb',null); ?>"><?php print Dyhb::L("官方主页",'__DYHB__@HelloworldDyhb',null); ?></a>][<a href="http://bbs.doyouhaobaby.net/" title=""><?php print Dyhb::L("官方社区",'__DYHB__@HelloworldDyhb',null); ?></a>]
		</div>
	</div>
	<p class="copyright"><em>DoYouHaoBaby</em>&nbsp;<sup><?php echo(DYHB_VERSION); ?></sup>&nbsp;<i class="slogan">{  BUTIFUL APP START HERE  }</i></p>
</div>
</body>
</html>