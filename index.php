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
<div class="container">
    <div class="blank-120"></div>
    <div class="blank-60"></div>
	<div class="row" style="border:solid 0px red;">
		<form class="form-inline" method="post" action="search.php">
            <div class="form-group">
                <input type="text" class="form-control" id="keywordsid" name="keywords" placeholder="关键字..." value="<?php if(isset($keywords)){echo $keywords;} ?>">
                <button type="submit" class="btn btn-search"><i class="icon-search"></i></button>
            </div>
            <div class="hotwords"><span>吴奇隆刘诗诗婚礼</span><span>任贤齐</span><span>太阳的后裔</span><span>莱昂纳多写书法</span></div>
        </form>
	</div>
	<div class="row">
		<div class="col-md-12">
		<?php $dosql->Execute("SELECT * FROM v_db_infoarticle WHERE 1=1 AND delstate='false' AND checkinfo=true ORDER BY createtime DESC LIMIT 0,8");
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
        		<li>
        			<div class="image image-text-list">
        				<a href=""><h3><small><?php echo $row['title'] ?></small></h3></a>
        				<p><?php echo $row['description']; ?></p>
        			</div>
        		</li>
        	</ul>
        <?php
            }
        ?>
		</div>
	</div>
</div>
</body>
</html>