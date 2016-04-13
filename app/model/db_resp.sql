SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `db_frames` DEFAULT CHARACTER SET utf8 ;
USE `db_frames` ;

CREATE TABLE IF NOT EXISTS `db_frames`.`vehicles` (
  `idvehicle` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
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
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`volatile_stop` (
  `vehicles_idvehicle` BIGINT UNSIGNED NOT NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `eventTime` DATETIME NULL,
  INDEX `fk_volatile_stop_vehicles1_idx` (`vehicles_idvehicle` ASC),
  PRIMARY KEY (`vehicles_idvehicle`),
  CONSTRAINT `fk_volatile_stop_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`users` (
  `idusers` INT UNSIGNED NOT NULL,
  `name_user` VARCHAR(45) NULL,
  `active` TINYINT(1) NULL,
  `status` TINYINT(1) NULL,
  `date_user` DATETIME NULL,
  PRIMARY KEY (`idusers`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`drivers` (
  `iddrivers` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_driver` VARCHAR(45) NULL,
  `ap_driver` VARCHAR(45) NULL,
  `active` TINYINT(1) NULL,
  `date_driver` DATETIME NULL,
  `users_idusers` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`iddrivers`),
  INDEX `fk_drivers_users1_idx` (`users_idusers` ASC),
  CONSTRAINT `fk_drivers_users1`
    FOREIGN KEY (`users_idusers`)
    REFERENCES `db_frames`.`users` (`idusers`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`turn` (
  `idturn_driver` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_turn` VARCHAR(45) NULL,
  `start_turn` TIME NULL,
  `end_turn` TIME NULL,
  `date_turn` DATETIME NULL,
  `users_idusers` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idturn_driver`),
  INDEX `fk_turn_users1_idx` (`users_idusers` ASC),
  CONSTRAINT `fk_turn_users1`
    FOREIGN KEY (`users_idusers`)
    REFERENCES `db_frames`.`users` (`idusers`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`route` (
  `idroute` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_route` VARCHAR(45) NULL,
  `name_start` VARCHAR(30) NULL,
  `start_lat` CHAR(18) NULL,
  `start_lon` CHAR(18) NULL,
  `name_end` VARCHAR(30) NULL,
  `end_lat` CHAR(18) NULL,
  `end_lon` CHAR(18) NULL,
  `date_route` DATETIME NULL,
  `users_idusers` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idroute`),
  INDEX `fk_route_users1_idx` (`users_idusers` ASC),
  CONSTRAINT `fk_route_users1`
    FOREIGN KEY (`users_idusers`)
    REFERENCES `db_frames`.`users` (`idusers`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`rate` (
  `idrate` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_rate` VARCHAR(45) NULL,
  `users_idusers` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idrate`),
  INDEX `fk_rate_users1_idx` (`users_idusers` ASC),
  CONSTRAINT `fk_rate_users1`
    FOREIGN KEY (`users_idusers`)
    REFERENCES `db_frames`.`users` (`idusers`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`driver_events` (
  `iddriver_events` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicles_idvehicle` BIGINT UNSIGNED NOT NULL,
  `drivers_iddrivers` BIGINT UNSIGNED NOT NULL,
  `date_driver_event` DATETIME NULL,
  PRIMARY KEY (`iddriver_events`, `vehicles_idvehicle`, `drivers_iddrivers`),
  INDEX `fk_vehicles_has_drivers_drivers1_idx` (`drivers_iddrivers` ASC),
  INDEX `fk_vehicles_has_drivers_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_vehicles_has_drivers_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_vehicles_has_drivers_drivers1`
    FOREIGN KEY (`drivers_iddrivers`)
    REFERENCES `db_frames`.`drivers` (`iddrivers`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`vehicle_route` (
  `vehicles_idvehicle` BIGINT UNSIGNED NOT NULL,
  `route_idroute` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`vehicles_idvehicle`, `route_idroute`),
  INDEX `fk_vehicles_has_route_route1_idx` (`route_idroute` ASC),
  INDEX `fk_vehicles_has_route_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_vehicles_has_route_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_vehicles_has_route_route1`
    FOREIGN KEY (`route_idroute`)
    REFERENCES `db_frames`.`route` (`idroute`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_frames`.`driver_turn` (
  `drivers_iddrivers` BIGINT UNSIGNED NOT NULL,
  `idturn_driver` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`drivers_iddrivers`, `idturn_driver`),
  INDEX `fk_drivers_has_turn_turn1_idx` (`idturn_driver` ASC),
  INDEX `fk_drivers_has_turn_drivers1_idx` (`drivers_iddrivers` ASC),
  CONSTRAINT `fk_drivers_has_turn_drivers1`
    FOREIGN KEY (`drivers_iddrivers`)
    REFERENCES `db_frames`.`drivers` (`iddrivers`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_drivers_has_turn_turn1`
    FOREIGN KEY (`idturn_driver`)
    REFERENCES `db_frames`.`turn` (`idturn_driver`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
