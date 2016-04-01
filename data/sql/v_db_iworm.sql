-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-03-29 11:58:23
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
-- 表的结构 `v_db_infourl`
--

CREATE TABLE IF NOT EXISTS `v_db_infourl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'urlid',
  `parenturl` varchar(255) NOT NULL COMMENT '来源地址',
  `url` varchar(255) NOT NULL COMMENT '链接地址',
  `urlip` varchar(30) NOT NULL COMMENT '链接ip',
  `urlhost` varchar(255) NOT NULL COMMENT '链接主机',
  `createtime` int(10) NOT NULL COMMENT '收录时间',
  `inctimes` mediumint(8) unsigned NOT NULL COMMENT '收录次数',
  `incstate` enum('true','false') NOT NULL COMMENT '摘取状态',
  `delstate` enum('true','false') NOT NULL COMMENT '删除状态',
  `deltime` int(10) unsigned NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- 表的结构 `v_db_infourl`
--


CREATE TABLE IF NOT EXISTS `v_db_infoarticle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `classid` smallint(5) unsigned NOT NULL COMMENT '文章所属分类id',
  `title` varchar(80) NOT NULL COMMENT '标题',
  `flag` varchar(30) NOT NULL COMMENT '属性',
  `source` varchar(50) NOT NULL COMMENT '文章来源',
  `author` varchar(50) NOT NULL COMMENT '作者编辑',
  `linkurl` varchar(255) NOT NULL COMMENT '来源链接',
  `keywords` varchar(50) NOT NULL COMMENT '关键词',
  `description` varchar(255) NOT NULL COMMENT '摘要',
  `content` mediumtext NOT NULL COMMENT '详细内容',
  `picurl` varchar(100) NOT NULL COMMENT '缩略图片',
  `picarr` text NOT NULL COMMENT '组图',
  `hits` mediumint(8) unsigned NOT NULL COMMENT '点击次数',
  `orderid` int(10) unsigned NOT NULL COMMENT '排列排序',
  `posttime` int(10) NOT NULL COMMENT '更新时间',
  `checkinfo` enum('true','false') NOT NULL COMMENT '审核状态',
  `delstate` enum('true','false') NOT NULL COMMENT '删除状态',
  `deltime` int(10) unsigned NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- 表的结构 `v_db_infcharset`
--

CREATE TABLE IF NOT EXISTS `v_db_charset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'charsetid',
  `charset` varchar(50) NOT NULL COMMENT 'charset',
  `host` varchar(255) NOT NULL COMMENT '来源主机',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

