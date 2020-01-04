CREATE DATABASE IF NOT EXISTS symf_tasks;

USE symf_tasks;

CREATE TABLE IF NOT EXISTS users(
    id          int(11) auto_incrment not null,
    `role`      varchar(50),
    `name`      varchar(100),
    surname     varchar(200),
    email       varchar(255),
    password    varchar(255),
    created_at  datetime,
    CONSTRAINT pk_users PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS tasks(
    id          int(11) auto_incrment not null,
    user_id     int(11),
    title       varchar(100),
    content     text,
    priority    varchar(20),
    hours       int(8),
    created_at  datetime,
    CONSTRAINT pk_task PRIMARY KEY(id),
    CONSTRAINT fk_task_user FOREIGN KEY(user_id) REFERENCES users(id)
) ENGINE=InnoDb;