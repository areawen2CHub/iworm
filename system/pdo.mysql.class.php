<?php   //if(!defined('IN_VYAHUI')) exit('Request Error!');

/*
**************************
(C)2016-2016 
update: 2016-6-30 
person: zhang
**************************
*/


/*
 * 数据库类
 * 
 * 调用这个类前,请先设定这些外部变量
 * $GLOBALS['db_host'];
 * $GLOBALS['db_user'];
 * $GLOBALS['db_pwd'];
 * $GLOBALS['db_name'];
 * $GLOBALS['db_tablepre'];
 *
 * 在系统所有文件中不需要单独初始化本类
 * 可直接用 $dosql 或 $db 进行操作
 * 为了防止错误，操作完后不必关闭数据库
 */

//	设置页面的编码格式	
header("Content-Type:text/html;charset=utf-8");



function open(){
	//	数据库类型
	$dbms = 'mysql';
	//	数据库名
	$dbName = 'iworm_db';
	//	数据库用户名
	$user = 'root';
	//	数据库密码
	$password = '1258';
	//	主机
	$host = 'localhost';

	$dsn = "$dbms:host=$host;dbname=$dbName";

	try{
		$pdo = new PDO($dsn,$user,$password);	//	实例化对象
		return $pdo;
	}catch(Exception $ex){
		echo $ex->getMessage();
	}
}

function queryTest(){
	try{
		$pdo = open();
		$sql = 'select * from  v_db_keywords';
		$data = $pdo->prepare($sql);			//	准备查询语句
		$data->execute();						//	执行查询语句，并返回结果集
		$res = $data->fetchAll();
		return $res;
	}catch(Exception $ex){
		return null;
	}
}

$data = queryTest();
for($i=0;$i<count($data);$i++){
	echo $data[$i]['id'].$data[$i]['keyword'].'</br>';
}

?>