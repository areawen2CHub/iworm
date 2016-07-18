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

//	一级目录
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'].'\iworm\\'); 		//	定义服务器的绝对路径
define('BASE_SYSTEM', BASE_PATH.'\system\\');					//	定义系统文件夹的绝对路径
define('BASE_DATA', BASE_PATH.'\data\\');						//	定义数据文件夹的绝对路径
define('BASE_INCLUDE', BASE_PATH.'\include\\');					//	定义引用文件夹的绝对路径
define('BASE_SMARTY', BASE_PATH.'\smarty\\');					//	定义smarty模板文件夹的绝对路径
define('BASE_CONTENT', BASE_PATH.'\content\\');					//	定义content文件夹绝对路径
define('BASE_DATAACCESS', BASE_PATH.'\dataAccess\\');		    //	定义dataAccess文件夹绝对路径
define('BASE_BUSINESS', BASE_PATH.'\business\\');		        //	定义business文件夹绝对路径
define('BASE_MODEL', BASE_PATH.'\model\\');		                //	定义model文件夹绝对路径
define('BASE_HELPR', BASE_PATH.'\helpr\\');		                //	定义helper文件夹绝对路径
define('BASE_ENTITY', BASE_PATH.'\entity\\');		            //	定义entity文件夹绝对路径
//	二级目录
define('SMARTY_CONFIGS','\configs\\');							//	smarty模板配置文件
define('DATA_INTER','\interface\\');                            //	接口
define('DATA_TYPE','\mysqlToInterface\\');                          //	实现接口的数据库类型

?>