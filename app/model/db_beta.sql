SET FOREIGN_KEY_CHECKS=0;
TRUNCATE drivers;
TRUNCATE turn;
TRUNCATE driver_events; 
TRUNCATE driver_turn;
SET FOREIGN_KEY_CHECKS=1;

######################################################
insert into data_frame values(default, 12, 2, 10, 1, 0, 11, 4, 1, 0, current_timestamp, 19.14, -19.14, 356612024653182, 106);


SELECT up, down, onboard, sensor_state, error, false_up, false_down, up_block, down_block FROM data_frame where vehicle_idvehicle = 106 order BY event_date desc limit 1


################################## CONSULTAS PARA LAS TABLAS Y GRÁFICAS ######################################
SELECT vehicles.name_vehicle, data_frame.up, data_frame.down, data_frame.aboart,data_frame.false_up, data_frame.false_down, data_frame.event_date, data_frame.lat, data_frame.lon FROM data_frame INNER JOIN vehicles on data_frame.vehicle_idvehicle = vehicles.idvehicle where vehicles.idvehicle = 106 order BY event_date

#COUNSULTA PARA TODOS LOS CAMPOS PASANDOLE COMO PARAMETRO idvehicle y event_date(FECHA)
SELECT v.name_vehicle, f.up, f.down, f.onboard, f.false_up, f.false_down, f.event_date, f.lat, f.lon 
FROM data_frame as f
INNER JOIN vehicles as v
on f.vehicle_idvehicle = v.idvehicle 
GROUP BY v.idvehicle
ORDER BY event_date DESC;


#CONSULTA PARA SUBIDA POR ID VEHICULO, DÍA Y HORA 
SELECT b.name_vehicle, sum(a.up), a.event_date
FROM data_frame as a
RIGHT JOIN vehicles as b
on a.vehicle_idvehicle = b.idvehicle 
where b.idvehicle = 106 and date(event_date) = "2016-03-10" and hour(event_date)= "16"
order BY event_date;

#CONSULTA PARA SUBIDA POR ID VEHICULO EN INTERVALOS DE TIEMPO (Usar las Terminaciones :59:59 para que tome la hora completa)
SELECT b.name_vehicle, a.up, a.event_date
FROM data_frame as a
LEFT JOIN vehicles as b
on a.vehicle_idvehicle = b.idvehicle 
where b.idvehicle = 106 and event_date between "2016-03-10:16:00:00" and "2016-03-10:18:30:59"
order BY event_date DESC
LIMIT 1;

SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, max(f.up), f.down, f.onboard, f.sensor_state, max(f.up)*6 as total
FROM driver_events as de
INNER JOIN vehicles as v on de.vehicles_idvehicle = v.idvehicle
INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
#where f.event_date between time("14:00:00") and time("22:59:59")
group BY d.name_driver


SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, max(f.up), f.down, max(f.onboard), max(f.sensor_state), f.up, t.name_turn
FROM driver_events as de
INNER JOIN vehicles as v on de.vehicles_idvehicles = v.idvehicle
INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers
INNER JOIN data_frame as f on f.vehicle_idvehicle = v.idvehicle
INNER JOIN driver_turn as dt on dt.drivers_iddrivers = d.iddrivers
INNER JOIN turn as t on dt.turn_driver = t.idturn_driver
WHERE date(f.event_date) = curdate()
GROUP BY v.idvehicle
ORDER BY f.event_date DESC

SELECT v.idvehicle, max(f.up), max(f.down), max(f.onboard), max(f.sensor_state), max(f.up)
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
#WHERE date(event_date) = curdate() AND v.idvehicle = 106
WHERE f.event_date between DATE_sub(curdate(), INTERVAL 7 DAY) and curdate() AND v.idvehicle = 106
#GROUP BY f.event_date
ORDER BY f.event_date DESC

###################### CONSULTA REPORTE DEL DÍA ###############################
SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.sensor_state, f.up, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date (f.event_date) = curdate() and v.idvehicle in (17)
ORDER BY f.event_date DESC

##################### CONSULTA REPORTE POR SEMANA #############################
SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.sensor_state, f.up, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(f.event_date) between DATE_sub(curdate(), INTERVAL 7 DAY) and curdate() AND v.idvehicle = 106
ORDER BY f.event_date DESC

##################### CONSULTA REPORTE POR 15 DÍAS ############################
SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.sensor_state, f.up, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(f.event_date) between DATE_sub(curdate(), INTERVAL 15 DAY) and curdate() AND v.idvehicle = 106
ORDER BY f.event_date DESC

#################### CONSULTA REPORTE POR MES ##################################
SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.sensor_state, f.up, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(f.event_date) between DATE_sub(curdate(), INTERVAL 1 MONTH) and curdate() AND v.idvehicle = 106
ORDER BY f.event_date DESC

#################### CONSULTA REPORTE PERSONALIZADO ############################
SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.up*6, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(f.event_date) between "2016-04-11" and "2016-04-12" AND v.idvehicle = 106
ORDER BY f.event_date DESC

SELECT d.iddrivers, d.name_driver, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.up*6,f.event_date
FROM driver_events as de
INNER JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
INNER JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicle
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(f.event_date) between "2016-04-11" and "2016-04-13" and d.iddrivers = 1
ORDER BY f.event_date DESC
#SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.sensor_state, f.up*6, f.event_date
#FROM vehicles as v
#INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
#WHERE date(f.event_date) between "2016-04-11" and "2016-04-12" AND v.idvehicle = 106
#ORDER BY f.event_date DESC



SELECT v.idvehicle, v.name_vehicle, d.name_driver, f.up, f.down, f.onboard, f.up*6, t.name_turn
FROM driver_events as de
RIGHT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
RIGHT JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicles
INNER JOIN data_frame as f on f.vehicle_idvehicle = v.idvehicle
INNER JOIN driver_turn as dt on d.iddrivers = dt.drivers_iddrivers
INNER JOIN turn as t on t.idturn_driver = dt.drivers_iddrivers
WHERE d.name_driver = "Guillermo"
ORDER BY f.event_date DESC

SELECT v.idvehicle, v.name_vehicle, f.up as up, f.down as down, f.onboard as onboard, f.sensor_state as sensor, f.up*6 as total, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(event_date) = curdate() and v.idvehicle = 17
#GROUP BY v.idvehicle
ORDER BY event_date DESC LIMIT 1

SELECT * FROM (SELECT v.idvehicle, v.name_vehicle, f.up as up, f.down as down, f.onboard as onboard, f.sensor_state as sensor, f.up*6 as total, f.event_date
FROM vehicles as v
INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
WHERE date(event_date) = curdate() ORDER BY f.event_date DESC) evento GROUP BY idvehicle

SELECT * FROM (SELECT d.iddrivers, v.idvehicle, d.name_driver, de.date_driver_event FROM driver_events as de 
INNER JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicle 
INNER JOIN drivers as d on d.iddrivers = de.drivers_iddrivers 
ORDER BY de.date_driver_event DESC) conductor GROUP BY iddrivers;


SELECT v.idvehicle as vid, v.name_vehicle as vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.up as ingreso, f.event_date as fecha
	FROM vehicles as v
	INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
	WHERE date (f.event_date) between '2016-04-12' and '2016-04-14' and v.idvehicle = '17'
	ORDER BY f.event_date DESC

################################### Consultas para vehiculos sin conductores ########################################################
SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
FROM data_frame as f 
LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
INNER JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
INNER JOIN route as r on r.idroute = vr.route_idroute
ORDER BY f.event_date DESC
################################### Consultas para rutas ############################################################################
SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
FROM data_frame as f 
RIGHT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
INNER JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
INNER JOIN route as r on r.idroute = vr.route_idroute
#WHERE v.idvehicle = 20
ORDER BY f.event_date DESC

################################### Consultas para vehiculo ########################################################################
SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
FROM data_frame as f 
LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
LEFT JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
LEFT JOIN route as r on r.idroute = vr.route_idroute
#WHERE date(f.event_date) between '2016-04-13' and '2016-04-14' and v.idvehicle = 106
ORDER BY f.event_date DESC
