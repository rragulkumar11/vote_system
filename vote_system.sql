/*
SQLyog Trial v13.1.8 (64 bit)
MySQL - 8.0.31 : Database - vote_system
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vote_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `vote_system`;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(225) DEFAULT NULL,
  `lname` varchar(225) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` text,
  `status` varchar(50) DEFAULT NULL,
  `role` int DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`fname`,`lname`,`email`,`password`,`status`,`role`,`createdAt`,`updatedAt`) values 
(1,'Ragulkumar','R','rragulkumar11@gmail.com','c7a85972e188c669f0d1e24528a772a3','active',1,'2023-09-27 12:45:16','2023-09-27 12:45:16'),
(2,'Ragavan','N','ragavan@gmail.com','c7a85972e188c669f0d1e24528a772a3','active',2,'2023-09-27 12:45:55','2023-09-27 12:45:55'),
(3,'Saravanan','M','saravana@gmail.com','c7a85972e188c669f0d1e24528a772a3','active',2,'2023-09-27 12:46:49','2023-09-27 12:46:49'),
(4,'Naresh','M','snanresh@gmail.com','c7a85972e188c669f0d1e24528a772a3','active',2,'2023-09-27 13:13:38','2023-09-27 13:13:38'),
(5,'Ravi','Chandran','ravi@gmail.com','4297f44b13955235245b2497399d7a93','pending',2,'2023-09-27 13:21:00','2023-09-27 13:21:00');

/*Table structure for table `voter_data` */

DROP TABLE IF EXISTS `voter_data`;

CREATE TABLE `voter_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `address` text,
  `taluk` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `voterid` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `voter_data` */

insert  into `voter_data`(`id`,`first_name`,`last_name`,`gender`,`dob`,`mobile`,`email`,`district`,`address`,`taluk`,`state`,`voterid`,`createdAt`,`updatedAt`) values 
(1,'Ragulkumar','R','Male','1999-11-04','9342300134','rragulkumar11@gmail.com','madurai','South perumal mestry street madurai','madurai','Tamil nadu.','7c651f8acd1160651a56d01204a58deb','2023-09-27 13:23:17','2023-09-27 13:23:17'),
(2,'Saravanan','M','Male','1998-12-13','9342300136','saravana@gmail.com','Channenai.','Channenai.','Channenai.','Channenai.','a05cc86650a6551df54135a7608e1b25','2023-09-27 13:25:22','2023-09-27 13:25:22'),
(3,'Ragavan','N','Male','2005-05-13','9342300137','ragavan@gmail.com','Bengaluru','Bengaluru','Bengaluru','Bengaluru','caa7b8d2c5293731f78545d396e44051','2023-09-27 13:26:34','2023-09-27 13:26:34');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
