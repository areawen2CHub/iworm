<?php
header("Content-type:text/html;charset=utf-8");
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
$html = new HTML('http://travel.163.com/15/0608/08/ARIUIJJC00064LRB.html');
$html->GetHTMLContent();

function StrIsExist($ostr,$str){
        return false !== strpos($ostr,$str); 
    }

function GetHTMLImageOne($html){
	// 图片存储路径
	$save_path = 'uploads/image/';
	// 以年月日为文件夹名称
	$file_name = MyDate('ymd', GetMkTime(time()));
	$save_path .='20'.$file_name;
	//如果不存在，则创建
	if(!is_dir($save_path)){  
		$res=mkdir($save_path,0777,true); 
		if(!$res){
			return false;
		}
	}
	// 获取抓取网页上的所有img
    preg_match_all('/[src=|data-mce-src=][\"|\']([\S]*?.(png|jpg|jpeg|gif|bmp|swf|swc|psd|tiff|iff|jp2|jpx|jb2|jpc))[\"|\']/',$html->html,$ilist);
    // 定义图片数组
    $imglist = array();
    // 获取绝对路径
    for($i=0,$j=0; $i<count($ilist[1]);$i++){
    	// 获取第一个字符
        $fchar = substr($ilist[1][$i],0,1);
        // 获取图片格式
        $isty = substr($ilist[1][$i],strrpos($ilist[1][$i], ".")+1);
        // 判断格式是否正确
        if($isty=='png'||$isty=='jpg'||$isty=='jpeg'||$isty=='gif'||$isty=='bmp'||$isty=='swf'||$isty=='swc'||$isty=='psd'||$isty=='tiff'||$isty=='iff'||$isty=='jp2'||$isty=='jpx'||$isty=='jb2'||$isty=='jpc'){
        	if($fchar == 'w'){
            	$imglist[$j] = 'http://'.$ilist[1][$i];
            }else if($fchar == '/'){
            	$imglist[$j] = 'http://'.$html->host.$ilist[1][$i];
            }else{
            	$imglist[$j] = $ilist[1][$i];
            }
            $j++;
        }
    }
    for($i=0;$i<count($imglist);$i++){
    	echo $imglist[$i].'<br />';
    }
    // 判断函数getimagesize是否存在;
    if(!function_exists(getimagesize)){
    	throw new Exception('server not install getimagesize!');  
        exit();
    }
    // 定义筛选出的存储在本地图片路径
    $ret_imgurl = '';
    // 定义筛选出的远程图片路径
    $nee_imgurl = '';
    // 宽高比，默认为1
    $imgwh = 1;
    // 挑选合适图片
    for($i=0; $i<count($imglist); $i++){
    	// 获取图片名称
    	$imgname = substr($imglist[$i], strrpos($imglist[$i], "/")+1);
    	// // 去除后缀
    	// $imgname = substr($imgname, 0,strpos($imgname, "."));
    	// 获取主机名
    	$imghost = substr($html->host, strpos($html->host, ".")+1);
    	// 去除后缀
    	$imghost = substr($imghost, 0,strrpos($imghost, '.'));
    	// 获取图片对象
    	$imgobj  = getimagesize($imglist[$i]);
    	if(!empty($imgobj)){
    		// 不选logo之类的图片
    		if(!StrIsExist($imgname,'logo') && !StrIsExist($imgname,$imghost)){
    			// 获取图片
                $img = file_get_contents($imglist[$i]);
    			// 获取图片大小
    			$imgsize = strlen($img)/1024;
    			// 获取图片宽度和高度
    			$imgw  = $imgobj[0];
    			$imgh  = $imgobj[1];
    			if($imgsize < 500 && $imgw >= 120 && $imgh >= 80){
    				if(abs($imgw/$imgh-1.5) <= $imgwh){
    					$imgwh = abs($imgw/$imgh-1.5);
    					$nee_imgurl = $imglist[$i];
    				}
    			}
    		}
        }else{
        	continue;
        }
        clearstatcache();
    }
    if(!empty($nee_imgurl)){
    	// 获取图片
        $img = file_get_contents($nee_imgurl);
        // 获取图片格式
        $ext = substr($nee_imgurl,strrpos($nee_imgurl, ".")); 
        // 设置文件名称
        $filename = $save_path.'/'.MyDate('ymdhis', GetMkTime(time())).$ext; 
        // 保存图片；
        file_put_contents($filename,$img);
        // 获取文件存储在本地路径
        $ret_imgurl = $filename;
    }
    return $ret_imgurl;
}
// echo @GetHTMLImageOne($html);
?>