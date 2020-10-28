-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2020 at 11:35 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `huddland-parliament`
--

-- --------------------------------------------------------

--
-- Table structure for table `constituencies`
--

DROP TABLE IF EXISTS `constituencies`;
CREATE TABLE IF NOT EXISTS `constituencies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `electorate` int(11) NOT NULL,
  `region` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `constituencies`
--

INSERT INTO `constituencies` (`id`, `electorate`, `region`) VALUES
(1, 73292, 'Cruickshankstad'),
(2, 84555, 'Lockmanfort'),
(6, 79428, 'East Holdenport'),
(7, 75800, 'Shanahanburgh'),
(9, 84570, 'Port Autumnborough'),
(10, 88322, 'Port Terrellburgh'),
(11, 73626, 'North Kaela'),
(12, 89861, 'Port Vladimirfort'),
(13, 81042, 'Cornersbury'),
(14, 76271, 'Veummouth'),
(15, 94682, 'East Abdulville'),
(18, 91977, 'Reneemouth'),
(19, 83355, 'Rowebury'),
(25, 68477, 'North Millerbury'),
(26, 82823, 'Coleview'),
(27, 86452, 'West Donaldfurt'),
(28, 91638, 'West Helgaside'),
(29, 70647, 'Chelsieberg'),
(30, 85679, 'Camronmouth');

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

DROP TABLE IF EXISTS `interests`;
CREATE TABLE IF NOT EXISTS `interests` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`id`, `name`) VALUES
(1, 'Immigration'),
(2, 'National Security'),
(3, 'Prisons and Probation'),
(4, 'Policing'),
(5, 'Defence'),
(6, 'Health and Social care'),
(7, 'Business and Innovation'),
(8, 'Energy and Industrial Strategy'),
(9, 'International Trade'),
(10, 'Social Security'),
(11, 'Education'),
(12, 'Environment'),
(13, 'Rural Affairs'),
(14, 'Housing'),
(15, 'Transport'),
(16, 'International Development'),
(17, 'Culture, Media and Sport');

-- --------------------------------------------------------

--
-- Table structure for table `interest_member`
--

DROP TABLE IF EXISTS `interest_member`;
CREATE TABLE IF NOT EXISTS `interest_member` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `interest_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`member_id`,`interest_id`),
  KEY `fk_member_interest_members_interest_id` (`interest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interest_member`
--

INSERT INTO `interest_member` (`member_id`, `interest_id`) VALUES
(1, 1),
(8, 1),
(1, 2),
(5, 2),
(9, 3),
(1, 4),
(9, 4),
(5, 5),
(4, 6),
(7, 6),
(3, 7),
(8, 7),
(3, 8),
(8, 8),
(3, 9),
(10, 9),
(7, 10),
(4, 11),
(2, 12),
(6, 14),
(7, 14),
(4, 15),
(2, 16),
(10, 16);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `party_id` int(11) UNSIGNED DEFAULT NULL,
  `constituency_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_members_constituencies_party_id` (`party_id`),
  KEY `constituency_id` (`constituency_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `firstname`, `lastname`, `date_of_birth`, `party_id`, `constituency_id`) VALUES
(1, 'Quincy', 'Daniel', '1967-01-08', 3, 6),
(2, 'Tina', 'Barrows', '1979-10-30', 3, 7),
(3, 'Amira', 'Bogan', '1990-03-14', 3, 12),
(4, 'August', 'Little', '1973-12-10', 3, 13),
(5, 'Maxie', 'Price', '1958-09-01', 2, 25),
(6, 'Yasmine', 'Terry', '1963-08-21', 2, 28),
(7, 'Bryon', 'Balistreri', '1984-03-18', 4, 29),
(8, 'Alfreda', 'Connelly', '1970-07-19', 5, 30),
(9, 'Adil', 'Iqbal', '1961-02-10', 1, 2),
(10, 'Sophia', 'Podalski', '1953-04-13', 5, 15);

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

DROP TABLE IF EXISTS `parties`;
CREATE TABLE IF NOT EXISTS `parties` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `date_of_foundation` smallint(6) NOT NULL,
  `principal_colour` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `name`, `date_of_foundation`, `principal_colour`) VALUES
(1, 'Putting People First', 1917, 'purple'),
(2, 'Democratic Alliance', 1987, 'light blue'),
(3, 'Traditionalists', 1851, 'red'),
(4, 'Environmentalists', 1993, 'navy'),
(5, 'The Fairness Party', 2007, 'green');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(4) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kate', 'k.l.hutton@assign3.ac.uk', '$2y$10$C8RsCwFPKUbhuWU9ze4p9e1TdjJxhUVKp/IJF9kpxzul9jgmDya36', 1, NULL, NULL, NULL),
(2, 'Yousef', 'y.miandad@assign3.ac.uk', '$2y$10$x7f9igWGzIUVJ4XcGxVbmO6LHe.HwLLGqR0aA6gllxMT50.POHMM.', 2, NULL, NULL, NULL),
(3, 'Sunil', 's.laxman@assign3.ac.uk', '$2y$10$JBaf7d66ishGUwGDcgSs.uNKyqTqEcdMzZgiPBvp5034wCB.hikKS', 1, NULL, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interest_member`
--
ALTER TABLE `interest_member`
  ADD CONSTRAINT `fk_member_interest_members_interest_id` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`),
  ADD CONSTRAINT `fk_member_interest_members_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `fk_members_constituencies_constituency_id` FOREIGN KEY (`constituency_id`) REFERENCES `constituencies` (`id`),
  ADD CONSTRAINT `fk_members_parties_party_id` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
