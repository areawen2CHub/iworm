<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	关键字接口
interface IKeywordsEnter{
	/**
	 * 获取当前时间前24小时4个热门关键字
	 * 
	 * @param	无
	 * @return	array
	 * @update	2016-07-18
	 */
	public function getKeywordsNow();
	
	/**
	 * 获取当前时间前24小时内的关键字
	 *
	 * @param 	无
	 * @return	bool	true/fasle
	 * @update	2016-07-19
	 */
	public function getHotKeywords();
}
?>