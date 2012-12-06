<?dyhb
/**
 *  分页类 < 本类可以被继承 >
 *
 *　<!-- 分页方法 -->
 *  < $oPage = Page::RUN( 1000,5,$_GET['page'],'page={page}' );
 *    $oPage = Page::RUN( 1000,5,$_GET['page'],'list-{page}.html' );
 *    $oPage -> P(); >
 */
class Page{

	/**
	 * 总记录数
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nCount;

	/**
	 * 每页记录数
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nSize;

	/**
	 * 当前页
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nPage;

	/**
	 * 当前页开始记录
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nPageStart;

	/**
	 * 跳转额外参数
	 *
	 * @access protected
	 * @var string
	 */
	protected $_sParameter;

	/**
	 * 总页数
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nPageCount;

	/**
	 * 起始页
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nPageI;

	/**
	 * 结束页
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nPageUb;

	/**
	 * 限制
	 *
	 * @access protected
	 * @var int
	 */
	protected $_nPageLimit;

	/**
	 * 是否显示页面跳转
	 *
	 * @access protected
	 * @var bool
	 */
	protected $_bPageSkip = false;

	/**
	 * 认证器对象实例
	 *
	 * @access protected
	 * @var self
	 */
	protected static $_oDefaultDbIns=null;

	/**
	 * 构造函数(私有)
	 *
	 * @access protected
	 * @param $nCount  int 总数
	 * @param $nSize  int 每页数
	 * @param $nPage  int 当前page页面
	 * @return void
	 */
	protected function __construct($nCount=0,$nSize=1,$nPage=1){}

	/**
	 * 创建一个分页实例
	 *
	 * @access public
	 * @static
	 * @param $nCount int 总数
	 * @param $nSize int 每页数
	 * @param $nPage int 当前page页面
	 * @param $bDefaultIns bool 是否强制新创建一个验证器
	 * @return Page
	 */
	public static function RUN($nCount=0,$nSize=1,$nPage=1,$bDefaultIns=true){}

	/**
	 * 输出
	 *
	 * @access public
	 * @param mixed $Id 页面HTML Id值
	 * @param string $sTyle 风格样式
	 * @param string $sCurrent 选择样式
	 * @param string $sDisabled 不可以用样式
	 * @return string
	 */
	public function P($Id='pagenav',$sTyle='span',$sCurrent='current',$sDisabled='disabled'){}

	/**
	 * 是否显示页面跳转
	 *
	 * @access public
	 * @param bool $bPageSkip =true
	 * @return oldValue
	 */
	public function setPageSkip($bPageSkip=true){}

	/**
	 * 设置URL额外参数
	 *
	 * @access public
	 * @param string $sParameter 额外参数
	 * @return oldValue
	 */
	public function setParameter($sParameter=''){}

	/**
	 * 总记录数
	 *
	 * @access public
	 * @return int
	 */
	public function returnCount(){}

	/**
	 * 每页数量
	 *
	 * @access public
	 * @return int
	 */
	public function returnSize(){}

	/**
	 * 当前页数
	 *
	 * @access public
	 * @return int
	 */
	public function returnPage(){}

	/**
	 * 当前页码开始记录数
	 *
	 * @access public
	 * @return int
	 */
	public function returnPageStart(){}

	/**
	 * 总页数
	 *
	 * @access public
	 * @return int
	 */
	public function returnPageCount(){}

	/**
	 * 起始页
	 *
	 * @access public
	 * @return int
	 */
	public function returnPageI(){}

	/**
	 * 结束页
	 *
	 * @access public
	 * @return int
	 */
	public function returnPageUb(){}

	/**
	 * 限制
	 *
	 * @access public
	 * @return int
	 */
	public function returnPageLimit(){}

	/**
	 * 判断是否为数字
	 *
	 * @access private
	 * @param int $Id
	 * @return int
	 */
	private function numeric($Id){}

	/**
	 * 地址替换
	 *
	 * @access private
	 * @param int $nPage Page页面
	 * @return string
	 */
	private function pageReplace($nPage){}

	/**
	 * 首页
	 *
	 * @access private
	 * @param string $sTyle 风格样式
	 * @return string
	 */
	private function home($sTyle='span'){}

	/**
	 * 上一页
	 *
	 * @access protected
	 * @param string $sTyle 风格样式
	 * @return string
	 */
	protected function prev($sTyle='span'){}

	/**
	 * 下一页
	 *
	 * @access protected
	 * @param string $sTyle 风格样式
	 * @return string
	 */
	protected function next($sTyle='span'){}

	/**
	 * 尾页
	 *
	 * @access protected
	 * @param string $sTyle 风格样式
	 * @return void
	 */
	protected function last($sTyle='span'){}

}
