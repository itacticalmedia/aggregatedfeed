/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.41-0ubuntu0.14.04.1 : Database - aggregatedfeed
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `aggregatedfeed`;

/*Table structure for table `ResGroupResources` */

DROP TABLE IF EXISTS `ResGroupResources`;

CREATE TABLE `ResGroupResources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resGroupId` int(11) NOT NULL,
  `resourceId` int(11) NOT NULL,
  `createdOn` datetime DEFAULT NULL,
  `modifiedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `ResGroupResources` */

/*Table structure for table `ResourceGroup` */

DROP TABLE IF EXISTS `ResourceGroup`;

CREATE TABLE `ResourceGroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `createdOn` datetime DEFAULT NULL,
  `modifiedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('A','I','D') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `ResourceGroup` */

/*Table structure for table `Resources` */

DROP TABLE IF EXISTS `Resources`;

CREATE TABLE `Resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(45) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `routeName` varchar(255) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Resources` */

/*Table structure for table `RoleResGroup` */

DROP TABLE IF EXISTS `RoleResGroup`;

CREATE TABLE `RoleResGroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL,
  `resGroupId` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `RoleResGroup` */

/*Table structure for table `RoleResources` */

DROP TABLE IF EXISTS `RoleResources`;

CREATE TABLE `RoleResources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL,
  `resGroupId` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `RoleResources` */

/*Table structure for table `Roles` */

DROP TABLE IF EXISTS `Roles`;

CREATE TABLE `Roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `default` tinyint(1) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  `status` enum('A','I','D') DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Roles` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(55) DEFAULT NULL,
  `lastName` varchar(55) DEFAULT NULL,
  `email` varchar(125) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `roleID` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`firstName`,`lastName`,`email`,`password`,`roleID`,`created`,`modified`) values (5,'Admin','Admin','admin@admin.com','96e79218965eb72c92a549dd5a330112',2,'2013-07-16 00:03:07','2014-11-27 00:08:20');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
