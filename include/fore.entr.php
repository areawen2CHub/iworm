<?php

/*
 * 说明：前端引用文件
**************************
(C)2016-2016 vyahui.com
update: 2016-3-29 08:38:30
person: zhang
**************************
*/

require_once(dirname(__FILE__).'/common.entr.php'); //全局入口文件
// require_once(PHPMYWIND_INC.'/func.class.php');
// require_once(PHPMYWIND_INC.'/page.class.php');


if(!defined('IN_VYAHUI')) exit('Request Error!');

// 网站开关
if($cfg_webswitch == 'N')
{
	echo $cfg_switchshow.'<br /><br /><i>'.$cfg_webname.'</i>';
	exit();
}
?>