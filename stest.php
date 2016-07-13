<?php

require_once('\system\sys.php');					//	引入系统文件
require_once('\smarty\configs\config.php');			//	引用模板配置文件
require_once(BASE_INCLUDE.'conf.php');        //  引入配置文件
require_once(BASE_SYSTEM.'pdo.mysql.class.php');	//	引入数据库操作文件

$sql = 'select * from v_db_keywords';
$data = $mysql->query($sql);

for($i=0;$i<count($data);$i++){
	echo $data[$i]['keyword'].$data[$i]['seacount'].'<br />';
}


//	使用Smarty赋值方法将一对名称/方法发送到模板中
//$smarty->assign('title','第一个 Smarty 程序');
//$smarty->assign('content',$data);

//	推送给模板
//$smarty->display('index.html');





?>