CREATE TABLE IF NOT EXISTS `vehicle3` (
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