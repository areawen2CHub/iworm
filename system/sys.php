<?php
//	设置页面的编码格式
header("Contetn-Type:text/html;charset=utf-8");

/*
*
* 定义基础宏文件
* 2016-07-13
*
*/

//	网站是否开启
define('SUBGROUPS', true);

//	定义服务器的绝对路径
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'].'\iworm\\');
//	定义系统文件夹的绝对路径
define('BASE_SYSTEM', BASE_PATH.'\system\\');
//	定义数据文件夹的绝对路径
define('BASE_DATA', BASE_PATH.'\data\\');
//	定义引用文件夹的绝对路径
define('BASE_INCLUDE', BASE_PATH.'\include\\');
//	定义smarty模板文件夹的绝对路径
define('BASE_SMARTY', BASE_PATH.'\smarty\\');

?>