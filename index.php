<?php require_once(dirname(__FILE__).'/include/fore.entr.php'); // 引入前段入口文件
// 判断是否为详细页
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $row = $dosql->GetOne("SELECT * FROM v_db_infolist WHERE id='".$id."'");
    // if(isset($row)){
    //     echo '<div class="content"><div class="title"><h2>'.$row['title'].'</h2></div><div class="date"><span>更新时间：20'.MyDate('y-m-d', $row['createtime']).'</span><span>阅读量：'.$row['hits'].'</span></div><p>'.$row['content'].'</p></div>';
    // }else{
    //     echo '<p>资料更新中...</p>';
    // }
    $title       = $row['title'];
    $keywords    = $row['keywords'];
    $description = $row['description'];
}else{
    $title       = '为雅汇-做雅人';
    $keywords    = '为雅汇-做雅人';
    $description = '为雅汇-做雅人';;
}
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta charset="utf-8">
<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
<meta name="description" content="<?php echo $description; ?>">
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 , maximum-scale=1.0, user-scalable=0">
	<title><?php echo $title; ?></title>
<!-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="css/subgroups.base.css" media="screen">
<link rel="stylesheet" href="css/subgroups.main.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>
<!-- 头部 start -->
<div id="header-home" class="header-home">
    <div class="header-sign">
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="header-sign-left">
                        <!-- <i class="icon-phone"></i>18107459923 -->
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="header-sign-left">
                        <!-- <i class="icon-envelope"></i>vyahui@admin.com -->
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="header-sign-right text-right">
                        <ul>
                            <li><i class="icon-phone"></i>18107459923</li>
                            <li><i class="icon-envelope"></i>vyahui@admin.com</li>
<!--                             <li><a href="#" class="icon-trophy"></a></li>
                            <li><a href="#" class="icon-group"></a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-main nav-fixed-situ">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-6">
                    <div class="header-logo"><img src="images/header-logo.png"></div>
                </div>
                <div class="col-md-9 col-sm-8 col-xs-6">
                    <div class="header-menu">
                        <ul id="nav">
                           <li class="current"><a href=""><i class="icon-home"></i>&nbsp;首页</a></li>
                           <li><a href=""><i class="icon-comments"></i>&nbsp;娱乐新闻</a></li>
                           <li><a href=""><i class="icon-book"></i>&nbsp;科技人文</a></li>
                           <li><a href=""><i class="icon-info-sign"></i>&nbsp;当下时政</a></li>
                           <li><a href=""><i class="icon-question-sign"></i>&nbsp;奇闻趣事</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 头部 end -->
<div class="blank-120"></div>
<!-- mainbody start -->
<div class="mainbody">
    <div class="container">
        <div class="row">
        	<div class="col-md-8">
        		<div id="banner" class="banner slides-image-m1">
                	<div class="slides-pic">
                		<ul>
                			<li class="current"><img src="images/banner/1m.jpg"></li>
                			<li><img src="images/banner/2m.jpg"></li>
                			<li><img src="images/banner/3m.jpg"></li>
                			<li><img src="images/banner/4m.jpg"></li>
                			<li><img src="images/banner/5m.jpg"></li>
                		</ul>
                	</div>
            	</div>
            	<?php 
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    //增加一次点击量
                    $dosql->ExecNoneQuery("UPDATE v_db_infolist SET hits=hits+1 WHERE id='".$id."'");
                    $row = $dosql->GetOne("SELECT * FROM v_db_infolist WHERE id='".$id."'");
                    if(isset($row)){
                        echo '<div class="content"><div class="title"><h2>'.$row['title'].'</h2></div><div class="date"><span>更新时间：20'.MyDate('y-m-d', $row['createtime']).'</span><span>阅读量：'.$row['hits'].'</span></div><p>'.$row['content'].'</p></div>';
                    }else{
                        echo '<p>资料更新中...</p>';
                    }
                }else{
                    // 定义查询用sql语句
                    $checksql = "SELECT * FROM v_db_infolist WHERE 1=1 AND delstate='false' AND picurl !='' AND checkinfo=true ORDER BY createtime DESC LIMIT 0,20";
                    if(isset($_POST['keywords']) && htmlspecialchars($_POST['keywords']) !==''){
                        // 存在关键，则重新写$checksql
                        $checksql = "SELECT * FROM v_db_infolist WHERE 1=1 AND delstate='false' AND picurl !='' AND checkinfo=true AND (";
                        // 获取关键字
                        $keywords = htmlspecialchars($_POST['keywords']);
                        // 将中文逗号替换成英文逗号
                        $keywords = str_replace('，', ',', $keywords);
                        // 按空格拆分
                        $kw1 = explode(' ', $keywords);
                        // 定义收集关键词数组
                        $kwarr = array();
                        $k = 0;
                        for($i=0; $i<count($kw1); $i++){
                            $kw2 = explode(',', $kw1[$i]);
                            for($j=0; $j<count($kw2); $j++){
                                if(!empty($kw2[$j])){
                                    $kwarr[$k] = $kw2[$j];
                                    $k++;
                                }
                            }
                        }
                        // description sql部分
                        $dessql = '';
                        // 收录关键字
                        for($i=0; $i<count($kwarr); $i++){
                            // 拼接$checksql
                            if($i==0){
                                $checksql .= "title like '%".$kwarr[$i]."%'";
                                $dessql   .= "description like '%".$kwarr[$i]."%'";
                            }else{
                                $checksql .= " or title like '%".$kwarr[$i]."%'";
                                $dessql   .= " or description like '%".$kwarr[$i]."%'";
                            }
                            // 判断24小时之内是否收录过，收录则增加收录次数
                            $restime = GetMkTime(time())-24*3600;
                            // 检查是否存在
                            $row = $dosql->GetOne("SELECT * FROM v_db_keywords WHERE keyword='".$kwarr[$i]."' AND inittime > ".$restime."");
                            if(is_array($row)){
                                // 如果存在，增加一次收录
                                $dosql->ExecNoneQuery("UPDATE v_db_keywords SET seacount=seacount+1 WHERE id='".$row['id']."'");
                            }else{
                                $inittime = GetMkTime(time()); 
                                $sql = "INSERT INTO v_db_keywords (keyword, seacount, inittime) VALUES ('".$kwarr[$i]."', '1', '".$inittime."')";
                                if(!$dosql->ExecNoneQuery($sql)){
                                    throw new Exception('关键字插入语句错误'.$sql);  
                                    exit();
                                }
                            } 
                        }
                        //
                        $checksql = $checksql.") AND (".$dessql.") ORDER BY createtime DESC LIMIT 0,10";
                    }
                    // echo $checksql;
                    $dosql->Execute($checksql);
                    while($row = $dosql->GetArray())
                    {
                        // 获取链接地址
                        $linkurl = 'index.php???&cid='.GetRandNum(2).'&id='.$row['id'].'###'.GetRandStr(40).'???&id='.GetRandNum(2).'???'.GetRandStr(20).'+++';
                        // 获取图片
                        if($row['picurl']!='')
                            $picurl = $row['picurl'];
                        else
                            $picurl = 'images/alt180x120.png';
                    ?>
                    <ul>
                        <li style="padding: 3px 0;">
                            <div class="image image-text-list">
                                 <div class="inner-area-image"><img src="<?php echo $picurl;?>" width="180px;" height="120px;"></div>
                                 <div class="inner-area-content">
                                    <a href="<?php echo $linkurl; ?>"><h3><?php
                                    $tit = ReStrLen($row['title'],32); 
                                    // 关键字高亮
                                    if(isset($_POST['keywords']) && htmlspecialchars($_POST['keywords']) !=='' && !empty($kwarr)){
                                        for($i=0; $i<count($kwarr); $i++){
                                            $tit = str_replace($kwarr[$i], '<font color="#D40000">'.$kwarr[$i].'</font>', $tit);
                                        }
                                    }
                                    echo $tit;
                                    ?></h3></a>
                                    <p><?php 
                                    $des = ReStrLen($row['description'],75);
                                    // 关键字高亮
                                    if(isset($_POST['keywords']) && htmlspecialchars($_POST['keywords']) !=='' && !empty($kwarr)){
                                        for($i=0; $i<count($kwarr); $i++){
                                            $des = str_replace($kwarr[$i], '<font color="#D40000">'.$kwarr[$i].'</font>', $des);
                                        }
                                    }
                                    echo $des;
                                    ?></p>
                                    <p style="color: #777;font-size: 12px;margin: 30px 0 0 0;"><span style="margin-right: 10px;"><?php echo '20'.MyDate('y-m-d', $row['createtime']);?></span><span style="margin-right: 10px;">浏览量：<?php echo $row['hits']; ?></span></p>
                                </div>    
                            </div>
                        </li>
                    </ul> 
                    <?php  
                    }
                } 
                ?>
        	</div>
        	<div class="col-md-4"><?php require_once('right.php'); ?></div>
        </div>
    </div>
</div>
<!-- mainbody end -->
<!-- 底部 start-->
<?php require_once('footer.php'); ?>
<!-- 底部 end-->
<script src="js/subgroups.main.js"></script>
<script src="js/subgroups.slides.js"></script>
<script src="js/jquery.nav.js"></script>
<script>
    $(document).ready(function() {
        $('#nav').onePageNav();
    });
    </script>
</body>
</html>