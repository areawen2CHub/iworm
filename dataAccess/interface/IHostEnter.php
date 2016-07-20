<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	主机接口
interface IHostEnter{
	/**
	 * 插入主机
	 *
	 * @param	$hostName		string
	 * @param	$hostip			string
	 * return	$count			int
	 * update	2016-07-18
	 */
	public function addHost($hostName,$hostip);
	
	/**
	 * 判断主机是否存在
	 *
	 * @param	$hostName		string
	 * return	true/false			int
	 * update	2016-07-18
	 */
	public function isExistHost($hostName);
	
	/**
	 * 更新主机信息
	 *
	 * @param	$hostName		string
	 * return	true/false			int
	 * update	2016-07-18
	 */
	public function updateHost($hostName);
	
	/**
	 * 获取主机ID
	 *
	 * @param	$hostName		string
	 * return	$hostId			int
	 * update	2016-07-18
	 */
	public function getHostId($hostName);
	
	/**
	 * 获取待抓取的主机
	 *
	 * @return 主机对象数据
	 * @update 2016-07-20
	 */
	public function getSearchHost();
	
	/**
	 * 记录对主机的访问
	 * 
	 * @param	string	$hostName
	 * @return	int		$count
	 * @update	2016-07-20
	 */
	public function recordVisitHost($hostName);
}
?>