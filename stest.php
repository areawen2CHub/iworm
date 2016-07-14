<?php 

require_once('\system\sys.php');					//	引入系统文件
require_once(BASE_SMARTY.'\configs\config.php');			//	引用模板配置文件
require_once(BASE_SYSTEM.'mysql.php');				//	引入mysql操作文件

$arr = array();
$arr['keyword'] = 'ttt';
$arr['seacount'] = 2;
//print_r($arr);

$sql = 'insert into v_db_keywords (keyword,seacount,inittime) values';
for($i=0;$i<3;$i++){
	if($i==0){
		$str = '(":keyword",:seacount,"'.time().'")';
		$arr = array
		(
			new MysqlParam(":keyword","ttt"),
			new MysqlParam(":seacount",2)
		);
		$sql .= paramForm($str,$arr);
	}else{
		$str = ',(":keyword",4,"'.time().'")';
		$arr = array
		(
			new MysqlParam(":keyword","ttt"),
			new MysqlParam(":seacount",2)
		);
		$sql .= paramForm($str,$arr);
	}
}

$data = $mysql->exec($sql);
//echo $data;

//print_r($data);
//echo $data;
//echo $data->id;
//echo $data->queryString;

// for($i=0;$i<count($data);$i++){
// 	for($j=0;$j<count($data[$i]);$j++){
// 		echo $data[$i][$j];
// 	}
// 	echo '<br />';
	
// }


//	使用Smarty赋值方法将一对名称/方法发送到模板中
//$smarty->assign('title','第一个 Smarty 程序');
//$smarty->assign('content',$data);

//	推送给模板
//$smarty->display('index.html');





?>