<?php

require_once('\system\sys.php');                            	  //	引入系统文件
require_once(BASE_SMARTY.SMARTY_CONFIGS.'config.php');            //	引用模板配置文件
require_once(BASE_BUSINESS.'iworm2Bess.php');

$_iworm2Bess = new iworm2Bess();

global $smarty;

// 后台运行
ignore_user_abort(true);
// 响应时间无上线
set_time_limit(0);

while(true){
	//	获取待访问的主机
	$_searchHostModel = $_iworm2Bess->getSearchHost();
	if($_searchHostModel->hostId > 0){
		//	抓取该主机的内容
		$_iworm2Bess->initHtml($_searchHostModel->hostId, $_searchHostModel->hostName);
	}
	
	// 休眠1秒
	sleep(0.001);
}
?>