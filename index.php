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
	<title>iworm</title>
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
            	<?php $dosql->Execute("SELECT * FROM v_db_infoarticle WHERE 1=1 AND delstate='false' AND checkinfo=true ORDER BY createtime DESC LIMIT 0,20");
                    while($row = $dosql->GetArray())
                    {
                        //获取链接地址
                        // if($row['linkurl']=='' and $cfg_isreurl!='Y')
                        //     $gourl = 'infocentshow.php?cid='.$row['classid'].'&id='.$row['id'].'???'.GetRandNum(15).'&&&'.GetRandStr(12).GetRandNum(3).'+++';
                        // else if($cfg_isreurl=='Y')
                        //     $gourl = 'infocentshow-'.$row['classid'].'-'.$row['id'].'-1.html';
                        // else
                        //     $gourl = $row['linkurl'];
                    ?>
        			<ul>
                		<li style="padding: 3px 0;">
                			<div class="image image-text-list">
                			    <div class="inner-area-image"><img src="images/alt180x120.png" width="180px;" height="120px;"></div>
                			    <div class="inner-area-content">
                				    <a href=""><h3><?php echo ReStrLen($row['title'],32); ?></h3></a>
                				    <p><?php echo ReStrLen($row['description'],75); ?></p>
                				    <p style="color: #777;font-size: 12px;margin: 30px 0 0 0;"><span style="margin-right: 10px;"><?php echo '20'.MyDate('y-m-d', $row['createtime']);?></span><span style="margin-right: 10px;">浏览量：<?php echo $row['hits']; ?></span></p>
                				</div>    
                			</div>
                		</li>
                	</ul>
                <?php
                    }
                ?>
        	</div>
        	<div class="col-md-4"><?php require_once('right.php'); ?></div>
        </div>
    </div>
</div>
<!-- mainbody end -->
<div class="container">
<!--     <div class="blank-120"></div>
    <div class="blank-60"></div>
	<div class="row" style="border:solid 0px red;">
		<form class="form-inline" method="post" action="search.php">
            <div class="form-group">
                <input type="text" class="form-control" id="keywordsid" name="keywords" placeholder="关键字..." value="<?php if(isset($keywords)){echo $keywords;} ?>">
                <button type="submit" class="btn btn-search"><i class="icon-search"></i></button>
            </div>
            <div class="hotwords"><span>吴奇隆刘诗诗婚礼</span><span>任贤齐</span><span>太阳的后裔</span><span>莱昂纳多写书法</span></div>
        </form>
	</div> -->
	<div class="row">
		<div class="col-md-12">
		
		</div>
	</div>
</div>
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