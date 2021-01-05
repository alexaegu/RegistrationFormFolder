-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 06, 2020 at 01:03 
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_access`
--

-- --------------------------------------------------------

--
-- Table structure for table `Enters`
--

CREATE TABLE `Enters` (
  `Login1` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `Date2` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `Rate2` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Enters`
--

INSERT INTO `Enters` (`Login1`, `Date2`, `Rate2`) VALUES
('abraka23', '06-01-2020 12:46:28', 0),
('platochek', '06-01-2020 12:45:40', 0),
('platochek', '06-01-2020 12:47:15', 0),
('platochek', '06-01-2020 12:47:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Registrations`
--

CREATE TABLE `Registrations` (
  `Login1` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `Password1` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `Email1` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `Date1` char(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Registrations`
--

INSERT INTO `Registrations` (`Login1`, `Password1`, `Email1`, `Date1`) VALUES
('abraka23', '$2y$10$4YkO/iPAhQlVJvmP6JJxZeUR2aQSSLspZ/5ygfGfkXNq3EuuXwPau', 'moy@site.2.com.ru', '06-01-2020 12:41:15'),
('natasha', '$2y$10$2SFmU0.T.eT8GuBWpV/1wuJfLw4NXG/WQkIsQrJ3YGJ1LWDU5N3V6', 'kate@yandex.ru', '06-01-2020 12:43:56'),
('nekto16h', '$2y$10$wB8xwExqCcm6GKFtRbBtTOtMze/v0nLG25IMXT0VH/FAsA/n5rFKu', 'nekot.oryj@adress.net', '06-01-2020 12:42:30'),
('platochek', '$2y$10$wJtRd3EWk//wDqW1T709w.BNqyk/j2KgpI8jL9daR78xbbfsHGZoS', 'myshka.koshka@zveri.net', '06-01-2020 12:44:41'),
('sasha87', '$2y$10$tOCq5S9Pjq7cZYO/xiBvRuJq0KBhRqzUoYI40WHd60jaZEw85ZemO', 'sasha87@gmail.com', '06-01-2020 12:43:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Enters`
--
ALTER TABLE `Enters`
  ADD PRIMARY KEY (`Login1`,`Date2`);

--
-- Indexes for table `Registrations`
--
ALTER TABLE `Registrations`
  ADD PRIMARY KEY (`Login1`),
  ADD UNIQUE KEY `Email1` (`Email1`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Enters`
--
ALTER TABLE `Enters`
  ADD CONSTRAINT `Enters_ibfk_1` FOREIGN KEY (`Login1`) REFERENCES `Registrations` (`Login1`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
