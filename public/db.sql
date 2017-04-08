# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Database: PH
# Generation Time: 2017-02-26 23:59:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table applications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `applications`;

CREATE TABLE `applications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  `tshirt` enum('Small','Medium','Large','XL') COLLATE utf8_unicode_ci NOT NULL,
  `interests` text COLLATE utf8_unicode_ci,
  `dietary` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `applications_member_id_index` (`member_id`),
  KEY `applications_event_id_index` (`event_id`),
  CONSTRAINT `applications_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `applications_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table credentials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `credentials`;

CREATE TABLE `credentials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `credentials_member_id_foreign` (`member_id`),
  CONSTRAINT `credentials_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table event_member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_member`;

CREATE TABLE `event_member` (
  `event_id` int(10) unsigned NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `recorded_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`event_id`,`member_id`),
  KEY `event_member_event_id_index` (`event_id`),
  KEY `event_member_member_id_index` (`member_id`),
  KEY `event_member_recorded_by_index` (`recorded_by`),
  CONSTRAINT `event_member_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_member_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `event_member_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `privateEvent` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `facebook` text COLLATE utf8_unicode_ci NOT NULL,
  `requiresApplication` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table hackathons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hackathons`;

CREATE TABLE `hackathons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apply` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table location-member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `location-member`;

CREATE TABLE `location-member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_member_location_id_index` (`location_id`),
  KEY `location_member_member_id_index` (`member_id`),
  CONSTRAINT `location_member_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `location_member_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table locations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `loc_lat` double(8,2) NOT NULL,
  `loc_lng` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;

INSERT INTO `locations` (`id`, `name`, `city`, `loc_lat`, `loc_lng`, `created_at`, `updated_at`)
VALUES
	(1,'gfh','hjk',0.00,0.00,'2017-02-25 18:26:24','2017-02-25 18:26:24');

/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table majors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `majors`;

CREATE TABLE `majors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `majors` WRITE;
/*!40000 ALTER TABLE `majors` DISABLE KEYS */;

INSERT INTO `majors` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'devinsova','2017-02-14 03:49:29','2017-02-14 03:49:29');

/*!40000 ALTER TABLE `majors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table member_permission
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_permission`;

CREATE TABLE `member_permission` (
  `member_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `recorded_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`member_id`,`permission_id`),
  KEY `member_permission_member_id_index` (`member_id`),
  KEY `member_permission_permission_id_index` (`permission_id`),
  KEY `member_permission_recorded_by_index` (`recorded_by`),
  CONSTRAINT `member_permission_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `member_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `member_permission_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `member_permission` WRITE;
/*!40000 ALTER TABLE `member_permission` DISABLE KEYS */;

INSERT INTO `member_permission` (`member_id`, `permission_id`, `recorded_by`, `created_at`, `updated_at`)
VALUES
	(1,2,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(1,3,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(1,4,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(1,5,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(1,6,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(1,7,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(3,2,1,'2017-02-25 20:12:09','2017-02-25 20:12:09'),
	(3,3,1,'2017-02-25 20:12:19','2017-02-25 20:12:19'),
	(3,4,1,'2017-02-25 20:12:27','2017-02-25 20:12:27'),
	(3,5,1,'2017-02-25 20:12:37','2017-02-25 20:12:37'),
	(3,6,1,'2017-02-25 20:12:44','2017-02-25 20:12:44'),
	(3,7,1,'2017-02-25 20:12:52','2017-02-25 20:12:52'),
	(4,5,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(4,6,1,'2017-02-25 20:06:27','2017-02-25 20:06:27'),
	(4,7,1,'2017-02-25 20:06:27','2017-02-25 20:06:27');

/*!40000 ALTER TABLE `member_permission` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table member_project
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_project`;

CREATE TABLE `member_project` (
  `member_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`member_id`,`project_id`),
  KEY `member_project_member_id_index` (`member_id`),
  KEY `member_project_project_id_index` (`project_id`),
  CONSTRAINT `member_project_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `member_project_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `privateProfile` tinyint(1) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unsubscribed` tinyint(1) NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_public` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_edu` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `setupEmailSent` date NOT NULL,
  `member_status` enum('Member','Alumni','Inactive') COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('','Male','Female','Other','No') COLLATE utf8_unicode_ci NOT NULL,
  `graduation_year` smallint(6) NOT NULL,
  `major_id` int(10) unsigned DEFAULT NULL,
  `picture` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `facebook` text COLLATE utf8_unicode_ci NOT NULL,
  `github` text COLLATE utf8_unicode_ci NOT NULL,
  `linkedin` text COLLATE utf8_unicode_ci NOT NULL,
  `devpost` text COLLATE utf8_unicode_ci NOT NULL,
  `website` text COLLATE utf8_unicode_ci NOT NULL,
  `resume` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `authenticated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_email_unique` (`email`),
  UNIQUE KEY `members_username_unique` (`username`),
  KEY `members_major_id_foreign` (`major_id`),
  CONSTRAINT `members_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;

INSERT INTO `members` (`id`, `name`, `username`, `privateProfile`, `email`, `unsubscribed`, `phone`, `email_public`, `email_edu`, `password`, `setupEmailSent`, `member_status`, `gender`, `graduation_year`, `major_id`, `picture`, `description`, `facebook`, `github`, `linkedin`, `devpost`, `website`, `resume`, `created_at`, `updated_at`, `authenticated_at`, `remember_token`)
VALUES
	(1,'Devin Sova','devinsova',0,'wasd@wasd.com',0,'','','','$2y$10$efH3Dba2NoAFFwkVYcdIG.viN5d4vEnB33Leimvz/YgjDavlRzUoG','0000-00-00','Member','',2020,NULL,'','','','','','','','','2017-02-25 19:57:31','2017-02-25 19:57:31','2017-02-25 20:01:32',NULL),
	(2,'John Smith','johnsmith',0,'john@purduecs.com',0,'','','','508b9e7447198737d1f4f8ba84979d83','0000-00-00','Member','',1920,NULL,'','','','','','','','','2017-02-25 19:30:37','2017-02-25 19:30:54','0000-00-00 00:00:00','2ZCrFuYhCg9384urWCKRWm1aC2kptafrzzWt3wJf9bz1UIVFX2dFi04WLxR0'),
	(3,'Admin Admin','adminadmin',0,'admin@purduecs.com',0,'','','','456b7016a916a4b178dd72b947c152b7','0000-00-00','Member','',2099,NULL,'','','','','','','','','2017-02-25 19:33:42','2017-02-25 19:51:55','2017-02-25 19:43:39','IoIMmpfd6wQKlzS6546qTxytecdgQWlUjS9TWvm0amR0edPgNH1WIRyTYPk7'),
	(4,'Organizer Organizer','organizerorganizer',0,'organizer@purduecs.com',0,'','','','a3ae1b27a3b04f93096f3a7a1ebae2c4','0000-00-00','Member','',2000,NULL,'','','','','','','','','2017-02-25 19:34:23','2017-02-25 19:43:26','0000-00-00 00:00:00','PxWZIhn9no6jb3vykcvRKF0Gv32uSQPMGnJhpOKt3Ud0EFuWH4Xk7pD2e1v5');

/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`migration`, `batch`)
VALUES
	('2016_04_25_214738_create_members_table',1),
	('2016_04_26_050734_create_events_table',1),
	('2016_05_02_052544_create-locations-table',1),
	('2016_08_24_040909_create-applications-table',1),
	('2016_08_25_004942_create-majors-table',1),
	('2016_09_23_010119_add_superadmin_emails',1),
	('2016_09_23_024319_add_private_events',1),
	('2016_10_09_212954_add_phone_number',1),
	('2016_10_09_230907_create_projects_table',1),
	('2016_11_11_011633_create_credentials_table',1),
	('2016_11_16_202844_add_usernames',1),
	('2016_11_22_184934_add_remember_token',1),
	('2016_11_23_202047_create_permissions_table',1),
	('2016_11_25_235533_create_hackathons_table',1),
	('2016_11_27_164309_remove_admin_columns',1),
	('2016_12_11_035532_add_private_unsubscribed',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `organizer` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`id`, `name`, `organizer`, `description`, `created_at`, `updated_at`)
VALUES
	(2,'admin',0,'','2017-02-25 18:26:24','2017-02-25 18:26:24'),
	(3,'adminpermissions',0,'','2017-02-25 18:26:24','2017-02-25 18:26:24'),
	(4,'permissions',0,'','2017-02-25 18:26:24','2017-02-25 18:26:24'),
	(5,'events',1,'','2017-02-25 18:26:24','2017-02-25 18:26:24'),
	(6,'credentials',1,'','2017-02-25 18:26:24','2017-02-25 18:26:24'),
	(7,'members',1,'','2017-02-25 18:26:24','2017-02-25 18:26:24');

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `github` text COLLATE utf8_unicode_ci NOT NULL,
  `devpost` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_event_id_index` (`event_id`),
  CONSTRAINT `projects_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
