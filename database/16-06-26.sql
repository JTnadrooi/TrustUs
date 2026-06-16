-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 11:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

--
-- Database: `trust-us`
--
-- --------------------------------------------------------
--
-- Table structure for table `files`
--
CREATE TABLE
    `files` (
        `id` int (11) NOT NULL,
        `token` varchar(64) NOT NULL,
        `original_name` varchar(255) NOT NULL,
        `mime_type` varchar(100) NOT NULL,
        `size` int (11) NOT NULL,
        `upload_time` datetime NOT NULL DEFAULT current_timestamp(),
        `file_path` varchar(255) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `files`
--
ALTER TABLE `files` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

COMMIT;