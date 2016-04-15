<?php 
	require_once "conexion.php";

	class Model extends Conexion{

		public function _construct(){
			parent:: _construct();
		}

		public function login(){
			$login = $_POST['login'];
			$token = $_POST['token'];
				if ($login == "Valid") {
					session_start();
					$_SESSION['token'] = $token;
					$_SESSION['conectado']=true;
					echo $_SESSION['token'];
				} 
				else {
					echo "Usuario y contraseña equivocados";
				}
		}

		public function reports(){
			session_start();
			$value = $_POST['submenu'];
			$_SESSION['submenu'] = $value;
		}

		public function insertDriver(){
			$driverJson = $_POST['info'];
			$driverData = json_decode($driverJson, true);
			$name = utf8_decode($driverData['nombre']);
			$ap = utf8_decode($driverData['apellido']);
			$iduser = $driverData['userId'];
			$idturn = $driverData['shiftId'];
			$active = 0;

			$name = $this->mysqli->real_escape_string($name);
			$ap = $this->mysqli->real_escape_string($ap);

			if ($name != "" && $ap != "" && $iduser != 0 && $idturn !=0) {
				$query = $this->mysqli->query("SELECT name_driver, ap_driver from drivers WHERE name_driver='$name' and ap_driver='$ap' and users_idusers='$iduser'");
				if ($query->num_rows < 1) {
					if ($this->mysqli->query("INSERT INTO drivers values (default, '$name', '$ap','$active', current_timestamp, '$iduser')")) {
						echo "Se ingreso el conductor---";
						$iddrivers = $this->mysqli->insert_id;
						$query2 = $this->mysqli->query("SELECT * from drivers WHERE name_driver = '$name' and users_idusers = '$iduser' ORDER BY date_driver DESC LIMIT 1");
						echo $iddrivers;
						if ($this->mysqli->query("INSERT INTO driver_turn values ('$iddrivers', '$idturn')")) {
							echo "Se guardo correcto";
						}
						else{
							echo "No se guardo, hubo algun error";
						}
					}
					else{
						echo "No se ingreso el conductor";
					}
				}
				else{
					echo "Ya existe un conductor con ese nombre";
				}
			}
			else{
				echo "Error al ingresa valores";
			}
		}

		public function insertTurn(){
			$name_turn = utf8_decode($_POST['turno']);
			$start_turn = $_POST['inicio'];
			$end_turn = $_POST['fin'];
			$iduser = $_POST['userid'];

			$name_turn = $this->mysqli->real_escape_string($name_turn);
			$query = "INSERT INTO turn values(default, '$name_turn', '$start_turn', '$end_turn', current_timestamp, '$iduser')";
			if ($this->mysqli->query($query)) {
				echo "Se guardo el turno";
				//echo $lastId = $this->mysqli->insert_id;
			}
			else{
				echo "No se guardo el turno";
			}
		}

		public function insertRoute(){
			$infoJ = $_POST["infoRuta"];
			$info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $info->vid;
			$name_route = utf8_decode($info->rutaNom);
			$name_start = utf8_decode($info->nomInicio);
			$start_lat = $info->ubicacion->inicio->lat;
			$start_lon = $info->ubicacion->inicio->lon;
			$name_end = utf8_decode($info->nomFin);
			$end_lat = $info->ubicacion->fin->lat;
			$end_lon = $info->ubicacion->fin->lon;
			$iduser = $info->userId;

			$name_route = $this->mysqli->real_escape_string($name_route);
			$name_start = $this->mysqli->real_escape_string($name_start);
			$name_end = $this->mysqli->real_escape_string($name_end);

			if ($name_route != "" && $name_start != "" && $name_end != "") {
				//$query = $this->mysqli->query("SELECT * FROM route WHERE name_route = '$name_route' and name_start = '$name_start' and name_end = '$name_end' ");
				$query = $this->mysqli->query("SELECT * FROM route WHERE name_route = '$name_route' and users_idusers = '$iduser'");
				if ($query->num_rows < 1) {
					if ($this->mysqli->query("INSERT INTO route values (default, '$name_route', '$name_start', '$start_lat', '$start_lon', '$name_end', '$end_lat', '$end_lon', current_timestamp, '$iduser')")) {
						echo "Se ingreso la ruta";
						$idroute = $this->mysqli->insert_id;
						if ($this->mysqli->query("INSERT INTO vehicle_route values('$vid', '$idroute')")) {
							echo "Se asigno la ruta al vehiculo";
						}
						else{
							echo "No se asigno la ruta al vehiculo";
						}

					}
					else{
						echo "No se ingreso la ruta";
					}
				}
				else{
					echo "Ya existe una ruta con ese nombre";
				}
			}
			else{
				echo "Ocurrio algún error";
			}
		}

		public function insertRate(){
			$infoJ = $_POST['tarifa'];
			$info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $info->vid;
			$iduser = $info->userId;
		}

		public function updateDriver(){
			$driverJson = $_POST['info'];
			$driverData = json_decode($driverJson, true);
			$name = utf8_decode($driverData['nombre']);
			$ap = utf8_decode($driverData['apellido']);
			$idturn = $driverData['shiftId'];
			$iddriver = $driverData['cid'];
			$active = 0;

			if ($name != "" && $ap != "") {
				if ($this->mysqli->query("UPDATE drivers SET name_driver = '$name', ap_driver ='$ap' WHERE iddrivers = '$iddriver' ")) {
					if ($idturn !=0) {
						if ($this->mysqli->query("UPDATE driver_turn SET drivers_iddrivers = '$iddriver', idturn_driver = '$idturn' ")) {
							echo "Se actualizo el turno";
						}
						else{
							echo "No se actualizo el turno";
						}
					}
					else{
						echo "No hay cambio en el turno";
					}
				}
				else{
					echo "No se actualizo el registro";
				}

			}
			else{
				echo "Error en los datos de entrada";
			}
		}

		public function updateTurn(){
			$name_turn = utf8_decode($_POST['turno']);
			$start_turn = $_POST['inicio'];
			$end_turn = $_POST['fin'];
			$idturn = $_POST['idturn'];
			if ($name_turn != "" && $start_turn != "" && $end_turn != "" && $idturn != 0) {
				if ($this->mysqli->query("UPDATE turn SET name_turn = '$name_turn', start_turn = '$start_turn', $end_turn = '$end_turn' WHERE idturn='$idturn_driver'")) {
					echo "Se actualizo el turno";
				}
				else{
					echo "No se actualizo el turno";
				}
			}
		}

		public function saveDriver(){
			$driverJson = $_POST['info'];
			$driverData = json_decode($driverJson, true);
			$name = utf8_decode($driverData["nombre"]);
			$ap = utf8_decode($driverData["apellido"]);
			$start_turn = $driverData['inicio'];
			$end_turn = $driverData['fin'];
			$iduser = $driverData['userId'];
			$active = 0;
			//$name_driver = "Guillermo";
			$query3 = $this->mysqli->query("SELECT name_driver, ap_driver from drivers WHERE name_driver='$name' and ap_driver='$ap' and users_idusers='$iduser'");
				if ($query3->num_rows < 1) {
					if ($this->mysqli->query("INSERT INTO drivers values (default, '$name', '$ap','$active', current_timestamp, '$iduser')")) {
						echo "Se ingreso el Conductor";
						$query = $this->mysqli->query("SELECT *  from drivers WHERE name_driver = '$name' and users_idusers='$iduser' ORDER BY date_driver DESC LIMIT 1");
						//print_r($row = $query->fetch_array());
						while ($row = $query->fetch_array()) {
							$iddrivers = $row['iddrivers'];
							if ($this->mysqli->query("INSERT INTO turn values (default, null, '$start_turn', '$end_turn', current_timestamp)")) {
								echo "Se ingreso el turno";
								$query1 = $this->mysqli->query("SELECT idturn_driver, start_turn, end_turn FROM turn ORDER BY date_turn DESC LIMIT 1");
								//print_r($query1);
								while ($row1 = $query1->fetch_array()) {
									$idturn = $row1['idturn_driver'];
									if ($this->mysqli->query("INSERT INTO driver_turn values ('$iddrivers', '$idturn')")) {
										echo "Se ingreso el turno correspondiente";
									}
									else{
										echo "No se ingreso el turno correspondiente";
									}
								}
							}
							else{
								$query1 = $this->mysqli->query("SELECT idturn_driver, start_turn, end_turn FROM turn ORDER BY date_turn DESC LIMIT 1");
							}
						}

					}
					else{
						echo "No se ingreso el Conductor";
					}
				}
				
				else{
					echo "Ya existe un conductor con ese nombre";
				}

			//echo "No esta funcionando";
		}

		public function mainQuery(){

			$userIdJson = $_POST['userId'];
			$userId  = json_decode($userIdJson, false, 512, JSON_BIGINT_AS_STRING);

			$query2 = $this->mysqli->query("SELECT iddrivers, name_driver FROM drivers WHERE active = 0 and users_idusers = '$userId'");
			//$query2 = $this->mysqli->query("SELECT name_driver FROM drivers WHERE active = 0");
			$query3 = $this->mysqli->query("SELECT * FROM (SELECT d.iddrivers, v.idvehicle, d.name_driver, de.date_driver_event, d.active FROM driver_events as de 
			INNER JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicle 
			INNER JOIN drivers as d on d.iddrivers = de.drivers_iddrivers 
			ORDER BY de.date_driver_event ASC) conductor
			WHERE active = 1
			GROUP BY iddrivers");
			$query4 = $this->mysqli->query("SELECT * FROM (SELECT v.idvehicle, v.name_vehicle, f.up as up, f.down as down, f.onboard as onboard, f.sensor_state as sensor, f.up*6 as total, f.event_date as fecha
			FROM vehicles as v
			INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
			WHERE date(event_date) = curdate() ORDER BY f.event_date DESC) evento GROUP BY idvehicle");

    	 	$output2 = '{"Inactivo":[';
    	 	while ($row2 = $query2->fetch_array()) {
    	 		if ($output2!='{"Inactivo":[') {$output2 .= ",";}
    	 		//$output2 .= '{"nombre":"'.utf8_encode($row2["name_driver"]).'"}';
    	 		$output2 .= '{"nombre":"'.utf8_encode($row2["name_driver"]).'",';
    	 		$output2 .= '"driverid":"'.$row2["iddrivers"].'"}';
    	 	}
    	 	$output2 .= "],";

    	 	$output3 = '"Driver_Vehicle":[';
    	 	while ($row3 = $query3->fetch_array()) {
    	 		if ($output3!='"Driver_Vehicle":[') {$output3 .= ",";}
    	 		$output3 .= '{"Vid":"'.$row3["idvehicle"].'",';
    	 		$output3 .= '"Cid":"'.$row3['iddrivers'].'",';
    	 		$output3 .= '"Conductor":"'.utf8_encode($row3["name_driver"]).'"}';
    	 	}
    	 	$output3 .= "],";

    	 	$output4 = '"Frame_Vehicle":[';
    	 	while ($row4 = $query4->fetch_array()) {
    	 		if ($output4!='"Frame_Vehicle":[') {$output4 .= ",";}
    	 		$output4 .= '{"Vid":"'.$row4["idvehicle"].'",';
    	 		$output4 .= '"Subidas":"'.$row4["up"].'",';
    	 		$output4 .= '"Bajadas":"'.$row4["down"].'",';
    	 		$output4 .= '"Abordo":"'.$row4["onboard"].'",';
    	 		$output4 .= '"Sensor":"'.$row4["sensor"].'",';
    	 		$output4 .= '"Ingreso":"'.$row4["total"].'",';
    	 		$output4 .= '"Fecha":"'.$row4["fecha"].'"}';
    	 	}
    	 	$output4 .= "]}";
    	 	
    	 	//echo $output.$output2.$output3.$output4;
    	 	echo $output2.$output3.$output4;
    	}

		public function sessionToken(){
			session_start();
			echo $_SESSION['token'];
		}

		public function allDrivers(){
			$userId = $_POST['userId'];
			//$userId  = json_decode($userIdJson, false, 512, JSON_BIGINT_AS_STRING);
			$query = $this->mysqli->query("SELECT *  FROM drivers WHERE users_idusers = '$userId'");

    	 	$output = '{"infoDriver":[';
    	 	while ($row = $query->fetch_array()) {
    	 		if ($output!='{"infoDriver":[') {$output .= ",";}
    	 		$output .= '{"id":"'.$row["iddrivers"].'",';
    	 		$output .= '"name":"'.utf8_encode($row["name_driver"]).'",';
    	 		$output .= '"ap_driver":"'.utf8_encode($row["ap_driver"]).'",';
    	 		$output .= '"active":"'.$row["active"].'",';
    	 		$output .= '"fecha":"'.$row["date_driver"].'"}';
    	 	}
    	 	$output .= "]}";
    	 	echo $output;
		}

		public function allTurns(){
			$userid = $_POST['userId'];
			//$userid = 8;
			$query = $this->mysqli->query("SELECT *  FROM turn WHERE users_idusers = '$userid'");
    	 	$output = '{"infoTurn":[';
    	 	while ($row = $query->fetch_array()) {
    	 		if ($output!='{"infoTurn":[') {$output .= ",";}
    	 		$output .= '{"id":"'.$row["idturn_driver"].'",';
    	 		$output .= '"name":"'.utf8_encode($row["name_turn"]).'",';
    	 		$output .= '"inicio":"'.$row["start_turn"].'",';
    	 		$output .= '"fin":"'.$row["end_turn"].'",';
    	 		$output .= '"fecha":"'.$row["date_turn"].'"}';
    	 	}
    	 	$output .= "]}";
    	 	echo $output;
		}

		public function allRoutes(){
			$userid = $_POST["userId"];
			//$userid = 8;
			$query = $this->mysqli->query("SELECT * FROM route WHERE users_idusers = '$userid'");
			$output = '{"infoRoute":[';
			while ($row = $query->fetch_array()) {
    	 		if ($output!='{"infoRoute":[') {$output .= ",";}
    	 		$output .= '{"id":"'.$row["idroute"].'",';
    	 		$output .= '"name":"'.utf8_encode($row["name_route"]).'",';
    	 		$output .= '"nomInicio":"'.utf8_encode($row["name_start"]).'",';
    	 		$output .= '"nomFin":"'.utf8_encode($row["name_end"]).'",';
    	 		$output .= '"ubicacion": { "incio": { "lat":'.$row['start_lat'].',"lat":'.$row['start_lon'].'},';
    	 		$output .= '"fin": { "lat":'.$row['end_lat'].',"lon":'.$row['end_lon'].'}},';
    	 		$output .= '"fecha":"'.$row["date_route"].'"}';
    	 	}
    	 	$output .= "]}";
    	 	echo $output;
		}

		public function assignDriver(){
			/*$assignJson = $_POST['asignar'];
			$assign = json_decode($assignJson, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $assign->vid;
			$iddrivers = $assign->iddrivers;*/
			$vid = $_POST['vid'];
			$iddrivers = $_POST['cid'];

			if ($this->mysqli->query("INSERT INTO driver_events values(default, '$vid', '$iddrivers', current_timestamp)")) {
				echo "Se asigno el Conductor";
				if ($this->mysqli->query("UPDATE drivers SET active = 1 WHERE iddrivers = '$iddrivers' ")) {
					echo "Se cambio el estado a activo";
				}
				else{
					echo "Error al cambiar estado del conductor";
				}
			}
			else{
				echo "No se asigno el Conductor";
			}
		}

		public function deallocateDriver(){
			$iddrivers = $_POST['cid'];

			if ($this->mysqli->query("UPDATE drivers SET active = 0 WHERE iddrivers = '$iddrivers' ")) {
				echo "Se cambio el estado a Inactivo";
			}
			else{
				echo "Error al cambiar estado del conductor";
			}
		}

		public function vehicleReport(){
			$infoJ = $_POST['info'];
			$info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
			$id = $info->id;
			$start = $info->tfin;
			$end = $info->tini;

			/*$id = 17;
			$start = '2016-04-13';
			$end = '2016-04-14';*/

			$query = $this->mysqli->query("SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
			FROM data_frame as f 
			LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
			LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
			LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
			LEFT JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
			LEFT JOIN route as r on r.idroute = vr.route_idroute
			WHERE date(f.event_date) between '$start' and '$end' and v.idvehicle = '$id' and date(f.event_date) = date(de.date_driver_event)
			ORDER BY f.event_date DESC");

			if ($query->num_rows > 1) {
				$output = '{"infoReport":[';
	    	 	while ($row = $query->fetch_array()) {
	    	 		if ($output!='{"infoReport":[') {$output .= ",";}
	    	 		$output .= '{"id":"'.$row["idvehicle"].'",';
	    	 		//$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
	    	 		//$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre1":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre2":"'.utf8_encode($row["name_driver"]).'",';
	    	 		$output .= '"nombre3":"'.utf8_encode($row["name_route"]).'",';
	    	 		$output .= '"subidas":"'.$row["up"].'",';
	    	 		$output .= '"bajadas":"'.$row["down"].'",';
	    	 		$output .= '"abordo":"'.$row["onboard"].'",';
	    	 		$output .= '"ingresos":"'.$row["ingreso"].'",';
	    	 		$output .= '"lat":"'.$row["lat"].'",';
	    	 		$output .= '"lon":"'.$row["lon"].'",';
	    	 		$output .= '"fecha":"'.$row["event_date"].'"}';
	    	 	}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
			else{
				$output = '{"infoReport":[';
	    	 	//while ($row = $query->fetch_array()) {
	    	 	//	if ($output!='{"infoReport":[') {$output .= ",";}
	    	 		$output .= '{"id":" ",';
	    	 		//$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
	    	 		//$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre1":" ",';
	    	 		$output .= '"nombre2":" ",';
	    	 		$output .= '"nombre3":" ",';
	    	 		$output .= '"subidas":" ",';
	    	 		$output .= '"bajadas":" ",';
	    	 		$output .= '"abordo":" ",';
	    	 		$output .= '"ingresos":" ",';
	    	 		$output .= '"lat":" ",';
	    	 		$output .= '"lon":" ",';
	    	 		$output .= '"fecha":" ",';
	    	 		$output .= '"mensaje":"No información de vehiculos"}';
	    	 	//}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
		}

		public function driverReport(){
			$infoJ = $_POST['info'];
			$info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
			$id = $info->id;
			$start = $info->tfin;
			$end = $info->tini;

			/*$id = 3;
			$start = '2016-04-13';
			$end = '2016-04-14';*/

			$query = $this->mysqli->query("SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
			FROM data_frame as f 
			LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
			LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
			LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
			LEFT JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
			LEFT JOIN route as r on r.idroute = vr.route_idroute
			WHERE date(f.event_date) between '$start' and '$end' and d.iddrivers = '$id' and date(f.event_date) = date(de.date_driver_event)
			ORDER BY f.event_date DESC");

			if ($query->num_rows > 1) {
				$output = '{"infoReport":[';
	    	 	while ($row = $query->fetch_array()) {
	    	 		if ($output!='{"infoReport":[') {$output .= ",";}
	    	 		$output .= '{"id":"'.$row["idvehicle"].'",';
	    	 		//$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
	    	 		//$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre1":"'.utf8_encode($row["name_driver"]).'",';
	    	 		$output .= '"nombre2":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre3":"'.utf8_encode($row["name_route"]).'",';
	    	 		$output .= '"subidas":"'.$row["up"].'",';
	    	 		$output .= '"bajadas":"'.$row["down"].'",';
	    	 		$output .= '"abordo":"'.$row["onboard"].'",';
	    	 		$output .= '"ingresos":"'.$row["ingreso"].'",';
	    	 		$output .= '"lat":"'.$row["lat"].'",';
	    	 		$output .= '"lon":"'.$row["lon"].'",';
	    	 		$output .= '"fecha":"'.$row["event_date"].'"}';
	    	 	}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
			else{
				$output = '{"infoReport":[';
	    	 	//while ($row = $query->fetch_array()) {
	    	 	//	if ($output!='{"infoReport":[') {$output .= ",";}
	    	 		$output .= '{"id":" ",';
	    	 		//$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
	    	 		//$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre1":" ",';
	    	 		$output .= '"nombre2":" ",';
	    	 		$output .= '"nombre3":" ",';
	    	 		$output .= '"subidas":" ",';
	    	 		$output .= '"bajadas":" ",';
	    	 		$output .= '"abordo":" ",';
	    	 		$output .= '"ingresos":" ",';
	    	 		$output .= '"lat":" ",';
	    	 		$output .= '"lon":" ",';
	    	 		$output .= '"fecha":" ",';
	    	 		$output .= '"mensaje":"No información de Conductores"}';
	    	 	//}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
		}

		public function routeReport(){
			$infoJ = $_POST['info'];
			$info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
			$id = $info->id;
			$start = $info->tfin;
			$end = $info->tini;

			/*$id = 2;
			$start = '2016-04-13';
			$end = '2016-04-14';*/

			$query = $this->mysqli->query("SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
			FROM data_frame as f 
			LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
			LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
			LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
			LEFT JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
			LEFT JOIN route as r on r.idroute = vr.route_idroute
			WHERE date(f.event_date) between '$start' and '$end' and r.idroute = '$id' and date(f.event_date) = date(de.date_driver_event)
			ORDER BY f.event_date DESC");

			if ($query->num_rows > 1) {
				$output = '{"infoReport":[';
	    	 	while ($row = $query->fetch_array()) {
	    	 		if ($output!='{"infoReport":[') {$output .= ",";}
	    	 		$output .= '{"id":"'.$row["idvehicle"].'",';
	    	 		//$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
	    	 		//$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre1":"'.utf8_encode($row["name_route"]).'",';
	    	 		$output .= '"nombre2":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre3":"'.utf8_encode($row["name_driver"]).'",';
	    	 		$output .= '"subidas":"'.$row["up"].'",';
	    	 		$output .= '"bajadas":"'.$row["down"].'",';
	    	 		$output .= '"abordo":"'.$row["onboard"].'",';
	    	 		$output .= '"ingresos":"'.$row["ingreso"].'",';
	    	 		$output .= '"lat":"'.$row["lat"].'",';
	    	 		$output .= '"lon":"'.$row["lon"].'",';
	    	 		$output .= '"fecha":"'.$row["event_date"].'"}';
	    	 	}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
			else{
				$output = '{"infoReport":[';
	    	 	//while ($row = $query->fetch_array()) {
	    	 	//	if ($output!='{"infoReport":[') {$output .= ",";}
	    	 		$output .= '{"id":" ",';
	    	 		//$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
	    	 		//$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
	    	 		$output .= '"nombre1":" ",';
	    	 		$output .= '"nombre2":" ",';
	    	 		$output .= '"nombre3":" ",';
	    	 		$output .= '"subidas":" ",';
	    	 		$output .= '"bajadas":" ",';
	    	 		$output .= '"abordo":" ",';
	    	 		$output .= '"ingresos":" ",';
	    	 		$output .= '"lat":" ",';
	    	 		$output .= '"lon":" ",';
	    	 		$output .= '"fecha":" ",';
	    	 		$output .= '"mensaje":"No información de las Rutas"}';
	    	 	//}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
		}

		public function logout(){
			session_start();
			session_unset();
			session_destroy();
		}

		public function sessionReport(){
			session_start();
			echo $_SESSION['submenu'];
		}
	}

	$instance = new Model();
	//$instance->driverReport();
	
	if ($_POST['type']=="login") {
		$instance->login();
	}
	elseif ($_POST['type']=="token") {
		$instance->sessionToken();
	}
	elseif ($_POST['type']=="submenu") {
		$instance->sessionReport();
	}
	elseif ($_POST['type']=="report") {
		$instance->reports();
	}
	elseif ($_POST['type']=="driver") {
		$instance->insertDriver();
	}
	elseif ($_POST['type']=="turno") {
		$instance->insertTurn();
	}
	elseif ($_POST['type']=="ruta") {
		$instance->insertRoute();
	}
	//elseif ($_POST['type']=="driver") {
	//	$instance->insertDriver();
	//}
	//elseif ($_POST['type']=="driver") {
	//	$instance->insertDriver();
	//}
	elseif ($_POST['type']=="infoDriver") {
		//$instance->vehicleReport();
		$instance->allDrivers();
	}
	elseif ($_POST['type']=="infoTurn"){
		$instance->allTurns();
	}
	elseif ($_POST['type']=="infoRoutes"){
		$instance->allRoutes();
	}
	elseif ($_POST['type']=="repVehiculo") {
		$instance->vehicleReport();
	}
	elseif ($_POST['type']=="repConductor") {
		$instance->driverReport();
	}
	elseif ($_POST['type']=="repRuta") {
		$instance->routeReport();
	}
	elseif ($_POST['type']=="asignar") {
		$instance->assignDriver();
	}
	elseif ($_POST['type']=="consulta") {
		$instance->mainQuery();
	}
	elseif ($_POST['type']=="logout") {
		$instance->logout();
	}
	else{
		echo "Error al llamar a la funcion";
	}
 ?>