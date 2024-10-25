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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.email_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table concert_event_system.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text,
  `max_participants` int NOT NULL,
  `current_participants` int DEFAULT '0',
  `status` enum('open','closed','canceled') DEFAULT 'open',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.events: ~4 rows (approximately)
INSERT INTO `events` (`id`, `name`, `date`, `time`, `location`, `description`, `max_participants`, `current_participants`, `status`, `image_path`, `created_at`) VALUES
	(15, 'Forestra', '2024-12-31', '15:00:00', 'Orchid Forest Cikole, Lembang, Bandung', 'Konser orkestra yang unik di tengah hutan pinus. Tahun ini, acara tersebut menampilkan Erwin Gutawa Orchestra bersama musisi populer seperti Nadin Amizah, Efek Rumah Kaca, The Adams, Scaller, Isyana Sarasvati, dan Jason Ranti. Berlangsung mulai pukul 11 siang, Forestra juga memiliki tiga area berbeda: Simfoni Area untuk konser utama, Gema Area yang menampilkan toko pop-up vinyl dan diskusi inspiratif, serta Rasa Area yang menawarkan berbagai pilihan makanan dan minuman', 1000, 0, 'open', 'uploads/events/671b27ac32900.jpg', '2024-10-25 05:07:56'),
	(16, 'Sunset di Kebun', '2025-07-23', '16:00:00', 'Kebun Raya Cibodas', 'Konser dengan konsep intimate show, penonton dapat menikmati alunan musik dari musisi seperti Nadin Amizah, Fiersa Besari, Fourtwnty, Kunto Aji, dan Reality Club.', 500, 0, 'open', 'uploads/events/671b290bc6f8f.jpg', '2024-10-25 05:13:47'),
	(17, 'Java Jazz Festival', '2025-05-20', '18:00:00', 'JIExpo Kemayoran, Jakarta.', 'The Event lineup include Laufey, a Grammy Award-winning Icelandic jazz singer-songwriter; October London, a soulful artist signed by Snoop Dogg&#039;s Death Row Records; and The Yussef Dayes Experience, known for its boundary-pushing jazz. Also featured are Randy Brecker, legendary trumpeter, and groups like Scary Pockets, The Amy Winehouse Band, and Incognito, who will return with their 19th album. The festival also highlights Indonesian talents such as Project Pop, Ardhito Pramono, Tompi, Maliq &amp; D’Essentials, and many more', 10000, 0, 'open', 'uploads/events/671b2ab92eed5.jpg', '2024-10-25 05:20:57'),
	(18, 'Knotfest festival', '2025-09-21', '18:30:00', 'Waterworks Park in Des Moines, Iowa.', 'Knotfest festival line up Des Moines, Iowa, this year&#039;s festival will feature Slipknot as the headliner, joined by an impressive roster, including Till Lindemann (Rammstein), Knocked Loose, Hatebreed, Poison the Well, and GWAR. Additional bands include Vended, Dying Wish, Zulu, Twin Temple, Holy Wars, Swollen Teeth, Spine, and Dose, adding diversity to the heavy metal lineup.', 7000, 0, 'open', 'uploads/events/671b2d5880a15.jpg', '2024-10-25 05:32:08');

-- Dumping structure for table concert_event_system.registrations
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `event_id` int DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.registrations: ~0 rows (approximately)

-- Dumping structure for table concert_event_system.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `profile_picture`) VALUES
	(2, 'Admin', 'Admin@gmail.com', '$2y$10$uZVYNwibVe48nCTab4NsR.7p15RuZ1dHN/srKybdvNkoqnFEl74Va', 'admin', '2024-10-22 03:23:55', '../uploads/profile_pictures/671b2c09ccc3b_pp.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
