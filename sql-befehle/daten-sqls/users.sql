
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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

