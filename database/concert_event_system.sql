-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for concert_event_system
CREATE DATABASE IF NOT EXISTS `concert_event_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `concert_event_system`;

-- Dumping structure for table concert_event_system.email_reset_tokens
CREATE TABLE IF NOT EXISTS `email_reset_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration_time` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.email_reset_tokens: ~2 rows (approximately)
DELETE FROM `email_reset_tokens`;
INSERT INTO `email_reset_tokens` (`id`, `email`, `token`, `created_at`, `expiration_time`) VALUES
	(7, 'JohnThor@gmail.com', 'f7d07f68b04027b601d76654bd83a6bf', '2024-10-21 15:47:49', '2024-10-21 09:17:49'),
	(8, 'JohnThor@gmail.com', 'af80566d2ef127d5a0183aea05efe8e1', '2024-10-21 15:49:54', '2024-10-21 09:19:54');

-- Dumping structure for table concert_event_system.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `max_participants` int DEFAULT '0',
  `current_participants` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.events: ~2 rows (approximately)
DELETE FROM `events`;
INSERT INTO `events` (`id`, `name`, `date`, `time`, `location`, `image_path`, `status`, `max_participants`, `current_participants`, `created_at`, `description`) VALUES
	(16, 'Valorant', '2024-10-25', '21:54:00', 'Rumah fahry', 'uploads/events/671a6457a3960.png', 'open', 5, 1, '2024-10-24 14:54:12', 'asdwa'),
	(18, 'mik', '2024-10-23', '21:58:00', 'Rumah fahry', 'uploads/events/671a608bf05b5.png', 'open', 5, 2, '2024-10-24 14:58:19', 'adsd');

-- Dumping structure for table concert_event_system.registrations
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('confirmed','canceled') DEFAULT 'confirmed',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.registrations: ~1 rows (approximately)
DELETE FROM `registrations`;
INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_date`, `status`) VALUES
	(26, 8, 18, '2024-10-24 15:51:11', 'confirmed'),
	(30, 9, 16, '2024-10-24 15:51:51', 'confirmed');

-- Dumping structure for table concert_event_system.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `profile_picture`) VALUES
	(4, 'Admin', 'admin@gmail.com', '$2y$10$Q6.28ikVA6wgYAzrsGF0iOYOb5QM8lZt0OxqFDiyT9pjZVqCVbSWW', 'admin', '2024-10-22 05:13:47', '../uploads/profile_pictures/671a5d945e1b8_Screenshot 2023-03-04 082647.png'),
	(8, 'John Thor', 'JohnThor@gmail.com', '$2y$10$fCB6neqFHsE9ugjKqnm83Ob5PhjWeso8PfBWD/trcQW2JKx1AefEi', 'user', '2024-10-24 15:09:33', '../uploads/profile_pictures/671a6364ed360_modicon.png'),
	(9, 'John Doe', 'JohnDoe@gmail.com', '$2y$10$BOyHwxJZ7S7QdkxujjO9RO2EDxFQhpXE86hYOraqi3YoE0hVRPI.G', 'user', '2024-10-24 15:44:01', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
