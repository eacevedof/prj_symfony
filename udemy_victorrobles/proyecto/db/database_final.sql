/*
SQLyog Ultimate v9.02 
MySQL - 5.5.5-10.4.11-MariaDB-1:10.4.11+maria~bionic : Database - symf_tasks
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`symf_tasks` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `symf_tasks`;

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `priority` varchar(20) DEFAULT NULL,
  `hours` int(8) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_task_user` (`user_id`),
  CONSTRAINT `fk_task_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `tasks` */

insert  into `tasks`(`id`,`user_id`,`title`,`content`,`priority`,`hours`,`created_at`) values (1,1,'Tarea 1','Contenido de prueba 1','high',40,'2020-01-05 20:07:19'),(2,2,'Tarea 2','Contenido de prueba 2','low',20,'2020-01-05 20:07:24'),(3,3,'Tarea 3','Contenido de prueba 3','medium',10,'2020-01-05 20:07:41'),(5,3,'Tarea 3','Contenido de prueba 3','medium',10,'2020-01-05 20:07:47'),(7,1,'Tarea 4','Contenido de prueba 4','high',50,'2020-01-05 20:08:12'),(8,19,'tttt','cccc','high',5,'2020-01-09 21:07:05'),(9,19,'hhhh','cccc','low',88,'2020-01-09 21:08:18'),(10,19,'xxx','yyy','medium',66,'2020-01-09 21:10:16'),(11,19,'xxx','yyy','medium',66,'2020-01-09 21:10:37'),(12,19,'Este es un titulo largo de la tarea ABCDEFGHI','Este es un contenido','low',15,'2020-01-09 21:16:59'),(13,19,'titu','cconnt','medium',1234,'2020-01-10 22:27:08'),(14,19,'titu','cconnt','medium',1234,'2020-01-10 22:27:08'),(16,20,'tarea null','contenido null','high',3,'2020-01-11 12:58:04'),(17,20,'hola mundo','para borrar','low',333,'2020-01-11 12:58:33'),(18,19,'NULL','NULL','high',0,'2020-01-11 13:24:34');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`role`,`name`,`surname`,`email`,`password`,`created_at`) values (1,'ROLE_USER','A','af','eacevedof@eaf.com','password','2020-01-05 20:03:31'),(2,'ROLE_USER','B','GH','bgh@eaf.com','password','2020-01-05 20:03:31'),(3,'ROLE_USER','C','JK','cjk@eaf.com','password','2020-01-05 20:03:31'),(4,'ROLE_USER','ttt','iii','fff@yahoo.es','$2y$04$zZutshZqxrzSyTZANM2yBuSC1vTy2Bzgx6LUHnJnQCxDv5nVTwou6','2020-01-06 11:18:49'),(5,'ROLE_USER','ttt','iii','fff@yahoo.es','$2y$04$4RqIIbuL1iFVb93JxqSGgeaK6yS5I8uLvntDN/HLuLfQ7LKYYWpfa','2020-01-06 11:20:42'),(6,'ROLE_USER','aa','bbb','dd@rr.com','$2y$04$VkELnZPxA68IS5ZagUFV..JqxiIMX4jTYX0z9VrBiG7NGZIy3yuSi','2020-01-06 11:30:56'),(7,'ROLE_USER','aa','bb','cc@go.com','$2y$04$x8PqzqiqVxPTh8xL0eBuseB2gmuwj8HHq7VL2cm7i/czx00jS.1Bu','2020-01-06 11:31:52'),(8,'ROLE_USER','aa','bb','cc@go.com','$2y$04$1yhxyY/8ojk0tOZE8iR.huQflqIG2kwUo.TInACawCKCKGpC.9/UC','2020-01-06 11:32:06'),(9,'ROLE_USER','aa','bb','cc@go.com','$2y$04$iSgbxh/4ykNlN11SBjGluOH/WPe.ye37jW8ow9RiSEI4cnsIm1Qbq','2020-01-06 11:35:06'),(10,'ROLE_USER','aa','bb','mmm@ccc.com','$2y$04$RHM3ZZB7qs2HsnMFswdUGeJGZa0p3l.wiR2ao/kvAYzbaAKokeX6m','2020-01-06 11:39:52'),(11,'ROLE_USER','eaf','eaf','eaf@eaf.com','$2y$04$ykFXVDQGK4xJrPgjUn6esuYh4QfoWOTUOYKS3mcyNPjLuZ8ik0yii','2020-01-06 12:36:44'),(12,'ROLE_USER','eaf','eaf','eaf@eaf.com','$2y$04$w4dlNVCGZi5dSp9JOqzu/.TkD8ILNnj6w1kW0fVUn4sb32km/i6pC','2020-01-06 12:37:26'),(13,'ROLE_USER','eaf','eaf','eaf@eaf.com','$2y$04$mxpmrzLVhdho.wDzgPsioejVlfzRCbW23//v51Tr3jrDARlS.roz2','2020-01-06 12:42:26'),(14,'ROLE_USER','aa','bb','cc@go.com','$2y$04$6RjSK4Een6pD8O2OUYgLZOcHYj5JBblPld9ywvxRUm4VIpEWM51Ki','2020-01-06 12:43:04'),(15,'ROLE_USER','aa','bb','cc@mm.c','$2y$04$MhPwYN.V/t7/CoiJ/NY6l.81cMPkzUoCbPZ9cruZUGMZXBuweatoe','2020-01-06 12:59:46'),(16,'ROLE_USER','abb','ddd','rrr@gg.com','$2y$04$Sq/lstjbBvVLW3frp5fxGuyIBMByCi36H0zQPaFQ8klK//bHVNzI6','2020-01-06 13:16:25'),(17,'ROLE_USER','aaa','xxx','ee@mmm.com.99','$2y$04$QGarCa1rGi.2QbXuHf5rGuxj6LmUDwA6lNH2kwPFlvL.vfj1nxQzy','2020-01-06 13:20:06'),(18,'ROLE_USER','agua','mala','ppp@mmm.com','$2y$04$PW8oOTuwbVoIJSN/oKtVA.xGrDa9UgzFftKDUIcZaBAEixTovraJW','2020-01-06 13:20:27'),(19,'ROLE_USER','aa','aa','aa@aa.com','$2y$04$TUze2I6sdud2P.VHf8e20OD/0vOpH14SgPwSGyxsAPTDSkjPZvQtq','2020-01-06 18:59:32'),(20,'ROLE_USER','bb','bb','bb@bb.com','$2y$04$Lh294P3hwKzAuq5OaLbKYOocyHa0zy8ajQvU9xstaqEWYTFlsL226','2020-01-06 19:01:09'),(21,'ROLE_USER','cc','cc','cc@cc.com','$2y$04$N/0ad33YWy5zCYJJmVaruev1nb.VSwrnzVfZS/IYjWywJuMddjrZC','2020-01-06 19:04:47'),(22,'ROLE_USER','xxx','xxx','xxx@xxx.com','$2y$04$zQKx.NScvOoxZNqyh/Vs2e/14hgmbUUqhEm7AvkZGvsmIv/WHUwQm','2020-01-07 20:54:59');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
