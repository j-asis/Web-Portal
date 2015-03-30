--
-- Create database
--
CREATE DATABASE IF NOT EXISTS board;
GRANT SELECT, INSERT, UPDATE, DELETE ON board.* TO board_root@localhost IDENTIFIED BY 'board_root';
FLUSH PRIVILEGES;

--
-- Create tables
--
                    
USE board;
                    
CREATE TABLE IF NOT EXISTS thread (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id                 INT UNSIGNED NOT NULL,
title                   VARCHAR(255) NOT NULL,
created                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id)
)ENGINE=InnoDB;


                    
CREATE TABLE IF NOT EXISTS comment (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
thread_id               INT UNSIGNED NOT NULL,
user_id                 INT UNSIGNED NOT NULL,
body                    TEXT NOT NULL,
created                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX (thread_id, created)
)ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS user (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
username                VARCHAR(50) NOT NULL,
first_name              VARCHAR(50) NOT NULL,
last_name               VARCHAR(50) NOT NULL,
email                   VARCHAR(50) NOT NULL,
password                VARCHAR(100) NOT NULL,
avatar                  VARCHAR(200),
PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS follow (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id                 INT UNSIGNED NOT NULL,
thread_id               INT UNSIGNED NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS likes (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id                 INT UNSIGNED NOT NULL,
comment_id               INT UNSIGNED NOT NULL,
PRIMARY KEY(id)
)ENGINE=InnoDB;