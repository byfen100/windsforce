<?dyhb
class ShmopCache extends DCache{

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

	/**
	 * 生成IPC key
	 *
	 * @access private
	 * @param string $sProject 项目标识名
	 * @return integer
	 */
	private function ftok($sProject){}

	/**
	 * 写入操作
	 *
	 * @access private
	 * @param string $sVal 待缓存的变量名字
	 * @param handel $hLH 句柄
	 * @return integer|boolen
	 */
	private function write($sVal,$hLH){}

	/**
	 * 共享锁定
	 *
	 * @access private
	 * @return boolen
	 */
	private function lock(){}

	/**
	 * 解除共享锁定
	 *
	 * @access private
	 * @param string $hFp 句柄
	 * @return boolen
	 */
	private function unLock($hFp){}

}
