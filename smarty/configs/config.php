<?php

//	定义Smarty目录的绝对路径
define('SMARTY_PATH', '\smarty\\');
//	加载Smarty类库文件
require BASE_PATH.SMARTY_PATH.'Smarty.class.php';

//	实例化一个Smarty对象
$smarty = new Smarty;

//	定义各个目录的路径
$smarty->template_dir = BASE_PATH.SMARTY_PATH.'templates';		//	模板目录	
$smarty->compile_dir  = BASE_PATH.SMARTY_PATH.'templates_c';	//	编译目录
$smarty->config_dir   = BASE_PATH.SMARTY_PATH.'configs';		//	config文件目录
$smarty->cache_dir    = BASE_PATH.SMARTY_PATH.'cache';			//	模板缓存目录

?>