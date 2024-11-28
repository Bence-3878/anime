-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Nov 22. 19:49
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `hazi`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `anime`
--

CREATE TABLE `anime` (
  `id` int(11) NOT NULL,
  `elozmeny_id` int(11) DEFAULT NULL,
  `folytatas_id` int(11) DEFAULT NULL,
  `szezon_id` int(11) DEFAULT NULL,
  `romanji_cim` varchar(120) NOT NULL,
  `angol_cim` varchar(120) NOT NULL,
  `leiras` text NOT NULL,
  `hossza` time DEFAULT NULL,
  `epizod_szam` int(11) DEFAULT NULL,
  `kezdo_datum` date DEFAULT NULL,
  `vege_datum` date DEFAULT NULL,
  `statusz` enum('fut','befejezett','tervezet','szunet') NOT NULL DEFAULT 'tervezet',
  `ertekeles` float DEFAULT NULL,
  `poszter` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `anime`
--

INSERT INTO `anime` (`id`, `elozmeny_id`, `folytatas_id`, `szezon_id`, `romanji_cim`, `angol_cim`, `leiras`, `hossza`, `epizod_szam`, `kezdo_datum`, `vege_datum`, `statusz`, `ertekeles`, `poszter`) VALUES
(1, NULL, 2, 245, 'Mushoku Tensei: Isekai Ittara Honki Dasu', 'Mushoku Tensei: Jobless Reincarnation', 'Despite being bullied, scorned, and oppressed all of his life, a 34-year-old shut-in still found the resolve to attempt something heroic—only for it to end in a tragic accident. But in a twist of fate, he awakens in another world as Rudeus Greyrat, starting life again as a baby born to two loving parents.\r\n\r\nPreserving his memories and knowledge from his previous life, Rudeus quickly adapts to his new environment. With the mind of a grown adult, he starts to display magical talent that exceeds all expectations, honing his skill with the help of a mage named Roxy Migurdia. Rudeus learns swordplay from his father, Paul, and meets Sylphiette, a girl his age who quickly becomes his closest friend.\r\n\r\nAs Rudeus\' second chance at life begins, he tries to make the most of his new opportunity while conquering his traumatic past. And perhaps, one day, he may find the one thing he could not find in his old world—love.', '00:23:00', 11, NULL, NULL, 'befejezett', NULL, 'https://cdn.myanimelist.net/images/anime/1530/117776.jpg'),
(2, 1, NULL, 248, 'Mushoku Tensei: Isekai Ittara Honki Dasu Part 2', 'Mushoku Tensei: Jobless Reincarnation Part 2', 'After the mysterious mana calamity, Rudeus Greyrat and his fierce student Eris Boreas Greyrat are teleported to the Demon Continent. There, they team up with their newfound companion Ruijerd Supardia—the former leader of the Superd\'s Warrior group—to form \"Dead End,\" a successful adventurer party. Making a name for themselves, the trio journeys across the continent to make their way back home to Fittoa.\r\n\r\nFollowing the advice he received from the faceless god Hitogami, Rudeus saves Kishirika Kishirisu, the Great Emperor of the Demon World, who rewards him by granting him a strange power. Now, as Rudeus masters the powerful ability that offers a number of new opportunities, it might prove to be more than what he bargained for when unexpected dangers threaten to hinder their travels.', '00:23:00', 12, NULL, NULL, 'befejezett', NULL, 'https://cdn.myanimelist.net/images/anime/1028/117777.jpg');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `anime_has_studio`
--

CREATE TABLE anime_studio (
  `anime_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `anime_has_studio`
--

INSERT INTO anime_studio (`anime_id`, `studio_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `anime_lista`
--

CREATE TABLE `anime_lista` (
  `felhasznalo_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `statusz` enum('nez','befejezett','tervezet','drop') DEFAULT 'nez',
  `hol_tart` int(11) DEFAULT NULL,
  `ertekeles` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `episode_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` time NOT NULL,
  `air_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `episodes`
--

INSERT INTO `episodes` (`id`, `anime_id`, `episode_number`, `title`, `duration`, `air_date`) VALUES
(1, 1, 1, 'Jobless Reincarnation', '00:23:00', '2021-01-11'),
(2, 1, 2, 'Master', '00:23:00', '2021-01-18'),
(3, 1, 3, 'Friend', '00:23:00', '2021-01-25'),
(4, 1, 4, 'Emergency Family Meeting', '00:23:00', '2021-02-01'),
(5, 1, 5, 'A Young Lady and Violence', '00:23:00', '2021-02-08'),
(6, 1, 6, 'A Day Off in Roa', '00:23:00', '2021-02-15'),
(7, 1, 7, 'What Lies Beyond Effort', '00:23:00', '2021-02-22'),
(8, 1, 8, 'Turning Point 1', '00:23:00', '2021-03-01'),
(9, 1, 9, 'Encounter', '00:23:00', '2021-03-08'),
(10, 1, 10, 'The Value of a Life and the First Job', '00:23:00', '2021-03-15'),
(11, 1, 11, 'Children and Warriors', '00:23:00', '2021-03-22'),
(12, 2, 1, 'The Woman with the Demon Eyes', '00:23:00', '2021-10-04'),
(13, 2, 2, 'Missed Connections', '00:23:00', '2021-10-11');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo`
--

CREATE TABLE `felhasznalo` (
  `id` int(11) NOT NULL,
  `felhasznalo_nev` varchar(12) NOT NULL,
  `jogosultsag` enum('admin','editor','user') NOT NULL DEFAULT 'user',
  `jelszo` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `felhasznalo`
--

INSERT INTO `felhasznalo` (`id`, `felhasznalo_nev`, `jogosultsag`, `jelszo`) VALUES
(1, 'alma', 'user', '$2y$10$AjrqS71EaBwuK8/BNiCbLuOW0z5wjvW1BJyuKw6Zsxan5qJMytXA2'),
(2, 'asd', 'user', '$2y$10$vqnzR8Bn9nxe6okzlIgP5eAL.tFsnTos3n8J6yLbechky6H019iJS');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `studio`
--

CREATE TABLE `studio` (
  `id` int(11) NOT NULL,
  `nev` varchar(45) NOT NULL,
  `alapitas` date NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `studio`
--

INSERT INTO `studio` (`id`, `nev`, `alapitas`, `logo`) VALUES
(1, 'Studio Bind', '2018-11-01', 'https://cdn.myanimelist.net/s/common/company_logos/17557685-c55a-4aa3-9990-a13667e7c1b5_600x600_i?s=c81759de42fc570ba43e173dff286257');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szezon`
--

CREATE TABLE `szezon` (
  `id` int(11) NOT NULL,
  `ev` int(11) NOT NULL,
  `szezon` enum('tel','tavasz','nyar','osz') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `szezon`
--

INSERT INTO `szezon` (`id`, `ev`, `szezon`) VALUES
(1, 1960, 'tel'),
(2, 1960, 'tavasz'),
(3, 1960, 'nyar'),
(4, 1960, 'osz'),
(5, 1961, 'tel'),
(6, 1961, 'tavasz'),
(7, 1961, 'nyar'),
(8, 1961, 'osz'),
(9, 1962, 'tel'),
(10, 1962, 'tavasz'),
(11, 1962, 'nyar'),
(12, 1962, 'osz'),
(13, 1963, 'tel'),
(14, 1963, 'tavasz'),
(15, 1963, 'nyar'),
(16, 1963, 'osz'),
(17, 1964, 'tel'),
(18, 1964, 'tavasz'),
(19, 1964, 'nyar'),
(20, 1964, 'osz'),
(21, 1965, 'tel'),
(22, 1965, 'tavasz'),
(23, 1965, 'nyar'),
(24, 1965, 'osz'),
(25, 1966, 'tel'),
(26, 1966, 'tavasz'),
(27, 1966, 'nyar'),
(28, 1966, 'osz'),
(29, 1967, 'tel'),
(30, 1967, 'tavasz'),
(31, 1967, 'nyar'),
(32, 1967, 'osz'),
(33, 1968, 'tel'),
(34, 1968, 'tavasz'),
(35, 1968, 'nyar'),
(36, 1968, 'osz'),
(37, 1969, 'tel'),
(38, 1969, 'tavasz'),
(39, 1969, 'nyar'),
(40, 1969, 'osz'),
(41, 1970, 'tel'),
(42, 1970, 'tavasz'),
(43, 1970, 'nyar'),
(44, 1970, 'osz'),
(45, 1971, 'tel'),
(46, 1971, 'tavasz'),
(47, 1971, 'nyar'),
(48, 1971, 'osz'),
(49, 1972, 'tel'),
(50, 1972, 'tavasz'),
(51, 1972, 'nyar'),
(52, 1972, 'osz'),
(53, 1973, 'tel'),
(54, 1973, 'tavasz'),
(55, 1973, 'nyar'),
(56, 1973, 'osz'),
(57, 1974, 'tel'),
(58, 1974, 'tavasz'),
(59, 1974, 'nyar'),
(60, 1974, 'osz'),
(61, 1975, 'tel'),
(62, 1975, 'tavasz'),
(63, 1975, 'nyar'),
(64, 1975, 'osz'),
(65, 1976, 'tel'),
(66, 1976, 'tavasz'),
(67, 1976, 'nyar'),
(68, 1976, 'osz'),
(69, 1977, 'tel'),
(70, 1977, 'tavasz'),
(71, 1977, 'nyar'),
(72, 1977, 'osz'),
(73, 1978, 'tel'),
(74, 1978, 'tavasz'),
(75, 1978, 'nyar'),
(76, 1978, 'osz'),
(77, 1979, 'tel'),
(78, 1979, 'tavasz'),
(79, 1979, 'nyar'),
(80, 1979, 'osz'),
(81, 1980, 'tel'),
(82, 1980, 'tavasz'),
(83, 1980, 'nyar'),
(84, 1980, 'osz'),
(85, 1981, 'tel'),
(86, 1981, 'tavasz'),
(87, 1981, 'nyar'),
(88, 1981, 'osz'),
(89, 1982, 'tel'),
(90, 1982, 'tavasz'),
(91, 1982, 'nyar'),
(92, 1982, 'osz'),
(93, 1983, 'tel'),
(94, 1983, 'tavasz'),
(95, 1983, 'nyar'),
(96, 1983, 'osz'),
(97, 1984, 'tel'),
(98, 1984, 'tavasz'),
(99, 1984, 'nyar'),
(100, 1984, 'osz'),
(101, 1985, 'tel'),
(102, 1985, 'tavasz'),
(103, 1985, 'nyar'),
(104, 1985, 'osz'),
(105, 1986, 'tel'),
(106, 1986, 'tavasz'),
(107, 1986, 'nyar'),
(108, 1986, 'osz'),
(109, 1987, 'tel'),
(110, 1987, 'tavasz'),
(111, 1987, 'nyar'),
(112, 1987, 'osz'),
(113, 1988, 'tel'),
(114, 1988, 'tavasz'),
(115, 1988, 'nyar'),
(116, 1988, 'osz'),
(117, 1989, 'tel'),
(118, 1989, 'tavasz'),
(119, 1989, 'nyar'),
(120, 1989, 'osz'),
(121, 1990, 'tel'),
(122, 1990, 'tavasz'),
(123, 1990, 'nyar'),
(124, 1990, 'osz'),
(125, 1991, 'tel'),
(126, 1991, 'tavasz'),
(127, 1991, 'nyar'),
(128, 1991, 'osz'),
(129, 1992, 'tel'),
(130, 1992, 'tavasz'),
(131, 1992, 'nyar'),
(132, 1992, 'osz'),
(133, 1993, 'tel'),
(134, 1993, 'tavasz'),
(135, 1993, 'nyar'),
(136, 1993, 'osz'),
(137, 1994, 'tel'),
(138, 1994, 'tavasz'),
(139, 1994, 'nyar'),
(140, 1994, 'osz'),
(141, 1995, 'tel'),
(142, 1995, 'tavasz'),
(143, 1995, 'nyar'),
(144, 1995, 'osz'),
(145, 1996, 'tel'),
(146, 1996, 'tavasz'),
(147, 1996, 'nyar'),
(148, 1996, 'osz'),
(149, 1997, 'tel'),
(150, 1997, 'tavasz'),
(151, 1997, 'nyar'),
(152, 1997, 'osz'),
(153, 1998, 'tel'),
(154, 1998, 'tavasz'),
(155, 1998, 'nyar'),
(156, 1998, 'osz'),
(157, 1999, 'tel'),
(158, 1999, 'tavasz'),
(159, 1999, 'nyar'),
(160, 1999, 'osz'),
(161, 2000, 'tel'),
(162, 2000, 'tavasz'),
(163, 2000, 'nyar'),
(164, 2000, 'osz'),
(165, 2001, 'tel'),
(166, 2001, 'tavasz'),
(167, 2001, 'nyar'),
(168, 2001, 'osz'),
(169, 2002, 'tel'),
(170, 2002, 'tavasz'),
(171, 2002, 'nyar'),
(172, 2002, 'osz'),
(173, 2003, 'tel'),
(174, 2003, 'tavasz'),
(175, 2003, 'nyar'),
(176, 2003, 'osz'),
(177, 2004, 'tel'),
(178, 2004, 'tavasz'),
(179, 2004, 'nyar'),
(180, 2004, 'osz'),
(181, 2005, 'tel'),
(182, 2005, 'tavasz'),
(183, 2005, 'nyar'),
(184, 2005, 'osz'),
(185, 2006, 'tel'),
(186, 2006, 'tavasz'),
(187, 2006, 'nyar'),
(188, 2006, 'osz'),
(189, 2007, 'tel'),
(190, 2007, 'tavasz'),
(191, 2007, 'nyar'),
(192, 2007, 'osz'),
(193, 2008, 'tel'),
(194, 2008, 'tavasz'),
(195, 2008, 'nyar'),
(196, 2008, 'osz'),
(197, 2009, 'tel'),
(198, 2009, 'tavasz'),
(199, 2009, 'nyar'),
(200, 2009, 'osz'),
(201, 2010, 'tel'),
(202, 2010, 'tavasz'),
(203, 2010, 'nyar'),
(204, 2010, 'osz'),
(205, 2011, 'tel'),
(206, 2011, 'tavasz'),
(207, 2011, 'nyar'),
(208, 2011, 'osz'),
(209, 2012, 'tel'),
(210, 2012, 'tavasz'),
(211, 2012, 'nyar'),
(212, 2012, 'osz'),
(213, 2013, 'tel'),
(214, 2013, 'tavasz'),
(215, 2013, 'nyar'),
(216, 2013, 'osz'),
(217, 2014, 'tel'),
(218, 2014, 'tavasz'),
(219, 2014, 'nyar'),
(220, 2014, 'osz'),
(221, 2015, 'tel'),
(222, 2015, 'tavasz'),
(223, 2015, 'nyar'),
(224, 2015, 'osz'),
(225, 2016, 'tel'),
(226, 2016, 'tavasz'),
(227, 2016, 'nyar'),
(228, 2016, 'osz'),
(229, 2017, 'tel'),
(230, 2017, 'tavasz'),
(231, 2017, 'nyar'),
(232, 2017, 'osz'),
(233, 2018, 'tel'),
(234, 2018, 'tavasz'),
(235, 2018, 'nyar'),
(236, 2018, 'osz'),
(237, 2019, 'tel'),
(238, 2019, 'tavasz'),
(239, 2019, 'nyar'),
(240, 2019, 'osz'),
(241, 2020, 'tel'),
(242, 2020, 'tavasz'),
(243, 2020, 'nyar'),
(244, 2020, 'osz'),
(245, 2021, 'tel'),
(246, 2021, 'tavasz'),
(247, 2021, 'nyar'),
(248, 2021, 'osz'),
(249, 2022, 'tel'),
(250, 2022, 'tavasz'),
(251, 2022, 'nyar'),
(252, 2022, 'osz'),
(253, 2023, 'tel'),
(254, 2023, 'tavasz'),
(255, 2023, 'nyar'),
(256, 2023, 'osz'),
(257, 2024, 'tel'),
(258, 2024, 'tavasz'),
(259, 2024, 'nyar'),
(260, 2024, 'osz'),
(261, 2025, 'tel'),
(262, 2025, 'tavasz'),
(263, 2025, 'nyar'),
(264, 2025, 'osz'),
(265, 2026, 'tel'),
(266, 2026, 'tavasz'),
(267, 2026, 'nyar'),
(268, 2026, 'osz'),
(269, 2027, 'tel'),
(270, 2027, 'tavasz'),
(271, 2027, 'nyar'),
(272, 2027, 'osz'),
(273, 2028, 'tel'),
(274, 2028, 'tavasz'),
(275, 2028, 'nyar'),
(276, 2028, 'osz'),
(277, 2029, 'tel'),
(278, 2029, 'tavasz'),
(279, 2029, 'nyar'),
(280, 2029, 'osz');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `romanji_cim` (`romanji_cim`),
  ADD UNIQUE KEY `angol_cim` (`angol_cim`),
  ADD KEY `fk_anime_elozmeny` (`elozmeny_id`),
  ADD KEY `fk_anime_folytatas` (`folytatas_id`),
  ADD KEY `fk_anime_szezon` (`szezon_id`);

--
-- A tábla indexei `anime_has_studio`
--
ALTER TABLE anime_studio
  ADD PRIMARY KEY (`anime_id`,`studio_id`),
  ADD KEY `fk_anime_has_studio_studio1` (`studio_id`);

--
-- A tábla indexei `anime_lista`
--
ALTER TABLE `anime_lista`
  ADD PRIMARY KEY (`felhasznalo_id`,`anime_id`),
  ADD KEY `fk_felhasznalo_has_anime_anime1` (`anime_id`);

--
-- A tábla indexei `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- A tábla indexei `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `felhasznalo_nev` (`felhasznalo_nev`);

--
-- A tábla indexei `studio`
--
ALTER TABLE `studio`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `szezon`
--
ALTER TABLE `szezon`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `anime`
--
ALTER TABLE `anime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `episodes`
--
ALTER TABLE `episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT a táblához `felhasznalo`
--
ALTER TABLE `felhasznalo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `studio`
--
ALTER TABLE `studio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `szezon`
--
ALTER TABLE `szezon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `anime`
--
ALTER TABLE `anime`
  ADD CONSTRAINT `fk_anime_elozmeny` FOREIGN KEY (`elozmeny_id`) REFERENCES `anime` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_anime_folytatas` FOREIGN KEY (`folytatas_id`) REFERENCES `anime` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_anime_szezon` FOREIGN KEY (`szezon_id`) REFERENCES `szezon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Megkötések a táblához `anime_has_studio`
--
ALTER TABLE anime_studio
  ADD CONSTRAINT `fk_anime_has_studio_anime1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  ADD CONSTRAINT `fk_anime_has_studio_studio1` FOREIGN KEY (`studio_id`) REFERENCES `studio` (`id`);

--
-- Megkötések a táblához `anime_lista`
--
ALTER TABLE `anime_lista`
  ADD CONSTRAINT `fk_felhasznalo_has_anime_anime1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  ADD CONSTRAINT `fk_felhasznalo_has_anime_felhasznalo1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`);

--
-- Megkötések a táblához `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
