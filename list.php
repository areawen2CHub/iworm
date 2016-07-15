<?php

require_once('\system\sys.php');                            	  //	引入系统文件
require_once(BASE_SMARTY.SMARTY_CONFIGS.'config.php');            //	引用模板配置文件
require_once (BASE_BUSINESS.'iwormBess.php');

$_iwormBess = new iwormBess();

global $smarty;

//  获取关键字
$keywords = htmlspecialchars($_GET['keywords']);

//	组装title
$title = $keywords.'--'.'磁力搜索';

//	获取查询结果集
$searchList = $_iwormBess->getSearchList($keywords);

$smarty->assign('title',$title);
$smarty->assign('keywords',$keywords);
$smarty->assign('searchList',$searchList);

//  推送给模板
$smarty->display('list.html');
?>