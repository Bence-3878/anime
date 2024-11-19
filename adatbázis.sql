-- MySQL Workbench Synchronization
-- Generated: 2024-11-14 17:38
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Bence

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `hazi` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `hazi`.`felhasználó` (
  `id` INT(11) auto_increment NOT NULL,
  `felhasználó_név` VARCHAR(12) NOT NULL,
  `jogosultság` ENUM('admin', 'editor', 'user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `felhasználó_név_UNIQUE` (`felhasználó_név` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`Anime` (
  `id` INT(11) auto_increment NOT NULL,
  `elözmény_id` INT(11) NULL DEFAULT NULL,
  `folytatás_id` INT(11) NULL DEFAULT NULL,
  `szezon_id` INT(11) NOT NULL,
  `romanji cim` VARCHAR(120) NOT NULL,
  `angol cím` VARCHAR(120) NOT NULL,
  `leírás` TEXT(2000) NOT NULL,
  `hossza` INT(11) NULL DEFAULT NULL,
  `kezdő dátum` DATE NULL DEFAULT NULL,
  `vége dátum` DATE NULL DEFAULT NULL,
  `status` ENUM('fut', 'befejezet', 'tervezet', 'szünet') NOT NULL,
  `értékelés` FLOAT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `romanji cim_UNIQUE` (`romanji cim` ASC) VISIBLE,
  UNIQUE INDEX `angol cím_UNIQUE` (`angol cím` ASC) VISIBLE,
  INDEX `fk_Anime_Anime_idx` (`elözmény_id` ASC) VISIBLE,
  INDEX `fk_Anime_Anime1_idx` (`folytatás_id` ASC) VISIBLE,
  INDEX `fk_Anime_szezon1_idx` (`szezon_id` ASC) VISIBLE,
  CONSTRAINT `fk_Anime_Anime`
    FOREIGN KEY (`elözmény_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Anime_Anime1`
    FOREIGN KEY (`folytatás_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Anime_szezon1`
    FOREIGN KEY (`szezon_id`)
    REFERENCES `hazi`.`szezon` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`Studió` (
  `id` INT(11) auto_increment NOT NULL,
  `név` VARCHAR(45) NOT NULL,
  `alapitás` DATE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`Anime_lista` (
  `felhasználó_id` INT(11) NULL DEFAULT NULL,
  `Anime_id` INT(11) NULL DEFAULT NULL,
  `status` ENUM('néz', 'befejezet', 'tervezet', 'drop') NULL DEFAULT 'néz',
  `hol_tart` INT(11) NULL DEFAULT NULL,
  `értékeél` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`felhasználó_id`, `Anime_id`),
  INDEX `fk_felhasználó_has_Anime_Anime1_idx` (`Anime_id` ASC) VISIBLE,
  INDEX `fk_felhasználó_has_Anime_felhasználó1_idx` (`felhasználó_id` ASC) VISIBLE,
  CONSTRAINT `fk_felhasználó_has_Anime_felhasználó1`
    FOREIGN KEY (`felhasználó_id`)
    REFERENCES `hazi`.`felhasználó` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_felhasználó_has_Anime_Anime1`
    FOREIGN KEY (`Anime_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`Anime_has_Studió` (
  `Anime_id` INT(11) NULL DEFAULT NULL,
  `Studió_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`Anime_id`, `Studió_id`),
  INDEX `fk_Anime_has_Studió_Studió1_idx` (`Studió_id` ASC) VISIBLE,
  INDEX `fk_Anime_has_Studió_Anime1_idx` (`Anime_id` ASC) VISIBLE,
  CONSTRAINT `fk_Anime_has_Studió_Anime1`
    FOREIGN KEY (`Anime_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Anime_has_Studió_Studió1`
    FOREIGN KEY (`Studió_id`)
    REFERENCES `hazi`.`Studió` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`Karakter` (
  `id` INT(11) auto_increment NOT NULL,
  `név` VARCHAR(45) NOT NULL,
  `leírás` TEXT(2000) NOT NULL,
  `kor` INT(11) NULL DEFAULT NULL,
  `magaság` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`ember` (
  `id` INT(11) auto_increment NOT NULL,
  `név` VARCHAR(45) NOT NULL,
  `leírás` TEXT(2000) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`szerep` (
  `Anime_id` INT(11) NOT NULL,
  `Karakter_id` INT(11) NOT NULL,
  `szinkron_szinés_id` INT(11) NULL DEFAULT NULL,
  `nyelv` VARCHAR(15) NULL DEFAULT NULL,
  PRIMARY KEY (`Anime_id`, `Karakter_id`, `szinkron_szinés_id`),
  INDEX `fk_Anime_has_Karakter_Karakter1_idx` (`Karakter_id` ASC) VISIBLE,
  INDEX `fk_Anime_has_Karakter_Anime1_idx` (`Anime_id` ASC) VISIBLE,
  INDEX `fk_Anime_has_Karakter_szinkron_szinés1_idx` (`szinkron_szinés_id` ASC) VISIBLE,
  CONSTRAINT `fk_Anime_has_Karakter_Anime1`
    FOREIGN KEY (`Anime_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Anime_has_Karakter_Karakter1`
    FOREIGN KEY (`Karakter_id`)
    REFERENCES `hazi`.`Karakter` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Anime_has_Karakter_szinkron_szinés1`
    FOREIGN KEY (`szinkron_szinés_id`)
    REFERENCES `hazi`.`ember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`kedvenc_karakter` (
  `felhasználó_id` INT(11) NULL DEFAULT NULL,
  `Karakter_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`felhasználó_id`, `Karakter_id`),
  INDEX `fk_felhasználó_has_Karakter_Karakter1_idx` (`Karakter_id` ASC) VISIBLE,
  INDEX `fk_felhasználó_has_Karakter_felhasználó1_idx` (`felhasználó_id` ASC) VISIBLE,
  CONSTRAINT `fk_felhasználó_has_Karakter_felhasználó1`
    FOREIGN KEY (`felhasználó_id`)
    REFERENCES `hazi`.`felhasználó` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_felhasználó_has_Karakter_Karakter1`
    FOREIGN KEY (`Karakter_id`)
    REFERENCES `hazi`.`Karakter` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`kedvenc_szinés` (
  `felhasználó_id` INT(11) NULL DEFAULT NULL,
  `szinkron_szinés_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`felhasználó_id`, `szinkron_szinés_id`),
  INDEX `fk_felhasználó_has_szinkron_szinés_szinkron_szinés1_idx` (`szinkron_szinés_id` ASC) VISIBLE,
  INDEX `fk_felhasználó_has_szinkron_szinés_felhasználó1_idx` (`felhasználó_id` ASC) VISIBLE,
  CONSTRAINT `fk_felhasználó_has_szinkron_szinés_felhasználó1`
    FOREIGN KEY (`felhasználó_id`)
    REFERENCES `hazi`.`felhasználó` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_felhasználó_has_szinkron_szinés_szinkron_szinés1`
    FOREIGN KEY (`szinkron_szinés_id`)
    REFERENCES `hazi`.`ember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`kedvenc_anime` (
  `felhasználó_id` INT(11) NULL DEFAULT NULL,
  `Anime_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`felhasználó_id`, `Anime_id`),
  INDEX `fk_felhasználó_has_Anime_Anime2_idx` (`Anime_id` ASC) VISIBLE,
  INDEX `fk_felhasználó_has_Anime_felhasználó2_idx` (`felhasználó_id` ASC) VISIBLE,
  CONSTRAINT `fk_felhasználó_has_Anime_felhasználó2`
    FOREIGN KEY (`felhasználó_id`)
    REFERENCES `hazi`.`felhasználó` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_felhasználó_has_Anime_Anime2`
    FOREIGN KEY (`Anime_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`szezon` (
  `id` INT(11) auto_increment NOT NULL,
  `szezon` VARCHAR(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `hazi`.`stáb` (
  `Anime_id` INT(11) NOT NULL,
  `ember_id` INT(11) NOT NULL,
  `pozició` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`Anime_id`, `ember_id`),
  INDEX `fk_Anime_has_ember_ember1_idx` (`ember_id` ASC) VISIBLE,
  INDEX `fk_Anime_has_ember_Anime1_idx` (`Anime_id` ASC) VISIBLE,
  CONSTRAINT `fk_Anime_has_ember_Anime1`
    FOREIGN KEY (`Anime_id`)
    REFERENCES `hazi`.`Anime` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Anime_has_ember_ember1`
    FOREIGN KEY (`ember_id`)
    REFERENCES `hazi`.`ember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


#insert into felhasználó()

SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
