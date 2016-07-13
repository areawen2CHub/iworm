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

select * from v_db_url where incsuccess='true';
/*创建视图vi_url*/
create view vi_url 
as
select u.id as id,hostid,url,urlcount,h.inccount as inccount,createtime 
from v_db_url as u
 
left join
v_db_host as h

on u.hostid=h.id

where 1=1
and u.incstate='false'
and u.delstate='false'
order by inccount desc,urlcount desc;

select * from vi_url limit 0,1;
select count(*) from v_db_url;
select count(*) from v_db_url where incsuccess='true';
/*2016-4-25*/
use dev_agora;
select PermissionID from rolepermission where RoleID=5;

select * from user;
select * from permission;
/*2016-4-26*/
select count(*) from rolepermission where PermissionID=8;
use dev_config;
select * from permission;
select * from roles;
select count(*) FROM banjistudent where banjiRole=5;
select * from rolepermission;
select ID as RoleTypeID,LevelOne as RoleTypeName from roles where FatherLevel = 0;
select ID as RoleID,LevelTwo as RoleName from roles where FatherLevel =1 order by ID;
update roles set LevelTwo = LevelOne ,LevelOnE = '' WHERE id = 10;
