<?php 

require_once('\system\sys.php');                            	  //	引入系统文件
require_once(BASE_SMARTY.SMARTY_CONFIGS.'config.php');            //	引用模板配置文件
require_once (BASE_BUSINESS.'iwormBess.php');

$_iwormBess = new iwormBess();

global $smarty;

//  获取网站首页title
$title = $_iwormBess->getTitle();
$smarty->assign('title',$title);

//	获取当天热门的5个关键字
$keywords = $_iwormBess->getKeywordsNow();
$smarty->assign('keywords',$keywords);

//  推送给模板
$smarty->display('index.html');
?>