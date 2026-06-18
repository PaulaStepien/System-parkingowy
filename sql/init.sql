-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2026 at 06:41 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `system_parkingowy`
--

-- --------------------------------------------------------

--
-- Table structure for table `firmy`
--

CREATE TABLE `firmy` (
  `id` int NOT NULL,
  `nazwa` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utworzono` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `firmy`
--

INSERT INTO `firmy` (`id`, `nazwa`, `utworzono`) VALUES
(1, 'Moja firma', '2026-06-18 18:06:45'),
(2, 'Testowa Sp. z o.o.', '2026-06-18 18:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `konfiguracja`
--

CREATE TABLE `konfiguracja` (
  `id` int NOT NULL,
  `firma_id` int DEFAULT NULL,
  `liczba_miejsc` int NOT NULL DEFAULT '10',
  `liczba_rezerwowych` int NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konfiguracja`
--

INSERT INTO `konfiguracja` (`id`, `firma_id`, `liczba_miejsc`, `liczba_rezerwowych`) VALUES
(1, 1, 10, 3),
(2, 2, 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `losowania`
--

CREATE TABLE `losowania` (
  `id` int NOT NULL,
  `firma_id` int DEFAULT NULL,
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_losowania` datetime NOT NULL,
  `typ` enum('jednorazowe','dzienne','tygodniowe','miesieczne') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'jednorazowe',
  `liczba_miejsc` int NOT NULL,
  `liczba_rezerwowych` int NOT NULL DEFAULT '5',
  `status` enum('zaplanowane','zakonczone') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zaplanowane',
  `utworzono` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `losowania`
--

INSERT INTO `losowania` (`id`, `firma_id`, `opis`, `data_losowania`, `typ`, `liczba_miejsc`, `liczba_rezerwowych`, `status`, `utworzono`) VALUES
(2, 1, NULL, '2026-06-18 16:32:00', 'jednorazowe', 15, 3, 'zakonczone', '2026-06-18 14:31:06'),
(3, 1, NULL, '2026-06-18 16:33:00', 'jednorazowe', 10, 3, 'zakonczone', '2026-06-18 14:32:55'),
(4, 1, NULL, '2026-06-18 20:18:00', 'jednorazowe', 10, 3, 'zaplanowane', '2026-06-18 18:18:52'),
(6, 2, NULL, '2026-06-18 20:29:00', 'jednorazowe', 7, 3, 'zakonczone', '2026-06-18 18:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int NOT NULL,
  `firma_id` int DEFAULT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `haslo_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rola` enum('superadmin','admin','pracownik') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pracownik',
  `imie` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nazwisko` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dzial` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nr_rejestracyjny` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bierze_udzial` tinyint(1) NOT NULL DEFAULT '0',
  `utworzono` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `firma_id`, `login`, `haslo_hash`, `rola`, `imie`, `nazwisko`, `dzial`, `nr_rejestracyjny`, `bierze_udzial`, `utworzono`) VALUES
(1, 1, 'admin', '$2y$10$EDGwOoMo/CkEMhlj9CMIneEywaR7W1IQRdr0AVny/.yoS8KtdcUEC', 'admin', 'Administrator', 'Systemu', NULL, NULL, 0, '2026-06-18 10:59:46'),
(2, 1, 'prac8064', '$2y$10$p9uaq.1OWQeWO7boKKtwuuwIwJ4qDtSKaAGzT0Tvm2KllWMtd6bjO', 'pracownik', 'Paulina', 'Stępień', 'IiE', 'TKI0785U', 0, '2026-06-18 13:21:23'),
(3, 1, 'prac8813', '$2y$10$KpeGBNP6orR7nTFNbDqq3Ocf2pqnaTuhgYRGRcEmXice7Ucfsvi8u', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:29:46'),
(4, 1, 'prac6730', '$2y$10$yselNMkfGgQuB/ro35bHXORMWZx1MXSpi3xLKHXZn8hq5OwKzwfvm', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:41'),
(5, 1, 'prac4985', '$2y$10$8r6mFtIpYSM6HxOmn8qsEOy9hqtkPM/TxHV8DRzrlxfsyYTgC1AN2', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:42'),
(6, 1, 'prac2767', '$2y$10$DIC7KevxiM3cIuI/Bbh8lOPTF5grOn.fVzoaOLtKRTd4PJa2Uj9Qu', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:43'),
(7, 1, 'prac5998', '$2y$10$359/sfEWV/YPLjKzY5RY3OR8tWs7D0j0gjuWtm6oimbHTiHCYDaGG', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:43'),
(8, 1, 'prac4418', '$2y$10$vkSBnwSxxG96EMATgdgqPuAOmGx3e4W6s.XwYbcUX19MN0KowoWNa', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:44'),
(9, 1, 'prac7165', '$2y$10$u2/U202YP/F7mUPvOlxPEeztZ6bLjk4h5dG5Jeqrh4wDEs7ygQz2.', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:44'),
(10, 1, 'prac3265', '$2y$10$wuEpUlwPhm/bgaeBaGvoH.ogA1XKTTHsVmq/BcT6bCYb/ZdZUVb/C', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:44'),
(11, 1, 'prac5221', '$2y$10$jQ9bxG.Nf.3mcyqDnqeeD.tMj0f4ncRIfZr4k6m/.wRUO1YHhBU2S', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:45'),
(12, 1, 'prac5379', '$2y$10$aSlJNdqC92gDnsMaQIy1gO3zP8VoF0/OoXXn.c0G.0DDbvkzqyffe', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:45'),
(13, 1, 'prac4349', '$2y$10$MUphclnRcdaZwaZHvCe17ORZdE1PJUreS8.gE6MBPc50N5CFqmVJy', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:45'),
(14, 1, 'prac9329', '$2y$10$ZxXMjLNpTOso.fVxSwfA2OSd5RapGM.0VvmuhO7.XNyWAeb2AlcO2', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:45'),
(15, 1, 'prac8730', '$2y$10$ixAMtpqF696VfMkiFOP5fe4L85WeUBNGXI1S9FYUZ5nZ/evIsn.6.', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:46'),
(16, 1, 'prac7122', '$2y$10$ifQjbN3MwXaSgOT04odCEeXBnV7H3aScVITIUEdCuuIMiZs628f3W', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:46'),
(17, 1, 'prac4725', '$2y$10$ksctdrGYS3pMyLTs7uz/eOvxqXgJmrDriZLIO4z1MAhWT1/Yyz9lS', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:46'),
(18, 1, 'prac9672', '$2y$10$MyibZNIMBMNc6r.3JOeMg.IRKEeLc2HEgf8gdtf3S/6ISk6YhPjOC', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:47'),
(19, 1, 'prac1187', '$2y$10$70sjvacVAoTSrRTRsgrD0OiiL.yXfXPPH3AZFPrA11lTGGhG3zzji', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:47'),
(20, 1, 'prac6975', '$2y$10$BLHhPdl7vLCInK4Zh.2s3eRtR3vGShnpcak/ZsuUzVhldtmolQlV.', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:47'),
(21, 1, 'prac5367', '$2y$10$/f33/UC/9g4c3UpOoDpkaeF34dGWpD.Av5JlTpqkUoLr4ytrzJIzW', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:48'),
(22, 1, 'prac3334', '$2y$10$JVWNBNbSR2MKwJNNrEWhi.Nqk2BMm4LRoAs5efiUeOJL37ka3FPw2', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:52:48'),
(23, NULL, 'superadmin', '$2y$10$j/tZtff.yzKO291j6ibwsOO3oBcSmGLWWUA9c29GkE0ncqLMJQgZy', 'superadmin', 'Super', 'Admin', NULL, NULL, 0, '2026-06-18 18:06:46'),
(24, 1, 'prac7635', '$2y$10$IdG6zSrN0BvYRv5kG9DuD.798KMtPpK0rTNAM1MWXsa7iqbG1m24m', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:18:32'),
(25, 2, 'admin1586', '$2y$10$HV.1jXQxp10wDBNNnN7ITueMMXNuAuSi6lfebJsOnQvYXLuBuzsGm', 'admin', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:24:56'),
(26, 2, 'prac6877', '$2y$10$A0BcN/8XLCdxjWCJc6jafu.4gAhVdfcxVW7rqbQGgkFUcCXKxaYIu', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:25'),
(27, 2, 'prac2917', '$2y$10$6HfAUNAd8dCaMq2nCiVB4u0mcyVn7A.ViyQi8sa4zn1vec4DjZ0Tq', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:47'),
(28, 2, 'prac3201', '$2y$10$DteiVS0KA8hQkT9OK4Y4.upzwB9epLJHb2rPy1yLiU/20/gr0U3YK', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:48'),
(29, 2, 'prac3182', '$2y$10$vSDVRjQVSHSoJXKrK6x43.oTmdq92zidOYSCUUshRSzot7IIUNbmK', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:48'),
(30, 2, 'prac1208', '$2y$10$QDeIK/Qj4MlQA1jwhbFCNOvUUOv6ud/3iw6WpLY8zIfw87g/dl.1O', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:49'),
(31, 2, 'prac5806', '$2y$10$4ZkaXj8YYUCsinORJGgtDe.lhfOdAM54AoeIMHvy.saM66jABGc1W', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:52'),
(32, 2, 'prac5491', '$2y$10$c6mISn5Yw/r1NgtRE7aeW.bjGTzg8J3yWg1rr0ZpveuYzbefxaa4y', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:53'),
(33, 2, 'prac1745', '$2y$10$tOAgEdGOzcFMZrHuTE44pOY41j4XbMxWl9R1Xrfd8mv08fL7XM5/S', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:53'),
(34, 2, 'prac2853', '$2y$10$GsQeSe3O5J3qt3qYtnq6B.iIn3ji/3PM4O5dX6c5FaY4SmZnxQwT.', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:54'),
(35, 2, 'prac2314', '$2y$10$UFMa3vVpuULmtAmiYEKnJOqHCYxgoKUuUcblApKwWpS9v2p3Qiwyu', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:56'),
(36, 2, 'prac1005', '$2y$10$cYsXVOQHS1ExpHGwnW8Qnedqq8w0L9XJIOkhjH5k5F2uLoPyKnxD6', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:57'),
(37, 2, 'prac2153', '$2y$10$yQyMdI18iIofgeEEkgXPm.yoOj5XHlwTejHeaCWihO8IUyOoJRMvi', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:57'),
(38, 2, 'prac3784', '$2y$10$1.I3mr7NgvJzZze.XEHVG.SkVANkVw/GIKy4MCZzWX0Njk4NnO3La', 'pracownik', NULL, NULL, NULL, NULL, 0, '2026-06-18 18:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `wyniki`
--

CREATE TABLE `wyniki` (
  `id` int NOT NULL,
  `losowanie_id` int NOT NULL,
  `uzytkownik_id` int NOT NULL,
  `status` enum('wylosowany','rezerwowy') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pozycja` int DEFAULT NULL,
  `utworzono` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wyniki`
--

INSERT INTO `wyniki` (`id`, `losowanie_id`, `uzytkownik_id`, `status`, `pozycja`, `utworzono`) VALUES
(1, 2, 3, 'wylosowany', 1, '2026-06-18 14:31:11'),
(2, 2, 16, 'wylosowany', 2, '2026-06-18 14:31:11'),
(3, 2, 9, 'wylosowany', 3, '2026-06-18 14:31:11'),
(4, 2, 14, 'wylosowany', 4, '2026-06-18 14:31:11'),
(5, 2, 2, 'wylosowany', 5, '2026-06-18 14:31:11'),
(6, 2, 7, 'wylosowany', 6, '2026-06-18 14:31:11'),
(7, 2, 10, 'wylosowany', 7, '2026-06-18 14:31:11'),
(8, 2, 15, 'wylosowany', 8, '2026-06-18 14:31:11'),
(9, 2, 6, 'wylosowany', 9, '2026-06-18 14:31:11'),
(10, 2, 12, 'wylosowany', 10, '2026-06-18 14:31:11'),
(11, 2, 4, 'wylosowany', 11, '2026-06-18 14:31:11'),
(12, 2, 8, 'wylosowany', 12, '2026-06-18 14:31:11'),
(13, 2, 11, 'wylosowany', 13, '2026-06-18 14:31:11'),
(14, 2, 19, 'wylosowany', 14, '2026-06-18 14:31:11'),
(15, 2, 18, 'wylosowany', 15, '2026-06-18 14:31:11'),
(16, 2, 5, 'rezerwowy', 16, '2026-06-18 14:31:11'),
(17, 2, 13, 'rezerwowy', 17, '2026-06-18 14:31:11'),
(18, 2, 17, 'rezerwowy', 18, '2026-06-18 14:31:11'),
(19, 3, 11, 'wylosowany', 1, '2026-06-18 14:35:01'),
(20, 3, 3, 'wylosowany', 2, '2026-06-18 14:35:01'),
(21, 3, 6, 'wylosowany', 3, '2026-06-18 14:35:01'),
(22, 3, 2, 'wylosowany', 4, '2026-06-18 14:35:01'),
(23, 3, 7, 'wylosowany', 5, '2026-06-18 14:35:01'),
(24, 3, 13, 'wylosowany', 6, '2026-06-18 14:35:01'),
(25, 3, 16, 'wylosowany', 7, '2026-06-18 14:35:01'),
(26, 3, 5, 'wylosowany', 8, '2026-06-18 14:35:01'),
(27, 3, 10, 'wylosowany', 9, '2026-06-18 14:35:01'),
(28, 3, 12, 'wylosowany', 10, '2026-06-18 14:35:01'),
(29, 3, 9, 'rezerwowy', 11, '2026-06-18 14:35:01'),
(30, 3, 8, 'rezerwowy', 12, '2026-06-18 14:35:01'),
(31, 3, 15, 'rezerwowy', 13, '2026-06-18 14:35:01'),
(32, 6, 35, 'wylosowany', 1, '2026-06-18 18:30:14'),
(33, 6, 34, 'wylosowany', 2, '2026-06-18 18:30:14'),
(34, 6, 33, 'wylosowany', 3, '2026-06-18 18:30:14'),
(35, 6, 30, 'wylosowany', 4, '2026-06-18 18:30:14'),
(36, 6, 26, 'wylosowany', 5, '2026-06-18 18:30:14'),
(37, 6, 29, 'wylosowany', 6, '2026-06-18 18:30:14'),
(38, 6, 28, 'wylosowany', 7, '2026-06-18 18:30:14'),
(39, 6, 31, 'rezerwowy', 8, '2026-06-18 18:30:14'),
(40, 6, 32, 'rezerwowy', 9, '2026-06-18 18:30:14'),
(41, 6, 27, 'rezerwowy', 10, '2026-06-18 18:30:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `firmy`
--
ALTER TABLE `firmy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konfiguracja`
--
ALTER TABLE `konfiguracja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firma_id` (`firma_id`);

--
-- Indexes for table `losowania`
--
ALTER TABLE `losowania`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firma_id` (`firma_id`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `firma_id` (`firma_id`);

--
-- Indexes for table `wyniki`
--
ALTER TABLE `wyniki`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_losowanie_uzytkownik` (`losowanie_id`,`uzytkownik_id`),
  ADD KEY `uzytkownik_id` (`uzytkownik_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `firmy`
--
ALTER TABLE `firmy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `konfiguracja`
--
ALTER TABLE `konfiguracja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `losowania`
--
ALTER TABLE `losowania`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `wyniki`
--
ALTER TABLE `wyniki`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `konfiguracja`
--
ALTER TABLE `konfiguracja`
  ADD CONSTRAINT `konfiguracja_ibfk_1` FOREIGN KEY (`firma_id`) REFERENCES `firmy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `losowania`
--
ALTER TABLE `losowania`
  ADD CONSTRAINT `losowania_ibfk_1` FOREIGN KEY (`firma_id`) REFERENCES `firmy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD CONSTRAINT `uzytkownicy_ibfk_1` FOREIGN KEY (`firma_id`) REFERENCES `firmy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wyniki`
--
ALTER TABLE `wyniki`
  ADD CONSTRAINT `wyniki_ibfk_1` FOREIGN KEY (`losowanie_id`) REFERENCES `losowania` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wyniki_ibfk_2` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
