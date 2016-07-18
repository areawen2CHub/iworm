<?php if(!defined('SUBGROUPS')) exit('System Error!');

require_once(BASE_DATAACCESS.DATA_INTER.'IUrlEnter.php');		
require_once(BASE_SYSTEM.'mysql.php');	

class UrlEnter implements IUrlEnter{
	//	数据库操作对象
	private $mysql;
	
	//	构造函数
	public function __construct(){
		$this->initialise();
	}
	
	//	适应低版本
	public function UrlEnter(){
		$this->__construct();
	}
	
	//	初始化函数
	private function initialise(){
		$this->mysql = new MySQL();
	}
	
	/**
	 * 获取查询url对象列表
	 * 
	 * @param	无
	 * @return  $arr       array  对象数组
	 * @update  2016-07-18
	 */
	public function getSearchUrl(){
		$sql = 'select id,hostid,url from vi_url limit 0,100';
		$searchUrl = $this->mysql->query($sql);
		return $searchUrl;
	}
	
	/**
	 * 记录本次访问
	 *
	 * @param	int		$id
	 * return	$count
	 * @update  2016-07-18
	 */
	public function recordVisit($id){
		$sql = 'update v_db_url set incstate="true", viscount=viscount+1, lasttime=neartime, neartime="'.GetMkTime(time()).'" where id='.$id;
		$count = $this->mysql->exec($sql);
		return $count;
	}
	
	/**
	 * 记录该url抓取为空
	 *
	 * @param	int		$id
	 * return	$count
	 * @update  2016-07-18
	 */
	public function isEmptyUrl($id){
		$sql = 'update v_db_url set isempty="true" where id='.$id;
		$count = $this->mysql->exec($sql);
		return $count;
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
	
	/**
	 * 判断url是否已收录
	 *
	 * @param unknown $url
	 * @return true/false
	 * @update 2016-07-18
	 */
	public function isExistUrl($url){
		$sql = 'select id from v_db_url where url='.$url.' limit 0,1';
		$id = $this->mysql->querySingle($sql);
		if($id > 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 增加url收录次数
	 *
	 * @param  string $url
	 * @return 影响行数
	 * @update 2016-07-18
	 */
	public function addUrlIncCount($url){
		$sql = 'update v_db_url set inccount=inccount+1 WHERE url="'.$url.'"';
		$count = $this->mysql->exec($sql);
		return $count;
	}
	
	/**
	 * 增加url
	 *
	 * @param  obj $urlObj
	 * @return 影响行数
	 * @update 2016-07-18
	 */
	public function addUrl(urlEntity $urlObj){
		$sql = 'insert into v_db_url (hostid,parenturl,url,createtime,inccount) values';
		$str = '(":hostid",":parenturl",":url","'.GetMkTime(time()).'",1)';
		$arr = array
		(
			new MysqlParam(":hostid", $urlObj->hostId),
			new MysqlParam(":parenturl", $urlObj->parentUrl),
			new MysqlParam(":url", $urlObj->url)
		);
		$sql .= paramForm($str, $arr);
		$count = $this->mysql->exec($sql);
		return $count;
	}
}
?>