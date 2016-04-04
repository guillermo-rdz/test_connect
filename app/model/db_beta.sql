SET FOREIGN_KEY_CHECKS=0;
TRUNCATE drivers;
TRUNCATE turn;
TRUNCATE driver_events; 
TRUNCATE driver_turn;
SET FOREIGN_KEY_CHECKS=1;
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

SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, max(f.up), f.down, f.onboard, f.sensor_state, max(f.up)*6 as total
from driver_events as de
INNER JOIN vehicles as v on de.vehicles_idvehicle = v.idvehicle
INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
#where f.event_date between time("14:00:00") and time("22:59:59")
group by d.name_driver

select v.name_vehicle, de.vehicles_idvehicles
from driver_events as de
INNER JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicles


SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, max(f.up), max(f.down), max(f.onboard), max(f.sensor_state), max(f.up), t.name_turn
FROM driver_events as de
INNER JOIN vehicles as v on de.vehicles_idvehicles = v.idvehicle
INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers
INNER JOIN data_frame as f on f.vehicle_idvehicle = v.idvehicle
INNER JOIN driver_turn as dt on dt.drivers_iddrivers = d.iddrivers
INNER JOIN turn as t on dt.turn_driver_idturn_driver = t.idturn_driver
#WHERE f.event_date between "2016-04-01:17:00:00" and "2016-04-01:18:00:00"
GROUP BY d.name_driver
ORDER BY f.event_date DESC


SELECT v.name_vehicle, d.name_driver, f.up, f.event_date
from vehicles  as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
INNER JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicles
INNER JOIN drivers as d on iddrivers = de.drivers_iddrivers
GROUP BY v.name_vehicle
ORDER BY f.event_date