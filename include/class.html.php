<?php   if(!defined('IN_VYAHUI')) exit('Request Error!');

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

class HTML
{
	var $url;
	var $html;
    var $char;
    var $host;

    // 构造函数初始化$url
	function HTML($url){
        // 初始化$url
		$this->url  = $url;
        // 初始化$html
		$this->html = $this->GetHTML();
        // 初始化￥char
        $this->char = $this->GetHTMLCharset();
        // 转换charset
        $this->ChangeHTMLCharset();
        // 初始化$host
        $this->host = $this->GetUrlHost($url);
	}
    
    /*
    * 函数说明：判断字符串中是否存在某个字符/字符串
    * 
    * @access  public
    * @parame  $objStr     string  目标字符串
    * @parame  $str        string  查询字符串
    * @return  fasle/true  bool    查询结果
    * @update  2016-03-28
    *
    */
    function StrIsExist($objStr,$str){
        return false !== strpos($objStr,$str); 
    }
    /*
    * 函数说明：抓取网页
    * 
    * @access  public
    * @parame  $url        string  网页地址
    * @return  $html       string  网页内容
    * @update  2016-03-29
    *
    */
    function GetHTML(){
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
        // 拆分$html
        //list($header, $html) = explode("\r\n\r\n", $html);    

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
    * 函数说明：获取网页charset
    * 
    * @access  public
    * @parame  无
    * @return  无
    * @update  2016-03-29
    *
    */
    function GetHTMLCharset(){
        // 获取本页面上所有charset
        preg_match_all('/charset=([\S]*)/',$this->html,$char);
        // 获取目标字符串
        $str = $char[1][0];
        // 如果没有，则不作处理
        if(empty($str)){
            $char = $str;
        }else{
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
                $char = substr($str, $fpos,$lpos);
            }else{
                $char = $str;
            }
        }    
        return $char;
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
        if(!empty($this->char)){
            $this->html = iconv( $this->char , "utf-8//IGNORE" , $this->html);
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
        $tit = $tlist[1][0];    
        if($tpos = stripos($tit, '_')){
            $tit = substr($tit, 0,$tpos);
        }else if($tpos = stripos($tit, '-')){
            $tit = substr($tit, 0,$tpos);
        }else{
            $tit = $tit;
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
    // function GetHTMLUrlAll(){
    // 	// 获取抓取网页上的所有url
    // 	preg_match_all('/href=\"([\s\S]*?)\"/',$this->html,$alist);
    //     // 定义存储a链接地址数组
    //     $wo_alist = array();
    //     // 获取有用的url
    //     for($i=0,$j=0; $i<count($alist[2]);$i++){
    //     	if($this->StrIsExist($alist[2][$i],'http://') || $this->StrIsExist($alist[2][$i],'https://')){
    //     		// 如果链接(a)中存在其它属性，则需要截取出href属性值
    //     		if($apos = stripos($alist[2][$i], '"')){
    //     			$wo_alist[$j] = substr($alist[2][$i], 0,$apos);
    //     		}else{
    //     			$wo_alist[$j] = $alist[2][$i];
    //     		}
    //     		$j++;
    //     	}
    //     }
    //     return $wo_alist;
    // }

    function GetHTMLUrlAll(){
    	// 获取抓取网页上的所有url
    	preg_match_all('/href=\"([\s\S]*?)\"/',$this->html,$alist);
        // 定义存储a链接地址数组
        $wo_alist = array();
        // 获取有用的url
        for($i=0,$j=0; $i<count($alist[1]);$i++){
            // 获取目标iurl
            $ourl  = $alist[1][$i];
            // 获取第一个字符
            $fchar = substr($ourl,0,1);
            // 这样的url不需要
            if($ourl == '/' || $this->StrIsExist($ourl,'.css') || $this->StrIsExist($ourl,'.js') || $this->StrIsExist($ourl,'javascript') || $this->StrIsExist($ourl,'#') || empty($ourl)){
                continue;
            }else if($fchar != 'h' && $fchar !='w' && $fchar != '/'){
                continue;
            }else if($fchar == '/'){
                $wo_alist[$j] = $this->host.$ourl;
                $j++;
            }else{
                $wo_alist[$j] = $ourl;
                $j++;
            }
        }
        return $wo_alist;
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
        	// $host = $this->GetUrlHost2($url);
        }else{
        	$host = $tempu['host'];
        }
        // 判断抽取出来的主机是否有www
        if(!$this->StrIsExist($host,'www.')){
        	$host = 'www.'.$host;
        }
        return $host;  
    }

    // function GetUrlHost2($url){  
    // 	$pattern = "/[/w-]+/.(com|net|org|gov|biz|com.tw|com.hk|com.ru|net.tw|net.hk|net.ru|info|cn|com.cn|net.cn|org.cn|gov.cn|mobi|name|sh|ac|la|travel|tm|us|cc|tv|jobs|asia|hn|lc|hk|bz|com.hk|ws|tel|io|tw|ac.cn|bj.cn|sh.cn|tj.cn|cq.cn|he.cn|sx.cn|nm.cn|ln.cn|jl.cn|hl.cn|js.cn|zj.cn|ah.cn|fj.cn|jx.cn|sd.cn|ha.cn|hb.cn|hn.cn|gd.cn|gx.cn|hi.cn|sc.cn|gz.cn|yn.cn|xz.cn|sn.cn|gs.cn|qh.cn|nx.cn|xj.cn|tw.cn|hk.cn|mo.cn|org.hk|is|edu|mil|au|jp|int|kr|de|vc|ag|in|me|edu.cn|co.kr|gd|vg|co.uk|be|sg|it|ro|com.mo)(/.(cn|hk))*/";  
    //     preg_match($pattern, $url, $matches);  
    //     if(count($matches) > 0) {  
    //         return $matches[0];  
    //     }else{  
    //         $rs = parse_url($url);  
    //         $main_url = $rs["host"];  
    //         if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url)){  
    //             return $main_url;  
    //         }else{  
    //             $arr = explode(".",$main_url);  
    //             $count=count($arr);  
    //             $endArr = array("com","net","org");//com.cn net.cn 等情况  
    //             if (in_array($arr[$count-2],$endArr)){  
    //                 $domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];  
    //             }else{  
    //                 $domain = $arr[$count-2].".".$arr[$count-1];  
    //             }  
    //             return $domain;  
    //         }  
    //     }  
    // }
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
    * 函数说明：插入一个url
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
    	if(is_array($row)){
    		// 如果存在，增加一次收录
            $dosql->ExecNoneQuery("UPDATE v_db_infourl SET inctimes=inctimes+1 WHERE url='".$url."'");
    	}else{
            // 如果不存在，则收录该url
            // 获取主机
            echo $url;
            $host = $this->GetUrlHost($url);
            // 获取IP
            $ip = $this->GetHostIP($host);
            // 创建时间
            $createtime = GetMkTime(time());    

            $sql = "INSERT INTO v_db_infourl (url, urlip, urlhost, createtime, inctimes, incstate,delstate) VALUES ('".$url."', '".$ip."', '".$host."', '".$createtime."', 1, 'false','false')";
            $dosql->ExecNoneQuery($sql);    

         //    if($dosql->ExecNoneQuery($sql)){
    		   //  header("location:$url");
    		   //  exit();
    	    // }
    	}
    }
    /*
    * 函数说明：判断该url对应的网页是否为文章
    * 
    * @access  public
    * @parame  $url         string  需要插入的url
    * @return  false/true   bool    是/否     
    * @update  2016-03-30
    *
    */
    function UrlIsArticle(){
    	// 不管该页面是否为需要的文章界面，都要将其中的url收集起来，作为下一次抓取的原url
    	// 获取html上所有的url
    	$urlall = $this->GetHTMLUrlAll(); 
    	// 已经收录的，增加收录次数，未收录的，收录进去
        // print_r($urlall);
        // echo count($urlall);
    	for($i=0; $i<count($urlall); $i++){
            echo $urlall[$i].'<br />';
    	    // $this->AddUrl($urlall[$i]);
        }    

        // 获取title
        $title = $this->GetHTMLTitle();
        // echo $title;    

        // 判断标题出现的次数
        $times = substr_count($this->html, $title);
        // echo $times;    
    }
}
?>