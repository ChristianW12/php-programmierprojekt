-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Erstellungszeit: 01. Nov 2025 um 13:13
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
-- Tabellenstruktur für Tabelle `bids`
--

CREATE TABLE `bids` (
  `bid_id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `mail` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `highest_price` tinyint(1) NOT NULL DEFAULT 0,
  `bid_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offers`
--

CREATE TABLE `offers` (
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `beschreibung` text DEFAULT NULL,
  `startpreis` decimal(10,2) NOT NULL,
  `aktueller_preis` decimal(10,2) DEFAULT NULL,
  `hoechstpreis` decimal(10,2) DEFAULT NULL,
  `start` datetime NOT NULL DEFAULT current_timestamp(),
  `ende` datetime NOT NULL,
  `kategorie` varchar(50)
     CHECK (kategorie IS NULL OR kategorie IN (
                    'Elektronik',
                    'Computer & Zubehör',
                    'Haushalt & Küche',
                    'Möbel & Wohnen',
                    'Kleidung & Accessoires',
                    'Filme & Musik',
                    'Bücher & Comics',
                    'Sport & Freizeit',
                    'Spielzeug & Modelle',
                    'Sammeln & Antiquitäten',
                    'Fahrzeuge & Zubehör',
                    'Musik & Instrumente',
                    'Tierbedarf',
                    'Reisen & Gepäck'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `offers`
--

INSERT INTO `offers` (`offer_id`, `user_id`, `title`, `beschreibung`, `startpreis`, `aktueller_preis`, `hoechstpreis`, `start`, `ende`, `kategorie`) VALUES
(1, 1, 'Vintage Kamera', 'Analoge Kamera in gutem Zustand, inkl. Tasche.', 25.00, NULL, NULL, '2025-10-15 09:00:00', '2025-11-01 12:00:00', 'Elektronik'),
(2, 2, 'Gaming Headset', 'Over-Ear, Noise-Cancelling, kaum genutzt.', 30.00, NULL, NULL, '2025-10-15 11:15:00', '2025-10-30 18:00:00', 'Elektronik'),
(3, 3, 'Sammlerfigur', 'Limitierte Edition, OVP.', 50.00, NULL, NULL, '2025-10-16 10:20:00', '2025-11-05 20:00:00', 'Sammeln & Antiquitäten'),
(4, 4, 'Mountainbike 26\"', 'Guter Zustand, Alurahmen.', 120.00, NULL, NULL, '2025-10-16 14:40:00', '2025-11-20 15:00:00', 'Sport & Freizeit'),
(5, 5, 'Bücherpaket', '10 Romane gemischter Genres.', 15.00, NULL, NULL, '2025-10-17 09:10:00', '2025-10-31 21:00:00', 'Bücher & Comics'),
(6, 6, 'Kaffeemaschine', 'Vollautomat mit Entkalker.', 80.00, NULL, NULL, '2025-10-17 15:55:00', '2025-11-10 11:00:00', 'Haushalt & Küche'),
(7, 7, 'Smartwatch Series X', 'Mit Ladegerät und Armband.', 45.00, NULL, NULL, '2025-10-18 08:45:00', '2025-10-29 20:00:00', 'Elektronik'),
(8, 8, 'LEGO Technik Set', 'Vollständig, inkl. Anleitung.', 70.00, NULL, NULL, '2025-10-18 13:30:00', '2025-11-02 13:00:00', 'Spielzeug & Modelle'),
(9, 9, 'Schallplatten Mix', '10 Vinyls, Rock & Jazz.', 40.00, NULL, NULL, '2025-10-19 09:20:00', '2025-11-15 19:00:00', 'Filme & Musik'),
(10, 1, 'Bürostuhl ergonomisch', 'Rückenfreundlich, höhenverstellbar.', 35.00, NULL, NULL, '2025-10-19 16:00:00', '2025-10-28 17:00:00', 'Möbel & Wohnen'),
(11, 2, 'Grafikkarte Mittelklasse', 'Läuft einwandfrei, mit OVP.', 95.00, NULL, NULL, '2025-10-20 10:05:00', '2025-11-25 18:00:00', 'Computer & Zubehör'),
(12, 3, 'Küchenmaschine', 'Viel Zubehör, sauber.', 60.00, NULL, NULL, '2025-10-20 14:50:00', '2025-11-12 12:00:00', 'Haushalt & Küche'),
(13, 4, 'Sneaker Größe 42', 'Kaum getragen, sauber.', 55.00, NULL, NULL, '2025-10-21 09:00:00', '2025-10-30 14:00:00', 'Kleidung & Accessoires'),
(14, 5, 'Monitor 24 Zoll', 'Full HD, IPS-Panel.', 75.00, NULL, NULL, '2025-10-21 18:30:00', '2025-11-05 11:45:00', 'Computer & Zubehör'),
(15, 6, 'Telefonanlage', 'Basis + 4 Mobilteile.', 25.00, NULL, NULL, '2025-10-22 08:40:00', '2025-11-10 12:00:00', 'Elektronik'),
(16, 7, 'E-Gitarre', 'Einsteiger-Modell, inkl. Tasche.', 85.00, NULL, NULL, '2025-10-22 13:10:00', '2025-11-16 15:00:00', 'Musik & Instrumente'),
(17, 8, 'Staubsauger Roboter', 'Mit Ladestation, funktionsfähig.', 120.00, NULL, NULL, '2025-10-23 09:15:00', '2025-11-17 10:00:00', 'Haushalt & Küche'),
(18, 9, 'Action-Cam 4K', 'Wasserdichtes Gehäuse inkl.', 65.00, NULL, NULL, '2025-10-23 15:45:00', '2025-11-18 12:30:00', 'Elektronik'),
(19, 1, 'Kindersitz Auto', 'ECE geprüft, sauber.', 30.00, NULL, NULL, '2025-10-24 11:00:00', '2025-11-08 09:00:00', 'Fahrzeuge & Zubehör'),
(20, 2, 'Campingkocher', 'Gaskocher, leicht, robust.', 20.00, NULL, NULL, '2025-10-24 17:20:00', '2025-11-05 08:00:00', 'Sport & Freizeit'),
(21, 3, 'Blu-ray Sammlung', '20 Filme, verschiedene Genres.', 40.00, NULL, NULL, '2025-10-25 08:55:00', '2025-11-21 18:00:00', 'Filme & Musik'),
(22, 4, 'Mikrofon USB', 'Podcast/Streaming geeignet.', 35.00, NULL, NULL, '2025-10-25 14:05:00', '2025-11-22 19:00:00', 'Elektronik'),
(23, 5, 'Schreibtischlampe LED', 'Dimmbar, warm/kalt.', 18.00, NULL, NULL, '2025-10-26 10:30:00', '2025-11-10 20:00:00', 'Haushalt & Küche'),
(24, 6, 'Fahrradhelm L', 'Unfallfrei, verstellbar.', 22.00, NULL, NULL, '2025-10-26 16:10:00', '2025-11-12 10:00:00', 'Sport & Freizeit'),
(25, 7, 'Externe SSD 1TB', 'NVMe im Gehäuse, schnell.', 75.00, NULL, NULL, '2025-10-27 09:00:00', '2025-11-25 11:00:00', 'Computer & Zubehör'),
(26, 8, 'Nintendo Switch Lite', 'Mit Tasche, guter Zustand.', 120.00, NULL, NULL, '2025-10-27 15:00:00', '2025-11-26 12:00:00', 'Elektronik'),
(27, 9, 'Drohne Einsteiger', 'Mit Kamera, Ersatzpropeller.', 90.00, NULL, NULL, '2025-10-28 11:15:00', '2025-11-27 13:00:00', 'Elektronik'),
(28, 1, 'Winterjacke M', 'Warm, wasserabweisend.', 28.00, NULL, NULL, '2025-10-28 17:45:00', '2025-11-18 14:00:00', 'Kleidung & Accessoires'),
(29, 2, 'Rucksack 30L', 'Laptopfach, regenfest.', 22.00, NULL, NULL, '2025-10-29 08:50:00', '2025-11-15 15:00:00', 'Sport & Freizeit'),
(30, 3, 'PC-Gehäuse ATX', 'Mit 3 Lüftern, schwarz.', 45.00, NULL, NULL, '2025-10-29 12:20:00', '2025-11-20 16:00:00', 'Computer & Zubehör'),
(31, 4, 'Keramikmesser Set', '3-teilig, sehr scharf.', 20.00, NULL, NULL, '2025-10-15 10:00:00', '2025-11-21 09:00:00', 'Haushalt & Küche'),
(32, 5, 'Gaming Maus', 'RGB, viele Tasten.', 25.00, NULL, NULL, '2025-10-16 12:30:00', '2025-11-22 10:00:00', 'Computer & Zubehör'),
(33, 6, 'Bluetooth Lautsprecher', 'Wasserfest, kräftiger Sound.', 30.00, NULL, NULL, '2025-10-17 11:40:00', '2025-11-23 11:00:00', 'Elektronik'),
(34, 7, 'Skihelm M', 'Mit Visier, kaum genutzt.', 40.00, NULL, NULL, '2025-10-18 09:50:00', '2025-11-24 12:00:00', 'Sport & Freizeit'),
(35, 8, 'LED-Beamer', 'Wohnzimmer-tauglich, HDMI.', 85.00, NULL, NULL, '2025-10-19 14:15:00', '2025-11-25 13:00:00', 'Elektronik'),
(36, 9, 'Fitness Tracker', 'Herzfrequenz, GPS.', 28.00, NULL, NULL, '2025-10-20 13:00:00', '2025-11-26 14:00:00', 'Elektronik'),
(37, 1, 'Küchenwaage', 'Digital, präzise.', 12.00, NULL, NULL, '2025-10-21 08:30:00', '2025-11-27 15:00:00', 'Haushalt & Küche'),
(38, 2, 'Esstisch 160cm', 'Eiche Dekor, mit Gebrauchsspuren.', 60.00, NULL, NULL, '2025-10-22 10:50:00', '2025-12-01 16:00:00', 'Möbel & Wohnen'),
(39, 3, 'Kinderspiel Küche', 'Zubehör inkl.', 25.00, NULL, NULL, '2025-10-23 16:25:00', '2025-12-02 17:00:00', 'Spielzeug & Modelle'),
(40, 4, 'Drehstuhl', 'Mesh-Rückenlehne.', 35.00, NULL, NULL, '2025-10-24 09:45:00', '2025-12-03 18:00:00', 'Möbel & Wohnen'),
(41, 5, 'Schreibtisch 120cm', 'Weiß, höhenverstellbar (manuell).', 55.00, NULL, NULL, '2025-10-25 11:10:00', '2025-12-04 19:00:00', 'Möbel & Wohnen'),
(42, 6, 'Fotodrucker', 'A6, inkl. 20 Blatt.', 45.00, NULL, NULL, '2025-10-26 15:35:00', '2025-12-05 20:00:00', 'Elektronik'),
(43, 7, 'Tastatur mechanisch', 'Klicky Switches, DE-Layout.', 35.00, NULL, NULL, '2025-10-27 10:00:00', '2025-12-06 09:30:00', 'Computer & Zubehör'),
(44, 8, 'Katzenkratzbaum', '170cm, stabil.', 40.00, NULL, NULL, '2025-10-28 13:50:00', '2025-12-07 10:30:00', 'Tierbedarf'),
(45, 9, 'Luftreiniger', 'HEPA-Filter, leise.', 50.00, NULL, NULL, '2025-10-29 16:00:00', '2025-12-08 11:30:00', 'Haushalt & Küche'),
(46, 1, 'Winterreifen Satz 16\"', 'Mit Felgen, Profil gut.', 120.00, NULL, NULL, '2025-10-15 17:00:00', '2025-12-09 12:30:00', 'Fahrzeuge & Zubehör'),
(47, 2, 'Raclette Grill', 'Für 8 Personen.', 25.00, NULL, NULL, '2025-10-16 18:00:00', '2025-12-10 13:30:00', 'Haushalt & Küche'),
(48, 3, 'Holzregal', '3 Ebenen, stabil.', 20.00, NULL, NULL, '2025-10-17 19:00:00', '2025-12-11 14:30:00', 'Möbel & Wohnen'),
(49, 4, 'Smartphone Zubehör Set', 'Hülle, Panzerglas, Ladegerät.', 15.00, NULL, NULL, '2025-10-18 20:00:00', '2025-12-12 15:30:00', 'Elektronik'),
(50, 5, 'Koffer 65L', 'Hartschale, leicht.', 35.00, NULL, NULL, '2025-10-19 21:00:00', '2025-12-13 16:30:00', 'Reisen & Gepäck');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offer_pic`
--

CREATE TABLE `offer_pic` (
  `pic_id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `is_cover` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(9, 'felix', 'felix.hart@mail.de', 'bT6qP2xM', 0, '28195', 'Marktplatz 7', 'Bremen'),
(10, 'Christian', 'christian@mail.com', '$2y$12$mCLLlH8F9dYF7R2KIzJ0ou2DhReT.0CAuKwK5yL/hFnHfUgWfZp16', 1, '12345', 'Hauptstr. 8', 'Stuttgart'),
(11, 'Alex', 'alexhesse@mail.de', 'admin', 1, '75365', 'Burgstr. 56', 'Calw'),
(12, 'Testuser', 'testuser@mail.de', 'test', 0, '00000', 'Teststr. 1', 'Teststadt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_comment`
--

CREATE TABLE `user_comment` (
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Indizes für die Tabelle `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `offer_pic`
--
ALTER TABLE `offer_pic`
  ADD PRIMARY KEY (`pic_id`),
  ADD KEY `offer_pic_ibfk_1` (`offer_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `user_comment`
--
ALTER TABLE `user_comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `target_id` (`target_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT für Tabelle `offer_pic`
--
ALTER TABLE `offer_pic`
  MODIFY `pic_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `user_comment`
--
ALTER TABLE `user_comment`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `offer_pic`
--
ALTER TABLE `offer_pic`
  ADD CONSTRAINT `offer_pic_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `user_comment`
--
ALTER TABLE `user_comment`
  ADD CONSTRAINT `user_comment_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_comment_ibfk_2` FOREIGN KEY (`target_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
