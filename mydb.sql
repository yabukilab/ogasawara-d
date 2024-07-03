CREATE DATABASE librarydb;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

#SQL文1

USE librarydb;

CREATE TABLE logininf (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

#SQL文2
USE librarydb;

CREATE TABLE detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    img mediumblob DEFAULT NULL,
    
    text TEXT DEFAULT NULL
);

#SQL文3: detailテーブルにgiveカラムとgetカラムを追加
ALTER TABLE detail ADD COLUMN give TINYINT(1) DEFAULT 0;
ALTER TABLE detail ADD COLUMN get TINYINT(1) DEFAULT 0;

#SQL文4: detailテーブルにownerカラムを追加
ALTER TABLE detail ADD COLUMN owner INT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;