<?php 
$dosql->Execute("SELECT * FROM v_db_infolist WHERE 1=1 AND delstate='false' AND picurl !='' AND checkinfo=true ORDER BY createtime DESC LIMIT 0,20");
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
				<a href="<?php echo $linkurl; ?>"><h3><?php echo ReStrLen($row['title'],32); ?></h3></a>
				<p><?php echo ReStrLen($row['description'],75); ?></p>
				<p style="color: #777;font-size: 12px;margin: 30px 0 0 0;"><span style="margin-right: 10px;"><?php echo '20'.MyDate('y-m-d', $row['createtime']);?></span><span style="margin-right: 10px;">浏览量：<?php echo $row['hits']; ?></span></p>
			</div>    
		</div>
	</li>
</ul>
<?php
    }
?>