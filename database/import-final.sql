SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

--
-- Database: `trust-us`
--
CREATE DATABASE IF NOT EXISTS `trust-us` DEFAULT CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_general_ci;

USE `trust-us`;

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
        `file_path` varchar(255) NOT NULL,
        `file_hash` char(64) NOT NULL,
        `sender_id` int (11) NOT NULL,
        `target_user_id` int (11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE
    `users` (
        `id` int (11) NOT NULL,
        `role` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` date NOT NULL,
        `username` varchar(255) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `files`
--
ALTER TABLE `files` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `token` (`token`),
ADD KEY `fk_files_sender` (`sender_id`),
ADD KEY `fk_files_target` (`target_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `files`
--
ALTER TABLE `files` ADD CONSTRAINT `fk_files_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_files_target` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;