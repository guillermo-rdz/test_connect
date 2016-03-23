 @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema db_frames
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_frames` DEFAULT CHARACTER SET utf8 ;
USE `db_frames` ;

-- -----------------------------------------------------
-- Table `db_frames`.`vehicles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_frames`.`vehicles` (
  `idvehicle` DOUBLE UNSIGNED NOT NULL,
  `name_vehicle` VARCHAR(45) NULL,
  `capacitance` INT NULL,
  `max_capacitance` INT NULL,
  `imei` DOUBLE NULL,
  PRIMARY KEY (`idvehicle`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_frames`.`data_frame`
-- -----------------------------------------------------
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
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_frames`.`volatile_frame`
-- -----------------------------------------------------
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


-- -----------------------------------------------------
-- Table `db_frames`.`volatile_stop`
-- -----------------------------------------------------
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



SELECT vehicles.name_vehicle, data_frame.up, data_frame.down, data_frame.aboart,data_frame.false_up, data_frame.false_down, data_frame.event_date, data_frame.lat, data_frame.lon from data_frame INNER JOIN vehicles on data_frame.vehicle_idvehicle = vehicles.idvehicle where vehicles.idvehicle = 106 order by event_date

#COUNSULTA PARA TODOS LOS CAMPOS PASANDOLE COMO PARAMETRO idvehicle y event_date(FECHA)
SELECT b.name_vehicle, a.up, a.down, a.aboart, a.false_up, a.false_down, a.event_date, a.lat, a.lon 
from data_frame as a
RIGHT JOIN vehicles as b
on a.vehicle_idvehicle = b.idvehicle 
where b.idvehicle = 106 and event_date = "2016-03-10"
order by event_date;


#CONSULTA PARA SUBIDA POR ID VEHICULO, DÍA Y HORA 
SELECT b.name_vehicle, sum(a.up), a.event_date
from data_frame as a
RIGHT JOIN vehicles as b
on a.vehicle_idvehicle = b.idvehicle 
where b.idvehicle = 106 and date(event_date) = "2016-03-10" and hour(event_date)= "16"
order by event_date;

#CONSULTA PARA SUBIDA POR ID VEHICULO EN INTERVALOS DE TIEMPO (Usar las perminaciones :59:59 para que tome la hora completa)
SELECT b.name_vehicle, a.up, a.event_date
from data_frame as a
RIGHT JOIN vehicles as b
on a.vehicle_idvehicle = b.idvehicle 
where b.idvehicle = 106 and event_date between "2016-03-10:16:00:00" and "2016-03-10:18:30:59"
order by event_date DESC
LIMIT 1;


select * from data_frame