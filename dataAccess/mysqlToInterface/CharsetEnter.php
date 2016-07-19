<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	引入接口
require_once(BASE_DATAACCESS.DATA_INTER.'ICharsetEnter.php');
require_once(BASE_SYSTEM.'mysql.php');

//	实现字符集接口
class CharsetEnter implements ICharsetEnter{
//	数据库操作对象
	private $mysql;

	//	构造函数
	public function __construct(){
		$this->initialise();
	}

	//	适应低版本
	public function CharsetEnter(){
		$this->__construct();
	}
	
	//	初始化
	private function initialise(){
		$this->mysql = new MySQL();
	}
	
	/**
	 * 通过hostid判断charset是否存在
	 *
	 * @param   $hostid         int
	 * @return  true/false
	 * @update  2016-07-18
	 */
	public function isExistCharset($hostid){
		$sql = 'select id from v_db_charset where hostid='.$hostid;
		$id = $this->mysql->querySingle($sql);
		if($id > 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 通过hostid判断charset是否存在
	 *
	 * @param   $hostid         int
	 * @param   $charset        string
	 * @return  影响行数
	 * @update  2016-07-18
	 */
	public function addCharset($hostid,$charset){
		$sql = 'insert into v_db_charset (hostid,charset) values';
		$str = '(":hostid",":charset")';
		$arr = array
		(
				new MysqlParam(":hostid", $hostid),
				new MysqlParam(":charset", $charset)
		);
		$sql .= paramForm($str, $arr);
		$count = $this->mysql->exec($sql);
		return $count;
	}
}
?>