<?php   if(!defined('IN_VYAHUI')) exit('Request Error!');
header("content-Type: text/html; charset=utf-8");

/*
**************************
(C)2016-2016 vyahui.com
update: 2016-3-30 21:42:55
person: zhang
**************************
*/

/*
 * html类
 * 
 * 该类定义了完成html操作相关的所有方法
 */

// 不限制响应时间
@set_time_limit(0);

class HTML
{
	var $url;
	var $html;
    var $charset;
    var $host;
    var $status;

    // 初始化变量
    function __construct($url){
        // 初始化
        $this->status = $this->Initialise($url);
    }

    // 兼容低版本
	function HTML($url){
         $this->__construct($url);
	}

    // 初始化
    function Initialise($url){
        // $url为空，则返回
        if(empty($url)){
            return false;
        }
        // 初始化$url
        $this->url  = $url;
        // 初始化$html
        $this->html = $this->GetHTMLObj();
        if(!empty($this->html) && $this->html != false){
            // 初始化$charset
            $this->charset = $this->GetHTMLCharset();
            // 转换charset
            $this->ChangeHTMLCharset();
            // 收录charset
            $this->AddCharset(); 
            // 初始化$host
            $this->host = $this->GetUrlHost($url);   
            // 不管该页面是否为需要的文章界面，都要将其中的url收集起来，作为下一次抓取的原url
            // 获取html上所有的url
            $allurl = $this->GetHTMLUrlAll(); 
            // 已经收录的，增加收录次数，未收录的，收录进去
            for($i=0; $i<count($allurl); $i++){
                $this->AddUrl($allurl[$i]);
            }
            // 获取网页内容
            global $dosql; 
            if(!$this->GetHTMLContent()){
                $dosql->ExecNoneQuery("UPDATE v_db_infourl SET delstate=true WHERE url='".$this->url."'"); 
                return false;
            }
            $dosql->ExecNoneQuery("UPDATE v_db_infourl SET incstate=true WHERE url='".$this->url."'");
            return true;
        }else{
            return false;
        }
    }
    /*
    * 函数说明：判断字符串中是否存在某个字符/字符串
    * 
    * @access  public
    * @parame  $objstr     string  目标字符串
    * @parame  $handstr    string  操作字符串
    * @return  fasle/true  bool    查询结果
    * @update  2016-03-28
    *
    */
    function JudgeStrIsExist($objstr,$handstr){
        return false !== strpos($objstr,$handstr); 
    }
    /*
    * 函数说明：抓取网页，返回网页对象或者空或者false
    * 
    * @access  public
    * @parame  无        
    * @return  $html       string  网页对象
    * @update  2016-03-29
    *
    */
    function GetHTMLObj(){
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
    	curl_setopt ($ch, CURLOPT_URL, $this->url);
        // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
        // 运行URL，获取页面
        $html = curl_exec($ch);    
        // echo $html;
        // 拆分$html
        if(isset($html)){
            list($header, $html) = explode("\r\n\r\n", $html);   
        }else{
            $html = '';
        }

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

        // 关闭cURL对象
        if ($html == false) {  
            curl_close($ch);  
        }  
        @curl_close($ch);     

        return $html;
    }
    /*
    * 函数说明：获取网页charset,返回charset值
    * 
    * @access  public
    * @parame  无
    * @return  $cha        string 
    * @update  2016-03-29
    *
    */
    function GetHTMLCharset(){
        // 获取本页面上所有charset
        preg_match_all('/charset=([\S]*)/',$this->html,$chalist);
        // 获取目标字符串
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
    /*
    * 函数说明：改变网页charset
    * 
    * @access  public
    * @parame  无
    * @return  无
    * @update  2016-03-29
    *
    */
    function ChangeHTMLCharset(){
        if(!empty($this->charset)){
            $this->html = iconv($this->charset , "utf-8//IGNORE" , $this->html);
        }
    }
    /*
    * 函数说明：插入charset
    * 
    * @access  public
    * @parame  $url         string  需要插入的url
    * @return  无
    * @update  2016-03-29
    *
    */
    function AddCharset(){    
        global $dosql;    
        $row = $dosql->GetOne("SELECT * FROM v_db_charset WHERE host='".$this->host."'");
        // 检查是否存在
        if(!is_array($row) && !empty($this->charset)){
            // 如果不存在，则收录
            $sql = "INSERT INTO v_db_charset (charset, host) VALUES ('".$this->charset."', '".$this->host."')";
            if(!$dosql->ExecNoneQuery($sql)){
                throw new Exception('AddCharset插入语句错误'.$sql);  
                exit();
            }
        }else{
            return false;
        }
    }
    /*
    * 函数说明：获取文章标题
    * 
    * @access  public
    * @parame  $html        string  网页内容
    * @return  $tit         string  文章标题
    * @update  2016-03-29
    *
    */
    function GetHTMLTitle(){
        // 获取本页面的标题
        preg_match_all( '/<title>([\s\S]*?)<\/title>/' , $this->html , $tlist );
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
    /*
    * 函数说明：获取网页所有url
    * 
    * @access  public
    * @parame  $html        string  网页内容
    * @return  $wo_alist    array   符合条件的url集合
    * @update  2016-03-29
    *
    */
    function GetHTMLUrlAll(){
    	// 获取抓取网页上的所有url
    	preg_match_all('/href=\"([\s\S]*?)\"/',$this->html,$alist);
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
                $eff_urllist[$j] = 'http://'.$this->host.$ourl;
                $j++;
            }else{
                $eff_urllist[$j] = $ourl;
                $j++;
            }
        }
        return $eff_urllist;
    }
    /*
    * 函数说明：获取url中的主机
    * 
    * @access  public
    * @parame  $url         string  需要插入的url
    * @return  $host        string  对应的主机
    * @update  2016-03-29
    *
    */
    function GetUrlHost($url){
        $tempu=parse_url($url);      
        if(empty($tempu['host'])){
            $host = $this->GetUrlHost2($url);
        }else{
            $host = $tempu['host'];
        }    
        // 判断抽取出来的主机是否有www
        if(!$this->JudgeStrIsExist($host,'www.')){
            $host = 'www.'.$host;
        }    
        return $host;  
    }
    function GetUrlHost2($url){  
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
    /*
    * 函数说明：获取主机对应的IP
    * 
    * @access  public
    * @parame  $host         string  主机名
    * @return  $ip           string  对应IP
    * @update  2016-03-29
    *
    */
    function GetHostIP($host){
    	$ip = gethostbyname($host);
    	return $ip;
    }
    /*
    * 函数说明：插入url
    * 
    * @access  public
    * @parame  $url         string  需要插入的url
    * @return  无
    * @update  2016-03-29
    *
    */
    function AddUrl($url){    
    	global $dosql;    
    	$row = $dosql->GetOne("SELECT * FROM v_db_infourl WHERE url='".$url."'");
    	// 检查是否存在
        // 如果存在，增加一次收录
    	if(is_array($row)){
            $dosql->ExecNoneQuery("UPDATE v_db_infourl SET inctimes=inctimes+1 WHERE url='".$url."'");
    	}else{
            // 如果不存在，则收录该url
            // 获取主机
            $host = $this->GetUrlHost($url);
            // 获取IP
            $ip = $this->GetHostIP($host);
            // 创建时间
            $createtime = GetMkTime(time());    

            $sql = "INSERT INTO v_db_infourl (parenturl, url, urlip, urlhost, createtime, inctimes, incstate,delstate) VALUES ('".$this->url."', '".$url."', '".$ip."', '".$host."', '".$createtime."', 1, 'false','false')";
            if(!$dosql->ExecNoneQuery($sql)){
                throw new Exception('AddUrl插入语句错误'.$sql);  
                exit();
            }
    	}
    }
    /*
    * 函数说明：获取网页上一张图片，用于前段缩略显示
    * 
    * @access  public
    * @parame  无
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
                return false;
            }
        }
        // 获取抓取网页上的所有img
        preg_match_all('/[src=|data-mce-src=][\"|\']([\S]*?.(png|jpg|jpeg|gif|bmp|swf|swc|psd|tiff|iff|jp2|jpx|jb2|jpc))[\"|\']/',$this->html,$ilist);
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
        }
        return $ret_imgurl;
    }
    /*
    * 函数说明：获取网页内容，如果是文章，则收录，并返回true，如果非文章，返回false
    * 
    * @access  public
    * @parame  无
    * @return  false/true   bool    是/否     
    * @update  2016-03-31
    *
    */
    function GetHTMLContent(){  
        // charset不能为空，为空则输出信息返回
        if(empty($this->charset)){
            echo 'charset为空，非所需信息!<br />';
            return false;
        }else{
            // 获取title
            $title = $this->GetHTMLTitle(); 
            // 如果标题为空，直接跳出
            if(empty($title)){
                echo 'title为空，非所需信息!<br />';
                return false;
            }else{
                // 判断标题出现的次数
                $times = substr_count($this->html, $title);
                // $title必须出现2-4次，再多可能就是首页了
                if($times>=1 && $times<5){
                    // 获取p标签的内容
                    // preg_match_all( '/<p[\s\S]*>[\s\S]*<\/p>/' , $this->html , $plist );
                    preg_match_all( '/<p>.*<\/p>/' , $this->html , $plist );
                    // print_r($plist);
                    $content = '';
                    for($i=0; $i<count($plist); $i++){
                        for($j=0; $j<count($plist[$i]); $j++){
                            // 剔除带a标签的p标签
                            if(!$this->JudgeStrIsExist($plist[$i][$j],'href=')){
                                $content .= str_replace("'", '"', $plist[$i][$j]);
                            }
                        }
                    }
                    // 判断$content大小
                    if(strlen($content)>=1000){
                        // 获取关键字
                        preg_match_all('/<meta[\s]+name=\"keywords\"[\s]+content=\"([\s\S]*?)\"[\s]*[\/]?>/', $this->html, $kwlist);
                        if(isset($kwlist[1][0])){
                            // 替换掉单引号
                            $keywords = str_replace("'", '"', $kwlist[1][0]);
                        }else{
                            $keywords = '';
                        }
                        // 获取描述
                        preg_match_all('/<meta[\s]+name=\"description\"[\s]+content=\"([\s\S]*?)\"[\s]*[\/]?>/', $this->html, $deslist);
                        if(isset($deslist[1][0])){
                            // 替换掉单引号
                            $description = str_replace("'", '"', $deslist[1][0]);
                            // echo '关键字'.$description.'<br />';
                        }else{
                            $description = '';
                        }
                        if(empty($keywords) || empty($description)){
                            echo '无关键词或描述!<br />';
                            return false;
                        }else{
                            // 获取图片
                            $picurl = $this->GetHTMLImageOne();
                            // 创建时间
                            $createtime = GetMkTime(time()); 
                            // 点击量
                            $hits = mt_rand(50,100);
                            global $dosql;    
                            $row = $dosql->GetOne("SELECT * FROM v_db_infoarticle WHERE ourl='".$this->url."'");
                            // 如果已存在，则退出
                            if(is_array($row)){
                                echo '已收录!<br />';
                                return false;
                            }else{
                                // 如果不存在，则收录
                                $sql = "INSERT INTO v_db_infoarticle (title, isoriginal, ourl, keywords, description, content, picurl, hits, orderid, createtime, checkinfo, delstate) VALUES ('".$title."', 'false', '".$this->url."', '".$keywords."', '".$description."', '".$content."', '".$picurl."', '".$hits."', '0', '".$createtime."', 'true', 'false')";
                                if(!$dosql->ExecNoneQuery($sql)){
                                    throw new Exception('GetHTMLContent插入语句错误'.$sql);  
                                    exit();
                                }else{
                                    echo '插入成功!<br />';
                                    return true;
                                }
                            }
                        }
                    }else{
                        echo '内容过少，可能是一则通知!<br />';
                        return false;
                    }
                }else{
                    echo '文章标题过多，可能是主页/模块主页!<br />';
                    return false;
                }         
            }            
        }
    }
}
?>
