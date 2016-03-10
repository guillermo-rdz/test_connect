CREATE TABLE IF NOT EXISTS `vehicle2` (
  `idvehicle` DOUBLE UNSIGNED NOT NULL,
  `name_vehicle` VARCHAR(45) NULL,
  `capacitance` INT NULL,
  `max_capacitance` INT NULL,
  `imei` VARCHAR(15) NULL,
  PRIMARY KEY (`idvehicle`))
ENGINE = InnoDB


CREATE TABLE IF NOT EXISTS `data_frame2` (
  `iddata_frame` BIGINT NOT NULL AUTO_INCREMENT,
  `up` INT NULL,
  `down` INT NULL,
  `aboart` INT NULL,
  `false_up` INT NULL,
  `false_down` INT NULL,
  `event_date` DATETIME NULL,
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `imei` DOUBLE NULL,
  `vehicle_idvehicle` DOUBLE UNSIGNED NOT NULL,
  PRIMARY KEY (`iddata_frame`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `users` (
  `iduser` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`iduser`))
ENGINE = InnoDB;