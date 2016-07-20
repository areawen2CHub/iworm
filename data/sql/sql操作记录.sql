/*2016-04-13*/
alter table v_db_url add isempty ENUM('false', 'true') NOT NULL COMMENT '内容是否为空';
alter table v_db_url add lasttime int(10) UNSIGNED NOT NULL COMMENT '上一次访问时间';
alter table v_db_url add neartime int(10) UNSIGNED NOT NULL COMMENT '最近访问时间';
alter table v_db_url add viscount MEDIUMINT(8) UNSIGNED NOT NULL COMMENT '访问数';
alter table v_db_keywords change searchtimes seacount MEDIUMINT(8);
alter table v_db_url change inctimes inccount MEDIUMINT(8);

update v_db_url set neartime=createtime+24*60 where incstate = 'true' and id>0;
update v_db_url set viscount=1 where incstate = 'true' and id>0;

/*查看profiles*/
show profiles;
/*显示查询的变量*/
show variables like '%pro%';
/*开启profile*/
set profiling = 1;
/*更改字段属性*/
alter table v_db_charset modify hostid int(10);

/*删除host表urlcount和inccount字段*/
alter table v_db_host drop column urlcount,drop column inccount;
/*删除host表neartime字段*/
alter table v_db_host drop column neartime;
/*更新host表errcount字段类型*/
alter table v_db_host drop column errcount;
/*更新url表incsuccess字段类型*/
alter table v_db_url modify incsuccess enum('false','true');
update v_db_url set incsuccess=null where incsuccess !='true' and id>0; 

alter table v_db_host add urlcount MEDIUMINT(8) UNSIGNED NOT NULL COMMENT 'url收录数';
alter table v_db_host add inccount MEDIUMINT(8) UNSIGNED NOT NULL COMMENT '成功收录数';
alter table v_db_host add neartime int(10) UNSIGNED NOT NULL COMMENT '最近收录时间';

/*更新urlcount*/
update v_db_host h,(select hostid,count(hostid) as urlcount from v_db_url group by hostid) u 
set h.urlcount=u.urlcount where h.id=u.hostid;
/*更新inccount*/
update v_db_host h,(select hostid,count(hostid) as inccount from v_db_url where incsuccess='true' group by hostid) u 
set h.inccount=u.inccount where h.id=u.hostid;

select * from v_db_host;

/*关闭安全更新模式*/
use iworm_db;
set SQL_SAFE_UPDATES=0;

alter table v_db_host modify isusing tinyint comment '是否已使用过，0未使用；1使用';
alter table v_db_url modify incstate tinyint comment '收录状态，0未收录；1已收录';
alter table v_db_url modify incsuccess tinyint comment '是否收录成功，0收录失败；1收录成功';
alter table v_db_url modify delstate tinyint comment '是否删除，0未删除；1已删除';
alter table v_db_url modify isempty tinyint comment '是否为空，0不为空；1是为空';
update v_db_url set incsuccess=0 where incsuccess<1 and id>0;

alter table v_db_url drop deltime;

alter table v_db_url modify neartime int(10) unsigned null comment '最近访问时间';
alter table v_db_url modify lasttime int(10) unsigned null comment '上一次访问时间';

alter table v_db_url modify viscount int(10) unsigned not null default 0 comment '访问次数';
alter table v_db_infolist modify classid int unsigned not null default 0 comment '类型Id';
alter table v_db_infolist modify isoriginal tinyint unsigned not null default 0 comment '是否原创';
update v_db_infolist set isoriginal = 0 where isoriginal=1 and id>0;
alter table v_db_infolist modify delstate tinyint unsigned not null default 0 comment '是否删除';
update v_db_infolist set delstate = 0 where delstate=1 and id>0;
alter table v_db_infolist modify checkinfo tinyint unsigned not null default 0 comment '是否审核';
alter table v_db_infolist modify deltime int(10) default 0 comment '删除时间';

select id from v_db_host where hostname = 'www.epay.163.com1';

select count(*) from v_db_host;
select count(*) from v_db_url;
select count(*) from v_db_infolist;


select * from v_db_keywords;

select * from v_db_host order by inittime desc;
select * from v_db_charset;
select * from v_db_url;
select * from v_db_infolist order by createtime desc;

select id,hostid,url from vi_url limit 0,100;
