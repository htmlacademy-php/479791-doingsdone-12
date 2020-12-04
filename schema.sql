CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(128) NOT NULL UNIQUE,
    user_id INT NOT NULL
);

CREATE TABLE users (doingsdone
    id INT AUTO_INCREMENT PRIMARY KEY,
    e_mail VARCHAR(128) NOT NULL UNIQUE,
    user_password CHAR(60) NOT NULL
);

CREATE TABLE tasks (
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(32) NOT NULL UNIQUE,
    task_deadline DATETIME,
    task_done tinyint(1) default NULL
);