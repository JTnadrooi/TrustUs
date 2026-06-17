## run the following code as a uery if that's your prefered method of importing it:

CREATE DATABASE IF NOT EXISTS `trust-us`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE `trust-us`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `files` (
    `id` int(11) NOT NULL,
    `token` varchar(64) NOT NULL,
    `original_name` varchar(255) NOT NULL,
    `mime_type` varchar(100) NOT NULL,
    `size` int(11) NOT NULL,
    `upload_time` datetime NOT NULL DEFAULT current_timestamp(),
    `file_path` varchar(255) NOT NULL,
    `file_hash` char(64) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `token` (`token`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

ALTER TABLE `files`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;