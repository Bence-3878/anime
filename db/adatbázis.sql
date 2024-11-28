SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `hazi` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `hazi`.`felhasznalo`
(
    `id`              INT(11)                          NOT NULL AUTO_INCREMENT,
    nev VARCHAR(12)                      NOT NULL UNIQUE,
    `jogosultsag`     ENUM ('admin', 'editor', 'user') NOT NULL DEFAULT 'user',
    `jelszo`          CHAR(60)                         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`anime`
(
    `id`           INT(11)                                          NOT NULL AUTO_INCREMENT,
    `elozmeny_id`  INT(11)                                                   DEFAULT NULL,
    `folytatas_id` INT(11)                                                   DEFAULT NULL,
    `szezon_id`    INT(11)                                                   DEFAULT NULL,
    `romanji_cim`  VARCHAR(120)                                     NOT NULL UNIQUE,
    `angol_cim`    VARCHAR(120)                                     NOT NULL UNIQUE,
    `leiras`       TEXT                                             NOT NULL,
    `hossza`       TIME                                                      DEFAULT NULL,
    `epizod_szam`  INT                                                       DEFAULT NULL,
    `kezdo_datum`  DATE                                                      DEFAULT NULL,
    `vege_datum`   DATE                                                      DEFAULT NULL,
    `statusz`      ENUM ('fut', 'befejezett', 'tervezet', 'szunet') NOT NULL DEFAULT 'tervezet',
    `ertekeles`    FLOAT(11)                                                 DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_anime_elozmeny`
        FOREIGN KEY (`elozmeny_id`)
        REFERENCES `hazi`.`anime` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_anime_folytatas`
        FOREIGN KEY (`folytatas_id`)
        REFERENCES `hazi`.`anime` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_anime_szezon`
        FOREIGN KEY (`szezon_id`)
        REFERENCES `hazi`.`szezon` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`studio`
(
    `id`       INT(11) AUTO_INCREMENT,
    `nev`      VARCHAR(45) NOT NULL,
    `alapitas` DATE        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`anime_lista`
(
    `felhasznalo_id` INT(11)                                        NOT NULL,
    `anime_id`       INT(11)                                        NOT NULL,
    `statusz`        ENUM ('nez', 'befejezett', 'tervezet', 'drop') NULL DEFAULT 'nez',
    `hol_tart`       INT(11)                                        NULL DEFAULT NULL,
    `ertekeles`      INT(11)                                        NULL DEFAULT NULL,
    PRIMARY KEY (`felhasznalo_id`, `anime_id`),
    CONSTRAINT `fk_felhasznalo_has_anime_felhasznalo1`
        FOREIGN KEY (`felhasznalo_id`)
        REFERENCES `hazi`.`felhasznalo` (`id`),
    CONSTRAINT `fk_felhasznalo_has_anime_anime1`
        FOREIGN KEY (`anime_id`)
        REFERENCES `hazi`.`anime` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`anime_has_studio`
(
    `anime_id`  INT(11) NOT NULL,
    `studio_id` INT(11) NOT NULL,
    PRIMARY KEY (`anime_id`, `studio_id`),
    CONSTRAINT `fk_anime_has_studio_anime1`
        FOREIGN KEY (`anime_id`)
        REFERENCES `hazi`.`anime` (`id`),
    CONSTRAINT `fk_anime_has_studio_studio1`
        FOREIGN KEY (`studio_id`)
        REFERENCES `hazi`.`studio` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`karakter`
(
    `id`     INT(11)     NOT NULL AUTO_INCREMENT,
    `nev`    VARCHAR(45) NOT NULL,
    `leiras` TEXT        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`szemely`
(
    `id`             INT(11)     NOT NULL AUTO_INCREMENT,
    `nev`            VARCHAR(45) NOT NULL,
    `leiras`         TEXT        NOT NULL,
    `szuletes_datum` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`szerep`
(
    `anime_id`            INT(11)     NOT NULL,
    `karakter_id`         INT(11)     NOT NULL,
    `szinkron_szinész_id` INT(11)     NOT NULL,
    `nyelv`               VARCHAR(15) NOT NULL,
    PRIMARY KEY (`anime_id`, `karakter_id`, `szinkron_szinész_id`),
    CONSTRAINT `fk_anime_has_karakter_anime1`
        FOREIGN KEY (`anime_id`)
        REFERENCES `hazi`.`anime` (`id`),
    CONSTRAINT `fk_anime_has_karakter_karakter1`
        FOREIGN KEY (`karakter_id`)
        REFERENCES `hazi`.`karakter` (`id`),
    CONSTRAINT `fk_anime_has_karakter_szinkron_szinész1`
        FOREIGN KEY (`szinkron_szinész_id`)
        REFERENCES `hazi`.`szemely` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`kedvenc_karakter`
(
    `felhasznalo_id` INT(11) NOT NULL,
    `karakter_id`    INT(11) NOT NULL,
    PRIMARY KEY (`felhasznalo_id`, `karakter_id`),
    CONSTRAINT `fk_felhasznalo_has_karakter_felhasznalo1`
        FOREIGN KEY (`felhasznalo_id`)
        REFERENCES `hazi`.`felhasznalo` (`id`),
    CONSTRAINT `fk_felhasznalo_has_karakter_karakter1`
        FOREIGN KEY (`karakter_id`)
        REFERENCES `hazi`.`karakter` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.kedvenc_szinesz
(
    `felhasznalo_id`      INT(11) NOT NULL,
    `szinkron_szinész_id` INT(11) NOT NULL,
    PRIMARY KEY (`felhasznalo_id`, `szinkron_szinész_id`),
    CONSTRAINT `fk_felhasznalo_has_szinész_felhasznalo1`
        FOREIGN KEY (`felhasznalo_id`)
        REFERENCES `hazi`.`felhasznalo` (`id`),
    CONSTRAINT `fk_felhasznalo_has_szinész_szinész1`
        FOREIGN KEY (`szinkron_szinész_id`)
        REFERENCES `hazi`.`szemely` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`episodes`
(
    `id`         INT(11)      NOT NULL AUTO_INCREMENT,
    `anime_id`   INT(11)      NOT NULL,
    `episode_number` INT(11)  NOT NULL,
    `title`      VARCHAR(255) NOT NULL,
    `duration`   TIME         NOT NULL,
    `air_date`   DATE         DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`anime_id`)
    REFERENCES `hazi`.`anime` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`szezon` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `szezon` VARCHAR(6) NOT NULL,
    `ev` INT,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.stab (
    anime_id INT(11) NOT NULL,
    szemely_id INT(11) NOT NULL,
    `pozicio` VARCHAR(20) NOT NULL,
    PRIMARY KEY (anime_id, szemely_id),
    CONSTRAINT `fk_Anime_has_ember_Anime1`
        FOREIGN KEY (anime_id)
            REFERENCES `hazi`.`Anime` (`id`),
    CONSTRAINT `fk_Anime_has_ember_ember1`
        FOREIGN KEY (szemely_id)
            REFERENCES `hazi`.`szemely` (`id`)
    )
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;