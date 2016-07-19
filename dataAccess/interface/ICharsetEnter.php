<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	字符集接口
interface ICharsetEnter{
	/**
	 * 通过hostid判断charset是否存在
	 *
	 * @param   $hostid         int
	 * @return  true/false
	 * @update  2016-07-18
	 */
	public function isExistCharset($hostid);
	
	/**
	 * 通过hostid判断charset是否存在
	 *
	 * @param   $hostid         int
	 * @param   $charset        string
	 * @return  影响行数
	 * @update  2016-07-18
	 */
	public function addCharset($hostid,$charset);
	
}
?>