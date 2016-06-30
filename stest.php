<?php

//	引用模板配置文件
require_once('\smarty\configs\config.php');


//	使用Smarty赋值方法将一对名称/方法发送到模板中
$smarty->assign('title','第一个 Smarty 程序');
$smarty->assign('content','快快快快快快');

//	推送给模板
$smarty->display('index.html');





?>