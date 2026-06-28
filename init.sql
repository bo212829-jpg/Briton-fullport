-- SQL to create database and tables for FullPort authentication
-- Run in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS fullport_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fullport_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS login_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  login_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip_address VARCHAR(45) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Optional: create an admin user (change password after running)
-- Use PHP's password_hash() to generate the hash and replace below
-- INSERT INTO users (username, email, password, is_admin) VALUES ('admin','admin@example.com','$2y$...HASH....',1);
