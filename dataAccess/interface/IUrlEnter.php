<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	引入实体
require_once(BASE_ENTITY.'urlEntity.php');

//	关键字接口
interface IUrlEnter{
	/**
	 * 获取查询url对象列表
	 * 
	 * @param	无
	 * @return  $arr       array  对象数组
	 * @update  2016-07-18
	 */
	public function getSearchUrl();
	
	/**
	 * 记录本次访问
	 *
	 * @param	int		$id
	 * return	影响行数
	 * @update  2016-07-18
	 */
	public function recordVisit($id);
	
	/**
	 * 记录该url抓取为空
	 *
	 * @param	int		$id
	 * return	影响行数
	 * @update  2016-07-18
	 */
	public function isEmptyUrl($id);
	
	/**
	 * 判断url是否已收录
	 * 
	 * @param  string $url
	 * @return true/false
	 * @update 2016-07-18
	 */
	public function isExistUrl($url);
	
	/**
	 * 增加url收录次数
	 *
	 * @param  string $url
	 * @return 影响行数
	 * @update 2016-07-18
	 */
	public function addUrlIncCount($url);
	
	/**
	 * 增加url
	 *
	 * @param  obj $urlObj
	 * @return 影响行数
	 * @update 2016-07-18
	 */
	public function addUrl(urlEntity $urlObj);
}
?>