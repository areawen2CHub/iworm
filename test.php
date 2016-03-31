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

function GetHtml($url){
	// 判断是否支持cURL
	if (!function_exists('curl_init')) {  
        throw new Exception('server not install curl');  
        exit();
    } 
	// 初始化一个cURL对象
	$ch = curl_init();
	// 抓取结果不直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    // 设置HEADER
    curl_setopt($ch, CURLOPT_HEADER, 1);  
    // 需要抓取的url
	curl_setopt ($ch, CURLOPT_URL, $url);
    // 设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
 
    // 运行URL，获取页面
    $html = curl_exec($ch);

    list($header, $html) = explode("\r\n\r\n", $html);

    // 判断是否是跳转页面
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
    if ($http_code == 301 || $http_code == 302) {  
        $matches = array();  
        preg_match('/Location:(.*?)\n/', $header, $matches);  
        $url = trim(array_pop($matches));  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_HEADER, false);  
        $html = curl_exec($ch);  
    }  

    // 关闭cURL对象
    if ($html == false) {  
        curl_close($ch);  
    }  
    @curl_close($ch); 

    $char = GetHtmlCharset($html);
    if(!empty($char)){
    	$html = iconv( $char , "utf-8//IGNORE" , $html);
    }
    return $html;
}

function GetHtmlCharset($html){
    // 获取本页面上所有charset
    preg_match_all( '/charset=([\S]*)/' , $html , $char );
    // 获取目标字符串
    if(isset($char[1][0])){
        $str = $char[1][0];
    }else{
        $str = '';
    }
    // 如果存在，则提取charset
    if(!empty($str)){
        // 判断是单引号还是双引号
        if(strpos($str, '"') == 0){
            $fpos = 1;
            $lpos = strrpos($str, '"')-1;
        }else if(strpos($str, '"') > 0){
            $fpos = 0;
            $lpos = strrpos($str, '"');
        }else if(strpos($str, "'") == 0){
            $fpos = 1;
            $lpos = strrpos($str, "'")-1;
        }else if(strpos($str, "'") > 0){
            $fpos = 0;
            $lpos = strrpos($str, "'");
        }else{
            $fpos = 0;
            $lpos = 0;
        }    
        // 是否需要截取
        if($lpos > $fpos){
            $char = substr($str, $fpos,$lpos);
        }else{
            $char = $str;
        }
    } 
    print_r($char);
    return $char;
}

GetHtml('http://www.100toutiao.com/index.php?m=Index&a=index&cat=99');
?>