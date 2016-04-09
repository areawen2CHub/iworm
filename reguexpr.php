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

$htmli = new HTMLI('http://www.szfa.com/');
// echo $html->html;
// $html->GetHTMLContent();
// $pattern = '/<p>.*<\/p>/';

//  function StrIsExist($ostr,$str){
//         return false !== strpos($ostr,$str); 
//     }
// preg_match_all($pattern,$html->html,$matches);
// // print_r($matches);
// $content = '';
// for($i=0;$i<count($matches);$i++){
// 	for($j=0;$j<count($matches[$i]);$j++){
// 		if(!StrIsExist($matches[$i][$j],'href=')){
// 			$content .= str_replace("'", '"', $matches[$i][$j]);
// 		}
// 	}
// }
// if(strlen($content) < 1000){
// 	echo '非适合文章';
// }else{
// 	echo $content;
// }
?>