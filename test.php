<?php

	ignore_user_abort(true);//关闭浏览器后，继续执行php代码
	set_time_limit(0);//程序执行时间无限制
	$sleep_time = 1;//多长时间执行一次
	do{
		echo '执行的文件'.time();
		sleep($sleep_time);
	}while(true)
?>