<?php if(!defined('SUBGROUPS')) exit('System Error!');

//	引入实体
require_once(BASE_ENTITY.'infolistEntity.php');

//	关信息列表接口
interface IInfolistEnter{
	/**
	 * 获取查询列表信息
	 *
	 */
	public function getSearchList($kwArr);
	
	/**
	 * 增加infolist
	 *
	 * @param  obj $infolistObj
	 * @return 影响行数
	 * @update 2016-07-18
	 */
	public function addInfolist(infolistEntity $infolistObj);
}
?>