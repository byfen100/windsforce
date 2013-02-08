<?php
/* [DoYouHaoBaby!] (C)WindsForce Team Start This From 2010.10.04.
   代码编译器列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

abstract class TemplateCodeCompilerBase{

	public function __construct(){
		$this->init_();
	}

	public function init_(){}

	public function arrayHandler(&$arrVars,$nType=1,$nGo=2){
		$nLen=count($arrVars);

		$sParam='';
		if($nType==1){// 类似$hello['test']['test2']
			for($nI=$nGo;$nI<$nLen;$nI++){
				$sParam.="['{$arrVars[$nI]}']";
			}
		}elseif($nType=='2'){// 类似$hello->test1->test2
			for($nI=$nGo;$nI<$nLen;$nI++){
				$sParam.="->{$arrVars[$nI]}";
			}
		}elseif($nType=='3'){// 类似$hello.test1.test2
			for($nI=$nGo;$nI<$nLen;$nI++){
				$sParam.=".{$arrVars[$nI]}";
			}
		}

		return $sParam;
	}

}

/* while循环 */
class TemplateCodeCompiler_while extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php while({$sContent}):".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('while',__CLASS__);
	}

}
TemplateCodeCompiler_while::regToCompiler();

/* 变量 */
class TemplateCodeCompiler_variable extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=!empty($sContent)?$this->parseContent($sContent):NULL;
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent){
		$sContent=str_replace(':','->',$sContent);// 以|分割字符串,数组第一位是变量名字符串,之后的都是函数参数&&函数{$hello|md5}

		$arrVar=explode('|',$sContent);
		$sVar=array_shift($arrVar);// 弹出第一个元素,也就是变量名
		if(strtolower(substr($sContent,0,2))=='d.'){// 系统变量
			$sName=$this->parseD($sVar);
		}elseif(strpos($sVar,'.')){// 访问数组元素或者属性
			$arrVars=explode('.',$sVar);
			if(substr($arrVars['1'],0,1)=="'" or substr($arrVars['1'],0,1)=='"' or substr($arrVars['1'],0,1)=="$"){
				$sName='$'.$arrVars[0].'.'.$arrVars[1].($this->arrayHandler($arrVars,3));// 特殊的.连接字符串
			}else{
				$bIsObject=FALSE;// 解析对象的方法
				if(substr($sContent,-1)==')'){
					$bIsObject=TRUE;
				}

				if($bIsObject===FALSE){// 非对象
					 switch(strtolower($GLOBALS['_commonConfig_']['TMPL_VAR_IDENTIFY'])) {
						case 'array': // 识别为数组
							$sName='$'.$arrVars[0].'[\''.$arrVars[1].'\']'.($this->arrayHandler($arrVars));
							break;
						case 'obj':  // 识别为对象
							$sName='$'.$arrVars[0].'->'.$arrVars[1].($this->arrayHandler($arrVars,2));
							break;
						default:  // 自动判断数组或对象 支持多维
							$sName='is_array($'.$arrVars[0].')?$'.$arrVars[0].'[\''.$arrVars[1].'\']'.($this->arrayHandler($arrVars)).' :$'.$arrVars[0].'->'.$arrVars[1].($this->arrayHandler($arrVars,2));
							break;
					}
				}else{
					$sName='$'.$arrVars[0].'->'.$arrVars[1].($this->arrayHandler($arrVars,2));
				}
			}
			$sVar=$arrVars[0];
		}elseif(strpos($sVar,'[')){// $hello['demo'] 方式访问数组
			$sName="$".$sVar;
			preg_match('/(.+?)\[(.+?)\]/is',$sVar,$arrArray);
			$sVar=$arrArray[1];
		}else{
			$sName="\$$sVar";
		}

		if(count($arrVar)>0){// 如果有使用函数
			$sName=$this->parseVarFunction($sName,$arrVar);// 传入变量名,和函数参数继续解析,这里的变量名是上面的判断设置的值
		}

		$sName=str_replace('^',':',$sName);

		$sCode=!empty($sName)?"<?php echo({$sName});?>":'';

		return $sCode;
	}

	public function parseD($sVar){
		$arrVars=explode('.',$sVar);// 解析‘.’

		$arrVars[1]=strtoupper(trim($arrVars[1]));
		$nLen=count($arrVars);
		$sAction="\$_@";
		if($nLen>=3){// cookie,session,get等等
			if(in_array(strtoupper($arrVars[1]),array('COOKIE','SESSION','GET','POST','SERVER','ENV','REQUEST'))){// PHP常用系统变量 < 忽略大小写 >
				$sCode=str_replace('@',$arrVars[1],$sAction).$this->arrayHandler($arrVars);// 替换调名称,并将使用arrayHandler函数获取下标,支持多维 ，以$demo[][][]方式
			}elseif(strtoupper($arrVars[1])=='LANG'){
				$sCode='Dyhb::L(\''.addslashes(stripslashes($arrVars[2])).'\''.(isset($arrVars[3])?',\''.$arrVars[3].'\'':',null').(isset($arrVars[4])?',\''.$arrVars[4].'\'':',null').')';
			}elseif(strtoupper($arrVars[1])=='CONFIG'){
				$sCode='Dyhb::C(\''.strtoupper($arrVars[2]).(isset($arrVars[3])?'.'.$arrVars[3]:'').'\')';
			}elseif( strtoupper($arrVars[1])=='CONST'){
				$sCode=strtoupper($arrVars[2]);
			}else{
				$sCode='';
			}
		}elseif($nLen===2){// 时间
			if(strtoupper($arrVars[1])=='NOW' or strtoupper($arrVars[1])=='TIME'){
				$sCode="date('Y-m-d H:i:s',time())";
			}elseif(strtoupper($arrVars[1])=='VERSION' || strtoupper($arrVars[1])=='DYHB_VERSION'){
				$sCode='DYHB_VERSION';
			}elseif(strtoupper($arrVars[1])=='LEFTTAG' || strtoupper($arrVars[1])=='LEFT'){
				$sCode='"{"';
			}elseif(strtoupper($arrVars[1])=='RIGHTTAG' || strtoupper($arrVars[1])=='RIGHT'){
				$sCode='"}"';
			}elseif(strtoupper($arrVars[1])=='TEMPLATE' || strtoupper($arrVars[1])=='BASENAME'){
				$sCode='__TMPL_FILE_NAME__';
			}else{
				$sCode=$arrVars[1];
			}
		}

		return $sCode;
	}

	public function parseVarFunction($sName,$arrVar){
		$nLen=count($arrVar);

		// 取得模板禁止使用函数列表
		$arrNot=explode(',',$GLOBALS['_commonConfig_']['TEMPLATE_NOT_ALLOWS_FUNC']);
		for($nI=0;$nI<$nLen;$nI++){
			if(0===stripos($arrVar[$nI],'default=')){
				$arrArgs=explode('=',$arrVar[$nI],2);
			}else{
				$arrArgs=explode('=',$arrVar[$nI]);
			}

			$arrArgs[0]=trim($arrArgs[0]);
			$arrArgs[0]=str_replace('+','::',$arrArgs[0]);
			if(isset($arrArgs[1])){
				$arrArgs[1]=str_replace('->',':',$arrArgs[1]);
			}

			switch(strtolower($arrArgs[0])) {
				case 'default':// 特殊模板函数
					$sName='('.$sName.')?('.$sName.'):'.$arrArgs[1];
					break;
				default:// 通用模板函数
					if(!in_array($arrArgs[0],$arrNot)){
						if(isset($arrArgs[1])){
							if(strstr($arrArgs[1],'**')){
								$arrArgs[1]=str_replace('**',$sName,$arrArgs[1]);
								$sName="$arrArgs[0]($arrArgs[1])";
							}else{
								$sName="$arrArgs[0]($sName,$arrArgs[1])";
							}
					}elseif(!empty($arrArgs[0])){
						$sName="$arrArgs[0]($sName)";
					}
				}
			}
		}

		return $sName;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('$',__CLASS__);
	}

}
TemplateCodeCompiler_variable::regToCompiler();

/* PHP脚本 */
class TemplateCodeCompiler_script extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php {$sContent};?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('~',__CLASS__);
	}

}
TemplateCodeCompiler_script::regToCompiler();

/* 注释 */
class TemplateCodeCompiler_note extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode(' ');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('#',__CLASS__);
	}

}
TemplateCodeCompiler_note::regToCompiler();

/* Javascript初始标签 */
class TemplateCodeCompiler_js_code extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sCompiled=TemplateRevertParser::encode("<script type=\"text/javascript\">");
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('script',__CLASS__);
	}

}
TemplateCodeCompiler_js_code::regToCompiler();

/* js导入Javascritpt标签 */
class TemplateCodeCompiler_js extends TemplateCodeCompiler_load{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent,'js');
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('js',__CLASS__);
	}

}
TemplateCodeCompiler_js::regToCompiler();

/* if标签 */
class TemplateCodeCompiler_if extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent);
		$sCompiled=TemplateRevertParser::encode('<'."?php {$sContent}:?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent){
		$sContent=str_replace(':','->',$sContent);

		$arrArray=explode(' ',$sContent);
		$bObj=false;
		$arrParam=array();
		foreach($arrArray as $sV){
			if(strpos($sV,'.')>0){
				$arrArgs=explode('.',$sV);
				$arrParam[]=$arrArgs[0].($this->arrayHandler($arrArgs,1,1));// 以$hello['hello1']['hello2']['hello2']方式
				$arrParamTwo[]=$arrArgs[0].($this->arrayHandler($arrArgs,2,1));// 以$hello->'hello1->'hello2'->'hello2'方式
				$bObj=true;
			}else{
				$arrParam[]=$sV;
				$arrParamTwo[]=$sV;
			}
		}

		if($bObj){
			$sStr='is_array('.$arrArgs[0].')'.'?'.join(' ',$arrParam).':'.join(' ',$arrParamTwo);
		}else{
			$sStr=join(' ',$arrParam);
		}

		$sStr=str_replace('+','::',$sStr);
		$sStr=str_replace('^',':',$sStr);

		return "if({$sStr})";
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('if',__CLASS__);
	}

}
TemplateCodeCompiler_if::regToCompiler();

/* foreach循环 */
class TemplateCodeCompiler_foreach extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent);
		$sCompiled=TemplateRevertParser::encode('<'."?php {$sContent}:".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent){
		preg_match_all('/\\$([\S]+)/',$sContent,$arrArray);

		$arrArray=$arrArray[1];
		$nNum=count($arrArray);
		if($nNum>0){
			if($nNum==2){
				$sResult="\${$arrArray[1]}";
			}elseif($nNum==3){
				$sResult="\${$arrArray[1]}=>\${$arrArray[2]}";
			}else{
				Dyhb::E(Dyhb::L('foreach,list的参数错误。','__DYHB__@Dyhb'));
			}
			
			return "if(is_array(\${$arrArray[0]})):foreach(\${$arrArray[0]} as $sResult)";
		}
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('foreach',__CLASS__);
		TemplateCodeParser::regCompilers('list',__CLASS__);
	}

}
TemplateCodeCompiler_foreach::regToCompiler();

/* for循环 */
class TemplateCodeCompiler_for extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php for({$sContent}):".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('d*for',__CLASS__);
	}

}
TemplateCodeCompiler_for::regToCompiler();

/* 部分常用结束标签 */
class TemplateCodeCompiler_endtag extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent);
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent){
		// do while 处理
		if(trim(substr($sContent,0,7))=='dowhile'){
			$sContent=trim(substr($sContent,7));
			$sContent="<?php }while({$sContent});?>";
		}

		switch($sContent){
			case 'list':
			case 'foreach':
				$sContent='<?php endforeach;endif;?>';
				break;
			case 'd*for':
				$sContent='<?php endfor;?>';
				break;
			case 'while':
				$sContent='<?php endwhile;?>';
				break;
			case 'script':
				$sContent='</script>';
				break;
			case 'if':
				$sContent='<?php endif;?>';
				break;
			case 'style':
				$sContent='</style>';
				break;
		}

		return $sContent;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('/',__CLASS__);
	}

}
TemplateCodeCompiler_endtag::regToCompiler();

/* elseif标签 */
class TemplateCodeCompiler_elseif extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent);
		$sCompiled=TemplateRevertParser::encode('<'."?php {$sContent}:?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent){
		$sContent=str_replace(':','->',$sContent);

		$arrArray=explode(' ',$sContent);
		$bObj=false;
		$arrParam=array();
		foreach($arrArray as $sV){
			if(strpos($sV,'.') > 0){
				$arrArgs =explode('.',$sV);
				$arrParam[]=$arrArgs[0].($this->arrayHandler($arrArgs,1,1));// 以$hello['hello1']['hello2']['hello2']方式
				$arrParamTwo[]=$arrArgs[0].($this->arrayHandler($arrArgs,2,1));// 以$hello->'hello1->'hello2'->'hello2'方式
				$bObj=true;
			}else{
				$arrParam[]=$sV;
				$arrParamTwo[]=$sV;
			}
		}

		if($bObj){
			$sStr='is_array('.$arrArgs[0].')'.'?'.join(' ',$arrParam).' : '.join(' ',$arrParamTwo);
		}else{
			$sStr=join(' ',$arrParam);
		}

		$sStr=str_replace('+','::',$sStr);
		$sStr=str_replace('^',':',$sStr);

		return "elseif({$sStr})";
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('elseif',__CLASS__);
	}

}
TemplateCodeCompiler_elseif::regToCompiler();

/* else标签 */
class TemplateCodeCompiler_else extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sCompiled=TemplateRevertParser::encode('<'."?php else:?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('d*else',__CLASS__);
	}

}
TemplateCodeCompiler_else::regToCompiler();

/* PHP echo标签 */
class TemplateCodeCompiler_echo extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo({$sContent});".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers(':',__CLASS__);
	}

}
TemplateCodeCompiler_echo::regToCompiler();

/* PHP do while */
class TemplateCodeCompiler_do_while extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sCompiled=TemplateRevertParser::encode('<'."?php do{".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('dowhile',__CLASS__);
	}

}
TemplateCodeCompiler_do_while::regToCompiler();

/* CSS内嵌开始标签 */
class TemplateCodeCompiler_css_style extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sCompiled=TemplateRevertParser::encode("<style type=\"text/css\">");
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('style',__CLASS__);
	}

}
TemplateCodeCompiler_css_style::regToCompiler();

/* CSS导入标签 */
class TemplateCodeCompiler_css extends TemplateCodeCompiler_load{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent,'css');
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('css',__CLASS__);
	}

}
TemplateCodeCompiler_css::regToCompiler();

/* Session */
class TemplateCodeCompiler_session extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$arrVars=explode('.',$sContent);
		$sContent=$this->arrayHandler($arrVars,1,0);
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(\$_SESSION{$sContent});".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('!',__CLASS__);
	}

}
TemplateCodeCompiler_session::regToCompiler();

/* Print */
class TemplateCodeCompiler_print extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php print({$sContent});?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('=',__CLASS__);
	}

}
TemplateCodeCompiler_print::regToCompiler();

/* PHP POST */
class TemplateCodeCompiler_post extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$arrVars=explode('.',$sContent);
		$sContent=$this->arrayHandler($arrVars,1,0);
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(\$_POST{$sContent});".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('&',__CLASS__);
	}

}
TemplateCodeCompiler_post::regToCompiler();

class TemplateCodeCompiler_origin extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('?',__CLASS__);
	}

}
TemplateCodeCompiler_origin::regToCompiler();

class TemplateCodeCompiler_load extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent);
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent,$sType=''){
		$sContent=trim($sContent);
		if(empty($sType)){
			$sType=strtolower(substr(strrchr($sContent,'.'),1));
		}else{
			$sContent=$sContent.'.'.$sType;
		}

		$arrSystemVar=array('__ROOT__','__APP__','__URL__','__ACTION__',
							'WEB_ADMIN_HTTPPATH','__FRAMEWORK__','__LIBCOM__',
							'__APPPUB__','__THEME__','__TMPL__','__PUBLIC__',
							'__TMPLPUB__');
		foreach($arrSystemVar as $sSystemVar){
			$sContent=str_replace($sSystemVar,"<?php echo {$sSystemVar};?>",$sContent);
		}

		$sParseStr='';
		if($sType=='js'){
			$sParseStr.='<script type="text/javascript" src="'.$sContent.'"></script>';
		}elseif($sType=='css') {
			$sParseStr.='<link rel="stylesheet" type="text/css" href="'.$sContent.'" />';
		}

		return $sParseStr;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('load',__CLASS__);
	}

}
TemplateCodeCompiler_load::regToCompiler();

class TemplateCodeCompiler_lang extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=addcslashes(stripslashes(trim($oObj->getContent())),'"');
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(Dyhb::L('{$sContent}'));".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('.',__CLASS__);
	}

}
TemplateCodeCompiler_lang::regToCompiler();

class TemplateCodeCompiler_isset extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sContent=$this->parseContent($sContent);
		$sCompiled=TemplateRevertParser::encode($sContent);
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sContent){
		$nStart=strpos($sContent,'(');// 为了兼容isset ()的括号出现空格的写法,获取位置
		$nEnd=strpos($sContent,')');
		$bIsEcho=strrchr(trim($sContent),"**");// 获取尾部**，**表示不输出,如果存在则删除
		if($bIsEcho){
			$sContent= substr(trim($sContent),0,strlen(trim($sContent))-2);
		}
		$sVar=trim(substr($sContent,$nStart+1,$nEnd-$nStart-1));// 获取变量和条件
		$sCondition=trim(substr ($sContent,$nEnd+1));

		if($bIsEcho){
			return "<?php $sVar=isset({$sVar})?{$sCondition};?>";
		}else{
			return "<?php echo(isset({$sVar})?{$sCondition});?>";
		}
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('isset',__CLASS__);
	}

}
TemplateCodeCompiler_isset::regToCompiler();

class TemplateCodeCompiler_include extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();

		$sContent=str_replace('->','.',$sContent);// 将 -> 还原为 .
		$sContent=$this->parseContent($sContent); // 替换一下，防止迁移的时候由于物理路径的原因，需要重新生成编译文件
		$sContent=str_replace(G::tidyPath(DYHB_PATH),'DYHB_PATH.\'',G::tidyPath($sContent));
		$sContent=str_replace(G::tidyPath(TEMPLATE_PATH),'TEMPLATE_PATH.\'',G::tidyPath($sContent));
		$sContent=rtrim($sContent,'\'');
		$sContent=strpos($sContent,'$')===0 || strpos($sContent,'(')?$sContent:$sContent.'\'';

		if(strpos($sContent,':\\') || strpos($sContent,'/')===0){
			$sContent='\''.$sContent;
		}

		$sCompiled="<?php \$this->includeChildTemplate(".$sContent.");?>";
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	public function parseContent($sTmplPublicName){
		$arrTemplateInfo=array();

		if(strpos($sTmplPublicName,'(')){// 函数直接返回数据
			return $sTmplPublicName;
		}elseif(''==$sTmplPublicName){// 如果模板文件名为空 按照默认规则定位
			$arrTemplateInfo=array('file'=>MODULE_NAME.$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR'].ACTION_NAME.$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX']);
		}else if(!strpos($sTmplPublicName,':\\') && strpos($sTmplPublicName,'/')!==0 &&
			substr($sTmplPublicName,0,1)!='$' && !is_file($sTmplPublicName)
		){//支持加载变量文件名&D:\phpcondition\......排除绝对路径分析&/home1/...... 排除Linux环境下面的绝对路径分析
			if(strpos($sTmplPublicName,'@')){// 分析主题
				$arrArray=explode('@',$sTmplPublicName);
				$arrTemplateInfo['theme']=ucfirst(strtolower(array_shift($arrArray)));
				$sTmplPublicName=array_shift($arrArray);
			}

			$arrValue=explode('+',$sTmplPublicName);
			$sTmplModuleName=$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/' && $arrValue[0]=='public'?'Public':$arrValue[0];
			$sTmplPublicName=str_replace($arrValue[0].'+',$sTmplModuleName.$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR'],$sTmplPublicName);// 分析文件&模块和操作
			$arrTemplateInfo['file']=$sTmplPublicName.$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX'];
		}

		if(!empty($arrTemplateInfo)){
			$sPath=!empty($arrTemplateInfo['theme']) || $GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/'?dirname(dirname(__TMPL_FILE_PATH__)):dirname(__TMPL_FILE_PATH__);
			return $sPath.'/'.(!empty($arrTemplateInfo['theme'])?$arrTemplateInfo['theme'].'/':'').$arrTemplateInfo['file'];
		}else{
			if(substr($sTmplPublicName,0,1)=='$'){// 返回变量
				return $sTmplPublicName;
			}else{
				return $sTmplPublicName;
			}
		}
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('include',__CLASS__);
	}

}
TemplateCodeCompiler_include::regToCompiler();

class TemplateCodeCompiler_get extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$arrVars=explode('.',$sContent);
		$sContent=$this->arrayHandler($arrVars,1,0);
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(\$_GET{$sContent});".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('%',__CLASS__);
	}

}
TemplateCodeCompiler_get::regToCompiler();

class TemplateCodeCompiler_cookie extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$arrVars=explode('.',$sContent);
		$sContent=$this->arrayHandler($arrVars,1,0);
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(\$_COOKIE{$sContent});".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('<',__CLASS__);
	}

}
TemplateCodeCompiler_cookie::regToCompiler();

class TemplateCodeCompiler_constant extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo({$sContent});".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('*',__CLASS__);
	}

}
TemplateCodeCompiler_constant::regToCompiler();

class TemplateCodeCompiler_config extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(Dyhb::C('{$sContent}'));".'?>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('@',__CLASS__);
	}

}
TemplateCodeCompiler_config::regToCompiler();

class TemplateCodeCompiler_before_increase_not_echo extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent() ;
		$sCompiled=TemplateRevertParser::encode('<'."?php ++{$sContent};?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('d*<++',__CLASS__);
	}
}
TemplateCodeCompiler_before_increase_not_echo::regToCompiler();

class TemplateCodeCompiler_before_increase extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(++{$sContent});?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('<++',__CLASS__);
	}

}
TemplateCodeCompiler_before_increase::regToCompiler();

class TemplateCodeCompiler_before_decrease_not_echo extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php --{$sContent};?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('d*<--',__CLASS__);
	}

}
TemplateCodeCompiler_before_decrease_not_echo::regToCompiler();

class TemplateCodeCompiler_before_decrease extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo(--{$sContent});?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('<--',__CLASS__);
	}

}
TemplateCodeCompiler_before_decrease::regToCompiler();

class TemplateCodeCompiler_after_increase_not_echo extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php  {$sContent}++;?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('d*++>',__CLASS__);
	}

}
TemplateCodeCompiler_after_increase_not_echo::regToCompiler();

class TemplateCodeCompiler_after_increase extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo({$sContent}++);?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('++>',__CLASS__);
	}

}
TemplateCodeCompiler_after_increase::regToCompiler();

class TemplateCodeCompiler_after_decrease_not_echo extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php {$sContent}--;?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('d*-->',__CLASS__);
	}

}
TemplateCodeCompiler_after_decrease_not_echo::regToCompiler();

class TemplateCodeCompiler_after_decrease extends TemplateCodeCompilerBase{

	public function compile(TemplateObj $oObj){
		$sContent=$oObj->getContent();
		$sCompiled=TemplateRevertParser::encode('<'."?php echo({$sContent}--);?".'>');
		$oObj->setCompiled($sCompiled);
		$oObj->setCompiler(null);

		return $sCompiled;
	}

	static public function regToCompiler(){
		TemplateCodeParser::regCompilers('-->',__CLASS__);
	}

}
TemplateCodeCompiler_after_decrease::regToCompiler();
