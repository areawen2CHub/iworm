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
// $html = new HTML('http://www.100toutiao.com/index.php?m=Index&a=show&cat=3&id=56058');

function GetHTMLImageOne(){
	// 图片存储路径
	$save_path = 'uploads/image/';
	// 以年月日为文件夹名称
	$file_name = MyDate('ymd', GetMkTime(time()));
	$save_path .='20'.$file_name;
	//如果不存在，则创建
	if(!is_dir($save_path)){  
		$res=mkdir($save_path,0777,true); 
		if($res){
			echo "目录".$save_path."创建成功";
		}else{
			echo "目录".$save_path."创建失败";
		}
	}else{
		echo "目录".$save_path."已存在";
	}
}
GetHTMLImageOne();
?>