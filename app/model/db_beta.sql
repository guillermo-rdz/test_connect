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


CREATE TABLE IF NOT EXISTS `db_frames`.`driver` (
  `iddriver` INT NOT NULL AUTO_INCREMENT,
  `name_driver` VARCHAR(45) NULL,
  `route` VARCHAR(45) NULL,
  `turn` VARCHAR(10) NULL,
  `price` FLOAT NULL,
  `vehicles_idvehicle` DOUBLE UNSIGNED NOT NULL,
  PRIMARY KEY (`iddriver`, `vehicles_idvehicle`),
  INDEX `fk_driver_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_driver_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `db_frames`.`drivers` (
  `iddrivers` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `turn` VARCHAR(45) NULL,
  `active` BINARY NULL,
  PRIMARY KEY (`iddrivers`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `db_frames`.`driver_events` (
  `iddriver_event` INT NOT NULL AUTO_INCREMENT,
  `vehicles_idvehicle` DOUBLE UNSIGNED NOT NULL,
  `drivers_iddrivers` INT NOT NULL,
  PRIMARY KEY (`iddriver_event`, `vehicles_idvehicle`, `drivers_iddrivers`),
  INDEX `fk_vehicles_has_drivers_drivers1_idx` (`drivers_iddrivers` ASC),
  INDEX `fk_vehicles_has_drivers_vehicles1_idx` (`vehicles_idvehicle` ASC),
  CONSTRAINT `fk_vehicles_has_drivers_vehicles1`
    FOREIGN KEY (`vehicles_idvehicle`)
    REFERENCES `db_frames`.`vehicles` (`idvehicle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicles_has_drivers_drivers1`
    FOREIGN KEY (`drivers_iddrivers`)
    REFERENCES `db_frames`.`drivers` (`iddrivers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
######################################################
insert into data_frame values(default, 12, 2, 10, 1, 0, 11, 4, 1, 0, current_timestamp, 19.14, -19.14, 356612024653182, 106);


SELECT up, down, onboard, sensor_state, error, false_up, false_down, up_block, down_block from data_frame where vehicle_idvehicle = 106 order by event_date desc limit 1


################################## CONSULTAS PARA LAS TABLAS Y GRÁFICAS ######################################
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

#CONSULTA PARA SUBIDA POR ID VEHICULO EN INTERVALOS DE TIEMPO (Usar las Terminaciones :59:59 para que tome la hora completa)
SELECT b.name_vehicle, a.up, a.event_date
from data_frame as a
Left JOIN vehicles as b
on a.vehicle_idvehicle = b.idvehicle 
where b.idvehicle = 106 and event_date between "2016-03-10:16:00:00" and "2016-03-10:18:30:59"
order by event_date DESC
LIMIT 1;

SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, d.turn, max(f.up), f.down, f.onboard, f.sensor_state, max(f.up)*6 as total
from driver_events as de
INNER JOIN vehicles as v on de.vehicles_idvehicle = v.idvehicle
INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
#where f.event_date between time("14:00:00") and time("22:59:59")
group by d.name_driver

select v.name_vehicle, sum(f.up)
from vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
where idvehicle = 106