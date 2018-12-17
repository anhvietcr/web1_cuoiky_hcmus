# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.19)
# Database: QLTK
# Generation Time: 2018-12-17 04:00:31 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `createdAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;

INSERT INTO `posts` (`id`, `id_user`, `data`, `createdAt`)
VALUES
	(1,1,'Hôm nay trời đẹp quá!','2018-10-24 00:00:00'),
	(2,37,'Yêu anh từ cái nhìn đầu tiên! <3 ','2018-10-24 08:23:25'),
	(3,2,'Yêu em từ cái nhìn đầu tiên - Dương Dương ahihi đồ ngốc','2018-10-24 08:24:51'),
	(4,2,'Tôi đang test chức năng đăng trạng thái mới','2018-10-24 09:23:42'),
	(5,2,'Là con gái thật tuyệt ','2018-10-25 05:37:57'),
	(6,2,'\r\n\r\n\r\n\r\n\r\n\r\n','2018-10-27 01:02:26'),
	(7,2,'Ahihi','2018-10-27 01:02:45'),
	(8,2,'&lt;script&gt;alert(\'Test\');&lt;/script&gt;','2018-10-28 03:16:04'),
	(9,2,'<script>alert(\'Test\');</script>','2018-10-28 05:30:47'),
	(10,40,'z','2018-10-28 15:32:50'),
	(11,39,'Test friend','2018-10-28 15:32:50'),
	(12,2,'\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n','2018-10-29 22:09:02');

/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table relationship
# ------------------------------------------------------------

DROP TABLE IF EXISTS `relationship`;

CREATE TABLE `relationship` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user1` int(11) DEFAULT NULL,
  `id_user2` int(11) DEFAULT NULL,
  `relationship` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `relationship` WRITE;
/*!40000 ALTER TABLE `relationship` DISABLE KEYS */;

INSERT INTO `relationship` (`id`, `id_user1`, `id_user2`, `relationship`)
VALUES
	(21,1,40,'11'),
	(22,39,41,'11'),
	(23,39,1,'11'),
	(24,39,2,'10'),
	(27,1,2,'11');

/*!40000 ALTER TABLE `relationship` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table reset_passwords
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reset_passwords`;

CREATE TABLE `reset_passwords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `secret` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `used` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `reset_passwords` WRITE;
/*!40000 ALTER TABLE `reset_passwords` DISABLE KEYS */;

INSERT INTO `reset_passwords` (`id`, `id_user`, `secret`, `createdAt`, `used`)
VALUES
	(1,NULL,'u','2018-10-25 10:58:26',0),
	(2,NULL,'d4qZ5d6gAw','2018-10-25 11:01:44',1),
	(3,NULL,'jcLJSbj6Ly','2018-10-25 11:02:11',0),
	(4,NULL,'XVRnuwKIfY','2018-10-25 11:03:27',0),
	(5,2,'7QkWM6V4ta','2018-10-25 11:04:14',1),
	(6,2,'Qoiez6LYa8','2018-10-25 11:39:30',1),
	(7,2,'9iA0Fcx0YV','2018-10-25 11:46:09',1),
	(8,2,'eSL6Iabuei','2018-10-25 11:47:39',0),
	(9,2,'ibCvj3ynkS','2018-10-26 20:23:52',0),
	(10,2,'EngSVQfjxs','2018-10-26 20:28:40',0),
	(11,2,'nVx5IoM5np','2018-10-27 00:33:20',0);

/*!40000 ALTER TABLE `reset_passwords` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `fullname` text COLLATE utf8_unicode_ci,
  `location` text COLLATE utf8_unicode_ci,
  `work` text COLLATE utf8_unicode_ci,
  `path_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT '/img/profile/user.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `email`, `password`, `fullname`, `location`, `work`, `path_image`)
VALUES
	(1,'phanthi@gmail.com','$2y$10$EGV0nmcG8bwPEX07scF6e.iVYQTb0siQl9lR09y8.hTw2oecI7BYa','Phan Thi Nhu Trang',NULL,NULL,'/img/profile/user.png'),
	(2,'phanthinhutrang111998@gmail.com','$2y$10$g1irrRAycAANNYZuespsP.m3pDYubnw1GT7vwljQ8twPRu85.JhHe','Như Trang','Hải Lăng','Sinh viên','/img/profile/2.JPG'),
	(37,'anguyen@gmail.com','$2y$10$t773NDp0/cqkjaQPTxOVFuNKw4yLZe5OANClwHmdS3/.j/Q6z6fs2','Nguyễn Văn A',NULL,NULL,'/img/profile/user.png'),
	(38,'','$2y$10$ysPM8Tudupmy8s4Iyu3sA.VbkJwvs7QOvxs2dz0U4.PyMeN.yJbY6','',NULL,NULL,'/img/profile/user.png'),
	(39,'1@gmail.com','$2y$10$4KyoRJgczzOiy4tGmXGCruEcRv7q6w6PzmcKgkUhi9n4uxG5qplUW','<script>alert(\'Test\');</script>',NULL,NULL,'/img/profile/user.png'),
	(40,'123@gmail.com','$2y$10$E4q69Q8DDPhMvbC1R0jGveiBFXrh5XE6Y4Tv.YtU9s3BSObmYkTRi','123',NULL,NULL,'/img/profile/user.png'),
	(41,'111998@gmail.com','$2y$10$O4teKJZbvrpBSQ.xFwCNCuN2c8eqyz5n.9kLkVKAERQx9mouwCGma','<script> alert(\'Test\'); </script>',NULL,NULL,'/img/profile/user.png');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
