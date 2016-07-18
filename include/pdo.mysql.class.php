<?php if(!defined('SUBGROUPS')) exit('System Error!');
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
	private $dbType;
	//	数据库名
	private $dbName;
	//	数据库用户名
	private $dbUser;
	//	数据库密码
	private $dbPassword;
	//	主机
	private $dbHost;
	//	连接字符串
	private $dbConn;
	//	是否需要开始SQL安全检查
	private $saveCheck;
	//	数据库连接对象
	private $pdo;

	//	构造函数
	function __construct(){
		$this->initialise();
	}

	//	供低版本PHP使用
	function MySQL(){
		$this->__construct();
	}
	
	//	初始化函数
	private function initialise(){
		$this->dbType     = $GLOBALS['db_type'];
		$this->dbName     = $GLOBALS['db_name'];
		$this->dbUser     = $GLOBALS['db_user'];
		$this->dbPassword = $GLOBALS['db_pwd'];
		$this->dbHost     = $GLOBALS['db_host'];
		$this->dbConn     = "$this->dbType:host=$this->dbHost;dbname=$this->dbName";
		
		$this->saveCheck = true;
		$this->pdo = null;
	}

	/*
	*
	* 打开数据库连接
	*
	*/	
	private function open(){

		global $cfg_errmode;   

		try{
			//	判断PHP是否支持PDO
			if(!class_exists('PDO')){
				$this->logError("not found PDO");
			}
			//	实例化对象
			$pdo = new PDO($this->dbConn,$this->dbUser,$this->dbPassword,array(PDO::ATTR_PERSISTENT => true));	

			switch($cfg_errmode)
			{
				case 'EXCEPTION':
					$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	//	异常模式
					break;
				case 'SILENT':
					$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);		//	默认模式
					break;
				case 'WARNING':
					$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);		//	警告模式
					break;
				default:
					$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
					break;
			}
			
			if(!$pdo){
				$this->logError("PDO连接失败！");
			}
			$this->pdo = $pdo;
		}catch(PDOException $e){
			$errLog  = 'PDO Exception Caught.Error with the database:<br/>';
			$errLog .= 'SQL connection:'.$this->dbConn.'|User:'.$this->dbUser.'|Password:'.$this->dbPassword;
			$errLog .= 'errorInfo:'.$e->getMessage().'<br/>';
			$errLog .= 'code:'.$e->getCode().'<br/>';
			$errLog .= 'file:'.$e->getFile().'<br/>';
			$errLog .= 'line:'.$e->getLine().'<br/>';
			$errLog .= 'trace:'.$e->getTraceAsString().'<br/>';
			$this->logError($errLog);
		}
	}

	/*
	*
	* 关闭数据库连接
	*
	*/	
	private function close(){
		$this->pod = null;
	}

	/*
	*
	* 执行查询语句，返回全部数据
	*
	* @param  string  $sql  查询语句
	* @param  array   $param  参数
	* @param  int     $i    返回结果集方式
	*/	
	public function query($sql,$param=array(),$i=0){
		try{
			if($this->pdo == null){
				$this->open();
			}
			if($this->saveCheck){
				$sql = $this->checkSQL($sql);
			}
			$data = $this->pdo->prepare($sql);					//	准备查询语句
			$data->execute($param);								//	执行查询语句，并返回结果集
			$res = $data->fetchAll($i);
			// $errCode = $data->errorCode();
			// if(empty($errCode)){
			// 	return $res;
			// }else{
			// 	$this->logError("SQL query:".$errCode."|".$sql);
			// 	return null;
			// }
			return $res;
		}catch(PDOException $e){
			$errLog  = 'PDO Exception Caught.Error with the database:<br/>';
			$errLog .= 'SQL query:'.$sql;
			$errLog .= 'errorInfo:'.$e->getMessage().'<br/>';
			$errLog .= 'code:'.$e->getCode().'<br/>';
			$errLog .= 'file:'.$e->getFile().'<br/>';
			$errLog .= 'line:'.$e->getLine().'<br/>';
			$errLog .= 'trace:'.$e->getTraceAsString().'<br/>';
			$this->logError($errLog);
		}
    }

    /*
	*
	* 执行查询语句，返回一行数据
	*
	* @param  string  $sql  查询语句
	* @param  array   $param  参数
	* @param  int     $i    返回结果集方式
	*/	
	public function queryOne($sql,$param=array(),$i=0){
		try{
			if($this->pdo == null){
				$this->open();
			}
			if($this->saveCheck){
				$sql = $this->checkSQL($sql);
			}
			$data = $this->pdo->prepare($sql);					//	准备查询语句
			$data->execute($param);	 							//	执行查询语句，并返回结果集
			$res = $data->fetch($i);
			return $res;
		}catch(PDOException $e){
			$errLog  = 'PDO Exception Caught.Error with the database:<br/>';
			$errLog .= 'SQL queryOne:'.$sql;
			$errLog .= 'errorInfo:'.$e->getMessage().'<br/>';
			$errLog .= 'code:'.$e->getCode().'<br/>';
			$errLog .= 'file:'.$e->getFile().'<br/>';
			$errLog .= 'line:'.$e->getLine().'<br/>';
			$errLog .= 'trace:'.$e->getTraceAsString().'<br/>';
			$this->logError($errLog);
		}
    }

	/*
	*
	* 执行查询语句，返回单个数据
	*
	* @param  string  $sql  查询语句
	* @param  array   $param  参数
	*/	
	public function querySingle($sql,$param=array()){
		try{
			if($this->pdo == null){
				$this->open();
			}
			if($this->saveCheck){
				$sql = $this->checkSQL($sql);
			}
			$data = $this->pdo->prepare($sql);				
			$data->execute($param);	 							
			$res = $data->fetchColumn(0);
			return $res;
		}catch(Exception $e){
			$errLog  = 'PDO Exception Caught.Error with the database:<br/>';
			$errLog .= 'SQL querySingle:'.$sql;
			$errLog .= 'errorInfo:'.$e->getMessage().'<br/>';
			$errLog .= 'code:'.$e->getCode().'<br/>';
			$errLog .= 'file:'.$e->getFile().'<br/>';
			$errLog .= 'line:'.$e->getLine().'<br/>';
			$errLog .= 'trace:'.$e->getTraceAsString().'<br/>';
			$this->logError($errLog);
		}
	} 

	/*
	*
	* 执行SQL语句(INSERT、DELETE和UPDATE)，返回受影响的行数
	*
	* @param  string  $sql  执行SQL语句
	*/	
	public function exec($sql){
		try{
			if($this->pdo == null){
				$this->open();
			}
			if($this->saveCheck){
				$sql = $this->checkSQL($sql);
			}
			$count = $this->pdo->exec($sql);				
			return $count;
		}catch(Exception $e){
			$errLog  = 'PDO Exception Caught.Error with the database:<br/>';
			$errLog .= 'SQL querySingle:'.$sql;
			$errLog .= 'errorInfo:'.$e->getMessage().'<br/>';
			$errLog .= 'code:'.$e->getCode().'<br/>';
			$errLog .= 'file:'.$e->getFile().'<br/>';
			$errLog .= 'line:'.$e->getLine().'<br/>';
			$errLog .= 'trace:'.$e->getTraceAsString().'<br/>';
			$this->logError($errLog);
		}
	} 

    /*
	*
	* SQL语句过滤程序
	*
    */
    private function checkSQL($sql, $querytype='select'){

        $clean   = '';
        $error   = '';
		$pos     = -1;
        $old_pos = 0;


        //	如果是普通查询语句，直接过滤一些特殊语法
        if($querytype == 'select'){
            if(preg_match('/[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}/', $sql)){
				$this->logError("$sql||SelectBreak",1);
            }
        }

        //	完整的SQL检查
        while(true){
            $pos = strpos($sql, '\'', $pos + 1);
            if($pos === false){
                break;
            }
            $clean .= substr($sql, $old_pos, $pos - $old_pos);

            while(true){
                $pos1 = strpos($sql, '\'', $pos + 1);
                $pos2 = strpos($sql, '\\', $pos + 1);
                if($pos1 === false){
                    break;
                }
                else if($pos2 == false || $pos2 > $pos1){
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }

            $clean .= '$s$';
            $old_pos = $pos + 1;
        }

        $clean .= substr($sql, $old_pos);
        $clean  = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

        //	老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
        if(strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0){
            $fail  = true;
            $error = 'union detect';
        }

        //	发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们
        else if(strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false){
            $fail  = true;
            $error = 'comment detect';
        }

        //	这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
        else if(strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0){
            $fail  = true;
            $error = 'slown down detect';
        }
        else if(strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0){
            $fail  = true;
            $error = 'slown down detect';
        }
        else if(strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0){
            $fail  = true;
            $error = 'file fun detect';
        }
        else if(strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0){
            $fail  = true;
            $error = 'file fun detect';
        }

        //	老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息
        else if(preg_match('~\([^)]*?select~s', $clean) != 0){
            $fail  = true;
            $error = 'sub select detect';
        }

        if(!empty($fail)){
			$this->logError("$sql,$error",1);
        }
        else{
            return $sql;
        }
    }

	/*
	 * 记录数据链接错误信息
	 *
	 * @param  string  $msg  错误信息
	 * @param  int     $t    错误类型
	 */
    private function logError($msg,$t=0){

    	global $cfg_diserror;

    	//向浏览器输出错误
		switch($t)
		{
			case 0:
			$title = '安全警告：MySql Error！';
			break;
			case 1:
			$title = '安全警告：请检查您的SQL语句是否合法，您的操作将被强制停止！';
			break;
			default;
		}

		$str  = '<div style="font-family:\'微软雅黑\';font-size:12px;">';
		$str .= '<h3 style="margin:0;padding:0;line-height:30px;color:red;">'.$title.'</h3>';
        $str .= '<strong>错误文件</strong>：'.GetCurUrl().'<br />';
        $str .= '<strong>错误信息</strong>：'.$msg.'';
        $str .= '</div>';

        //	判断是否显示错误
        if($cfg_diserror == 'Y'){
        	echo $str;
        }

		//	保存MySql错误日志
		$userIP  = GetIP();
		$getUrl  = GetCurUrl();
		$getTime = GetDateTime(time());
		$logFile = BASE_PATH.'/data/error/mysql_error_pdo_log.php';

		$savemsg = '<?php exit(); ?> Time: '.$getTime.'. || File: '.$getUrl.' || IP: '.$userIP.' || ErrorInfo: '.$msg."\r\n";
        Writef($logFile, $savemsg, 'a+');
    }
}

class MysqlParam
{
	var $key;
	var $value;

	//	构造函数
	function __construct($key,$value){
		$this->key = $key;
		$this->value = $value;
	}

	//	供低版本PHP使用
	function MysqlParam($key,$value){
		$this->key = $key;
		$this->value = $value;
	}
}

?>