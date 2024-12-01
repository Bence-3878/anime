-- --------------------------------------------------------
-- Hoszt:                        127.0.0.1
-- Szerver verzió:               8.0.30 - MySQL Community Server - GPL
-- Szerver OS:                   Win64
-- HeidiSQL Verzió:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Adatbázis struktúra mentése a hazi.
CREATE DATABASE IF NOT EXISTS `hazi` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `hazi`;

-- Struktúra mentése tábla hazi. anime
CREATE TABLE IF NOT EXISTS `anime` (
  `id` int NOT NULL AUTO_INCREMENT,
  `elozmeny_id` int DEFAULT NULL,
  `folytatas_id` int DEFAULT NULL,
  `szezon_id` int DEFAULT NULL,
  `romanji_cim` varchar(120) NOT NULL,
  `angol_cim` varchar(120) DEFAULT NULL,
  `leiras` text NOT NULL,
  `hossza` time DEFAULT NULL,
  `epizod_szam` int DEFAULT NULL,
  `kezdo_datum` date DEFAULT NULL,
  `vege_datum` date DEFAULT NULL,
  `statusz` enum('fut','befejezett','tervezet','szunet') NOT NULL DEFAULT 'tervezet',
  `ertekeles` float DEFAULT NULL,
  `poszter` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `romanji_cim` (`romanji_cim`),
  UNIQUE KEY `angol_cim` (`angol_cim`),
  KEY `fk_anime_elozmeny` (`elozmeny_id`),
  KEY `fk_anime_folytatas` (`folytatas_id`),
  KEY `fk_anime_szezon` (`szezon_id`),
  CONSTRAINT `fk_anime_elozmeny` FOREIGN KEY (`elozmeny_id`) REFERENCES `anime` (`id`),
  CONSTRAINT `fk_anime_folytatas` FOREIGN KEY (`folytatas_id`) REFERENCES `anime` (`id`),
  CONSTRAINT `fk_anime_szezon` FOREIGN KEY (`szezon_id`) REFERENCES `szezon` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.anime: ~4 rows (hozzávetőleg)
DELETE FROM `anime`;
INSERT INTO `anime` (`id`, `elozmeny_id`, `folytatas_id`, `szezon_id`, `romanji_cim`, `angol_cim`, `leiras`, `hossza`, `epizod_szam`, `kezdo_datum`, `vege_datum`, `statusz`, `ertekeles`, `poszter`) VALUES
	(1, NULL, 2, 260, 'Mushoku Tensei: Isekai Ittara Honki Dasu', 'Mushoku Tensei: Jobless Reincarnation', 'Amikor a harmincnégy éves, munkanélküli otaku élete zsákutcába ér, és úgy határoz, ideje átértékelni az életet - elgázolja egy teherautó és meghal! Meglepő módon egy csecsemő testében születik újjá, a kardok és a mágia különös, új világában. Új személyazonossága Rudeus Greyrat, miközben előző életének emlékeit is őrzi. Kövesd végig Rudeust a csecsemőkortól a felnőttkorig, ahogy egy csodás, de veszélyes világban próbálja megvalósítani önmagát. ', '00:23:00', 11, NULL, NULL, 'befejezett', NULL, 'https://cdn.myanimelist.net/images/anime/1530/117776.jpg'),
	(2, 1, NULL, 260, 'Mushoku Tensei: Isekai Ittara Honki Dasu Part 2', 'Mushoku Tensei: Jobless Reincarnation Part 2', 'After the mysterious mana calamity, Rudeus Greyrat and his fierce student Eris Boreas Greyrat are teleported to the Demon Continent. There, they team up with their newfound companion Ruijerd Supardia—the former leader of the Superd\'s Warrior group—to form "Dead End," a successful adventurer party. Making a name for themselves, the trio journeys across the continent to make their way back home to Fittoa.\r\n\r\nFollowing the advice he received from the faceless god Hitogami, Rudeus saves Kishirika Kishirisu, the Great Emperor of the Demon World, who rewards him by granting him a strange power. Now, as Rudeus masters the powerful ability that offers a number of new opportunities, it might prove to be more than what he bargained for when unexpected dangers threaten to hinder their travels.', '00:23:00', 12, NULL, NULL, 'befejezett', NULL, 'https://cdn.myanimelist.net/images/anime/1028/117777.jpg'),
	(3, NULL, NULL, 260, 'Dandadan', '', 'Reeling from her recent breakup, Momo Ayase, a popular high schooler, shows kindness to her socially awkward schoolmate, Ken Takakura, by standing up to his bullies. Ken misunderstands her intentions, believing he has made a new friend who shares his obsession with aliens and UFOs. However, Momo\'s own eccentric occult beliefs lie in the supernatural realm; she thinks aliens do not exist. A rivalry quickly brews as each becomes determined to prove the other wrong.\r\n\r\nDespite their initial clash over their opposing beliefs, Momo and Ken form an unexpected but intimate friendship, a bond forged in a series of supernatural battles and bizarre encounters with urban legends and paranormal entities. As both develop unique superhuman abilities, they learn to supplement each other\'s weaknesses, leading them to wonder if their newfound partnership may be about more than just survival.', '00:23:00', 12, '2024-10-04', '2024-12-20', 'fut', 9, 'https://cdn.myanimelist.net/images/anime/1584/143719.jpg'),
	(4, NULL, NULL, 260, 'Re:Zero kara Hajimeru Isekai Seikatsu 3rd Season', 'Re:ZERO -Starting Life in Another World- Season 3', 'One year after the events at the Sanctuary, Subaru Natsuki trains hard to better face future challenges. The peaceful days come to an end when Emilia receives an invitation to a meeting in the Watergate City of Priestella from none other than Anastasia Hoshin, one of her rivals in the royal selection. Considering the meeting\'s significance and the potential dangers Emilia could face, Subaru and his friends accompany her.\r\n\r\nHowever, as Subaru reconnects with old associates and companions in Priestella, new formidable foes emerge. Driven by fanatical motivations and engaging in ruthless methods to achieve their ambitions, the new enemy targets Emilia and threaten the very existence of the city. Rallying his allies, Subaru must give his all once more to stop their and nefarious goals from becoming a concrete reality.', '00:23:00', 16, '2024-10-02', NULL, 'szunet', NULL, 'https://cdn.myanimelist.net/images/anime/1706/144725.jpg'),
	(5, NULL, NULL, 247, 'Seirei Gensouki', 'Seirei Gensouki: Spirit Chronicles', 'When 20-year-old college student Haruto Amakawa dies in a traffic accident, he does not expect to wake up in an unfamiliar world in the body of a young boy named Rio. As their memories and personas fuse, Rio realizes that he now also possesses magical powers. He is relieved to find that his burning passion for revenge against his mother\'s murderers has not subsided, despite his newly changed identity.\r\n\r\nNot soon after, Rio comes across the kidnapped princess of the Bertram Kingdom and saves her without hesitation. To express his gratitude, the king grants him the opportunity to enroll in the Bertram Royal Academy. Believing this to be a new chapter in his life, he is excited to study at this prestigious academy, but life here proves to be difficult for him, a slum-dweller surrounded by the majestic children of nobles.\r\n\r\n[Written by MAL Rewrite]', '23:42:00', 12, '2021-02-06', '2021-09-21', 'befejezett', 7, 'https://cdn.myanimelist.net/images/anime/1094/110385l.jpg');

-- Struktúra mentése tábla hazi. anime_has_studio
CREATE TABLE IF NOT EXISTS `anime_has_studio` (
  `anime_id` int NOT NULL,
  `studio_id` int NOT NULL,
  PRIMARY KEY (`anime_id`,`studio_id`),
  KEY `fk_anime_has_studio_studio1` (`studio_id`),
  CONSTRAINT `fk_anime_has_studio_anime1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  CONSTRAINT `fk_anime_has_studio_studio1` FOREIGN KEY (`studio_id`) REFERENCES `studio` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.anime_has_studio: ~2 rows (hozzávetőleg)
DELETE FROM `anime_has_studio`;
INSERT INTO `anime_has_studio` (`anime_id`, `studio_id`) VALUES
	(1, 1),
	(2, 1),
	(5, 1);

-- Struktúra mentése tábla hazi. anime_lista
CREATE TABLE IF NOT EXISTS `anime_lista` (
  `felhasznalo_id` int NOT NULL,
  `anime_id` int NOT NULL,
  `statusz` enum('nez','befejezett','tervezet','drop') DEFAULT 'nez',
  `hol_tart` int NOT NULL DEFAULT '0',
  `ertekeles` int DEFAULT NULL,
  PRIMARY KEY (`felhasznalo_id`,`anime_id`),
  KEY `fk_felhasznalo_has_anime_anime1` (`anime_id`),
  CONSTRAINT `fk_felhasznalo_has_anime_anime1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  CONSTRAINT `fk_felhasznalo_has_anime_felhasznalo1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.anime_lista: ~1 rows (hozzávetőleg)
DELETE FROM `anime_lista`;
INSERT INTO `anime_lista` (`felhasznalo_id`, `anime_id`, `statusz`, `hol_tart`, `ertekeles`) VALUES
	(1, 2, NULL, 0, 0),
	(13, 3, NULL, 5, 5);

-- Struktúra mentése tábla hazi. episodes
CREATE TABLE IF NOT EXISTS `episodes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `anime_id` int NOT NULL,
  `episode_number` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` time NOT NULL,
  `air_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anime_id` (`anime_id`),
  CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.episodes: ~13 rows (hozzávetőleg)
DELETE FROM `episodes`;
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

-- Struktúra mentése tábla hazi. felhasznalo
CREATE TABLE IF NOT EXISTS `felhasznalo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nev` varchar(12) NOT NULL,
  `jogosultsag` enum('admin','editor','user') NOT NULL DEFAULT 'user',
  `jelszo` char(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `felhasznalo_nev` (`nev`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.felhasznalo: ~11 rows (hozzávetőleg)
DELETE FROM `felhasznalo`;
INSERT INTO `felhasznalo` (`id`, `nev`, `jogosultsag`, `jelszo`) VALUES
	(1, 'Bence1', 'admin', '$2y$10$AjrqS71EaBwuK8/BNiCbLuOW0z5wjvW1BJyuKw6Zsxan5qJMytXA2'),
	(2, 'asd', 'user', '$2y$10$vqnzR8Bn9nxe6okzlIgP5eAL.tFsnTos3n8J6yLbechky6H019iJS'),
	(3, 'almahj', 'editor', '$2y$10$Pu0rBdfOujiSky2IDeunweFajAVx14vnRVfPa8v.1iaX.MgP0vDou'),
	(4, 'hk', 'user', '$2y$10$xLcI56Rhu5WoseA6bObgDuywSsz5xPIB4Ax2uFiwuOZoAjVdxmzgi'),
	(6, 'kljjhgjhgj', 'user', '$2y$10$eqzckK8/ibkc.dHkOqW9W.cPjVGYDxIDcC7uXPXvK3YWTD5alqsJ2'),
	(7, 'fsd', 'user', '$2y$10$juPm0S32AVEMgljvzT/OdePWZNQLV99C8r7limHVZyqoYW/R2t6Ty'),
	(8, 'dad', 'user', '$2y$10$sEvfGJkErmoegfIvRHNi/.ru.BsZ5kOP6yDrNAf7jF5d4JA801XVC'),
	(9, 'bence', 'editor', '$2y$10$Ctvdw27MUOVjU4CQjhaBb.v3qSSiiI2eJPaC1BPfuo9RXE50SeeqG'),
	(10, 'sddsdsf', 'user', '$2y$10$FRje6Pjlqp75kBgysmdwbeb./uJup9zqGC7tEig5G0jCpUVD1vY9.'),
	(11, 'szilvi', 'user', '$2y$10$M68P1g4UZqsrsb5ZxcvtzuWpd9nKeNT6nngE7nBviEv9EtDDfS2iS'),
	(12, 'béla', 'user', '$2y$10$LVuBMgKmoVTX6ywcpuezkeDMXDRJxqi3/f.CxeeRymRQcd0yyqhWe'),
	(13, 'FWEXY_HUNn', 'admin', '$2y$10$BARhyaUGYONUdEO21x6wSe.wDS1abWqOmFkP/OW3e7nDIqIOOfHfa'),
	(14, 'FWEXY', 'user', '$2y$10$JHN4IXpCTAcnDnnc3kK0XeanWibmDiVJ4.jX.YuKiDfngvO08/cRu');

-- Struktúra mentése tábla hazi. karakter
CREATE TABLE IF NOT EXISTS `karakter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nev` varchar(45) NOT NULL,
  `leiras` text NOT NULL,
  `kep` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.karakter: ~2 rows (hozzávetőleg)
DELETE FROM `karakter`;
INSERT INTO `karakter` (`id`, `nev`, `leiras`, `kep`) VALUES
	(1, 'Rudeus Greyrat', 'Rudeus Greyrat is a reincarnated NEET loser who died, but his memories of his past life remained. His current body possesses high affinity for magic, even as a child.', 'https://cdn.myanimelist.net/images/characters/2/423667.jpg'),
	(2, 'Eris Boreas Greyrat', 'She is Rudeus\' older cousin and also his student. When Rudeus was around seven years old, he taught her magic along with math and reading. She is a violent, strong and proud girl and merciless towards anyone who holds negative emotions and intentions against Rudeus.\r\n', 'https://cdn.myanimelist.net/images/characters/14/324594.jpg');

-- Struktúra mentése tábla hazi. kedvenc_karakter
CREATE TABLE IF NOT EXISTS `kedvenc_karakter` (
  `felhasznalo_id` int NOT NULL,
  `karakter_id` int NOT NULL,
  PRIMARY KEY (`felhasznalo_id`,`karakter_id`),
  KEY `fk_felhasznalo_has_karakter_karakter1` (`karakter_id`),
  CONSTRAINT `fk_felhasznalo_has_karakter_felhasznalo1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`),
  CONSTRAINT `fk_felhasznalo_has_karakter_karakter1` FOREIGN KEY (`karakter_id`) REFERENCES `karakter` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.kedvenc_karakter: ~0 rows (hozzávetőleg)
DELETE FROM `kedvenc_karakter`;

-- Struktúra mentése tábla hazi. kedvenc_szinesz
CREATE TABLE IF NOT EXISTS `kedvenc_szinesz` (
  `felhasznalo_id` int NOT NULL,
  `szinkron_szinész_id` int NOT NULL,
  PRIMARY KEY (`felhasznalo_id`,`szinkron_szinész_id`),
  KEY `fk_felhasznalo_has_szinész_szinész1` (`szinkron_szinész_id`),
  CONSTRAINT `fk_felhasznalo_has_szinész_felhasznalo1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`),
  CONSTRAINT `fk_felhasznalo_has_szinész_szinész1` FOREIGN KEY (`szinkron_szinész_id`) REFERENCES `szemely` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.kedvenc_szinesz: ~0 rows (hozzávetőleg)
DELETE FROM `kedvenc_szinesz`;

-- Struktúra mentése tábla hazi. stab
CREATE TABLE IF NOT EXISTS `stab` (
  `anime_id` int NOT NULL,
  `szemely_id` int NOT NULL,
  `pozicio` varchar(20) NOT NULL,
  PRIMARY KEY (`anime_id`,`szemely_id`),
  KEY `fk_Anime_has_ember_ember1` (`szemely_id`),
  CONSTRAINT `fk_Anime_has_ember_Anime1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  CONSTRAINT `fk_Anime_has_ember_ember1` FOREIGN KEY (`szemely_id`) REFERENCES `szemely` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.stab: ~0 rows (hozzávetőleg)
DELETE FROM `stab`;

-- Struktúra mentése tábla hazi. studio
CREATE TABLE IF NOT EXISTS `studio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nev` varchar(45) NOT NULL,
  `alapitas` date NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.studio: ~0 rows (hozzávetőleg)
DELETE FROM `studio`;
INSERT INTO `studio` (`id`, `nev`, `alapitas`, `logo`) VALUES
	(1, 'Studio Bind', '2018-11-01', 'https://cdn.myanimelist.net/s/common/company_logos/17557685-c55a-4aa3-9990-a13667e7c1b5_600x600_i?s=c81759de42fc570ba43e173dff286257');

-- Struktúra mentése tábla hazi. szemely
CREATE TABLE IF NOT EXISTS `szemely` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nev` varchar(45) NOT NULL,
  `szuletes_datum` date DEFAULT NULL,
  `kep` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.szemely: ~0 rows (hozzávetőleg)
DELETE FROM `szemely`;
INSERT INTO `szemely` (`id`, `nev`, `szuletes_datum`, `kep`) VALUES
	(0, ' ', NULL, NULL),
	(1, 'Uchiyama, Yumi', '1987-10-30', 'https://cdn.myanimelist.net/images/voiceactors/1/67838.jpg'),
	(2, 'Sugita, Tomokazu', '1980-10-11', 'https://cdn.myanimelist.net/images/voiceactors/1/81054.jpg');

-- Struktúra mentése tábla hazi. szerep
CREATE TABLE IF NOT EXISTS `szerep` (
  `anime_id` int NOT NULL,
  `karakter_id` int NOT NULL,
  `szinkron_szinész_id` int NOT NULL,
  `nyelv` varchar(15) NOT NULL,
  PRIMARY KEY (`anime_id`,`karakter_id`,`szinkron_szinész_id`),
  KEY `fk_anime_has_karakter_karakter1` (`karakter_id`),
  KEY `fk_anime_has_karakter_szinkron_szinész1` (`szinkron_szinész_id`),
  CONSTRAINT `fk_anime_has_karakter_anime1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  CONSTRAINT `fk_anime_has_karakter_karakter1` FOREIGN KEY (`karakter_id`) REFERENCES `karakter` (`id`),
  CONSTRAINT `fk_anime_has_karakter_szinkron_szinész1` FOREIGN KEY (`szinkron_szinész_id`) REFERENCES `szemely` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.szerep: ~4 rows (hozzávetőleg)
DELETE FROM `szerep`;
INSERT INTO `szerep` (`anime_id`, `karakter_id`, `szinkron_szinész_id`, `nyelv`) VALUES
	(1, 1, 1, 'Japán'),
	(1, 1, 2, 'Japán'),
	(2, 1, 1, 'Japán'),
	(2, 1, 2, 'Japán');

-- Struktúra mentése tábla hazi. szezon
CREATE TABLE IF NOT EXISTS `szezon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ev` int NOT NULL,
  `szezon` enum('tel','tavasz','nyar','osz') NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `egyedi` (`ev`,`szezon`)
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb3;

-- Tábla adatainak mentése hazi.szezon: ~0 rows (hozzávetőleg)
DELETE FROM `szezon`;
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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
