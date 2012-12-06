<?dyhb
class XcacheCache{

	/**
	 * 默认的缓存配置
	 *
	 * @var array
	 */
	protected $_arrOptions=array();

	/**
	 * 构造函数
	 *
	 * @access public
	 * @param $arrOptions array|null 初始化配置
	 */
	public function __construct(array $arrOptions=null){}

	/**
	 * 获取一个缓存内容，过期或者不存在返回NULL
	 *
	 * @access public
	 * @param $sCacheName string 缓存项目名字
	 * @return string,null
	 */
	public function getCache($sCacheName){}

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
	 * @return bool
	 */
	public function deleleCache($sCacheName){}

}
