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
  `route` VARCHAR(45) NULL,
  PRIMARY KEY (`idvehicle`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`data_frame` (
  `iddata_frame` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `up` INT NULL,
  `down` INT NULL,
  `onboard` INT NULL,
  `sensor_state` INT NULL,
  `error` INT NULL,
  `false_up` INT NULL,
  `false_down` INT NULL,
  `up_block` INT NULL,
  `down_block` INT NULL,
  `event_date` DATETIME NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `imei` DOUBLE NULL,
  `vehicle_idvehicle` DOUBLE NOT NULL,
  PRIMARY KEY (`iddata_frame`),
  INDEX `fk_data_frame_vehicles_idx` (`vehicle_idvehicle` ASC),
  CONSTRAINT `fk_data_frame_vehicles`
    FOREIGN KEY (`vehicle_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`volatile_stop` (
  `idvolatile_stop` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `eventTime` DATETIME NULL,
  `vehicles_idvehicle` DOUBLE NOT NULL,
  PRIMARY KEY (`idvolatile_stop`),
  INDEX `fk_volatile_stop_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_volatile_stop_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`drivers` (
  `iddrivers` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_driver` VARCHAR(45) NULL,
  `active` TINYINT(1) NULL,
  `date_driver` DATETIME NULL,
  PRIMARY KEY (`iddrivers`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`driver_events` (
  `iddriver_events` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicles_idvehicles` DOUBLE NOT NULL,
  `drivers_iddrivers` INT NOT NULL,
  PRIMARY KEY (`iddriver_events`, `vehicles_idvehicles`, `drivers_iddrivers`),
  INDEX `fk_vehicles_has_drivers_drivers1_idx` (`drivers_iddrivers` ASC),
  INDEX `fk_vehicles_has_drivers_vehicles1_idx` (`vehicles_idvehicles` ASC),
  CONSTRAINT `fk_vehicles_has_drivers_vehicles1`
    FOREIGN KEY (`vehicles_idvehicles`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicles_has_drivers_drivers1`
    FOREIGN KEY (`drivers_iddrivers`)
    REFERENCES `db_frames`.`drivers` (`iddrivers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`users` (
  `idusers` INT UNSIGNED NOT NULL,
  `name_user` VARCHAR(45) NULL,
  PRIMARY KEY (`idusers`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`turn` (
  `idturn_driver` INT NOT NULL AUTO_INCREMENT,
  `name_turn` VARCHAR(45) NULL,
  `start_turn` TIME NULL,
  `end_turn` TIME NULL,
  `date_turn` DATETIME NULL,
  PRIMARY KEY (`idturn_driver`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`driver_turn` (
  `drivers_iddrivers` INT UNSIGNED NOT NULL,
  `turn_driver` INT NOT NULL,
  PRIMARY KEY (`drivers_iddrivers`, `turn_driver`),
  INDEX `fk_drivers_has_turn_driver_turn_driver1_idx` (`turn_driver` ASC),
  INDEX `fk_drivers_has_turn_driver_drivers1_idx` (`drivers_iddrivers` ASC),
  CONSTRAINT `fk_drivers_has_turn_driver_drivers1`
    FOREIGN KEY (`drivers_iddrivers`)
    REFERENCES `db_frames`.`drivers` (`iddrivers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_drivers_has_turn_driver_turn_driver1`
    FOREIGN KEY (`turn_driver`)
    REFERENCES `db_frames`.`turn` (`idturn_driver`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`main_table` (
  `idmain_table` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_vehicle` DOUBLE NULL,
  `name_vehicle` VARCHAR(45) NULL,
  `name_driver` VARCHAR(45) NULL,
  `route` VARCHAR(45) NULL,
  `up` INT NULL,
  `down` INT NULL,
  `onboard` INT NULL,
  `sensor_state` TINYINT(1) NULL,
  `name_turn` VARCHAR(45) NULL,
  PRIMARY KEY (`idmain_table`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
