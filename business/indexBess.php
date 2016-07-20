<?php if(!defined('SUBGROUPS')) exit('System Error!');
//	引入实现接口操作文件
require_once(BASE_DATAACCESS.DATA_TYPE.'KeywordsEnter.php');	
require_once(BASE_DATAACCESS.DATA_TYPE.'InfolistEnter.php');

//	引入model文件
require_once(BASE_MODEL.'listModel.php');


class indexBess{
	//	定义关键字数据库操作对象
	private $_KeywordsEnter;
	private $_InfolistEnter;
	
	//	构造函数
	public function __construct(){
		$this->initialise();
	}
	
	//	适应低版本
	public function indexBess(){
		$this->__construct();
	}
	
	//	初始化函数
	private function initialise(){
		$this->_KeywordsEnter = new KeywordsEnter();
		$this->_InfolistEnter = new InfolistEnter();
	}
	
	/*
	 * 获取首页title
	 * 
	 */
	public function getTitle(){
		global $cfg_title;
		return $cfg_title;
	}
	
	/*
	 * 获取当前时间前24小时4个热门关键字
	 *
	 */
	public function getKeywordsNow(){
		$keywords = $this->_KeywordsEnter->getKeywordsNow();
		//	将二维数组组装成一维
		$arr = array();
		for($i=0;$i<count($keywords);$i++){
			$arr[count($arr)] = $keywords[$i][0];
		}
		return $arr;
	}
	
	/**
	 * 获取查询结果列表
	 *
	 * @param	string	$keywords
	 * return	$_searchListModel数组
	 */
	public function getSearchList($keywords){
		//	将中文逗号替换成英文逗号
		$keywords = str_replace('，', ',', $keywords);
		//	按空格拆分
		$kw1 = explode(' ', $keywords);
		//	定义收集关键词数组
		$kwArr = array();
		//	按照空格和逗号拆分关键字
		for($i=0; $i<count($kw1); $i++){
			$kw2 = explode(',', $kw1[$i]);
			for($j=0; $j<count($kw2); $j++){
				if(!empty($kw2[$j])){
					$kwArr[count($kwArr)] = $kw2[$j];
				}
			}
		}
	    
		//	获取结果集
		$searchList = $this->_InfolistEnter->getSearchList($kwArr);
		$arr = array();
		for($i=0;$i<count($searchList);$i++){
			$_searchListModel = new searchListModel();
			$_searchListModel->url = $searchList[$i]["url"];
			$_searchListModel->createTime = MyDate('Y-m-d', $searchList[$i]["createtime"]);
			//	高亮关键字
			for($j=0;$j<count($kwArr);$j++){
				$_searchListModel->title = str_replace($kwArr[$j], '<font color="#CC0000">'.$kwArr[$j].'</font>', $searchList[$i]["title"]);
				$_searchListModel->keywords = str_replace($kwArr[$j], '<font color="#CC0000">'.$kwArr[$j].'</font>', $searchList[$i]["keywords"]);
				$_searchListModel->description = str_replace($kwArr[$j], '<font color="#CC0000">'.$kwArr[$j].'</font>', $searchList[$i]["description"]);
			}
			$arr[count($arr)] = $_searchListModel;
		}
		return $arr;
	}
}

?>