<?php require_once(dirname(__FILE__).'/include/fore.entr.php'); // 引入前段入口文件
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta charset="utf-8">
<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
<meta name="description" content="">
<meta name="keywords" content="subgroups">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 , maximum-scale=1.0, user-scalable=0">
	<title><?php echo $title ?></title>
<!-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="css/subgroups.base.css" media="screen">
<link rel="stylesheet" href="css/subgroups.main.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>

<?php
// 抓取网页
$i = 0;
$j = 0;
$sql = "select u.id as id,u.hostid as hostid,u.url as url,h.inccount as inccount,h.errcount as errcount 
from v_db_url u left join v_db_host h on u.hostid = h.id where 1=1 
and u.incstate='false' 
and u.delstate='false'
and u.isempty = 'false'
and h.isusing ='true'
order by h.inccount desc,h.errcount asc LIMIT 0,1000";
$dosql->Execute($sql);
while($row = $dosql->GetArray()){
	if(!empty($row['id']) && !empty($row['hostid']) && !empty($row['url'])){
		if($i>=10){
			break;
		}else{
			$html = new HTML($row['id'],$row['hostid'],$row['url']);
		    if($html->status){
			    $i++;
		    }
		}
	}
	$j++;
}
echo $j.'<br />';
echo $i;
?>
</body>
</html>