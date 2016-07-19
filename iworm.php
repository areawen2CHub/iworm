<?php 

require_once('\system\sys.php');                            	  //	引入系统文件
require_once(BASE_SMARTY.SMARTY_CONFIGS.'config.php');            //	引用模板配置文件
require_once (BASE_BUSINESS.'iwormBess.php');

$_iwormBess = new iwormBess();

global $smarty;

// 后台运行
ignore_user_abort(true);
// 响应时间无上线
set_time_limit(0);
// while (true) {
// 	// 抓取网页
//     $i = 0;
//     $j = 0;
//     $dosql->Execute("select * from vi_url limit 0,100");
//     while($row = $dosql->GetArray()){
//     	if(!empty($row['id']) && !empty($row['hostid']) && !empty($row['url'])){
//     		if($i>=10){
//     			break;
//     		}else{
//     			$html = new HTML($row['id'],$row['hostid'],$row['url']);
//     		    if($html->status){
//     			    $i++;
//     		    }
//     		}
//     	}
//     	$j++;
//     }
//     // 休眠1分钟
//     sleep(60);
// }

// function IsExistStr2($objstr,$handstr){
// 	return false !== strpos($objstr,$handstr);
// }

// $isExist = IsExistStr2('goal.sports.163.com','www.');
// if(!$isExist){
// 	echo 'www.';
// }


while(true){
	//	抓取网页
	$i = 0;
	$j = 0;
	
	//	获取查询url对象数组
	$searchUrlArr = $_iwormBess->getSearchUrl();
	for($i=0;$i<count($searchUrlArr);$i++){
		if(!empty($searchUrlArr[$i]->id) && !empty($searchUrlArr[$i]->hostid) && !empty($searchUrlArr[$i]->url)){
			if($i>=10){
				break;
			}else{
				$stateInfo = $_iwormBess->initHtml($searchUrlArr[$i]->id, $searchUrlArr[$i]->hostid, $searchUrlArr[$i]->url);
				if($stateInfo == 'ok'){
					$i++;
				}
				echo $stateInfo;
			}
		}
		$j++;
	}
	// 休眠1秒
	sleep(1);
}
?>
