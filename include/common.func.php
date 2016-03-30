<?php	if(!defined('IN_VYAHUI')) exit('Request Error!');

/*
**************************
(C)2016-2016 vyahui.com
update: 2016-3-29 16:03:41
person: Feng
**************************
*/

//获得当前的页面文件的url
if(!function_exists('GetCurUrl'))
{
	function GetCurUrl()
	{
		if(!empty($_SERVER['REQUEST_URI']))
		{
			$nowurls = explode('?',$_SERVER['REQUEST_URI']);
			$nowurl = $nowurls[0];
		}
		else
		{
			$nowurl = $_SERVER['PHP_SELF'];
		}

		return $nowurl;
	}
}

//获取IP
if(!function_exists('GetIP'))
{
	function GetIP()
	{
		static $ip = NULL;
		if($ip !== NULL) return $ip;
	
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip  = trim($arr[0]);
		}
		else if(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(isset($_SERVER['REMOTE_ADDR']))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	
		//IP地址合法验证
		$ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
		return $ip;
	}
}

//返回格林威治标准时间
if(!function_exists('MyDate'))
{
	function MyDate($format='Y-m-d H:i:s', $timest=0)
	{
		global $cfg_timezone;

		$addtime = $cfg_timezone * 3600;
		if(empty($format))
			$format = 'Y-m-d H:i:s';

		return gmdate($format, $timest+$addtime);
	}
}


//返回格式化(Y-m-d H:i:s)的时间
if(!function_exists('GetDateTime'))
{
	function GetDateTime($mktime)
	{
		return MyDate('Y-m-d H:i:s',$mktime);
	}
}


//返回格式化(Y-m-d)的日期
if(!function_exists('GetDateMk'))
{
	function GetDateMk($mktime)
	{
		return MyDate('Y-m-d', $mktime);
	}
}


//从普通时间转换为Linux时间截
if(!function_exists('GetMkTime'))
{
	function GetMkTime($dtime)
	{
		if(!preg_match("/[^0-9]/", $dtime))
		{
			return $dtime;
		}
		$dtime = trim($dtime);
		$dt = array(1970, 1, 1, 0, 0, 0);
		$dtime = preg_replace("/[\r\n\t]|日|秒/", " ", $dtime);
		$dtime = str_replace("年", "-", $dtime);
		$dtime = str_replace("月", "-", $dtime);
		$dtime = str_replace("时", ":", $dtime);
		$dtime = str_replace("分", ":", $dtime);
		$dtime = trim(preg_replace("/[ ]{1,}/", " ", $dtime));
		$ds = explode(" ", $dtime);
		$ymd = explode("-", $ds[0]);
		if(!isset($ymd[1])) $ymd = explode(".", $ds[0]);
		if(isset($ymd[0])) $dt[0] = $ymd[0];
		if(isset($ymd[1])) $dt[1] = $ymd[1];
		if(isset($ymd[2])) $dt[2] = $ymd[2];
		if(strlen($dt[0])==2) $dt[0] = '20'.$dt[0];
		if(isset($ds[1]))
		{
			$hms = explode(":", $ds[1]);
			if(isset($hms[0])) $dt[3] = $hms[0];
			if(isset($hms[1])) $dt[4] = $hms[1];
			if(isset($hms[2])) $dt[5] = $hms[2];
		}
		foreach($dt as $k=>$v)
		{
			$v = preg_replace("/^0{1,}/", '', trim($v));
			if($v == '')
			{
				$dt[$k] = 0;
			}
		}

		$mt = mktime($dt[3], $dt[4], $dt[5], $dt[1], $dt[2], $dt[0]);
		if(!empty($mt)) return $mt;
		else return time();
	}
}

//写入文件内容
if(!function_exists('Writef'))
{
	function Writef($file,$str,$mode='w')
	{
		if(file_exists($file) && is_writable($file))
		{
			$fp = fopen($file, $mode);
			flock($fp, 3);
			fwrite($fp, $str);
			fclose($fp);

			return TRUE;
		}
		else if(!file_exists($file))
		{
			$fp = fopen($file, $mode);
			flock($fp, 3);
			fwrite($fp, $str);
			fclose($fp);
		}
		else
		{
			return FALSE;
		}
	}
}
?>