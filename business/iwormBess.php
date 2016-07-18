<?php if(!defined('SUBGROUPS')) exit('System Error!');
//	引入实现接口操作文件
require_once(BASE_DATAACCESS.DATA_TYPE.'UrlEnter.php');
require_once(BASE_DATAACCESS.DATA_TYPE.'HostEnter.php');
require_once(BASE_DATAACCESS.DATA_TYPE.'InfolistEnter.php');

//	引入model文件
require_once(BASE_MODEL.'iwormModel.php');


class iwormBess{
	//	定义url数据库操作对象
	private $_UrlEnter;
	//	定义host(主机)数据库操作对象
	private $_HostEnter;
	//	定义infolist(信息列表)数据库操作对象
	private $_InfolistEnter;
	
	//	构造函数
	public function __construct(){
		$this->initialise();
	}
	
	//	适应低版本
	public function iwormBess(){
		$this->__construct();
	}
	
	//	初始化函数
	private function initialise(){
		$this->_UrlEnter  = new UrlEnter();
		$this->_HostEnter = new HostEnter();
		$this->_InfolistEnter = new InfolistEnter();
	}
	
	/**
	 * 获取查询url对象列表
	 * 
	 * @param	无
	 * @return  $arr       array  对象数组
	 * @update  2016-07-18
	 */
	public function getSearchUrl(){
		//	获取结果集
		$searchUrl = $this->_UrlEnter->getSearchUrl();
		
		//	组装成对象数组
		$arr = array();
		for($i=0;$i<count($searchUrl);$i++){
			$_searchUrlModel = new searchUrlModel();
			$_searchUrlModel->id = $searchUrl[$i]["id"];
			$_searchUrlModel->hostid = $searchUrl[$i]["hostid"];
			$_searchUrlModel->url = $searchUrl[$i]["url"];
			$arr[count($arr)] = $_searchUrlModel;
		}
		return $arr;
	}
	
	/**
	 * 初始化抓取
	 *
	 * @param	int		$id
	 * @param	int		$hostid
	 * @param	string	$url
	 */
	public function initHtml($id,$hostid,$url){
		//	定义返回提示信息
		$backCode = '';
		//	操作影响行数
		$count = 0;
		do{
			//	更改收录状态、访问数、上次访问时间和最近访问时间
			$count = $this->_UrlEnter->recordVisit($id);
			if($count < 1){
				//	记录url失败
				$backCode = 'recordVisit记录url失败<br/>';
				break;
			}
	
			//	获取HTML对象
			$html = $this->getHTMLObj($url);
			//	判断抓取的网页
			if($html === false || strlen($html) < 10){
				$backCode = $id.'html为空!<br />';
				//	记录该url抓取为空
				$this->_UrlEnter->isEmptyUrl($id);
				break;
			}
			
			//	获取字符集
			$charset = $this->getHTMLCharset($html);
			//	如果不为空，则转换
			if($charset != ''){
				//	转换字符集
				$html = $this->changeHTMLCharset($charset, $html);
				//	插入字符集
				$this->addCharset($hostid,$charset);
			}
			
			//	获取主机
			$host = $this->getUrlHost($url);
			
			//	获取所有url
			$allurl = $this->getHTMLUrlAll($html, $host);
			// 已经收录的，增加收录次数，未收录的，收录进去
			for($i=0; $i<count($allurl); $i++){
				// 获取主机
				$host = $this->getUrlHost($url);
				if(!empty($host)){
					// 收录主机，或者增加主机收录的url数
					$this->addHost($host);
				}
				$this->addUrl($allurl[$i],$url);
			}
			
			//	判断$charset
			if(empty($charset)){
				$backCode = 'charset为空，非所需信息!<br />';
				break;
			}
			
			//	获取文章titie
			$title = $this->getHTMLTitle($html);
			if(empty($title)){
				$backCode = 'title为空，非所需信息!<br />';
				break;
			}
			
			//	获取内容，若满足条件，则收录
			$backCode = $this->getHTMLContent($id, $html, $title);
		}while(false);
		return $backCode;
	}
	
	/**
	 * 函数说明：抓取网页，返回网页对象或者空或者false
	 *
	 * @access  public
	 * @param	string	$url
	 * @return  $html       string  网页对象
	 * @update  2016-03-29
	 */
	private function getHTMLObj($url){
		// 判断是否支持cURL
		if (!function_exists('curl_init')) {
			throw new Exception('server not install curl');
			exit();
		}
		// 初始化一个cURL对象
		$ch = curl_init();
		// 抓取结果不直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 设置HEADER
		curl_setopt($ch, CURLOPT_HEADER, 1);
		// 需要抓取的url
		curl_setopt ($ch, CURLOPT_URL, $url);
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		// 运行URL，获取页面
		$html = curl_exec($ch);
	
		// 判断是否是跳转页面
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 301 || $http_code == 302) {
			$matches = array();
			preg_match('/Location:(.*?)\n/', $header, $matches);
			$url = trim(array_pop($matches));
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$html = curl_exec($ch);
		}
		// echo $html;
	
		// 关闭cURL对象
		if ($html == false) {
			curl_close($ch);
		}
		@curl_close($ch);
	
		return $html;
	}
	
	/**
	 * 获取网页charset,返回charset值
	 *
	 * @param   $html		obj
	 * @return  $cha        string
	 * @update  2016-03-29
	 */
	private function getHTMLCharset($html){
		//	获取本页面上所有charset
		preg_match_all('/charset=([\S]*)/',$html,$chalist);
		//	获取目标字符串
		if(isset($chalist[1][0])){
			// 如果存在，则提取出
			$str = $chalist[1][0];
			// 判断是单引号还是双引号
			if(strpos($str, '"') == 0){
				$fpos = 1;
				$lpos = strrpos($str, '"')-1;
			}else if(strpos($str, '"') > 0){
				$fpos = 0;
				$lpos = strrpos($str, '"');
			}else if(strpos($str, "'") == 0){
				$fpos = 1;
				$lpos = strrpos($str, "'")-1;
			}else if(strpos($str, "'") > 0){
				$fpos = 0;
				$lpos = strrpos($str, "'");
			}else{
				$fpos = 0;
				$lpos = 0;
			}
			// 是否需要截取
			if($lpos > $fpos){
				$cha = substr($str, $fpos,$lpos);
			}else{
				$cha = $str;
			}
			// 如果获取的charset非法，则赋空
			if(strlen($cha)>6){
				$cha = '';
			}
		}else{
			$cha = '';
		}
		return $cha;
	}
	
	/**
	 * 函数说明：改变网页charset
	 *
	 * @param   $charset		string
	 * @param   $html			obj
	 * @return  $html
	 * @update  2016-03-29
	 */
	private function changeHTMLCharset($charset,$html){
		if(!empty($charset)){
			$html = iconv($charset , "utf-8//IGNORE" , $html);
		}
		return $html;
	}
	
	/**
	 * 插入charset
	 *
	 * @param   $charset         string  需要插入的charset
	 * @return  无
	 * @update  2016-03-29
	 */
	private function addCharset($hostid,$charset){
		//	判断是否已存在
		$isExist = $this->_UrlEnter->isExistCharset($hostid);
		if(!$isExist){
			//	收录charset
			$this->_UrlEnter->addCharset($hostid,$charset);
		}else{
			return false;
		}
	}
	
	/**
	 * 获取url中的主机
	 *
	 * @parame  $url         string  需要插入的url
	 * @return  $host        string  对应的主机
	 * @update  2016-03-29
	 */
	private function getUrlHost($url){
		$tempu=parse_url($url);
		if(empty($tempu['host'])){
			$host = $this->getUrlHost2($url);
		}else{
			$host = $tempu['host'];
		}
		// 判断抽取出来的主机是否有www
		if(!$this->JudgeStrIsExist($host,'www.')){
			$host = 'www.'.$host;
		}
		return $host;
	}
	private function getUrlHost2($url){
		$pattern = "/[/w-]+/.(com|net|org|gov|biz|com.tw|com.hk|com.ru|net.tw|net.hk|net.ru|info|cn|com.cn|net.cn|org.cn|gov.cn|mobi|name|sh|ac|la|travel|tm|us|cc|tv|jobs|asia|hn|lc|hk|bz|com.hk|ws|tel|io|tw|ac.cn|bj.cn|sh.cn|tj.cn|cq.cn|he.cn|sx.cn|nm.cn|ln.cn|jl.cn|hl.cn|js.cn|zj.cn|ah.cn|fj.cn|jx.cn|sd.cn|ha.cn|hb.cn|hn.cn|gd.cn|gx.cn|hi.cn|sc.cn|gz.cn|yn.cn|xz.cn|sn.cn|gs.cn|qh.cn|nx.cn|xj.cn|tw.cn|hk.cn|mo.cn|org.hk|is|edu|mil|au|jp|int|kr|de|vc|ag|in|me|edu.cn|co.kr|gd|vg|co.uk|be|sg|it|ro|com.mo)(/.(cn|hk))*/";
		preg_match($pattern, $url, $matches);
		if(count($matches) > 0) {
			return $matches[0];
		}else{
			$rs = parse_url($url);
			$main_url = $rs["host"];
			if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url)){
				return $main_url;
			}else{
				$arr = explode(".",$main_url);
				$count=count($arr);
				$endArr = array("com","net","org");//com.cn net.cn 等情况
				if (in_array($arr[$count-2],$endArr)){
					$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
				}else{
					$domain = $arr[$count-2].".".$arr[$count-1];
				}
				return $domain;
			}
		}
	}
	
	/**
	 * 获取主机对应的IP
	 *
	 * @parame  $host         string  主机名
	 * @return  $ip           string  对应IP
	 * @update  2016-03-29
	 *
	 */
	function getHostIP($host){
		$ip = gethostbyname($host);
		return $ip;
	}
	
	/**
	 * 插入host
	 *
	 * @param   $hostName         string  需要插入的hostName
	 * @return  $count
	 * @update  2016-04-07
	 *
	 */
	private function addHost($hostName){
		//	定义操作影响行数
		$count = 0;
		// 检查是否存在
		$isExist = $this->_HostEnter->isExistHost($hostName);
		if(!$isExist){
			// 如果不存在，则收录
			// 获取IP
			$hostip = $this->getHostIP($hostName);
			// 初始化时间
			$inittime = GetMkTime(time());
			$count = $this->_HostEnter->addHost($hostName, $hostip);
		}else{
			$count = $this->_HostEnter->updateHost($hostName);
		}
		return $count;
	}
	
	/**
	 * 获取网页所有url
	 *
	 * @param   $html        string  网页内容
	 * @return  $wo_alist    array   符合条件的url集合
	 * @update  2016-03-29
	 */
	function getHTMLUrlAll($html,$host){
		// 获取抓取网页上的所有url
		preg_match_all('/href=\"([\s\S]*?)\"/',$html,$alist);
		// 定义存储有效链接地址数组
		$eff_urllist = array();
		// 获取有用的url
		for($i=0,$j=0; $i<count($alist[1]);$i++){
			// 获取目标iurl
			$ourl = $alist[1][$i];
			// 获取第一个字符
			$fcha = substr($ourl,0,1);
			// 这样的url不需要
			// 过滤掉带有单引号的url
			if($ourl == '/' || $this->JudgeStrIsExist($ourl,'.css') || $this->JudgeStrIsExist($ourl,'.js') || $this->JudgeStrIsExist($ourl,'javascript') || $this->JudgeStrIsExist($ourl,'#') || $this->JudgeStrIsExist($ourl,"'") || empty($ourl)){
				continue;
			}else if($fcha != 'h' && $fcha !='w' && $fcha != '/'){
				continue;
			}else if($fcha == '/'){
				$eff_urllist[$j] = 'http://'.$host.$ourl;
				$j++;
			}else{
				$eff_urllist[$j] = $ourl;
				$j++;
			}
		}
		return $eff_urllist;
	}
	
	/**
	 * 插入url
	 *
	 * @parame  $url         string  需要插入的url
	 * @parame  $parentUrl   string  需要插入的url来源url
	 * @return  无
	 * @update  2016-03-29
	 *
	 */
	private function addUrl($url,$parentUrl){
		// 检查是否存在
		$isExist = $this->_UrlEnter->isExistUrl($url);
		if($isExist){
			// 如果存在，增加一次收录
			$count = $this->_UrlEnter->addUrlIncCount($url);
		}else{
			// 如果不存在，则收录该url
			$hostName = $this->getUrlHost($url);
			// 获取主机id
			$hostId =$this->_HostEnter->getHostId($hostName);
			//	创建url实体
			$_url = new urlEntity();
			//	初始化实体
			$_url->hostId = $hostId;
			$_url->parentUrl = $parentUrl;
			$_url->url = $url;
			
			$count = $this->_UrlEnter->addUrl($_url);
			return $count;
		}
	}
	
	/**
	 * 获取网页上一张图片，用于前段缩略显示
	 *
	 * @param   无
	 * @return  $ret_imgurl   string    图片存储在本地的位置
	 * @update  2016-04-06
	 *
	 */
	function GetHTMLImageOne(){
		// 图片存储路径
		$save_path = 'uploads/image/';
		// 以年月日为文件夹名称
		$file_name = MyDate('ymd', GetMkTime(time()));
		$save_path .='20'.$file_name;
		//如果不存在，则创建
		if(!is_dir($save_path)){
			$res=mkdir($save_path,0777,true);
			if(!$res){
				echo '创建文件夹错误<br />';
				return false;
			}
		}
		// 获取抓取网页上的所有img
		preg_match_all('/[src=|data-mce-src=][\"|\']([\S]*?.(png|jpg|jpeg|gif|bmp|swf|swc|psd|tiff|iff|jp2|jpx|jb2|jpc))[\"|\']/',$html,$ilist);
		// 定义图片数组
		$imglist = array();
		// 获取绝对路径
		for($i=0,$j=0; $i<count($ilist[1]);$i++){
			// 获取第一个字符
			$fchar = substr($ilist[1][$i],0,1);
			// 获取图片格式
			$isty = substr($ilist[1][$i],strrpos($ilist[1][$i], ".")+1);
			// 判断格式是否正确
			if($isty=='png'||$isty=='jpg'||$isty=='jpeg'||$isty=='gif'||$isty=='bmp'||$isty=='swf'||$isty=='swc'||$isty=='psd'||$isty=='tiff'||$isty=='iff'||$isty=='jp2'||$isty=='jpx'||$isty=='jb2'||$isty=='jpc'){
				if($fchar == 'w'){
					$imglist[$j] = 'http://'.$ilist[1][$i];
				}else if($fchar == '/'){
					$imglist[$j] = 'http://'.$this->host.$ilist[1][$i];
				}else{
					$imglist[$j] = $ilist[1][$i];
				}
				$j++;
			}
		}
		// for($i=0;$i<count($imglist);$i++){
		//     echo $imglist[$i].'<br />';
		// }
		// 判断函数getimagesize是否存在;
		if(!function_exists('getimagesize')){
			throw new Exception('server not install getimagesize!');
			exit();
		}
		// 定义筛选出的存储在本地图片路径
		$ret_imgurl = '';
		// 定义筛选出的远程图片路径
		$nee_imgurl = '';
		// 宽高比，默认为1
		$imgwh = 1;
		// 挑选合适图片
		for($i=0; $i<count($imglist); $i++){
			// 获取图片名称
			$imgname = substr($imglist[$i], strrpos($imglist[$i], "/")+1);
			// // 去除后缀
			// $imgname = substr($imgname, 0,strpos($imgname, "."));
			// 获取主机名
			$imghost = substr($this->host, strpos($this->host, ".")+1);
			// 去除后缀
			$imghost = substr($imghost, 0,strrpos($imghost, '.'));
			// 获取图片对象
			$imgobj  = @getimagesize($imglist[$i]);
			if(!empty($imgobj)){
				// 不选logo之类的图片
				if(!$this->JudgeStrIsExist($imgname,'logo') && !$this->JudgeStrIsExist($imgname,$imghost)){
					// 获取图片
					$img = file_get_contents($imglist[$i]);
					// 获取图片大小
					$imgsize = strlen($img)/1024;
					// 获取图片宽度和高度
					$imgw  = $imgobj[0];
					$imgh  = $imgobj[1];
					if($imgsize < 200 && $imgw >= 120 && $imgh >= 80){
						if(abs($imgw/$imgh-1.5) <= $imgwh){
							$imgwh = abs($imgw/$imgh-1.5);
							$nee_imgurl = $imglist[$i];
						}
					}
				}
			}else{
				continue;
			}
			clearstatcache();
		}
		if(!empty($nee_imgurl)){
			// 获取图片
			$img = file_get_contents($nee_imgurl);
			// 获取图片格式
			$ext = substr($nee_imgurl,strrpos($nee_imgurl, "."));
			// 设置文件名称
			$filename = $save_path.'/'.MyDate('ymdhis', GetMkTime(time())).$ext;
			// 保存图片；
			file_put_contents($filename,$img);
			// 获取文件存储在本地路径
			$ret_imgurl = $filename;
		}else{
			$ret_imgurl = '';
		}
		return $ret_imgurl;
	}
	
	/**
	 * 获取文章标题
	 *
	 * @param   $html        string  网页内容
	 * @return  $tit         string  文章标题
	 * @update  2016-03-29
	 *
	 */
	private function getHTMLTitle($html){
		// 获取本页面的标题
		preg_match_all( '/<title>([\s\S]*?)<\/title>/' , $html , $tlist );
		// 获取匹配到的title
		if(isset($tlist[1][0])){
			$tit = $tlist[1][0];
			// 去掉标题所属模块
			if($tpos = stripos($tit, '_')){
				$tit = substr($tit, 0,$tpos);
			}else if($tpos = stripos($tit, '-')){
				$tit = substr($tit, 0,$tpos);
			}else{
				$tit = $tit;
			}
		}else{
			$tit = '';
		}
		return $tit;
	}
	
	/**
	 * 获取网页内容，如果是文章，则收录，并返回true，如果非文章，返回false
	 *
	 * @param   $id			 int
	 * @param   $html	     obj
	 * @param   $title	     string
	 * @return  $errorInfo   string
	 * @update  2016-03-31
	 *
	 */
	function getHTMLContent($id,$html,$title){
		//	返回提示信息
		$stateInfo = '';
		do{
			//	判断标题出现的次数
			$times = substr_count($html, $title);
			//	$title必须出现2-4次，再多可能就是首页了
			if($times>=1 && $times<5){
				$stateInfo = '文章标题过多，可能是主页/模块主页!<br />';
				break;
			}
			
			//	获取p标签的内容
			//	preg_match_all( '/<p[\s\S]*>[\s\S]*<\/p>/' , $this->html , $plist );
			preg_match_all( '/<p>.*<\/p>/' , $this->html , $plist );
			//	print_r($plist);
			$content = '';
			for($i=0; $i<count($plist); $i++){
				for($j=0; $j<count($plist[$i]); $j++){
					//	剔除带a标签的p标签
					if(!$this->JudgeStrIsExist($plist[$i][$j],'href=')){
						$content .= str_replace("'", '"', $plist[$i][$j]);
					}
				}
			}
			//	判断$content大小
			if(strlen($content)<500){
				$stateInfo = '内容过少，可能是一则通知!<br />';
				break;
			}
			
			//	获取关键字
			preg_match_all('/<meta[\s]+name=\"keywords\"[\s]+content=\"([\s\S]*?)\"[\s]*[\/]?>/', $html, $kwlist);
			if(empty($kwlist[1][0])){
				$stateInfo = '关键字为空!<br />';
				break;
			}
			// 替换掉单引号
			$keywords = str_replace("'", '"', $kwlist[1][0]);
			// 获取当前时间的前24小时
			// $time = GetMkTime(time())-24*3600;
			// // 关键字是否存在
			// $kwisexist = false;
			// $dosql->Execute("SELECT * FROM v_db_keywords WHERE 1=1 AND inittime>'".$time."' ORDER BY seacount DESC LIMIT 0,4");
			// while($row = $dosql->GetArray()){
			//     if($this->JudgeStrIsExist($keywords,$row['keyword'])){
			//         $kwisexist = true;
			//         break;
			//     }
			// }
				
			// if(!$kwisexist){
			//     echo '非要找的内容!<br />';
			//     return false;
			// }
			
			//	获取描述
			preg_match_all('/<meta[\s]+name=\"description\"[\s]+content=\"([\s\S]*?)\"[\s]*[\/]?>/', $this->html, $deslist);
			if(empty($deslist[1][0])){
				$stateInfo = '无描述!<br />';
				break;
			}
			//	替换掉单引号
			$description = str_replace("'", '"', $deslist[1][0]);
			//	获取图片
			if($this->GetHTMLImageOne($html) === false){
				$picurl = '';
			}else{
				$picurl = $this->GetHTMLImageOne($html);
			}
			//	创建时间
			$createtime = GetMkTime(time());
			//	点击量
			$hits = mt_rand(50,100);
			//	$row = $dosql->GetOne("SELECT * FROM v_db_url WHERE url='".$this->url."'");
			//	收录
			//	创建infolist实体对象
			$infolistObj = new infolistEntity();
			//	初始化
			$infolistObj->title = $title;
			$infolistObj->urlId = $id;
			$infolistObj->keywords = $keywords;
			$infolistObj->description = $description;
			$infolistObj->picurl = $picurl;
			$infolistObj->hits = $hits;
			$infolistObj->orderid = 0;
			$count = $this->_InfolistEnter->addInfolist($infolistObj);
			if($count < 1){
				//	添加失败
				$stateInfo = '插入infolist失败<br/>';
				break;
			}
			$stateInfo = 'ok';
		}while(false);
		return $stateInfo;
	}
}
?>