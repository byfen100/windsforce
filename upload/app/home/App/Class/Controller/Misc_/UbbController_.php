<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Ubb控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UbbController extends Controller{

	protected $_sType='';
	
	public function index(){
		$sType=trim(G::getGpc('type','G'));

		if(!in_array($sType,array('','sign'))){
			$sType='sign';
		}

		$this->_sType=$sType;

		if($sType=='sign'){
			$this->sign();

			return;
		}
		
		$arrUbbbases=array(
			'b'=>array('[b]'.Dyhb::L('粗体文字','__COMMON_LANG__@Ubb').' Abc[/b]'),
			'i'=>array('[i]'.Dyhb::L('斜体文字','__COMMON_LANG__@Ubb').' Abc[/i]'),
			'u'=>array('[u]'.Dyhb::L('下划线文字','__COMMON_LANG__@Ubb').' Abc[/u]'),
			'strike'=>array('[strike]'.Dyhb::L('删除线文字','__COMMON_LANG__@Ubb').' Abc[/strike]'),
			'color'=>array('[color=red]'.Dyhb::L('红颜色','__COMMON_LANG__@Ubb').'[/color]'),
			'size'=>array('[size=3]'.Dyhb::L('文字大小为','__COMMON_LANG__@Ubb').' 3[/size]'),
			'font'=>array('[font='.Dyhb::L('仿宋','__COMMON_LANG__@Ubb').']'.Dyhb::L('字体为仿宋','__COMMON_LANG__@Ubb').'[/font]'),
			'strong'=>array('[strong]'.Dyhb::L('强调文字','__COMMON_LANG__@Ubb').' Abc[/strong]'),
			'align'=>array('[p align=center]'.Dyhb::L('内容居中','__COMMON_LANG__@Ubb').'[/p]'),
			'url'=>array('[url]'.$GLOBALS['_option_']['windsforce_program_url'].'[/url]',Dyhb::L('超级链接','__COMMON_LANG__@Ubb')),
			'url2'=>array('[url='.$GLOBALS['_option_']['windsforce_program_url'].']$'.$GLOBALS['_option_']['windsforce_program_name'].' '.Dyhb::L('开发框架','__COMMON_LANG__@Ubb').'[/url]',Dyhb::L('超级链接','__COMMON_LANG__@Ubb').''),
			'url3'=>array(' '.$GLOBALS['_option_']['windsforce_program_url'],Dyhb::L('自动添加链接','__COMMON_LANG__@Ubb').''),
			'email'=>array('[email]'.$GLOBALS['_option_']['admin_email'].'[/email]',''.Dyhb::L('Email链接','__COMMON_LANG__@Ubb').''),
			'quote'=>array('[quote]$'.$GLOBALS['_option_']['windsforce_program_name'].' App Framework | '.Dyhb::L('由WindsForce TEAM开发的APP框架软件','__COMMON_LANG__@Ubb').'[/quote]'),
			'blockquote'=>array('[blockquote]Hello world![/blockquote]'),
			'code'=>array('[code]$'.$GLOBALS['_option_']['windsforce_program_name'].' App Framework | '.Dyhb::L('由WindsForce TEAM开发的APP框架软件','__COMMON_LANG__@Ubb').'[/code]'),
			'php'=>array('[php]<?php echo \'Hello world!\';?>[/php]',Dyhb::L('源代码因为标签特殊的原因不可见色','__COMMON_LANG__@Ubb').''),
			'hide'=>array('[hide]'.Dyhb::L('隐藏内容','__COMMON_LANG__@Ubb').' Abc[/hide]',Dyhb::L('仅登陆用户才能看到','__COMMON_LANG__@Ubb')),
			'img'=>array('[img]'.$GLOBALS['_option_']['site_url'].'/Public/images/common/iconlogo/logo.gif[/img]'),
			'sup'=>array('X[sup]2[/sup]'),
			'sub'=>array('X[sub]2[/sub]'),
			'hr'=>array('Hello [hr] world!'),
			'br'=>array('Hello [br]world!'),
			'acronym'=>array('[acronym=12]'.strtoupper($GLOBALS['_option_']['windsforce_program_name']).'[/acronym]',Dyhb::L('首字母的缩写词','__COMMON_LANG__@Ubb')),
			'fly'=>array('[fly]'.Dyhb::L('飞行效果','__COMMON_LANG__@Ubb').'[/fly]'),
		);

		$arrUbbadvs=array(
			'attachment'=>array('[attachment]1[/attachment]',Dyhb::L('解析内部附件,如果附件不存在将显示为空','__COMMON_LANG__@Ubb')),
			'mp3'=>array('[mp3]'.$GLOBALS['_option_']['site_url'].'/Public/images/common/sound/pm_1.mp3[/mp3]',Dyhb::L('仅支持MP3格式音乐','__COMMON_LANG__@Ubb')),
			'video'=>array('[video]'.$GLOBALS['_option_']['site_url'].'/Public/images/common/ubb_flash.swf[/video]',Dyhb::L('支持swf,asf,wmv,avi,rm,rmvb,flv,mp4','__COMMON_LANG__@Ubb')),
			'tag'=>array('[TAG]#'.Dyhb::L('新鲜事话题','__COMMON_LANG__@Ubb').'#[/TAG]'),
			'message'=>array('[MESSAGE]@admin[/MESSAGE]',Dyhb::L('新鲜事中的@功能，仅UBB过滤函数参数不同','__COMMON_LANG__@Ubb')),
			'message2'=>array('[MESSAGE]@admin[/MESSAGE]',Dyhb::L('其它地方中的@功能，仅UBB过滤函数参数不同','__COMMON_LANG__@Ubb')),
		);

		$this->assign('arrUbbbases',$arrUbbbases);
		$this->assign('arrUbbadvs',$arrUbbadvs);
		$this->assign('sType',$sType);

		$this->display('misc+ubb');
	}

	public function sign(){
		$arrUbbs=array(
			'b'=>array('[b]'.Dyhb::L('粗体文字','__COMMON_LANG__@Ubb').' Abc[/b]'),
			'i'=>array('[i]'.Dyhb::L('斜体文字','__COMMON_LANG__@Ubb').' Abc[/i]'),
			'u'=>array('[u]'.Dyhb::L('下划线文字','__COMMON_LANG__@Ubb').' Abc[/u]'),
			'strike'=>array('[strike]'.Dyhb::L('删除线文字','__COMMON_LANG__@Ubb').' Abc[/strike]'),
			'color'=>array('[color=red]'.Dyhb::L('红颜色','__COMMON_LANG__@Ubb').'[/color]'),
			'size'=>array('[size=3]'.Dyhb::L('文字大小为','__COMMON_LANG__@Ubb').' 3[/size]'),
			'url'=>array('[url]'.$GLOBALS['_option_']['windsforce_program_url'].'[/url]',Dyhb::L('超级链接','__COMMON_LANG__@Ubb')),
			'url2'=>array('[url='.$GLOBALS['_option_']['windsforce_program_url'].']$'.$GLOBALS['_option_']['windsforce_program_name'].' '.Dyhb::L('开发框架','__COMMON_LANG__@Ubb').'[/url]',Dyhb::L('超级链接','__COMMON_LANG__@Ubb')),
			'url3'=>array(' '.$GLOBALS['_option_']['windsforce_program_url'],Dyhb::L('自动添加链接','__COMMON_LANG__@Ubb')),
			'email'=>array('[email]'.$GLOBALS['_option_']['admin_email'].'[/email]',Dyhb::L('Email链接','__COMMON_LANG__@Ubb')),
			'img'=>array('[img]'.$GLOBALS['_option_']['site_url'].'/Public/images/common/iconlogo/logo.gif[/img]'),
			'sup'=>array('X[sup]2[/sup]'),
			'sub'=>array('X[sub]2[/sub]'),
			'hr'=>array('Hello [hr] world!'),
			'br'=>array('Hello [br]world!'),
		);

		$this->assign('arrUbbs',$arrUbbs);
		$this->assign('sType','sign');

		$this->display('misc+ubbsign');
	}

	public function ubb_title_(){
		return $this->_sType=='sign'?Dyhb::L('签名UBB代码','Controller/Misc'):Dyhb::L('UBB代码','Controller/Misc');
	}

	public function ubb_keywords_(){
		return $this->ubb_title_();
	}

	public function ubb_description_(){
		return $this->ubb_title_();
	}

}
