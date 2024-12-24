DROP DATABASE IF EXISTS IziAcademy_db;

CREATE DATABASE IziAcademy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE IziAcademy_db;

CREATE TABLE users (
user_id INT NOT NULL AUTO_INCREMENT,
user_name VARCHAR(50) NOT NULL,
user_lastname VARCHAR(50) NOT NULL,
email_address VARCHAR(100) NOT NULL UNIQUE,
user_password VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT current_timestamp,
PRIMARY key(user_id)
);
