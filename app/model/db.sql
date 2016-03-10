SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


CREATE SCHEMA IF NOT EXISTS `base_tramas` DEFAULT CHARACTER SET utf8 ;
USE `base_tramas` ;


CREATE TABLE IF NOT EXISTS `base_tramas`.`vehicles` (
  `idvehicle` DOUBLE UNSIGNED NOT NULL,
  `name_vehicle` VARCHAR(45) NULL,
  `capacitance` INT NULL,
  `max_capacitance` INT NULL,
  `imei` VARCHAR(15) NULL,
  PRIMARY KEY (`idvehicle`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `base_tramas`.`data_frame` (
  `iddata_frame` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `up` INT NULL,
  `down` INT NULL,
  `aboart` INT NULL,
  `false_up` INT NULL,
  `false_down` INT NULL,
  `error` INT NULL,
  `event_date` DATETIME NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `imei` DOUBLE NULL,
  `vehicle_idvehicle` DOUBLE UNSIGNED NOT NULL,
  PRIMARY KEY (`iddata_frame`),
  INDEX `fk_data_frame_vehicle1_idx` (`vehicle_idvehicle` ASC),
  CONSTRAINT `fk_data_frame_vehicle1`
    FOREIGN KEY (`vehicle_idvehicle`)
    REFERENCES `base_tramas`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `base_tramas`.`volatile_frame` (
  `idvolatile_frame` INT NOT NULL,
  `idvehicle` INT NULL,
  `up` INT NULL,
  `down` INT NULL,
  `abord` INT NULL,
  `false_up` INT NULL,
  `false_down` INT NULL,
  `error` INT NULL,
  `eventTime` DATETIME NULL,
  `imei` VARCHAR(15) NULL,
  PRIMARY KEY (`idvolatile_frame`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `base_tramas`.`volatile_stop` (
  `idvolatile_stop` INT NOT NULL,
  `idveihicle` DOUBLE NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `eventTime` DATETIME NULL,
  PRIMARY KEY (`idvolatile_stop`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
