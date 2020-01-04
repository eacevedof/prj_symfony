CREATE DATABASE IF NOT EXISTS symf_tasks;

USE symf_tasks;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE IF NOT EXISTS tasks(
    id          int(11) not null auto_incrment,
    user_id     int(11) not null,
    title       varchar(100),
    content     text,
    priority    varchar(20),
    hours       int(8),
    created_at  datetime,
    CONSTRAINT pk_task PRIMARY KEY(id),
    CONSTRAINT fk_task_user FOREIGN KEY(user_id) REFERENCES users(id)
) ENGINE=InnoDb;