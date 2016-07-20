<?php if(!defined('SUBGROUPS')) exit('System Error!');

require_once(BASE_DATAACCESS.DATA_INTER.'IHostEnter.php');		
require_once(BASE_SYSTEM.'mysql.php');	
require_once(BASE_HELPR.'enumHelpr.php');

class HostEnter implements IHostEnter{
	//	数据库操作对象
	private $mysql;
	//	是否使用
	//private $enumHelpr;
	
	//	构造函数
	public function __construct(){
		$this->initialise();
	}
	
	//	适应低版本
	public function HostEnter(){
		$this->__construct();
	}
	
	//	初始化函数
	private function initialise(){
		$this->mysql = new MySQL();
		//$this->enumHelpr = new enumHelpr();
	}
	
	/**
	 * 插入主机
	 *
	 * @param	$hostName		string
	 * @param	$hostip			string
	 * return	$count			int
	 * update	2016-07-18
	 */
	public function addHost($hostName,$hostip){
		$sql = 'insert into v_db_host (hostname,hostip,inittime,isusing,urlcount,inccount,neartime) values';
		$str = '(":hostname",":hostip","'.GetMkTime(time()).'",'.enumIsUsing::isUsing.',1,1,"'.GetMkTime(time()).'")';
		$arr = array
		(
			new MysqlParam(":hostname", $hostName),
			new MysqlParam(":hostip", $hostip)
		);
		$sql .= paramForm($str, $arr);
		$count = $this->mysql->exec($sql);
		return $count;
	}
	
	/**
	 * 判断主机是否存在
	 *
	 * @param	$hostName		string
	 * return	true/false			int
	 * update	2016-07-18
	 */
	public function isExistHost($hostName){
		$sql = 'select id from v_db_host where hostname="'.$hostName.'" limit 0,1';
		$id = $this->mysql->querySingle($sql);
		if($id > 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 更新主机信息
	 *
	 * @param	$hostName		string
	 * return	true/false			int
	 * update	2016-07-18
	 */
	public function updateHost($hostName){
		$sql = 'update v_db_host set urlcount=urlcount+1,neartime="'.GetMkTime(time()).'" WHERE hostname="'.$hostName.'"';
		$count = $this->mysql->exec($sql);
		return $count;
	}
	
	/**
	 * 获取主机ID
	 *
	 * @param	$hostName		string
	 * return	$hostId			int
	 * update	2016-07-18
	 */
	public function getHostId($hostName){
		$sql = 'select id from v_db_host where hostname="'.$hostName.'" limit 0,1';
		$hostId = $this->mysql->querySingle($sql);
		if($hostId > 0){
			return $hostId;
		}else{
			return -1;
		}
	}
	
	/**
	 * 获取待抓取的主机
	 *
	 * @return 主机对象数据
	 * @update 2016-07-20
	 */
	public function getSearchHost(){
		//	获取待抓取的主机
		$sql = 'select id,hostname from v_db_host where isusing='.enumIsUsing::isUsing.' and neartime<'.(GetMkTime(time()) - 7*24*360).' limit 0,1';
		$hostObj = $this->mysql->query($sql);
		return $hostObj;
	}
	
	/**
	 * 记录对主机的访问
	 * 
	 * @param	string	$hostName
	 * @return	int		$count
	 * @update	2016-07-20
	 */
	public function recordVisitHost($hostName){
		$sql = 'update v_db_host set neartime='.GetMkTime(time()).' where hostname="'.$hostName.'"';
		$count = $this->mysql->exec($sql);
		if($count > 0){
			return $count;
		}else{
			return -1;
		}
	}
}