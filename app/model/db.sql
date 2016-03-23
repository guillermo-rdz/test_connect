SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `db_frames` DEFAULT CHARACTER SET utf8 ;
USE `db_frames` ;

CREATE TABLE IF NOT EXISTS `db_frames`.`vehicles` (
  `idvehicle` DOUBLE UNSIGNED NOT NULL,
  `name_vehicle` VARCHAR(45) NULL,
  `capacitance` INT NULL,
  `max_capacitance` INT NULL,
  `imei` DOUBLE NULL,
  PRIMARY KEY (`idvehicle`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `db_frames`.`data_frame` (
  `iddata_frame` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `up` INT NULL,
  `down` INT NULL,
  `onboard` INT NULL,
  `sensor_state` INT(1) NULL,
  `error` INT NULL,
  `false_up` INT NULL,
  `false_down` INT NULL,
  `up_block` INT NULL,
  `down_block` INT NULL,
  `event_date` DATETIME NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `imei` DOUBLE NULL,
  `vehicle_idvehicle` DOUBLE UNSIGNED NOT NULL,
  PRIMARY KEY (`iddata_frame`),
  INDEX `fk_data_frame_vehicle1_idx` (`vehicle_idvehicle` ASC),
  CONSTRAINT `fk_data_frame_vehicle1`
    FOREIGN KEY (`vehicle_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `db_frames`.`volatile_frame` (
  `idvolatile_frame` INT NOT NULL,
  `up` INT NULL,
  `down` INT NULL,
  `onboard` INT NULL,
  `sensor_state` INT(1) NULL,
  `error` INT NULL,
  `false_up` INT NULL,
  `false_down` INT NULL,
  `up_block` INT NULL,
  `down_block` INT NULL,
  `eventTime` DATETIME NULL,
  `imei` DOUBLE NULL,
  `event_type` VARCHAR(10) NULL,
  `vehicles_idvehicle` DOUBLE UNSIGNED NOT NULL,
  PRIMARY KEY (`idvolatile_frame`),
  INDEX `fk_volatile_frame_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_volatile_frame_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `db_frames`.`volatile_stop` (
  `idvolatile_stop` INT NOT NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `eventTime` DATETIME NULL,
  `tipo` VARCHAR(10) NULL,
  `vehicles_idvehicle` DOUBLE UNSIGNED NOT NULL,
  PRIMARY KEY (`idvolatile_stop`),
  INDEX `fk_volatile_stop_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_volatile_stop_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
