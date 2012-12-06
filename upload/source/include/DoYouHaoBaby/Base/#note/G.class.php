<?dyhb
class G{

	/**
	 * 启动一个验证码
	 *
	 * @access public
	 * @static
	 * @param array|array $arrOption 验证码配置条件
	 * @param boolean $bChinesecode=false 是否使用中文验证码
	 * @param int $nSeccodeTupe=1 设置创建验证码文字的方式 1：基于字体来创建 2：基于图片来创建文字
	 * @return string
	 */
	public static function seccode($arrOption=null,$bChinesecode=false,$nSeccodeTupe=1){}

	/**
	 * 全局变量统一处理
	 *
	 * @access public
	 * @static
	 * @param string $sKey 键值
	 * @param string $Var 类型
	 * @return string
	 */
	static function getGpc($sKey,$sVar='R'){}

	/**
	 * 去除转义字符（递归）
	 *
	 * @access public
	 * @static
	 * @param $sString string|array  待操作的数据
	 * @param $bRecursive bool 是否递归
	 * @return mixed string|array
	 */
	static public function stripslashes($String,$bRecursive=true){}

	/**
	 * 如果magic为开启状态，转义（递归）
	 *
	 × < 注意：系统对POST数据进行了自动转义，这里仅针对非POST发送的数据进行转义处理 >
	 *
	 * @access public
	 * @static
	 * @param string|array $String 待过滤的数据
	 * @param $bRecursive bool 是否递归
	 * @return mixed string|array
	 */
	static public function addslashes($String,$bRecursive=true){}

	/**
	 * 判断Magic的状态
	 *
	 * @access public
	 * @static
	 * @return bool
	 */
	static public function getMagicQuotesGpc(){}

	/**
	 * 判断变量的类型
	 *
	 * < PHP的原生函数is_a()只判断对象，本函数将所有类型和类(class)同等看待 >
	 * < 如果类型是类名，则以is_a判断 >
	 *
	 * @access public
	 * @param mixed $Var 待检查的变量名
	 * @param string $sType 类型
	 * @static
	 * @return void
	 */
	static public function varType($Var,$sType){}

	/**
	 * URL重定向
	 *
	 * @access public
	 * @static
	 * @param string $sUrl 跳转的URL
	 * @param int $nTime 等待时间秒
	 * @param string $sMsg 跳转消息
	 * @return void
	 */
	static public function urlGoTo($sUrl,$nTime=0,$sMsg=''){}

	/**
	 * 产生乱数字串
	 *
	 * @access public
	 * @param int $nLength 字串长度
	 * @param string|null $sCharBox=null 可用字符
	 * @param bool $bNumeric=false 是否限定数字
	 * @static
	 * @return string
	 */
	 static public function randString($nLength,$sCharBox=null,$bNumeric=false){}

	/**
	 * 返回当前时间（微秒级）
	 *
	 * @access public
	 * @param bool $bExact=true 精确到微秒，该参数false的话，等同 time()
	 * @static
	 * @return float|int
	 */
	static public function now($bExact=true){}

	/**
	 * 判断变量 是否符合给定的类型
	 *
	 * < 可以给入多种类型，变量只需符合其中之一。 >
	 * < 如果类型是类名，则以is_a判断 >
	 *
	 * @access public
	 * @param mixed $Var 待检查的变量名
	 * @param string|array $Types 必须符合的各项类型
	 * @static
	 * @return void
	 */
	static public function isThese($Var,$Types){}

	/**
	 * 递归地检查给定的两个类(接口)之间的继承关系。(严格检查)
	 *
	 * < 如果参数$subClass_instance和$baseClass相同，仍然返回true，表示"is_kind_of"关系成立。>
	 * < 如果$subClass_instance或$baseClass传入不存在的类名，简单的返回false，而不会抛出异常，
	 *   或进行更严重的错误处理。>
	 * < php的原生函数is_subclass_of()只对直接继承提供判断，本函数是对is_subclass_of()的加强。>
	 * < 注：is_subclass_of()在判断直接继承时，仍然有用。>
	 *
	 * @access public
	 * @static
	 * @param string|object $SubClass 子类，或子类的一个实例
	 * @param string $sBaseClass 父类
	 * @return bool
	 */
	static public function isKindOf($SubClass,$sBaseClass){}

	/**
	 * 检查一个类或对象是否实现了某个接口
	 *
	 * @access public
	 * @static
	 * @param string|object $Class 待检查的类或对象
	 * @param string $sInterface 应实现的接口名
	 * @param bool $bStrictly=false 是否严格检查所有的接口的所有方法均已实现（非抽象方法）
	 * @return bool
	 */
	static public function isImplementedTo($Class,$sInterface,$bStrictly=false){}

	/**
	 * 以严格的方式检查数组
	 *
	 * @access public
	 * @param array $arrArray 待检查的数组
	 * @param array $arrTypes 类型
	 * @static
	 * @return void
	 */
	static public function checkArray($arrArray,array $arrTypes){}

	/**
	 * 创建目录
	 *
	 * @access public
	 * @static
	 * @param string $Dir 需要创建的目录名称
	 * @param int $nMode 权限
	 * @return true
	 */
	static public function makeDir($Dir,$nMode=0777){}

	/**
	 * 返回 路径$sFromPath 到 路径$sToPath 的相对路径
	 *
	 * @access public
	 * @param $sFromPath string 开始路径
	 * @param $sToPath string 目标路径
	 * @static
	 * @return string,null
	 */
	static public function getRelativePath($sFromPath,$sToPath){}

	/**
	 * 转换附件大小单位
	 *
	 * @access public
	 * @static
	 * @access public
	 * @param string $nFileSize 文件大小 kb
	 * @return string 文件大小
	 */
	static public function changeFileSize($nFileSize){}

	/**
	 * 获取页面当前时间
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	static public function getMicrotime(){}

	/**
	 * 判断一个数组是否为一维数组
	 *
	 * @access public
	 * @param array $arrArray 待分析的数组
	 * @static
	 * @return bool
	 */
	static public function oneImensionArray($arrArray){}

	/**
	 * 获得用户的真实IP地址
	 *
	 * @access  public
	 * @return  string
	 */
	static public function getIp(){}

	/**
	 * DISCUZ 加密函数
	 * 
	 *  < @example
	 *    $a = authcode('abc', 'ENCODE', 'key');
	 *    $b = authcode($a, 'DECODE', 'key');  // $b(abc)
	 *    $a = authcode('abc', 'ENCODE', 'key', 3600);
	 *    $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空 >
	 *
	 * @access public
	 * @param string $string 原文或者密文
	 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
	 * @param string $key 密钥
	 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
	 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
	 */
	static public function authcode($string,$operation=TRUE,$key=null,$expiry=3600){}

	/**
	 *  将含有单位的数字转成字节
	 *
	 * @access public
	 * @param string $sVal 带单位的数字
	 * @return int $sVal
	 */
	static public function returnBytes($sVal){}

	/**
	 * 遍历指定目录中全部一级目录
	 *
	 * @access public
	 * @static
	 * @param string $sDir 文件夹路径
	 * @return array $arrFiles 文件目录的数组
	 * @return array
	 */
	static public function listDir($sDir,$bFullPath=FALSE){}

	/**
	 * 判断类是否存在静态函数
	 *
	 * @access public
	 * @static
	 * @param $sClassName string 类名字
	 * @param $sMethodName string 方法名
	 * @return bool
	 */
	 public static function hasStaticMethod($sClassName,$sMethodName){}

	/**
	 * 序列化失败解决方法 （UTF-8） http://be-evil.org/post-140.html
	 *
	 * @access public
	 * @static
	 * @param string $sSerial 序列化的字符串
	 * @return void
	 */
	static public function mbUnserialize($sSerial){}

	static public function getAvatar($nUid,$sSize='middle'){}

	/**
	 * 取得扩展名
	 *
	 * @access public
	 * @param $sFileName string 文件名
	 * @param $nCase 返回类型 1：大写 2：小写 3：直接返回
	 * @static
	 * @return string
	 */
	static public function getExtName($sFileName,$nCase=0){}

}
