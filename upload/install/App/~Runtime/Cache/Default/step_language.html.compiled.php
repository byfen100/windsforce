<?php  /* DoYouHaoBaby Framework 模板缓存文件生成时间：2013-03-11 08:32:31  */ ?>
<?php $this->includeChildTemplate(TEMPLATE_PATH.'/common_header.html');?><ul class="breadcrumb"><li><a href="<?php echo(__APP__);?>" title="<?php print Dyhb::L("主页",'Template/Default/Common',null);?>"><i class='icon-home'></i></a>&nbsp;<span class="divider">/</span></li><li>Select Language | WINDSFORCE-<?php echo(WINDSFORCE_SERVER_VERSION);?> - <?php echo(WINDSFORCE_SERVER_RELEASE);?></li></ul><div class="hero-unit"><h1>Wind<span style="color:red;">s</span>Force</h1><p>风之力APP开发框架 (To Make Prowerful APP.天神下凡APP)</p><p><a class="btn btn-success btn-large" href="http://windsforce.net" target="_blank">Learn more &raquo;</a></p></div><div class="row"><div class="span12"><h2>The preview</h2><p><img src="<?php echo(__TMPLPUB__);?>/images/preview.jpg" /></p></div></div><div class="row"><div class="span12"><h2>Please select your language!</h2><p><?php $sLangCookieName=$GLOBALS['_commonConfig_']['COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME']===true?APP_NAME.'_language':'language';?><select name="lang-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;"><option value="">-- Please select your language --</option><?php $i=1;?><?php if(is_array($arrInstallLangs)):foreach($arrInstallLangs as $key=>$sInstallLangs):?><option value="?l=<?php echo(strtolower($sInstallLangs));?>" <?php if(strtolower(Dyhb::cookie($sLangCookieName))==strtolower($sInstallLangs)):?>selected<?php endif;?>><?php echo($sInstallLangs);?></option><?php $i++;?><?php endforeach;endif;?></select></p></div></div><div class="row"><div class="span12"><div class="well"><p><input type='button' value='Next' class='btn btn-success' onclick="window.location.href='<?php echo(Dyhb::U( 'index/select' ));?>'"/></p></div></div></div><?php $this->includeChildTemplate(TEMPLATE_PATH.'/common_footer.html');?>