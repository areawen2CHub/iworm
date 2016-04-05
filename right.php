<?php
// 右侧部分
?>

<div class="title tit-iworm">
    <span><i class="icon-th-large"></i></span><h3>关注我们</h3>
</div>
<div style="border:solid 1px #ddd;height: 110px;border-top: 0;padding: 15px 30px 30px 30px;clear: both;margin: 0 0 20px 0;">
	<div class="row">
        <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="image radius-all-px-5">
                <a href="#"><img src="images/weibo.png" alt="#" width="50px" height="50px"></a>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="image radius-all-px-5">
                <a href="#"><img src="images/weibo.png" alt="#" width="50px" height="50px"></a>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="image radius-all-px-5">
                <a href="#"><img src="images/weibo.png" alt="#" width="50px" height="50px"></a>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="image radius-all-px-5">
                <a href="#"><img src="images/weibo.png" alt="#" width="50px" height="50px"></a>
            </div>
        </div>
    </div>
</div>
<div style="border:solid 0px #ddd;clear: both;margin: 0 0 20px 0;">
	<form class="form-inline" method="post" action="search.php">
        <div class="form-group">
            <input type="text" class="form-control" id="keywordsid" name="keywords" placeholder="关键字..." value="<?php if(isset($keywords)){echo $keywords;} ?>">
            <button type="submit" class="btn btn-search"><i class="icon-search"></i></button>
        </div>
        <div class="hotwords"><span>吴奇隆刘诗诗婚礼</span><span>任贤齐</span><span>太阳的后裔</span><span>莱昂纳多写书法</span></div>
    </form>
</div>
<div class="title tit-iworm">
    <span><i class="icon-th-large"></i></span><h3>今日头条</h3>
</div>
<div style="border:solid 1px #ddd;border-top: 0;padding: 10px 15px 15px 15px;clear: both;margin: 0 0 20px 0;">
	<div class="list">
        <ul class="list-num">
            <?php 
            // 获取当前时间的前24小时
            $time = GetMkTime(time())-24*3600;
            // 定义排名
            $i = 1;
            $dosql->Execute("SELECT * FROM v_db_infoarticle WHERE 1=1 AND delstate='false' AND checkinfo=true AND createtime>'$time' ORDER BY hits DESC LIMIT 0,9");
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
    	        <li><span class="squ radius-all-px-2 <?php if($i==1 || $i==2 || $i==3) echo 'hot';?>"><?php echo $i; ?></span><a href=""><?php echo ReStrLen($row['title'],22); ?></a></li>
            <?php
                $i++;
            }
            ?>
        </ul>
    </div>
</div>
<div class="title tit-iworm">
    <span><i class="icon-th-large"></i></span><h3>推荐阅读</h3>
</div>