<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	关键字接口

interface IKeywordsEnter{
	/*
	 * 获取当前时间前24小时4个热门关键字
	 * 
	 */
	public function getKeywordsNow();
}
?>