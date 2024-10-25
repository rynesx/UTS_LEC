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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table concert_event_system.events: ~4 rows (approximately)
INSERT INTO `events` (`id`, `name`, `date`, `time`, `location`, `description`, `max_participants`, `current_participants`, `status`, `image_path`, `created_at`) VALUES
	(15, 'Forestra', '2024-12-31', '15:00:00', 'Orchid Forest Cikole, Lembang, Bandung', 'Konser orkestra yang unik di tengah hutan pinus. Tahun ini, acara tersebut menampilkan Erwin Gutawa Orchestra bersama musisi populer seperti Nadin Amizah, Efek Rumah Kaca, The Adams, Scaller, Isyana Sarasvati, dan Jason Ranti. Berlangsung mulai pukul 11 siang, Forestra juga memiliki tiga area berbeda: Simfoni Area untuk konser utama, Gema Area yang menampilkan toko pop-up vinyl dan diskusi inspiratif, serta Rasa Area yang menawarkan berbagai pilihan makanan dan minuman', 1000, 0, 'open', 'uploads/events/671b27ac32900.jpg', '2024-10-25 05:07:56'),
	(16, 'Sunset di Kebun', '2025-07-23', '16:00:00', 'Kebun Raya Cibodas', 'Konser dengan konsep intimate show, penonton dapat menikmati alunan musik dari musisi seperti Nadin Amizah, Fiersa Besari, Fourtwnty, Kunto Aji, dan Reality Club.', 500, 0, 'open', 'uploads/events/671b290bc6f8f.jpg', '2024-10-25 05:13:47'),
	(17, 'Java Jazz Festival', '2025-05-20', '18:00:00', 'JIExpo Kemayoran, Jakarta.', 'The Event lineup include Laufey, a Grammy Award-winning Icelandic jazz singer-songwriter; October London, a soulful artist signed by Snoop Dogg&amp;#039;s Death Row Records; and The Yussef Dayes Experience, known for its boundary-pushing jazz. Also featured are Randy Brecker, legendary trumpeter, and groups like Scary Pockets, The Amy Winehouse Band, and Incognito, who will return with their 19th album. The festival also highlights Indonesian talents such as Project Pop, Ardhito Pramono, Tompi, Maliq &amp;amp; D’Essentials, and many more', 10000, 0, 'open', 'uploads/events/671b2ab92eed5.jpg', '2024-10-25 05:20:57'),
	(18, 'Knotfest festival', '2025-09-21', '18:30:00', 'Waterworks Park in Des Moines, Iowa.', 'Knotfest festival line up Des Moines, Iowa, this year&#039;s festival will feature Slipknot as the headliner, joined by an impressive roster, including Till Lindemann (Rammstein), Knocked Loose, Hatebreed, Poison the Well, and GWAR. Additional bands include Vended, Dying Wish, Zulu, Twin Temple, Holy Wars, Swollen Teeth, Spine, and Dose, adding diversity to the heavy metal lineup.', 7000, 0, 'open', 'uploads/events/671b2d5880a15.jpg', '2024-10-25 05:32:08'),
	(19, 'Kerlap Kerlip Fest', '2025-01-22', '14:00:00', 'Indonesia Convention Exhibition (ICE) BSD', '📍 Lokasi: ICE BSD City, Tangerang\r\n🕒 Mulai: 14.00 WIB\r\n\r\n✨ Lineup Artis Terkenal\r\nBergabunglah dalam kemeriahan Kerlap Kerlip Fest dan saksikan sederet musisi papan atas yang akan mengguncang panggung! Lineup meliputi:\r\n\r\nDenny Caknan: Raja pop Jawa yang akan membawa lagu-lagu penuh nuansa cinta dan kehidupan sehari-hari.\r\nNDX AKA: Duo hip-hop Jawa dengan sentuhan dangdut yang khas.\r\nGildCoustic dan Tipe-X: Kolaborasi antara akustik unik dan ska yang bersemangat.\r\nHura Hura Club dan Robokop: Dengan lagu-lagu yang pasti memeriahkan suasana.\r\nBaale dan Nadim Amizah: Melodi khas yang penuh cerita dan pesan mendalam.\r\nNikmati pengalaman musik spektakuler dalam suasana festival yang hidup! Jangan lewatkan juga area food and beverage, spot foto tematik, dan hiburan lainnya untuk menambah keseruan acara.\r\n\r\n🎟️ Dapatkan Tiket Anda Sekarang!\r\nTiket bisa dibeli melalui platform resmi Kerlap Kerlip Fest atau mitra penjualan tiket online.\r\n\r\n#KerlapKerlipFest2024 #FestivalMusikIndonesia #ICEBSDCity', 500, 0, 'open', 'uploads/events/671b65e682f71.jpg', '2024-10-25 09:33:26'),
	(20, 'GIGINFINITY anniversay 30th', '2024-12-13', '19:00:00', 'Istora Senayan', 'Spesial untuk Anda, Penggemar Setia GIGI!\r\n\r\nMari bersama rayakan 30 tahun perjalanan GIGI dalam konser spektakuler yang mengangkat nuansa patriotik, cinta tanah air, dan semangat kebersamaan. Saksikan aksi panggung legendaris dari Armand Maulana, Thomas Ramdhan, Gusti Hendy, dan Dewa Budjana!\r\n\r\n🎸 Penampilan Kolaboratif Eksklusif!\r\nDimeriahkan oleh kolaborasi dengan Afgan, Kris Dayanti, Mahalini, dan Ariel NOAH yang akan membawakan lagu-lagu ikonik GIGI dengan aransemen baru!\r\n\r\n🎶 Setlist Nostalgia dan Hits Populer!\r\nMenyanyikan bersama lagu-lagu terbaik seperti &quot;Perdamaian,&quot; &quot;Janji,&quot; &quot;Damainya Cinta,&quot; dan banyak lagi, menjanjikan malam penuh kenangan dan kemeriahan yang tak terlupakan.\r\n\r\n🕊️ Pesan Khusus untuk Bangsa\r\nSaksikan juga visual simbolik dengan tema nasionalisme yang kuat, mulai dari gitar Garuda hingga bendera merah-putih, sebagai penghormatan bagi negeri ini.', 2000, 0, 'open', 'uploads/events/671b6874248cf.jpeg', '2024-10-25 09:44:20'),
	(21, 'Head In The Cloud', '2025-05-23', '19:00:00', 'Communitiy Park PIK 2', 'Head in the Clouds Festival akan kembali hadir di Indonesia pada 2025. Festival musik ini akan digelar oleh 88rising di Jakarta, dengan berbagai artis ternama yang akan tampil di Community Park PIK 2. Acara ini mengusung konsep unik yang memadukan berbagai genre dari pop, hip-hop, hingga EDM, menampilkan artis seperti Rich Brian, NIKI, Joji, Jackson Wang, dan banyak lagi. Head in the Clouds sebelumnya diadakan pada Desember 2024 dengan kehadiran lebih dari 20 artis, termasuk nama besar seperti (G)I-DLE dan YOASOBI, yang berhasil menarik ribuan penonton', 2000, 0, 'open', 'uploads/events/671b6b4d2f8a1.jpeg', '2024-10-25 09:56:29'),
	(22, 'Pestapora', '2025-07-05', '15:00:00', 'Gambir Expo &amp; Hall D2 JIEXPO Kemayoran, Jakarta', '📍 Lokasi: Gambir Expo &amp; Hall D2 JIEXPO, Kemayoran, Jakarta\r\n\r\n🎶 Lineup Terkemuka\r\nFestival ini menghadirkan lebih dari 160 penampil dengan variasi genre. Di antaranya:\r\n\r\nHari 1: Tulus, Hindia, Ardhito Pramono, Susilo Bambang Yudhoyono (SBY)\r\nHari 2: Raisa, The SIGIT, Feel Koplo\r\nHari 3: Pamungkas, Andien, Maliq &amp; D’Essentials, Dewa 19\r\nNikmati pengalaman musik beragam dan penuh kejutan di Pestapora 2024', 2000, 0, 'open', 'uploads/events/671b6d2bdc901.jpg', '2024-10-25 10:04:27'),
	(23, 'LALALA Fest', '2025-08-22', '17:00:00', 'JIExpo Kemayoran, Jakarta', 'LaLaLa Fest 2025 akan berlangsung dari tanggal 23 hingga 25 Agustus di Jakarta International Expo (JIExpo) Kemayoran, Jakarta. Festival ini menyajikan berbagai genre dan menampilkan musisi internasional serta lokal terkenal, termasuk Conan Gray, Bruno Major, Aurora, Madison Beer, The Temper Trap, Nothing But Thieves, 10CM, dan Sasha Alex Sloan. Artis dari Indonesia seperti Isyana Sarasvati, Nadin Amizah, Maliq &amp; D&#039;Essentials, Danilla, Reality Club, dan GAC juga akan hadir, menjadikan acara ini penuh warna dengan bakat-bakat musik dari berbagai negara.\r\n\r\nBagi pengunjung, tersedia beberapa kategori tiket, mulai dari Daily Pass hingga 3-Day Pass dengan harga presale yang bervariasi, serta pilihan tiket VIP untuk pengalaman yang lebih eksklusif. Tiket dijual melalui situs resmi mereka dengan beberapa kategori presale yang sudah dibuka sejak awal tahun', 3000, 0, 'open', 'uploads/events/671b6dfc01318.jpg', '2024-10-25 10:07:56'),
	(24, 'UMN Festival', '2024-11-30', '16:00:00', 'Universitas Multimedia Nusantara', '✨ Lineup Artis\r\nUMN Festival tahun ini menghadirkan musisi-musisi yang sedang digemari dengan lineup spesial:\r\n\r\nJuicy Luicy: Band dengan hits seperti &quot;Lantas&quot; yang membawa nuansa pop dan lirik puitis.\r\nPaul Partohap: Penyanyi indie yang dikenal dengan vokalnya yang khas dan lirik mendalam.\r\nNO!Z: Grup dengan energi punk yang siap memberikan penampilan penuh semangat.\r\nFestival ini juga menyajikan area kuliner, pameran seni, dan aktivitas interaktif yang cocok untuk semua pengunjung. Tiket bisa diperoleh melalui situs resmi UMN Festival dan platform mitra penjualan tiket online. Pastikan hadir dan jadi bagian dari pengalaman penuh warna di UMN Festival 2024!', 2000, 0, 'open', 'uploads/events/671b6f056cad3.jpg', '2024-10-25 10:12:21'),
	(25, 'Night In UMN', '2024-11-06', '20:00:00', 'Universitas Multimedia Nusantara', '✨ Tentang Acara\r\nBergabunglah untuk malam penuh kreativitas di Night At UMN, di mana seni, musik, dan pengalaman interaktif bersatu. Acara ini menampilkan berbagai genre musik dari band lokal dan DJ terkemuka, serta instalasi seni yang mengagumkan.\r\n\r\n🎶 Lineup Musisi dan DJ\r\n\r\nDimas Fajar: Penyanyi dan penulis lagu indie yang dikenal dengan melodi catchy dan lirik emosional.\r\nDJ Nessa: DJ wanita berbakat yang akan mengguncang malam dengan setlist elektronik yang energik.\r\nSamantha Band: Band pop yang akan menyajikan lagu-lagu hits terbaru dan klasik.\r\nNoah &amp; The Ocean: Band dengan alunan musik folk yang akan membawa suasana santai dan damai.\r\n🎨 Pameran Seni\r\nNikmati instalasi seni interaktif dari seniman lokal, termasuk lukisan, patung, dan seni digital yang akan menghiasi ruang acara.\r\n\r\n🍔 Food &amp; Beverage\r\nSajian kuliner dari berbagai food truck dan stand makanan lokal, menjanjikan pengalaman rasa yang beragam.\r\n\r\n🎟️ Tiket\r\nTiket dapat dibeli secara online melalui situs resmi Night At UMN dan partner tiket daring dengan harga mulai dari Rp100.000.\r\n\r\n\r\n(serius ini boongan)', 500, 0, 'open', 'uploads/events/671b6fd497843.jpg', '2024-10-25 10:15:48');

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
