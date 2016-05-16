DROP TABLE IF EXISTS `#__kide`, `#__kide_bans`, `#__kide_iconos`, `#__kide_privados`, `#__kide_privados_offline`, `#__kide_sesion`;

CREATE TABLE `#__kide` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `userid` int(12) NOT NULL,
  `rango` int(1) NOT NULL,
  `color` varchar(6) NOT NULL,
  `img` text NOT NULL,
  `url` text NOT NULL,
  `time` int(12) NOT NULL,
  `token` int(12) NOT NULL,
  `sesion` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO #__kide (name,text,time,rango,sesion,token,userid,img,url) VALUES ('System', 'Welcome!', UNIX_TIMESTAMP(), 0, 0, 0, 0, '', '');

CREATE TABLE `#__kide_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sesion` varchar(32) NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `#__kide_iconos` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `code` varchar(15) NOT NULL,
  `img` varchar(31) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__kide_iconos` (`code`, `img`, `ordering`) VALUES
			(':_(', 'crying.png', 11), ('8)', 'glasses.png', 10), (':S', 'confused.png', 8), (':O', 'surprise.png', 7),
			(':|', 'plain.png', 6), (':D', 'grin.png', 5), (':P', 'razz.png', 4), (';)', 'wink.png', 3), (':(', 'sad.png', 2),
			(':)', 'smile.png', 1), (':-*', 'kiss.png', 12), ('(!)', 'important.png', 13), ('(?)', 'help.png', 14),
			(':-|', 'plain.png', 21), (':-)', 'smile.png', 15), (':-(', 'sad.png', 16), (';-)', 'wink.png', 17),
			(':-P', 'razz.png', 18), (':-D', 'grin.png', 20), (':-O', 'surprise.png', 19), ('O.O', 'eek.png', 9),
			('xD', 'grin.png', 22);

CREATE TABLE `#__kide_privados` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `fid` int(11) NOT NULL,
  `from` varchar(255) NOT NULL,
  `to` varchar(32) NOT NULL,
  `rango` int(1) NOT NULL,
  `color` varchar(6) NOT NULL,
  `img` text NOT NULL,
  `time` int(12) NOT NULL,
  `sesion` varchar(32) NOT NULL,
  `key` int(7) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `#__kide_privados_offline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(6) NOT NULL,
  `rango` int(1) NOT NULL,
  `msg` text NOT NULL,
  `img` text NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `#__kide_sesion` (
  `name` varchar(255) NOT NULL,
  `userid` int(12) NOT NULL,
  `rango` int(1) NOT NULL,
  `img` text NOT NULL,
  `time` int(12) NOT NULL,
  `sesion` varchar(32) NOT NULL,
  `privado` int(1) NOT NULL,
  `ocultar` int(1) NOT NULL,
  `key` int(7) NOT NULL,
  UNIQUE KEY `sesion` (`sesion`)
) DEFAULT CHARSET=utf8;
