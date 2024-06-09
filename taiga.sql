-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Трв 30 2024 р., 01:28
-- Версія сервера: 8.0.34
-- Версія PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `taiga`
--

-- --------------------------------------------------------

--
-- Структура таблиці `accounts`
--

CREATE TABLE `accounts` (
  `ID` int NOT NULL,
  `UserName` varchar(32) DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Flags` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `accounts`
--

INSERT INTO `accounts` (`ID`, `UserName`, `Password`, `Flags`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'ab'),
(2, 'moderator', '0408f3c997f309c03b08bf3a4bc7b730', 'b');

-- --------------------------------------------------------

--
-- Структура таблиці `images`
--

CREATE TABLE `images` (
  `ID` int NOT NULL,
  `RoomID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `images`
--

INSERT INTO `images` (`ID`, `RoomID`) VALUES
(158, 31),
(159, 31),
(160, 31),
(161, 31),
(162, 31),
(163, 31),
(164, 32),
(165, 32),
(166, 32),
(167, 32),
(168, 32),
(169, 32),
(170, 33),
(171, 33),
(172, 33),
(173, 33),
(174, 33),
(175, 33),
(176, 34),
(177, 34),
(178, 34),
(179, 34),
(180, 34),
(181, 34),
(182, 35),
(183, 35),
(184, 35),
(185, 35),
(186, 35),
(187, 35),
(188, 36),
(189, 36),
(190, 36),
(191, 36),
(192, 36),
(193, 36),
(194, 37),
(195, 37),
(196, 37),
(197, 37),
(198, 37),
(199, 37),
(200, 38),
(201, 38),
(202, 38),
(203, 38),
(204, 38),
(205, 38),
(206, 39),
(207, 39),
(208, 39),
(209, 39),
(210, 39),
(211, 39),
(212, 39),
(213, 39),
(214, 39),
(215, 40),
(216, 40),
(217, 40),
(218, 40),
(219, 40),
(220, 40),
(221, 40),
(222, 40),
(223, 40),
(224, 41),
(225, 41),
(226, 41),
(227, 41),
(228, 41),
(229, 41),
(230, 41),
(231, 41),
(232, 41),
(233, 42),
(234, 42),
(235, 42),
(236, 42),
(237, 42),
(238, 42),
(239, 42),
(240, 42),
(241, 42),
(242, 43),
(243, 43),
(244, 43),
(245, 43),
(246, 43),
(247, 43),
(248, 43),
(249, 43),
(250, 43),
(251, 43),
(252, 44),
(253, 44),
(254, 44),
(255, 44),
(256, 44),
(257, 44),
(258, 44),
(259, 44),
(260, 44),
(261, 44),
(262, 45),
(263, 45),
(264, 45),
(265, 45),
(266, 45),
(267, 45),
(268, 45),
(269, 45),
(270, 45),
(271, 45),
(272, 46),
(273, 46),
(274, 46),
(275, 46),
(276, 46),
(277, 46),
(278, 46),
(279, 46),
(280, 46),
(281, 46);

-- --------------------------------------------------------

--
-- Структура таблиці `reservations`
--

CREATE TABLE `reservations` (
  `ID` int NOT NULL,
  `RoomID` int DEFAULT NULL,
  `From` date DEFAULT NULL,
  `To` date DEFAULT NULL,
  `Adults` int DEFAULT NULL,
  `Children` int DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Food` tinyint(1) DEFAULT NULL,
  `Price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `reservations`
--

INSERT INTO `reservations` (`ID`, `RoomID`, `From`, `To`, `Adults`, `Children`, `Email`, `Surname`, `Name`, `Phone`, `Food`, `Price`) VALUES
(18, 31, '2024-06-11', '2024-06-15', 1, 0, 'Borimir_Priimak@i.ua', 'Приймак', 'Боримир', '+3800135924079', 1, 10400),
(19, 31, '2024-06-17', '2024-06-22', 1, 0, 'Ladomir.Koretsukkii43@i.ua', 'Корецький', 'Ладомир', '+3800188478511', 1, 13000),
(20, 42, '2024-06-07', '2024-06-19', 1, 3, 'Olena93@yandex.ua', 'Стигайло', 'Олена', '+3806752084781', 1, 97200),
(21, 46, '2024-06-04', '2024-06-11', 2, 0, 'Larisa65@meta.ua', 'Янюк', 'Лариса', '+3804916316141', 1, 8400),
(22, 36, '2024-06-05', '2024-06-14', 2, 1, 'Sonya.Vergun10@e-mail.ua', 'Вергун', 'Соня', '+3802995663793', 1, 46800),
(23, 40, '2024-06-01', '2024-06-05', 2, 0, 'Lada_Prigoda@gmail.com', 'Пригода', 'Лада', '+3808131179779', 1, 28800),
(24, 34, '2024-06-01', '2024-06-03', 1, 0, 'Vasiluk24@e-mail.ua', 'Сторчак', 'Василь', '+3809734664321', 1, 8200),
(25, 34, '2024-06-17', '2024-06-19', 2, 0, 'Borislava.Miklashevsukka@yandex.ua', 'Миклашевська', 'Борислава', '+3800298161537', 0, 8000),
(26, 42, '2024-06-25', '2024-06-30', 1, 0, 'Melanuuya.Goiko@ex.ua', 'Гойко', 'Меланія', '+3807838333782', 0, 40000),
(27, 45, '2024-06-18', '2024-06-21', 2, 2, 'Georguui.Vereschuk6@yandex.ua', 'Верещук', 'Георгій', '+3806809306219', 0, 28500),
(28, 38, '2024-06-20', '2024-06-22', 1, 0, 'Budimir.Stepanetsuk3@yandex.ua', 'Степанець', 'Будимир', '+3803574982258', 1, 12200),
(29, 43, '2024-05-30', '2024-06-01', 1, 0, 'testmail@gmail.com', 'Test', 'Name', '+38057869412', 0, 17000),
(30, 34, '2024-06-08', '2024-06-11', 1, 0, 'Bryachislav.Bandera25@i.ua', 'Бандера', 'Брячислав', '+3808917534023', 1, 12300),
(31, 40, '2024-06-08', '2024-06-11', 2, 0, 'Nazar47@i.ua', 'Роменець', 'Назар', '+3801744235641', 1, 21600),
(32, 39, '2024-06-08', '2024-06-11', 2, 0, 'stanislavtkach187@gmail.com', 'Ткач', 'Станіслав', '+380573812359', 0, 19500);

-- --------------------------------------------------------

--
-- Структура таблиці `rooms`
--

CREATE TABLE `rooms` (
  `ID` int NOT NULL,
  `RoomType` int DEFAULT NULL,
  `RoomName` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Price` int DEFAULT NULL,
  `RoomCount` int DEFAULT NULL,
  `DoubleBeds` int DEFAULT NULL,
  `SingleBeds` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `rooms`
--

INSERT INTO `rooms` (`ID`, `RoomType`, `RoomName`, `Price`, `RoomCount`, `DoubleBeds`, `SingleBeds`) VALUES
(31, 1, 'Затишний Закуток', 2500, 1, 1, 0),
(32, 1, 'Лісове Сяйво', 3000, 1, 1, 2),
(33, 1, 'Кедровий Сад\n', 3500, 1, 2, 2),
(34, 1, 'Сосновий Гай', 4000, 1, 1, 0),
(35, 2, 'Тихий Притулок', 4500, 2, 1, 2),
(36, 2, 'Ведмежий Кут', 5000, 2, 2, 2),
(37, 2, 'Орлине Гніздо', 5500, 2, 2, 0),
(38, 2, 'Хвойний Приют', 6000, 2, 1, 0),
(39, 3, 'Вовчий Ліг', 6500, 2, 2, 0),
(40, 3, 'Сибірська Романтика', 7000, 2, 2, 1),
(41, 3, 'Лосиний Ліс', 7500, 2, 2, 2),
(42, 3, 'Світанок у Тайзі', 8000, 2, 2, 3),
(43, 4, 'Ялинова Роща', 8500, 3, 2, 1),
(44, 4, 'Лісова Перлина', 9000, 3, 2, 2),
(45, 4, 'Тайга Ранкове Світло', 9500, 3, 2, 3),
(46, 4, 'Бруснична Галявина', 10000, 3, 3, 3);

-- --------------------------------------------------------

--
-- Структура таблиці `types`
--

CREATE TABLE `types` (
  `ID` int NOT NULL,
  `Type` varchar(32) DEFAULT NULL,
  `Benefits` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `types`
--

INSERT INTO `types` (`ID`, `Type`, `Benefits`) VALUES
(1, 'Економ', '[\"Wi-Fi\", \"Міні-холодильник\", \"Кондиціонер\", \"Ванна кімната\"]'),
(2, 'Стандарт', '[\"Wi-Fi\", \"TV\", \"Міні-холодильник\", \"Кондиціонер\", \"Фен\", \"Балкон\", \"Ванна кімната\"]'),
(3, 'Преміум', '[\"Wi-Fi\", \"TV\", \"Міні-холодильник\", \"Кондиціонер\", \"Туалетне приладдя\", \"Чайник\", \"Фен\", \"Балкон\", \"Ванна кімната\", \"Капці\"]'),
(4, 'Люкс', '[\"Wi-Fi\", \"TV\", \"Міні-холодильник\", \"Кондиціонер\", \"Туалетне приладдя\", \"Міні-бар\", \"Вітальня\", \"Чайник\", \"Фен\", \"Диван\", \"Балкон\", \"Ванна кімната\", \"Капці\"]');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`ID`);

--
-- Індекси таблиці `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RoomID` (`RoomID`);

--
-- Індекси таблиці `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RoomID` (`RoomID`);

--
-- Індекси таблиці `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RoomType` (`RoomType`);

--
-- Індекси таблиці `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `images`
--
ALTER TABLE `images`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT для таблиці `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблиці `rooms`
--
ALTER TABLE `rooms`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблиці `types`
--
ALTER TABLE `types`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`ID`);

--
-- Обмеження зовнішнього ключа таблиці `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`ID`);

--
-- Обмеження зовнішнього ключа таблиці `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`RoomType`) REFERENCES `types` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
