<?php if(!defined('SUBGROUPS')) exit('System Error!');

require_once(BASE_DATAACCESS.DATA_INTER.'IInfolistEnter.php');
require_once(BASE_SYSTEM.'mysql.php');

//	实现关信息列表接口
class InfolistEnter implements IInfolistEnter{
	//	数据库操作对象
	private $mysql;

	//	构造函数
	function __construct(){
		$this->mysql = new MySQL();
	}

	//	适应低版本
	function InfolistEnter(){
		$this->mysql = new MySQL();
	}

	/**
	 * 获取查询列表信息
	 *
	 */
	public function getSearchList($kwArr){
		$sql1  = 'select title,keywords,description,i.createtime createtime,url from v_db_infolist i ';
		$sql1 .= 'left join v_db_url u on i.urlid=u.id  where i.delstate="false" and checkinfo=true and (';
		
		// 收录关键字
		for($i=0; $i<count($kwArr); $i++){
			// 拼接$checksql
			if($i==0){
				$sql1 .= 'keywords like "%'.$kwArr[$i].'%"';
			}else{
				$sql1 .= ' or keywords like "%'.$kwArr[$i].'%"';
			}
			//	判断24小时之内是否收录过，收录则增加收录次数
			$restime = GetMkTime(time())-24*3600;
			// 检查是否存在
			$sql2 = 'select id from v_db_keywords where keyword=:keyword and inittime>:inittime limit 0,1';
			$arr1 = array(':keyword'=>$kwArr[$i],':inittime'=>$restime);
			$id = $this->mysql->querySingle($sql2,$arr1);
			if($id > 0){
				//	如果存在，增加一次收录
				$sql3 = 'update v_db_keywords set seacount=seacount+1 where id='.$id;
				$count = $this->mysql->exec($sql3);
			}else{
				$inittime = GetMkTime(time());
				$sql4 = 'insert into v_db_keywords (keyword, seacount, inittime) values';
				$str1 = '(":keyword",:seacount,"'.$inittime.'")';
				$arr2 = array
				(
					new MysqlParam(":keyword",$kwArr[$i]),
					new MysqlParam(":seacount",1)
				);
				$sql4 .= paramForm($str1,$arr2);
				$count = $this->mysql->exec($sql4);
			}
		}
		$sql1 .=') order by createtime desc limit 0,10';
		$searchArr = $this->mysql->query($sql1);
		return $searchArr;
	}
	
	/**
	 * 增加infolist
	 *
	 * @param  obj $infolistObj
	 * @return 影响行数
	 * @update 2016-07-18
	 */
	public function addInfolist(infolistEntity $infolistObj){
		$sql = 'insert into v_db_infolist (title, urlid, keywords, description, picurl, hits, orderid, createtime) values';
		$str = '(":title",:urlid,":keywords",":description",":picurl",:hits,:orderid,"'.GetMkTime(time()).'")';
		$arr = array
		(
				new MysqlParam(":title", $infolistObj->title),
				new MysqlParam(":urlid", $infolistObj->urlId),
				new MysqlParam(":keywords", $infolistObj->url),
				new MysqlParam(":description", $infolistObj->description),
				new MysqlParam(":picurl", $infolistObj->picurl),
				new MysqlParam(":hits", $infolistObj->hits),
				new MysqlParam(":orderid", $infolistObj->orderid)
		);
		$sql .= paramForm($str, $arr);
		$count = $this->mysql->exec($sql);
		return $count;
	}
}
?>