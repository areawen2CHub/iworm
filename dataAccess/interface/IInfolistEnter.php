<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	关信息列表接口

interface IInfolistEnter{
	/*
	 * 获取查询列表信息
	 *
	 */
	public function getSearchList($kwArr);
}
?>