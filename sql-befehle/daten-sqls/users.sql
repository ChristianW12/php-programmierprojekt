-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Erstellungszeit: 24. Okt 2025 um 13:01
-- Server-Version: 11.5.2-MariaDB-ubu2404
-- PHP-Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `auktion`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `plz` varchar(5) DEFAULT NULL,
  `str` varchar(50) DEFAULT NULL,
  `ort` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`user_id`, `name`, `mail`, `password`, `is_admin`, `plz`, `str`, `ort`) VALUES
(1, 'max', 'max.mustermann@mail.de', 'q4Yp9X', 0, '70173', 'Königstr. 10', 'Stuttgart'),
(2, 'anna', 'anna.klein@mail.de', 'H7Gz3wK', 0, '20095', 'Mönckebergstr. 2', 'Hamburg'),
(3, 'lukas', 'lukas.schmidt@mail.de', 'Lk9dP3aB', 0, '50667', 'Domplatz 1', 'Köln'),
(4, 'sarah', 'sarah.meier@mail.de', 'pR8mZ2', 0, '80331', 'Marienplatz 8', 'München'),
(5, 'tobias', 'tobias.bauer@mail.de', 'X2tQ9fLk', 0, '04109', 'Augustusplatz 5', 'Leipzig'),
(6, 'julia', 'julia.hoff@mail.de', 'n7Wk4Vb2', 0, '01067', 'Altmarkt 3', 'Dresden'),
(7, 'daniel', 'daniel.weber@mail.de', 'Jm3dT8pQ', 0, '80336', 'Theresienstr. 4', 'München'),
(8, 'laura', 'laura.berger@mail.de', 'Zp9rLm4', 0, '60311', 'Römerberg 9', 'Frankfurt'),
(9, 'felix', 'felix.hart@mail.de', 'bT6qP2xM', 0, '28195', 'Marktplatz 7', 'Bremen');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
