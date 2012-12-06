<?dyhb
class ApachenoteCache{

	/**
	 * 默认的缓存配置
	 *
	 * @var array
	 */
	protected $_arrOptions=array();

	/**
	 * 句柄
	 *
	 * @var bool
	 */
	protected $_hHandel=null;

	/**
	 * 是否连接成功
	 *
	 * @var bool
	 */
	protected $_bConnected=false;

	/**
	 * 构造函数
	 *
	 * @access public
	 * @param $arrOptions array|null 初始化配置
	 */
	public function __construct(array $arrOptions=null){}

	/**
	 * 是否连接
	 *
	 * @access public
	 * @return boolen
	 */
	public function isConnected(){}

	/**
	 * 获取一个缓存内容，过期或者不存在返回NULL
	 *
	 * @access public
	 * @param $sCacheName string 缓存项目名字
	 * @param $arrOptions array|null 缓存配置
	 * @return string,null
	 */
	public function getCache($sCacheName,array $arrOptions=null){}

	/**
	 * 设置一个缓存内容
	 *
	 * @access public
	 * @param string $sCacheName 缓存名字
	 * @param mixed $Data 缓存内容
	 * @param $arrOptions array|null 缓存配置
	 * @return string,bool
	 */
	public function setCache($sCacheName,$Data,array $arrOptions=null){}

	/**
	 * 删除一个缓存内容
	 *
	 * @access public
	 * @param $sCacheName 缓存名字
	 * @param $arrOptions array|null 缓存配置
	 * @return bool
	 */
	public function deleleCache($sCacheName,array $arrOptions=null){}

	/**
	 * 打开缓存
	 *
	 * @param $arrOptions array 配置
	 * @access protected
	 */
	protected function open($arrOptions=array()){}

	/**
	 * 关闭缓存
	 *
	 * @access protected
	 */
	protected function close(){}

}
