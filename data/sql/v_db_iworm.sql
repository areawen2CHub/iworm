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
-- 表的结构 `v_db_host`
--
CREATE TABLE IF NOT EXISTS `v_db_host` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '关键字表id',
  `hostname` VARCHAR(50) NOT NULL COMMENT '主机名',
  `hostip` VARCHAR(30) NOT NULL COMMENT '主机对应ip',
  `urlcount` MEDIUMINT(8) UNSIGNED NOT NULL COMMENT 'url收录数',
  `inccount` MEDIUMINT(8) UNSIGNED NOT NULL COMMENT '获取文章数',
  `inittime` INT(10) UNSIGNED NOT NULL COMMENT '第一次收录时间',
  `neartime` INT(10) UNSIGNED NOT NULL COMMENT '最近收录时间',
  `errcount` MEDIUMINT(4) UNSIGNED NOT NULL COMMENT '出错次数',
  `isusing` ENUM('true', 'false') NOT NULL COMMENT '主机状态，是否还在运行',
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- 表的结构 `v_db_url`
--
CREATE TABLE IF NOT EXISTS `v_db_url` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'urlid',
  `hostid` INT(10) UNSIGNED NOT NULL,
  `parenturl` VARCHAR(255) NOT NULL COMMENT '来源地址',
  `url` VARCHAR(255) NOT NULL COMMENT '链接地址',
  `createtime` INT(10) UNSIGNED NOT NULL COMMENT '收录时间',
  `inctimes` MEDIUMINT(8) UNSIGNED NOT NULL COMMENT '收录次数',
  `incstate` ENUM('true', 'false') NOT NULL COMMENT '收录状态',
  `incsuccess` ENUM('true', 'false') NOT NULL COMMENT '是否收录成功',
  `delstate` ENUM('true', 'false') NOT NULL COMMENT '删除状态',
  `deltime` INT(10) NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

--
-- 表的结构 `v_db_infolist`
--
CREATE TABLE IF NOT EXISTS `v_db_infolist` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'urlid',
  `classid` SMALLINT(5) UNSIGNED NOT NULL COMMENT '文章分类id',
  `title` VARCHAR(80) NOT NULL COMMENT '文章标题',
  `flag` VARCHAR(30) NOT NULL COMMENT '属性',
  `isoriginal` ENUM('false', 'true') NOT NULL COMMENT '是否原创',
  `author` VARCHAR(50) NOT NULL COMMENT '收录时间',
  `urlid` INT(10) UNSIGNED NOT NULL,
  `keywords` VARCHAR(50) NOT NULL COMMENT '收录状态',
  `description` VARCHAR(255) NOT NULL COMMENT '摘要',
  `content` MEDIUMTEXT NOT NULL COMMENT '详细内容',
  `picurl` VARCHAR(100) NOT NULL COMMENT '缩略图',
  `picarr` TEXT NOT NULL COMMENT '图组',
  `hits` MEDIUMINT(8) NOT NULL COMMENT '浏览数',
  `orderid` INT(10) UNSIGNED NOT NULL COMMENT '排序',
  `createtime` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `checkinfo` ENUM('true', 'false') NOT NULL COMMENT '审核情况',
  `delstate` ENUM('false', 'true') NOT NULL COMMENT '删除状态',
  `deltime` INT(10) NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- 表的结构 `v_db_charset`
--
CREATE TABLE IF NOT EXISTS `v_db_charset` (
  `id` SMALLINT(10) UNSIGNED NOT NULL COMMENT '关键字表id',
  `hostid` INT(10) UNSIGNED NOT NULL,
  `charset` VARCHAR(10) NOT NULL COMMENT '关键字',
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
