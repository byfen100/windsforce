<?dyhb
/**
 * 缓存说明
 *
 * <!-- 关于serialize -->
 * <  自动序列化数据后再写入缓存，默认为 true
 *    可以很方便的缓存 PHP 变量值（例如数组），但要慢一点。>
 *
 */
class FileCache{

	/**
	 * 默认的缓存配置
	 *
	 * @var array
	 */
	protected $_arrOptions=array();

	/**
	 * 固定要写入缓存文件头部的内容
	 *
	 * @var string
	 */
	static protected $_sStaticHead='<?php die(); ?>';

	/**
	 * 固定头部的长度
	 *
	 * @var int
	 */
	static protected $_nStaticHeadLen=15;

	/**
	 * 构造函数
	 *
	 * @access public
	 * @param $arrOptions array|null 初始化配置
	 */
	public function __construct(array $arrOptions=null){}

	/**
	 * 检查一个缓存在给定时长是否过期
	 *
	 * @access public
	 * @param $sCacheName 缓存项目名字
	 * @param $arrOptions array|null 缓存配置
	 * @param $nTime 缓存时间，单位秒（s）,-1表示存在缓存即有效
	 * @return void
	 */
	public function checkCache($sCacheName,$arrOptions,$nTime=-1){}

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
	 * @param string $sData 缓存内容
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
	 * 取得变量的存储文件名
	 *
	 * @access protected
	 * @param  string $sCacheName 缓存变量名
	 * @param $arrOptions array|null 缓存配置
	 * @return string
	 */
	protected function getCacheFilePath($sCacheName,$arrOptions){}

	/**
	 * 写入文件数据到文件 < 缓存到文件  >
	 *
	 * @access public
	 * @param string $sFileName 文件名字
	 * @param string $sData 写入文件内容
	 * return int 文件大小
	 */
	 public function writeData($sFileName,$sData){}

}
