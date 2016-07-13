<?php   
/*
 * 数据库类
 * 
 * 调用这个类前,请先设定这些外部变量
 * $GLOBALS['db_host'];
 * $GLOBALS['db_user'];
 * $GLOBALS['db_pwd'];
 * $GLOBALS['db_name'];
 * $GLOBALS['db_tablepre'];
 *
 * 在系统所有文件中不需要单独初始化本类
 * 可直接用 $mysql进行操作
 * 为了防止错误，操作完后不必关闭数据库
 */

$mysql = new MySQL();

class MySQL
{
	//	数据库类型
	var $dbType;
	//	数据库名
	var $dbName;
	//	数据库用户名
	var $dbUser;
	//	数据库密码
	var $dbPassword;
	//	主机
	var $dbHost;
	//	连接字符串
	var $dbConn;

	//	构造函数
	function __construct(){
		$this->dbType     = $GLOBALS['db_type'];
		$this->dbName     = $GLOBALS['db_name'];
		$this->dbUser     = $GLOBALS['db_user'];
		$this->dbPassword = $GLOBALS['db_pwd'];
		$this->dbHost     = $GLOBALS['db_host'];
		$this->dbConn     = "$this->dbType:host:$this->dbHost;dbname=$this->dbName";
	}

	//	供低版本PHP使用
	function MySQL(){
		$this->dbType     = $GLOBALS['db_type'];
		$this->dbName     = $GLOBALS['db_name'];
		$this->dbUser     = $GLOBALS['db_user'];
		$this->dbPassword = $GLOBALS['db_pwd'];
		$this->dbHost     = $GLOBALS['db_host'];
		$this->dbConn     = "$this->dbType:host=$this->dbHost;dbname=$this->dbName";
	}

	/*
	*
	* 打开数据库
	*
	*/	
	function open(){
		try{
			$pdo = new PDO($this->dbConn,$this->dbUser,$this->dbPassword);	//	实例化对象
			print_r($pdo);
			if(is_null($pdo)){
				echo '空';
			}else{
				echo '不为空';
			}
			return $pdo;
		}catch(Exception $e){
			$this->logError("$this->dbConn:连接数据库失败，可能密码不对或数据库服务器异常！");
		}
	}

	/*
	*
	* 执行查询语句，返回数据据集
	*
	* @param  string  $sql  查询语句
	*/	
	function query($sql){
	try{
		$pdo = $this->open();
		$sql = $sql;
		$data = $pdo->prepare($sql);			//	准备查询语句
		$data->execute();						//	执行查询语句，并返回结果集
		$res = $data->fetchAll();
		return $res;
	}catch(Exception $e){
		$this->logError("$sql:查询语句异常！");
	}
}

	/*
	 * 记录数据链接错误信息
	 *
	 * @param  string  $msg  错误信息
	 * @param  int     $t    错误类型
	 */
    function logError($msg,$t=0)
    {
		//	保存MySql错误日志
		$userIP  = GetIP();
		$getUrl  = GetCurUrl();
		$getTime = GetDateTime(time());
		$logFile = BASE_PATH.'/data/error/mysql_error_log.php';

		$savemsg = '<?php exit(); ?> Time: '.$getTime.'. || Page: '.$getUrl.' || IP: '.$userIP.' || Error: '.$msg."\r\n";
        Writef($logFile, $savemsg, 'a+');
    }
}

?>