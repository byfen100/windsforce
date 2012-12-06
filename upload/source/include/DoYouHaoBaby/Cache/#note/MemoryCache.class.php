<?dyhb
class MemoryCache{

	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(){}

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
	 * @return string,bool
	 */
	public function setCache($sCacheName,$Data){}

	/**
	 * 删除一个缓存内容
	 *
	 * @access public
	 * @param $sCacheName 缓存名字
	 * @return bool
	 */
	public function deleleCache($sCacheName){}

}
