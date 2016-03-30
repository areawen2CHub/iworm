-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-03-29 17:26:02
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
-- 表的结构 `v_db_infoimg`
--

CREATE TABLE IF NOT EXISTS `v_db_infoimg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片信息id',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '站点id',
  `classid` smallint(5) unsigned NOT NULL COMMENT '所属栏目id',
  `parentid` smallint(5) unsigned NOT NULL COMMENT '所属栏目上级id',
  `parentstr` varchar(80) NOT NULL COMMENT '所属栏目上级id字符串',
  `mainid` smallint(5) NOT NULL COMMENT '二级类别id',
  `mainpid` smallint(5) NOT NULL COMMENT '二级类别父id',
  `mainpstr` varchar(80) NOT NULL COMMENT '二级累呗父id字符串',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `v_db_infoimg`
--

INSERT INTO `v_db_infoimg` (`id`, `siteid`, `classid`, `parentid`, `parentstr`, `mainid`, `mainpid`, `mainpstr`, `title`, `colorval`, `boldval`, `flag`, `source`, `author`, `linkurl`, `keywords`, `description`, `content`, `picurl`, `picarr`, `hits`, `orderid`, `posttime`, `checkinfo`, `delstate`, `deltime`) VALUES
(1, 1, 1, 0, '0,', -1, -1, '', 'banner-01', '', '', 'f', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445420578.jpg', '', 155, 1, 1445414097, 'true', '', 0),
(2, 1, 1, 0, '0,', -1, -1, '', 'banner-02', '', '', 'f', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445418849.jpg', '', 153, 2, 1445414144, 'true', '', 0),
(3, 1, 2, 0, '0,', -1, -1, '', '1', '', '', 'h', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445418384.jpg', '', 168, 3, 1445415529, 'true', '', 0),
(4, 1, 2, 0, '0,', -1, -1, '', '2', '', '', 'h', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445416267.jpg', '', 178, 4, 1445415557, 'true', '', 0),
(5, 1, 2, 0, '0,', -1, -1, '', '3', '', '', 'h', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445423192.jpg', '', 85, 5, 1445415579, 'true', '', 0),
(6, 1, 2, 0, '0,', -1, -1, '', '4', '', '', 'h', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445422933.jpg', '', 82, 6, 1445415599, 'true', '', 0),
(7, 1, 2, 0, '0,', -1, -1, '', '5', '', '', 'h', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445418562.jpg', '', 131, 7, 1445415628, 'true', '', 0),
(8, 1, 2, 0, '0,', -1, -1, '', '6', '', '', 'h', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445419745.jpg', '', 76, 8, 1445415649, 'true', '', 0),
(9, 1, 2, 0, '0,', -1, -1, '', '7', '', '', 'h', '', 'admin', '', '49', '', '', 'uploads/image/20151021/1445417229.jpg', '', 64, 9, 1445415671, 'true', '', 0),
(10, 1, 2, 0, '0,', -1, -1, '', '8', '', '', 'h', '', 'admin', '', '45', '', '', 'uploads/image/20151021/1445416669.jpg', '', 153, 10, 1445415691, 'true', '', 0),
(11, 1, 2, 0, '0,', -1, -1, '', 'hots1', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445417337.jpg', '', 76, 11, 1445415717, 'true', '', 0),
(12, 1, 2, 0, '0,', -1, -1, '', 'hots2', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445420468.jpg', '', 160, 12, 1445415748, 'true', '', 0),
(13, 1, 2, 0, '0,', -1, -1, '', 'hots3', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445420287.jpg', '', 195, 13, 1445415772, 'true', '', 0),
(14, 1, 2, 0, '0,', -1, -1, '', 'hots4', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445416407.jpg', '', 121, 14, 1445415798, 'true', '', 0),
(15, 1, 2, 0, '0,', -1, -1, '', 'hots5', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445417289.jpg', '', 105, 15, 1445415823, 'true', '', 0),
(16, 1, 2, 0, '0,', -1, -1, '', 'hots6', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445424992.jpg', '', 138, 16, 1445415849, 'true', '', 0),
(17, 1, 2, 0, '0,', -1, -1, '', 'hots7', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445416996.jpg', '', 64, 17, 1445415871, 'true', '', 0),
(18, 1, 2, 0, '0,', -1, -1, '', 'hots8', '', '', 'c', '', 'admin', '', '', '', '', 'uploads/image/20151021/1445419960.jpg', '', 146, 18, 1445415898, 'true', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
