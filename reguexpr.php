<?php

/*
**************************
(C)2016-2016 vyahui.com
update: 2016-4-1 11:15:55
person: zhang
**************************
*/

/*
 * html类
 * 
 * 学习php正则表达式
 */
require_once(dirname(__FILE__).'/include/common.entr.php'); //全局入口文件
header("content-Type: text/html; charset=utf-8");

// $pattern = '/Windows(?:95|98|NT|2000)/';
// $pattern = '/[wn]/';
// $str = 'Windows2000';
// preg_match_all($pattern,$str,$matches);

// print_r($matches);

$html = new HTMLI('http://www.100toutiao.com/index.php?m=Index&a=show&cat=3&id=56012');
// $html->GetHTMLContent();
// $pattern = '/<meta[\s]+name=\"keywords\"[\s]+content=\"([\s\S]*?)\"[\s]+\/>/';

// preg_match_all($pattern,$html->html,$matches);
// // print_r($matches);
// echo $matches[1][0];
?>