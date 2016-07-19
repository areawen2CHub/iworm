<?php if(!defined('SUBGROUPS')) exit('System Error!');

require_once(BASE_DATAACCESS.DATA_INTER.'IKeywordsEnter.php');		
require_once(BASE_SYSTEM.'mysql.php');									

//	实现关键字接口
class KeywordsEnter implements IKeywordsEnter{
	//	数据库操作对象
	private $mysql;
	
	//	构造函数
	public function __construct(){
		$this->initialise();
	}
	
	//	适应低版本
	public function KeywordsEnter(){
		$this->__construct();
	}
	
	//	初始化函数
	private function initialise(){
		$this->mysql = new MySQL();
	}
	
	/**
	 * 获取当前时间前24小时4个热门关键字
	 * 
	 * @param	无
	 * @return	array
	 * @update	2016-07-18
	 */
	public function getKeywordsNow(){
		// 获取当前时间的前24小时
		$time = GetMkTime(time())-24*3600;
		$sql = 'select keyword from v_db_keywords WHERE inittime>:inittime order by seacount desc limit 0,4';
		$arr = array(':inittime'=>$time);
		//	获取关键字数据集，并以数字索引形式返回
		$keywords = $this->mysql->query($sql,$arr,3);
		return $keywords;
	}
	
	/**
	 * 获取当前时间前24小时内的关键字
	 *
	 * @param 	无
	 * @return	bool	true/fasle
	 * @update	2016-07-19
	 */
	public function getHotKeywords(){
		// 获取当前时间的前24小时
		$time = GetMkTime(time())-24*3600;
		$sql = 'select keyword from v_db_keywords WHERE inittime>:inittime order by seacount';
		$arr = array(':inittime'=>$time);
		//	获取关键字数据集，并以数字索引形式返回
		$keywords = $this->mysql->query($sql,$arr,3);
		return $keywords;
	}
}
?>