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
  CONSTRAINT pk_task PRIMARY KEY(id),
  CONSTRAINT fk_task_user FOREIGN KEY(user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tasks` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO users VALUES(NULL,'ROLE_USER','A','af','eacevedof@eaf.com','password',CURTIME());
INSERT INTO users VALUES(NULL,'ROLE_USER','B','GH','bgh@eaf.com','password',CURTIME());
INSERT INTO users VALUES(NULL,'ROLE_USER','C','JK','cjk@eaf.com','password',CURTIME());

INSERT INTO tasks VALUES (NULL, 1, 'Tarea 1', 'Contenido de prueba 1', 'high', 40,CURTIME());
INSERT INTO tasks VALUES (NULL, 2, 'Tarea 2', 'Contenido de prueba 2', 'low', 20,CURTIME());
INSERT INTO tasks VALUES (NULL, 3, 'Tarea 3', 'Contenido de prueba 3', 'medium', 10,CURTIME());
INSERT INTO tasks VALUES (NULL, 1, 'Tarea 4', 'Contenido de prueba 4', 'high', 50,CURTIME());