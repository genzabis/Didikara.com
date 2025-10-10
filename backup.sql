-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_didikara
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contributors`
--

DROP TABLE IF EXISTS `contributors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `province_id` tinyint(3) unsigned DEFAULT NULL,
  `district` varchar(120) DEFAULT NULL,
  `occupation` varchar(120) DEFAULT NULL,
  `organization` varchar(150) DEFAULT NULL,
  `role` enum('mapper','advocate','documenter','technologist','multiple') NOT NULL,
  `time_commitment` enum('low','medium','high','flexible') NOT NULL,
  `motivation` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `agree_terms` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_contrib_province` (`province_id`),
  KEY `idx_contact` (`email`,`phone`),
  KEY `idx_role_time` (`role`,`time_commitment`),
  KEY `idx_status` (`status`),
  KEY `fk_contributors_user` (`user_id`),
  CONSTRAINT `fk_contrib_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_contributors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributors`
--

LOCK TABLES `contributors` WRITE;
/*!40000 ALTER TABLE `contributors` DISABLE KEYS */;
INSERT INTO `contributors` VALUES (1,NULL,'Ayu Lestari','ayu.lestari@example.com','081234567890',1,'Kabupaten Banyumas','Guru SD','SDN 1 Sokaraja','mapper','medium','Ingin membantu memetakan kondisi sekolah di daerah terpencil.','Pernah menjadi relawan literasi.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(2,NULL,'Rafi Pratama','rafi.pratama@example.com','085612345678',2,'Kota Bandung','Mahasiswa','UIN Bandung','documenter','high','Senang melakukan riset lapangan dan dokumentasi sosial.','Aktif di komunitas kampus.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(3,NULL,'Siti Rahmawati','siti.rahma@example.com','082112223334',3,'Kabupaten Gresik','Pegawai Dinas Pendidikan','Disdik Gresik','advocate','medium','Ingin memperbaiki kebijakan berbasis data nyata.','Berpengalaman di birokrasi pendidikan.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(4,NULL,'Dimas Saputra','dimas.saputra@example.com','081355667788',4,'Kota Yogyakarta','Desainer Grafis','Komunitas EduTech','technologist','flexible','Mau bantu bikin tampilan data pendidikan lebih mudah dibaca.','Pernah buat dashboard data sosial.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(5,NULL,'Nurul Aini','nurul.aini@example.com','081322445566',5,'Kabupaten Sleman','Mahasiswa','UIN SAIZU Purwokerto','mapper','low','Mau berkontribusi lewat data.','Ikut proyek kuliah berbasis data.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(6,NULL,'Budi Hartono','budi.hartono@example.com','081234889900',1,'Kota Semarang','Wirausaha','Komunitas Edupreneur','advocate','medium','Peduli pada pendidikan vokasi di desa.','Aktif di forum UMKM.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(7,NULL,'Clara Widya','clara.widya@example.com','082134567890',2,'Kota Bogor','Peneliti Sosial','LIPI','documenter','high','Meneliti dampak sosial pendidikan digital.','Publikasi di jurnal nasional.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(8,NULL,'Ahmad Fauzi','ahmad.fauzi@example.com','082145678901',3,'Kabupaten Malang','Mahasiswa','UB Malang','technologist','medium','Mau kembangkan aplikasi edukasi lokal.','Ikut hackathon bidang pendidikan.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(9,NULL,'Mega Putri','mega.putri@example.com','081278945612',4,'Kota Surabaya','Jurnalis','Media Pendidikan','documenter','high','Ingin mendokumentasikan cerita perubahan sekolah.','Reporter rubrik edukasi.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57'),(10,NULL,'Yusuf Rahman','yusuf.rahman@example.com','081365478912',5,'Kabupaten Bantul','Mahasiswa','UNY','mapper','medium','Mau bantu petakan sarana pendidikan daerah.','Suka melakukan survey lapangan.',1,'approved','2025-10-09 13:13:57','2025-10-09 13:13:57');
/*!40000 ALTER TABLE `contributors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issue_types`
--

DROP TABLE IF EXISTS `issue_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issue_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(60) NOT NULL,
  `name` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issue_types`
--

LOCK TABLES `issue_types` WRITE;
/*!40000 ALTER TABLE `issue_types` DISABLE KEYS */;
INSERT INTO `issue_types` VALUES (1,'fasilitas','Fasilitas'),(2,'guru','Guru'),(3,'kurikulum','Kurikulum'),(4,'akses','Akses'),(5,'teknologi','Teknologi'),(6,'alat','Alat & Perlengkapan'),(7,'administrasi','Administrasi'),(8,'ekstrakurikuler','Ekstrakurikuler'),(9,'lainnya','Lainnya');
/*!40000 ALTER TABLE `issue_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provinces` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (1,'jawa-tengah','Jawa Tengah'),(2,'jawa-barat','Jawa Barat'),(3,'jawa-timur','Jawa Timur'),(4,'dki-jakarta','DKI Jakarta'),(5,'di-yogyakarta','DI Yogyakarta'),(6,'aceh','Aceh'),(7,'sumatera-utara','Sumatera Utara'),(8,'sumatera-barat','Sumatera Barat'),(9,'riau','Riau'),(10,'kepulauan-riau','Kepulauan Riau'),(11,'jambi','Jambi'),(12,'bengkulu','Bengkulu'),(13,'sumatera-selatan','Sumatera Selatan'),(14,'lampung','Lampung'),(15,'kepulauan-bangka-belitung','Kepulauan Bangka Belitung'),(16,'banten','Banten'),(17,'bali','Bali'),(18,'nusa-tenggara-barat','Nusa Tenggara Barat'),(19,'nusa-tenggara-timur','Nusa Tenggara Timur'),(20,'kalimantan-barat','Kalimantan Barat'),(21,'kalimantan-tengah','Kalimantan Tengah'),(22,'kalimantan-selatan','Kalimantan Selatan'),(23,'kalimantan-timur','Kalimantan Timur'),(24,'kalimantan-utara','Kalimantan Utara'),(25,'sulawesi-utara','Sulawesi Utara'),(26,'sulawesi-tengah','Sulawesi Tengah'),(27,'sulawesi-selatan','Sulawesi Selatan'),(28,'sulawesi-tenggara','Sulawesi Tenggara'),(29,'gorontalo','Gorontalo'),(30,'sulawesi-barat','Sulawesi Barat'),(31,'maluku','Maluku'),(32,'maluku-utara','Maluku Utara'),(33,'papua','Papua'),(34,'papua-barat','Papua Barat'),(35,'papua-tengah','Papua Tengah'),(36,'papua-pegunungan','Papua Pegunungan'),(37,'papua-selatan','Papua Selatan'),(38,'papua-barat-daya','Papua Barat Daya');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_attachments`
--

DROP TABLE IF EXISTS `report_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  CONSTRAINT `fk_attach_report` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_attachments`
--

LOCK TABLES `report_attachments` WRITE;
/*!40000 ALTER TABLE `report_attachments` DISABLE KEYS */;
INSERT INTO `report_attachments` VALUES (1,46,'uploads/reports/WhatsApp_Image_2025-08-16_at_19.17.17_dc7ecdd8_68e76fb62b197.jpg','image/jpeg',636383,'2025-10-09 15:17:58'),(2,47,'uploads/reports/WhatsApp_Image_2025-08-16_at_19.17.17_dc7ecdd8_68e7730ddb4c3.jpg','image/jpeg',636383,'2025-10-09 15:32:13'),(3,48,'uploads/reports/IMG-20251009-WA0045_68e773dbcea80.jpg','image/jpeg',45890,'2025-10-09 15:35:39'),(4,49,'uploads/reports/IMG-20251009-WA0045_68e773de71cfc.jpg','image/jpeg',45890,'2025-10-09 15:35:42'),(5,50,'uploads/reports/WhatsApp_Image_2025-08-16_at_19.17.17_dc7ecdd8_68e774c82f3c4.jpg','image/jpeg',636383,'2025-10-09 15:39:36'),(6,51,'uploads/reports/Screenshot__2__68e776b91414e.png','image/png',424987,'2025-10-09 15:47:53'),(7,52,'uploads/reports/Screenshot__15__68e779e5c8511.png','image/png',502701,'2025-10-09 16:01:25'),(8,53,'uploads/reports/WhatsApp_Image_2025-08-16_at_19.17.17_dc7ecdd8_68e77aa79208a.jpg','image/jpeg',636383,'2025-10-09 16:04:39');
/*!40000 ALTER TABLE `report_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `school_name` varchar(200) NOT NULL,
  `province_id` tinyint(3) unsigned DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `issue_type_id` tinyint(3) unsigned NOT NULL,
  `severity` enum('low','medium','high') NOT NULL DEFAULT 'low',
  `description` text NOT NULL,
  `reporter_name` varchar(150) DEFAULT NULL,
  `reporter_nik` varchar(20) DEFAULT NULL,
  `reporter_email` varchar(200) DEFAULT NULL,
  `reporter_phone` varchar(32) DEFAULT NULL,
  `agree_terms` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','confirmed','investigating','resolved','rejected','archived') NOT NULL DEFAULT 'pending',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_issue` (`issue_type_id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_province` (`province_id`),
  KEY `idx_severity` (`severity`),
  KEY `idx_lat_lng` (`latitude`,`longitude`),
  KEY `fk_reports_user` (`user_id`),
  FULLTEXT KEY `ft_description` (`description`),
  CONSTRAINT `fk_reports_issue` FOREIGN KEY (`issue_type_id`) REFERENCES `issue_types` (`id`),
  CONSTRAINT `fk_reports_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,NULL,'SDN 1 Sokaraja',1,'Jl. Raya Sokaraja No.10, Banyumas',1,'medium','Kondisi toilet sekolah rusak dan tidak berfungsi selama 2 minggu.','Ayu Lestari',NULL,'ayu.lestari@example.com','081234567890',1,'resolved',-7.4240000,109.2390000,'2025-10-09 13:15:02','2025-10-09 13:20:41'),(2,NULL,'SMPN 3 Bandung',2,'Jl. Merdeka No.21, Bandung',2,'high','Kekurangan guru matematika sejak awal semester.','Rafi Pratama',NULL,'rafi.pratama@example.com','085612345678',1,'confirmed',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(3,NULL,'SDN 5 Gresik',3,'Jl. KH. Ahmad Dahlan No.8, Gresik',1,'medium','Atap kelas bocor ketika hujan, siswa belajar di luar ruangan.','Siti Rahmawati',NULL,'siti.rahma@example.com','082112223334',1,'resolved',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(4,NULL,'SMA 1 Yogyakarta',4,'Jl. Malioboro No.45, Yogyakarta',3,'low','Kurikulum baru belum tersosialisasi dengan baik ke semua guru.','Dimas Saputra',NULL,'dimas.saputra@example.com','081355667788',1,'pending',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(5,NULL,'SMK N 2 Sleman',5,'Jl. Kaliurang Km.10, Sleman',5,'high','Lab komputer tidak berfungsi karena 70% perangkat rusak.','Nurul Aini',NULL,'nurul.aini@example.com','081322445566',1,'investigating',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(6,NULL,'SDN 3 Semarang',1,'Jl. Imam Bonjol No.3, Semarang',1,'medium','Listrik sekolah sering padam saat jam belajar berlangsung.','Budi Hartono',NULL,'budi.hartono@example.com','081234889900',1,'resolved',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(7,NULL,'SMPN 4 Bogor',2,'Jl. Pajajaran No.17, Bogor',7,'low','Kegiatan administrasi sekolah masih dilakukan manual.','Clara Widya',NULL,'clara.widya@example.com','082134567890',1,'pending',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(8,NULL,'SMA 2 Malang',3,'Jl. Raya Malang No.33, Malang',4,'high','Akses ke sekolah sulit karena jalan rusak parah setelah banjir.','Ahmad Fauzi',NULL,'ahmad.fauzi@example.com','082145678901',1,'resolved',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(9,NULL,'SMK 1 Surabaya',3,'Jl. Darmo No.9, Surabaya',2,'medium','Banyak guru senior pensiun tanpa pengganti tetap.','Mega Putri',NULL,'mega.putri@example.com','081278945612',1,'confirmed',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(10,NULL,'SDN 2 Bantul',5,'Jl. Parangtritis Km.7, Bantul',1,'medium','Dinding kelas retak, membahayakan keselamatan siswa.','Yusuf Rahman',NULL,'yusuf.rahman@example.com','081365478912',1,'resolved',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(11,NULL,'SMPN 6 Purwokerto',1,'Jl. Gerilya No.88, Banyumas',6,'low','Kekurangan alat peraga IPA untuk praktikum.','Ayu Lestari',NULL,'ayu.lestari@example.com','081234567890',1,'investigating',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(12,NULL,'SDN 4 Bandung',2,'Jl. Asia Afrika No.5, Bandung',8,'medium','Kegiatan ekstrakurikuler berhenti karena kekurangan pembina.','Rafi Pratama',NULL,'rafi.pratama@example.com','085612345678',1,'pending',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(13,NULL,'SMA N 3 Gresik',3,'Jl. Veteran No.18, Gresik',1,'high','Kelas baru tidak memiliki meja dan kursi memadai.','Siti Rahmawati',NULL,'siti.rahma@example.com','082112223334',1,'resolved',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(14,NULL,'SMPN 5 Yogyakarta',4,'Jl. Solo No.23, Yogyakarta',9,'low','Tidak ada data siswa digital, semua masih manual.','Dimas Saputra',NULL,'dimas.saputra@example.com','081355667788',1,'confirmed',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(15,NULL,'SMK 2 Sleman',5,'Jl. Godean No.14, Sleman',1,'medium','Kebersihan sekolah kurang, tempat sampah tidak tersedia cukup.','Nurul Aini',NULL,'nurul.aini@example.com','081322445566',1,'resolved',NULL,NULL,'2025-10-09 13:15:02','2025-10-09 13:15:02'),(36,NULL,'SDN 1 Sokaraja',1,'Jl. Raya Sokaraja No.10, Banyumas',1,'medium','Toilet sekolah rusak dan tidak berfungsi selama 2 minggu.','Ayu Lestari',NULL,'ayu.lestari@example.com','081234567890',1,'resolved',-7.4240000,109.2390000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(37,NULL,'SMPN 2 Cibinong',2,'Jl. Raya Cibinong KM 2, Bogor',4,'high','Jalan menuju sekolah rusak parah saat musim hujan.','Rafi Pratama',NULL,'rafi.pratama@example.com','085612345678',1,'pending',-6.4810000,106.8318000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(38,NULL,'SMAN 3 Denpasar',17,'Jl. Gunung Agung No.2, Denpasar Barat',3,'medium','Kurikulum kejuruan belum sinkron dengan kebutuhan industri.','Dewa Putra',NULL,'dewa@example.com','081345678901',1,'investigating',-8.6705000,115.2126000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(39,NULL,'SMPN 1 Samarinda',23,'Jl. Diponegoro No.4, Samarinda',5,'medium','Sekolah kekurangan perangkat komputer untuk pelajaran TIK.','Bayu Saputra',NULL,'bayu@example.com','082112223333',1,'confirmed',-0.5022000,117.1536000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(40,NULL,'SDN 5 Manado',25,'Jl. Sam Ratulangi No.10, Manado',2,'medium','Kekurangan guru Bahasa Inggris dan IPA.','Lia Marlina',NULL,'lia@example.com','081277788899',1,'pending',1.4748000,124.8421000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(41,NULL,'SMKN 1 Kupang',19,'Jl. Ahmad Yani No.12, Kupang',3,'low','Program keahlian belum diperbarui sesuai kebutuhan industri.','Agus Prasetyo',NULL,'agus@example.com','081255555555',1,'investigating',-10.1772000,123.6070000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(42,NULL,'SMPN 4 Pontianak',20,'Jl. Tanjungpura No.21, Pontianak',1,'medium','Atap aula sekolah bocor parah.','Mega Utami',NULL,'mega@example.com','081333444555',1,'resolved',-0.0227000,109.3414000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(43,NULL,'SDN 2 Wonosari',5,'Jl. Baron No.5, Gunungkidul',4,'low','Akses internet sangat lemah, menyulitkan pembelajaran daring.','Rahmat Hidayat',NULL,'rahmat@example.com','081266667777',1,'pending',-7.9623000,110.6035000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(44,NULL,'SMAN 1 Padang',8,'Jl. Sudirman No.5, Padang Barat',2,'medium','Guru matematika pensiun, belum ada pengganti.','Indah Sari',NULL,'indah@example.com','081299998888',1,'confirmed',-0.9471000,100.4172000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(45,NULL,'SMAN 1 Jayapura',33,'Jl. Trikora No.10, Jayapura',1,'high','Bangunan laboratorium rusak pasca gempa ringan.','Maria Lestari',NULL,'maria@example.com','081278889999',1,'pending',-2.5337000,140.7181000,'2025-10-09 14:01:40','2025-10-09 14:01:40'),(46,NULL,'SDN 1 Purbalingga',16,'Karangpucung 03/01',7,'medium','dassadafsfaf','Arifian Ilham Nur Riandana',NULL,'arifianilhamnurriandana@gmail.com','085174472744',1,'pending',-7.3859072,109.3664768,'2025-10-09 15:17:58','2025-10-09 15:17:58'),(47,NULL,'SMKN 1 Purbalingga',1,'Purbalingga, Jawa Tengah',2,'medium','gurunya kalo ngajar cuma ngobrol sendiri','Arifian Ilham Nur Riandana',NULL,'arifianilhamnurriandana@gmail.com','085174472744',1,'pending',-7.3859072,109.3664768,'2025-10-09 15:32:13','2025-10-09 15:32:13'),(48,NULL,'SDN 1 KERTANEGARA',29,'krpc',2,'medium','gakanjdkxnannakk','Arifian Ilham Nur Riandana',NULL,'arifianilhamnurriandana@gmail.com','085174472744',1,'pending',NULL,NULL,'2025-10-09 15:35:39','2025-10-09 15:35:39'),(49,NULL,'SDN 1 KERTANEGARA',29,'krpc',2,'medium','gakanjdkxnannakk','Arifian Ilham Nur Riandana',NULL,'arifianilhamnurriandana@gmail.com','085174472744',1,'pending',NULL,NULL,'2025-10-09 15:35:42','2025-10-09 15:35:42'),(50,NULL,'SMKN 1 Kalimanah',1,'Kertanegara',3,'medium','kurikulumnya gajelas','Arifian Ilham Nur Riandana',NULL,'arifianilhamnurriandana@gmail.com','085174472744',1,'pending',-7.3859072,109.3664768,'2025-10-09 15:39:36','2025-10-09 15:39:36'),(51,NULL,'SMKN 1 Banjarnegara',1,'Banjarnegara, Jawa Tengah',6,'low','ada beberapa kursi yang rusak','Arifian Ilham Nur Riandana',NULL,'arifianilhamnurriandana@gmail.com','085174472744',1,'pending',-7.3859072,109.3664768,'2025-10-09 15:47:53','2025-10-09 15:47:53'),(52,NULL,'SMKN 1 Cikarang',2,'Kertanegara',2,'medium','gurunya galak galak','Arifian Ilham Nur Riandana','3303182704040003','arifianilhamnurriandana@gmail.com','085174472744',1,'pending',NULL,NULL,'2025-10-09 16:01:25','2025-10-09 16:01:25'),(53,NULL,'SDN 1 Sukamaju',2,'Kertanegara',7,'medium','adhlaskdjhaksj','Arifian Ilham Nur Riandana','3303182704040003','arifianilhamnurriandana@gmail.com','085174472744',1,'pending',-7.3905343,109.3481785,'2025-10-09 16:04:39','2025-10-09 16:04:39');
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','admin_daerah','admin_wilayah') DEFAULT NULL,
  `province_id` tinyint(3) unsigned DEFAULT NULL,
  `district` varchar(120) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `province_id` (`province_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin pusat','admin@example.com',NULL,'$2y$10$esEBMMr1Cp41.fG1Wm2XWumWY4vqGkj0qdrzADclCz9sytzP2Nd1.','admin',NULL,NULL,1,'2025-10-09 10:17:37','2025-10-10 16:54:35'),(2,'admin daerah','daerah@example.com',NULL,'$2y$10$EFIDzqps6k5qsG6PLsdmleSv8/Eq8uHlHVFmaRdkGl.d3tYohebXu','admin_daerah',NULL,'purbalingga',1,'2025-10-09 10:17:37','2025-10-10 17:14:16'),(3,'admin wilayah','wilayah@example.com',NULL,'$2y$10$iW4dNbwO60BFdYXQQGVM8uGg.gk9DeOz.dohRKGTX8l0etVwzmeVO','admin_wilayah',1,NULL,1,'2025-10-09 10:17:37','2025-10-10 17:17:14');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-10 17:53:00
