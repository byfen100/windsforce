<?dyhb
class View{

	/**
	 * Template控件
	 *
	 * @access private
	 * @var array
	 */
	private $_oPar=null;

	/**
	 * Template控件
	 *
	 * @access private
	 * @var array
	 */
	private $_oTemplate;

	/**
	 * 是否分享全局Template控件
	 *
	 * @access private
	 * @static
	 * @var object
	 */
	static private $_oShareGlobalTemplate;

	/**
	 * 模版名字
	 *
	 * @access private
	 * @var bool
	 */
	private $_sTemplate;

	/**
	 * 模板运行时间
	 *
	 * @access private
	 * @var int
	 */
	private $_nRuntime;

	/**
	 * 构造函数
	 *
	 * @access public
	 * @param $oPar 控制器
	 * @return void
	 */
	public function __construct($oPar=null,$sTemplate=null,$oTemplate=null){}

	/**
	 * 自动定位模板文件
	 *
	 * @access private
	 * @param string $sTemplateFile 文件名
	 * @return string
	 */
	public function parseTemplateFile($sTemplateFile){}

	/**
	 * 获取Template控件
	 *
	 * @access public
	 * @return Template
	 */
	public function getTemplate(){}

	/**
	 * 设置Template控件
	 *
	 * @access public
	 * @param $oTemplate Template对象
	 * @return oldValue
	 */
	public function setTemplate(Template $oTemplate){}

}
