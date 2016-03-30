-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-03-30 14:16:56
-- 服务器版本： 5.5.28
-- PHP Version: 5.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vyahui_db`
--

-- --------------------------------------------------------

--
-- 表的结构 `v_db_infolist`
--

CREATE TABLE IF NOT EXISTS `v_db_infolist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '列表信息id',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '站点id',
  `classid` smallint(5) unsigned NOT NULL COMMENT '所属栏目id',
  `parentid` smallint(5) unsigned NOT NULL COMMENT '所属栏目上级id',
  `parentstr` varchar(80) NOT NULL COMMENT '所属栏目上级id字符串',
  `mainid` smallint(5) NOT NULL COMMENT '二级类别id',
  `mainpid` smallint(5) NOT NULL COMMENT '二级类别上级id',
  `mainpstr` varchar(80) NOT NULL COMMENT '二级类别上级id字符串',
  `title` varchar(80) NOT NULL COMMENT '标题',
  `colorval` char(10) NOT NULL COMMENT '字体颜色',
  `boldval` char(10) NOT NULL COMMENT '字体加粗',
  `flag` varchar(30) NOT NULL COMMENT '属性',
  `source` varchar(50) NOT NULL COMMENT '文章来源',
  `author` varchar(50) NOT NULL COMMENT '作者编辑',
  `linkurl` varchar(255) NOT NULL COMMENT '跳转链接',
  `keywords` varchar(50) NOT NULL COMMENT '关键词',
  `description` varchar(255) NOT NULL COMMENT '摘要',
  `content` mediumtext NOT NULL COMMENT '详细内容',
  `picurl` varchar(100) NOT NULL COMMENT '缩略图片',
  `picarr` text NOT NULL COMMENT '组图',
  `hits` mediumint(8) unsigned NOT NULL COMMENT '点击次数',
  `orderid` int(10) unsigned NOT NULL COMMENT '排列排序',
  `posttime` int(10) NOT NULL COMMENT '更新时间',
  `checkinfo` enum('true','false') NOT NULL COMMENT '审核状态',
  `delstate` set('true') NOT NULL COMMENT '删除状态',
  `deltime` int(10) unsigned NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
