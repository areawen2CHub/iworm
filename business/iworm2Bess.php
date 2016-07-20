<?php

//	引入实现接口操作文件
require_once(BASE_DATAACCESS.DATA_TYPE.'UrlEnter.php');
require_once(BASE_DATAACCESS.DATA_TYPE.'HostEnter.php');
require_once(BASE_DATAACCESS.DATA_TYPE.'InfolistEnter.php');
require_once(BASE_DATAACCESS.DATA_TYPE.'CharsetEnter.php');
require_once(BASE_DATAACCESS.DATA_TYPE.'KeywordsEnter.php');

//	引入model文件
require_once(BASE_MODEL.'iworm2Model.php');

class iworm2Bess{
	//	定义url数据库操作对象
	private $_UrlEnter;
	//	定义host(主机)数据库操作对象
	private $_HostEnter;
	//	定义infolist(信息列表)数据库操作对象
	private $_InfolistEnter;
	//	定义charset(字符集表)数据库操作对象
	private $_CharsetEnter;
	//	定义charset(字符集表)数据库操作对象
	private $_KeywordsEnter;

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
		$this->_CharsetEnter = new CharsetEnter();
		$this->_KeywordsEnter = new KeywordsEnter();
	}
	
	/**
	 * 获取待抓取的主机
	 *
	 * @return 主机地址
	 * @update 2016-07-20
	 */
	public function getSearchHost(){
		$hostObj = $this->_HostEnter->getSearchHost();
		$_searchHostModel = new searchHostModel();
		$_searchHostModel->hostId = $hostObj[0]["id"];
		$_searchHostModel->hostName = $hostObj[0]["hostname"];
		return $_searchHostModel;
	}
	
	/**
	 * 初始化抓取
	 *
	 * @param	int		$hostName
	 * @param	string	$url
	 * @update  2016-07-20
	 */
	public function initHtml($hostId,$hostName){
		//	定义返回提示信息
		$backCode = '';
		//	操作影响行数
		$count = 0;
		do
		{
			//	记录对该主机的访问
			if(!empty($hostName)){
				$count = $this->_HostEnter->recordVisitHost($hostName);
			}
			
			//	获取Html对象
			$html = $this->getHtmlObj($hostName);
			//	判断抓取的网页
			if($html === false || strlen($html) < 10){
				break;
			}
			
			//	获取所有url
			$allUrl = $this->getHtmlUrlAll($html, $hostName);
			for($i=0;$i<count($allUrl);$i++){
				//	获取主机
				$hostName2 = $this->getUrlHost($allUrl[$i]);
				if(!empty($hostName2)){
					// 收录主机，或者增加主机收录的url数
					$count = $this->addHost($hostName2);
				}
				
				//	抓取网页内容
				$html = $this->getHtmlObj($allUrl[$i]);
				//	判断抓取的网页
				if($html === false || strlen($html) < 10){
					//	继续抓取下一个
					continue;
				}
				
				//	获取字符集
				$charset = $this->getHtmlCharset($html);
				//	如果不为空，则转换
				if($charset != ''){
					//	转换字符集
					$html = $this->changeHtmlCharset($charset, $html);
					//	插入字符集
					$this->addCharset($hostId,$charset);
				}
				
// 				//	判断$charset
// 				if(empty($charset)){
// 					//	
// 					continue;
// 				}
					
				//	获取文章titie
				$title = $this->getHtmlTitle($html);
				if(empty($title)){
					continue;
				}
				
				//	判断标题出现的次数
				$times = substr_count($html, $title);
				//	$title必须出现2-4次，再多可能就是首页了
				if($times == 0 || $times >=5){
					//	标题过多
					continue;
				}
				
				$keywords = $this->getHtmlKeywords($html);
				//	判断是否存在关键字，或者关键字是否过长
				if($keywords == '' || strlen($keywords) >= 1000){
					continue;
				}
				//	替换单引号
				$keywords = str_replace("'", '"', $keywords);
				
				$desc = $this->getHtmlDes($html);
				//	替换单引号
				$desc = str_replace("'", '"', $desc);
				//	判断描述是否存在，或者关键字是否过长
				if($desc == '' || strlen($desc) >= 1000){
					continue;
				}
				
				//	获取图片
				if($this->GetHtmlImageOne($html,$hostName2) === false){
					$picurl = '';
				}else{
					$picurl = $this->GetHtmlImageOne($html,$hostName2);
				}
				
				//	创建时间
				$createtime = GetMkTime(time());
				//	点击量
				$hits = mt_rand(50,100);
				
				//	创建infolist实体对象
				$infolistObj = new infolistEntity();
				//	初始化
				$infolistObj->title = $title;
				$infolistObj->urlId = 0;
				$infolistObj->keywords = $keywords;
				$infolistObj->description = $desc;
				$infolistObj->picurl = $picurl;
				$infolistObj->hits = $hits;
				$infolistObj->orderid = 0;
				$infolistObj->url = $allUrl[$i];
				$count = $this->_InfolistEnter->addInfolist($infolistObj);
			}
		}while (false);
	}
	
	/**
	 * 函数说明：抓取网页，返回网页对象或者空或者false
	 *
	 * @access  public
	 * @param	string	$url
	 * @return  $html       string  网页对象
	 * @update  2016-03-29
	 */
	private function getHtmlObj($url){
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
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$html = curl_exec($ch);
		}
	
		// 关闭cURL对象
		if ($html == false) {
			curl_close($ch);
		}
		@curl_close($ch);
	
		return $html;
	}
	
	/**
	 * 获取网页所有url
	 *
	 * @param   $html        string  网页内容
	 * @param   $hostName    string  主机名
	 * @return  $wo_alist    array   符合条件的url集合
	 * @update  2016-03-29
	 */
	function getHtmlUrlAll($html,$hostName){
		//	获取抓取网页上的所有url
		preg_match_all('/href=\"([\s\S]*?)\"/',$html,$alist);
		//	定义存储有效链接地址数组
		$eff_urllist = array();
		//	获取有用的url
		for($i=0,$j=0; $i<count($alist[1]);$i++){
			//	获取目标iurl
			$ourl = $alist[1][$i];
			//	获取第一个字符
			$fcha = substr($ourl,0,1);
			//	这样的url不需要
			//	过滤掉带有单引号的url
			if($ourl == '/' || IsExistStr($ourl,'.css') || IsExistStr($ourl,'.js') || IsExistStr($ourl,'javascript') || IsExistStr($ourl,'#') || IsExistStr($ourl,"'") || empty($ourl)){
				continue;
			}else if($fcha != 'h' && $fcha !='w' && $fcha != '/'){
				continue;
			}else if($fcha == '/'){
				$eff_urllist[$j] = 'http://'.$hostName.$ourl;
				$j++;
			}else{
				$eff_urllist[$j] = $ourl;
				$j++;
			}
		}
		return $eff_urllist;
	}
	
	/**
	 * 获取网页charset,返回charset值
	 *
	 * @param   $html		obj
	 * @return  $cha        string
	 * @update  2016-03-29
	 */
	private function getHtmlCharset($html){
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
	private function changeHtmlCharset($charset,$html){
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
		$isExist = $this->_CharsetEnter->isExistCharset($hostid);
		if(!$isExist){
			//	收录charset
			$this->_CharsetEnter->addCharset($hostid,$charset);
		}else{
			return false;
		}
	}
	
	/**
	 * 获取url中的主机
	 *
	 * @parame  $url         string  需要插入的url
	 * @return  $hostName    string  对应的主机
	 * @update  2016-03-29
	 */
	private function getUrlHost($url){
		$tempu=parse_url($url);
		if(empty($tempu['host'])){
			$hostName = $this->getUrlHost2($url);
		}else{
			$hostName = $tempu['host'];
		}
		// 判断抽取出来的主机是否有www
		if(!IsExistStr($hostName,'www.')){
			$hostName = 'www.'.$hostName;
		}
		return $hostName;
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
		//	检查是否存在
		$isExist = $this->_HostEnter->isExistHost($hostName);
		if(!$isExist){
			//	如果不存在，则收录
			//	获取IP
			$hostip = $this->getHostIP($hostName);
			//	初始化时间
			$inittime = GetMkTime(time());
			$count = $this->_HostEnter->addHost($hostName, $hostip);
		}else{
			//$count = $this->_HostEnter->updateHost($hostName);
		}
		return $count;
	}
	
	/**
	 * 获取文章标题
	 *
	 * @param   $html        string  网页内容
	 * @return  $tit         string  文章标题
	 * @update  2016-03-29
	 *
	 */
	private function getHtmlTitle($html){
		//	获取本页面的标题
		preg_match_all( '/<title>([\s\S]*?)<\/title>/' , $html , $tlist );
		//	获取匹配到的title
		if(isset($tlist[1][0])){
			$tit = $tlist[1][0];
			//	去掉标题所属模块
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
	 * 获取关键字
	 * 
	 * @param 	obj 	$html
	 * @return 	string  $keywords
	 * @update	2016-07-20
	 */
	private function getHtmlKeywords($html){
		$keywords = '';
		//	获取关键字
		preg_match_all('/<meta[\s]+name=\"keywords\"[\s]+content=\"([\s\S]*?)\"[\s]*[\/]?>/', $html, $kwlist);
		if(!empty($kwlist[1][0])){
			//	替换掉单引号
			$keywords = str_replace("'", '"', $kwlist[1][0]);
		}
		return $keywords;
	}
	
	/**
	 * 获取描述
	 *
	 * @param 	obj 	$html
	 * @return 	string  $description
	 * @update	2016-07-20
	 */
	private function getHtmlDes($html){
		$description = '';
		//	获取描述
		preg_match_all('/<meta[\s]+name=\"description\"[\s]+content=\"([\s\S]*?)\"[\s]*[\/]?>/', $html, $deslist);
		if(!empty($deslist[1][0])){
			$description = str_replace("'", '"', $deslist[1][0]);
		}
		return $description ;
	}
	
	/**
	 * 获取网页上一张图片，用于前段缩略显示
	 *
	 * @param   $html 		  obj
	 * @param   $hostName     string
	 * @return  $ret_imgurl   string    图片存储在本地的位置
	 * @update  2016-04-06
	 *
	 */
	function GetHtmlImageOne($html,$hostName){
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
					$imglist[$j] = 'http://'.$hostName.$ilist[1][$i];
				}else{
					$imglist[$j] = $ilist[1][$i];
				}
				$j++;
			}
		}

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
			$imghost = substr($hostName, strpos($hostName, ".")+1);
			// 去除后缀
			$imghost = substr($imghost, 0,strrpos($imghost, '.'));
			// 获取图片对象
			$imgobj  = @getimagesize($imglist[$i]);
			if(!empty($imgobj)){
				// 不选logo之类的图片
				if(!IsExistStr($imgname,'logo') && !IsExistStr($imgname,$imghost)){
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
}
?>