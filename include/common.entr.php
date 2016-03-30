<?php	header("Content-Type:text/html;charset=utf-8");

/*
 * 说明：全局引用入口文件
**************************
(C)2016-2016 vyahui.com
update: 2016-3-29 08:52:30
person: zhang
**************************
*/

define('VYAHUI_INC', preg_replace("/[\/\\\\]{1,}/", '/', dirname(__FILE__)));
// define('PHPMYWIND_ROOT', preg_replace("/[\/\\\\]{1,}/", '/', substr(PHPMYWIND_INC, 0, -8)));
// define('PHPMYWIND_DATA', PHPMYWIND_ROOT.'/data');
// define('PHPMYWIND_TEMP', PHPMYWIND_ROOT.'/templates');
// define('PHPMYWIND_UPLOAD', PHPMYWIND_ROOT.'/uploads');
// define('PHPMYWIND_BACKUP', PHPMYWIND_DATA.'/backup');

// 网站是否开启
define('IN_VYAHUI', TRUE);


//检查外部传递的值并转义
// function _RunMagicQuotes(&$svar)
// {
// 	//PHP5.4已经将此函数移除
//     if(@!get_magic_quotes_gpc())
//     {
//         if(is_array($svar))
//         {
//             foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
//         }
//         else
//         {
//             if(strlen($svar)>0 &&
// 			   preg_match('#^(cfg_|GLOBALS|_GET|_POST|_SESSION|_COOKIE)#',$svar))
//             {
// 				exit('不允许请求的变量值!');
//             }

//             $svar = addslashes($svar);
//         }
//     }
//     return $svar;
// }


// //直接应用变量名称替代
// foreach(array('_GET','_POST') as $_request)
// {
// 	foreach($$_request as $_k => $_v)
// 	{
// 		if(strlen($_k)>0 &&
// 		   preg_match('#^(GLOBALS|_GET|_POST|_SESSION|_COOKIE)#',$_k))
// 		{
// 			exit('不允许请求的变量名!');
// 		}

// 		${$_k} = _RunMagicQuotes($_v);
// 	}
// }


require_once(VYAHUI_INC.'/common.conf.php');    // 全局配置文件
// require_once(VYAHUI_INC.'/html.func.php');      // html相关函数
require_once(VYAHUI_INC.'/common.func.php');    // 全局常用函数
require_once(VYAHUI_INC.'/mysql.conf.php');     // 引入数据库连接文件
require_once(VYAHUI_INC.'/class.html.php');     // 引入HTNL类


//引入数据库类
if($cfg_mysql_type == 'mysqli' &&
   function_exists('mysqli_init'))
   require_once(VYAHUI_INC.'/mysqli.class.php');
else
   require_once(VYAHUI_INC.'/mysql.class.php');
   

//引入语言包



//Session保存路径
// $sess_savepath = PHPMYWIND_DATA.'/sessions/';
// if(is_writable($sess_savepath) &&
//    is_readable($sess_savepath))
//    session_save_path($sess_savepath);


// //上传文件保存路径
// $cfg_image_dir = PHPMYWIND_UPLOAD.'/image';
// $cfg_soft_dir  = PHPMYWIND_UPLOAD.'/soft';
// $cfg_media_dir = PHPMYWIND_UPLOAD.'/media';


// //系统版本号
// $cfg_vernum  = '5.2 Beta';
// $cfg_vertime = '201412281230';


// //设置默认时区
// if(PHP_VERSION > '5.1')
// {
// 	$time51 = $cfg_timezone * -1;
//     @date_default_timezone_set('Etc/GMT'.$time51);
// }


// //判断是否开启错误提示
// if($cfg_diserror == 'Y')
// 	error_reporting(E_ALL);
// else
// 	error_reporting(0);


// //判断访问设备
// //如果手动更改后台目录，请将/admin目录更改成新后台目录
// if(IsMobile() && !strstr(GetCurUrl(),'4g.php') &&
//    $cfg_mobile=='Y' && !strstr(GetCurUrl(),'/admin'))
// {
// 	header('location:4g.php');
// }

?>