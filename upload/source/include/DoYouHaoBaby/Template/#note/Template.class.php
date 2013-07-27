<?dyhb
class Template{

	/**
	 * Template对象
	 *
	 * @access private
	 * @var array
	 */
	private $TEMPLATE_OBJS=array() ;

	/**
	 * 分析器管理器程序
	 *
	 * @access private
	 * @var
	 */
	private $_oParserManager ;

	/**
	 * 模板编译路径
	 *
	 * @access protected
	 * @var string
	 */
	protected $_sCompiledFilePath;

	/**
	 * 模板主题
	 *
	 * @access protected
	 * @var string
	 */
	protected $_sThemeName='';

	/**
	 * 是否为子模板
	 *
	 * @access protected
	 * @var bool
	 */
	protected $_bIsChildTemplate=FALSE;

	/**
	 * 编译缓存文件是否保存在系统内部
	 *
	 * <!--说明-->
	 * < 系统有些编译文件，不需要暴露在外面，那么我们可以通过这个设置，保存在系统内部。
	 *   因为系统保存的一般不会改变，除非系统内核升级 >
	 *
	 * @access protected
	 * @var bool
	 */
	static protected $_bWithInTheSystem=FALSE;

	/**
	 * 模板目录
	 *
	 * @access private
	 * @static
	 * @var string
	 */
	static private $_sTemplateDir;

	/**
	 * 当前模板变量
	 *
	 * @access private
	 * @var
	 */
	private $_arrVariable;

	/**
	 * 加入一个Template对象
	 *
	 * @access public
	 * @param $oTemplateObj TemplateObj Template对象
	 * @return void
	 */
	public function putInTemplateObj_(TemplateObj $oTemplateObj){}

	/**
	 * 清除已有的Template对象
	 *
	 * @access public
	 * @return int
	 */
	public function clearTemplateObj(){}

	/**
	 * 编译模版, 返回编译文件的路径
	 *
	 * @access public
	 * @param $sTemplatePath string 模版文件路径
	 * @param $sCompiledPath='' string 输出路径
	 * @return string
	 */
	public function compile($sTemplatePath,$sCompiledPath=''){}

	/**
	 * 分析模版前处理
	 *
	 * @access public
	 * @param $sCompiled string 分析原文
	 * @return void
	 */
	protected function bParseTemplate_(&$sCompiled){}

	/**
	 * 编译模板对象
	 *
	 * @access protected
	 * @reuturn string
	 */
	protected function compileTemplateObj(){}

	/**
	 * 通过缺省的方式 指定 编译输出路径
	 *
	 * @access public
	 * @param $sTemplatePath string 模版文件路径
	 * @return string
	 */
	public function getCompiledPath($sTemplatePath){}

	/**
	 * 设置缓存文件是否保存在系统，即在模板文件同级新建一个Compiled 文件
	 *
	 * @access public
	 * @param $sTemplatePath string 模版文件路径
	 * @return string
	 */
	static public function in($bWithInTheSystem=false){}

	/**
	 * 返回模板编译路径
	 *
	 * @access public
	 * @return string
	 */
	public function returnCompiledPath(){}

	/**
	 * 编译文件 是否过期（模版文件有改动）
	 *
	 * @access protected
	 * @param $sTemplatePath string 模版文件路径
	 * @param $sCompiledPath string 编译文件
	 * @return bool
	 */
	protected function isCompiledFileExpired($sTemplatePath,$sCompiledPath){}

	/**
	 * 生成编译文件
	 *
	 * @access protected
	 * @param $sTemplatePath string 模版路径
	 * @param $sCompiledPath string 编译文件路径
	 * @param &$sCompiled string 各个 TemplateObj编译内容
	 * @return void
	 */
	protected function makeCompiledFile($sTemplatePath,$sCompiledPath,&$sCompiled){}
	
	/**
	 * 设置一个模版文件存放目录
	 *
	 * @access public
	 * @param $sDir string 模版文件存放目录
	 * @static
	 * @return void
	 */
	static public function setTemplateDir($sDir){}

	/**
	 * 根据模版 文件名查找 文件
	 *
	 * @access public
	 * @param $arrTemplateFile array 模版文件名称
	 * @static
	 * @return string
	 */
	static public function findTemplate($arrTemplateFile){}

	/**
	 * 加入一个Template对象
	 *
	 * @access public
	 * @param $oTemplateObject TempateObj Template对象
	 * @return void
	 */
	public function putInTemplateObj(TemplateObj $oTemplateObj){}

	/**
	 * 分析模版前处理
	 *
	 * @access public
	 * @param $sCompiled string 分析原文
	 * @return void
	 */
	protected function bParseTemplate_(&$sCompiled){}

	/**
	 * 加载子模板
	 *
	 * @access public
	 * @param $TemplateFile string 模版文件的路径或名称
	 * @return void
	 */
	public function includeChildTemplate($sTemplateFile){}

	/**
	 * 显示模版
	 *
	 * @access public
	 * @param $sTemplateFile string 模版文件的路径或名称
	 * @param $bDisplayAtOnce=true bool 是否立即显示
	 * @return string
	 */
	public function display($TemplateFile,$bDisplayAtOnce=true){}

	/**
	 * 设置用户界面变量
	 *
	 * @access public
	 * @param $Name array|string 变量名称或包含所有变量的数组
	 * @param $Value mixed 变量值
	 * @return oldVar|null
	 */
	public function setVar($Name,$Value=null){}

	/**
	 * 获取变量值
	 *
	 * @access public
	 * @param $sName string 变量名字
	 * @return mixed
	 */
	public function getVar($sName){}

}
