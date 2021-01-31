CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(128) NOT NULL UNIQUE,
    user_id INT NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(128) NOT NULL,
    e_mail VARCHAR(128) NOT NULL UNIQUE,
    user_password CHAR(60) NOT NULL
);

CREATE TABLE tasks (
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(256) NOT NULL,
    task_deadline DATETIME,
    task_done tinyint(1) default 0,
    file varchar(256)
);