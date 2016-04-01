<?php
require_once(dirname(__FILE__).'/include/common.entr.php'); //全局入口文件
// require_once(PHPMYWIND_INC.'/func.class.php');
// require_once(PHPMYWIND_INC.'/page.class.php');


if(!defined('IN_VYAHUI')) exit('Request Error!');

// 网站开关
if($cfg_webswitch == 'N')
{
	echo $cfg_switchshow.'<br /><br /><i>'.$cfg_webname.'</i>';
	exit();
}
	// ignore_user_abort(true);//关闭浏览器后，继续执行php代码
	// set_time_limit(0);//程序执行时间无限制
	// $sleep_time = 1;//多长时间执行一次
	// do{
	// 	echo '执行的文件'.time();
	// 	sleep($sleep_time);
	// }while(true)

$html = '<p class="symposiastx_photo_attention">亿欧网专家作者，经纬易达信息咨询公司BM研究经理</p><p class="symposiastx_photo_attention">亿欧网专家作者</p>';
$title = '亿欧网';
echo substr_count($html, $title);
?>