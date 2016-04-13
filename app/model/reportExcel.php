<?php 
	date_default_timezone_set('America/Mexico_City'); 
	require_once 'lib/PHPExcel/PHPExcel.php';
	$conexion = new mysqli('localhost','root','qaz','db_frames2');
	if (mysqli_connect_errno()) {
    	printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
    	exit();
	}

	$query = "SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.up as ingreso, f.event_date
	FROM vehicles as v
	INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
	WHERE date (f.event_date) between '$start' and '$end' and v.idvehicle = '$vid'
	ORDER BY f.event_date DESC";

	$row = $conexion->query($query);

	if ($row->num_rows > 1) {
		if (PHP_SAPI == 'cli')
			die('Este archivo solo se puede ver desde un navegador web');
	}
	else{
		echo "No hay resultados";
	}
?>