<?php 
	require_once "conexion.php";

	class model extends conexion{

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
    	 	//$query = $this->mysqli->query("SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, d.turn, max(f.up) as mxup, f.down, f.onboard, f.sensor_state, max(f.up) as total from driver_events as de INNER JOIN vehicles as v on de.vehicles_idvehicle = v.idvehicle INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle group by d.name_driver");
    	 	/*$query = $this->mysqli->query("SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, max(f.up) as up, max(f.down) as down, max(f.onboard) as onboard, max(f.sensor_state) as sensor_state, max(f.up) as total, t.name_turn
			FROM driver_events as de
			INNER JOIN vehicles as v on de.vehicles_idvehicles = v.idvehicle
			INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers
			INNER JOIN data_frame as f on f.vehicle_idvehicle = v.idvehicle
			INNER JOIN driver_turn as dt on dt.drivers_iddrivers = d.iddrivers
			INNER JOIN turn as t on dt.turn_driver = t.idturn_driver
			GROUP BY d.name_driver
			ORDER BY f.event_date DESC");*/
			$query2 = $this->mysqli->query("SELECT iddrivers, name_driver FROM drivers WHERE active = 0 and users_idusers = '$userId'");
			//$query2 = $this->mysqli->query("SELECT name_driver FROM drivers WHERE active = 0");
			$query3 = $this->mysqli->query("SELECT v.idvehicle, d.name_driver 
			FROM driver_events as de 
			INNER JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicles 
			INNER JOIN drivers as d on d.iddrivers = de.drivers_iddrivers");
			$query4 = $this->mysqli->query("SELECT v.idvehicle, max(f.up) as up, max(f.down) as down, max(f.onboard) as onboard, max(f.sensor_state) as sensor, max(f.up)*6 as total
			FROM vehicles as v
			INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
			WHERE date(event_date) = curdate()
			GROUP BY v.idvehicle");

    	 	/*$output = '{"data":[';
    	 	while ($row = $query->fetch_array()) {
    	 		if ($output!='{"data":[') {$output .= ",";}
    	 		$output .= '{"Vid":"'.$row["idvehicle"].'",';
    	 		$output .= '"Vehiculo":"'.$row["name_vehicle"].'",';
    	 		$output .= '"Conductor":"'.$row["name_driver"].'",';
    	 		$output .= '"Ruta":"'.$row["route"].'",';
    	 		$output .= '"Subidas":"'.$row["up"].'",';
    	 		$output .= '"Bajadas":"'.$row["down"].'",';
    	 		$output .= '"Abordo":"'.$row["onboard"].'",';
    	 		$output .= '"Estado del sensor":"'.$row["sensor_state"].'",';
    	 		$output .= '"Total":"'.$row["total"].'",';
    	 		$output .= '"Turno":"'.$row["name_turn"].'"}';
    	 	}

    	 	$output .= "],";*/

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
    	 		$output4 .= '"Ingresos":"'.$row4["total"].'"}';
    	 	}
    	 	$output4 .= "]}";
    	 	
    	 	//echo $output.$output2.$output3.$output4;
    	 	echo $output2.$output3.$output4;

    	 }

		public function sessionToken(){
			session_start();
			echo $_SESSION['token'];
		}

		public function vehicleReport(){
			$infoJ = $_POST['info'];
			$info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $info->vid;
			$start = $info->tini;
			$end = $info->tfin;

			$query = $this->mysqli->query("SELECT v.idvehicle, v.name_vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.sensor_state, f.up as ingreso, f.event_date
			FROM vehicles as v
			INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
			WHERE date (f.event_date) between '$start' and '$end' and v.idvehicle = '$vid'
			ORDER BY f.event_date DESC");
			if ($query->num_rows > 1) {
				$output = '{"infoVehicle":[';
	    	 	while ($row = $query->fetch_array()) {
	    	 		if ($output!='{"infoVehicle":[') {$output .= ",";}
	    	 		$output .= '{"vid":"'.utf8_encode($row["idvehicle"]).'",';
	    	 		$output .= '"vehiculo":"'.$row["name_vehicle"].'",';
	    	 		$output .= '"lat":"'.$row["lat"].'",';
	    	 		$output .= '"lon":"'.$row["lon"].'",';
	    	 		$output .= '"up":"'.$row["up"].'",';
	    	 		$output .= '"down":"'.$row["down"].'",';
	    	 		$output .= '"onboard":"'.$row["onboard"].'",';
	    	 		$output .= '"ingreso":"'.$row["ingreso"].'",';
	    	 		$output .= '"fecha":"'.$row["event_date"].'"}';
	    	 	}
	    	 	$output .= "]}";
	    	 	echo $output;
			}
			else{
				$output2 = '{"infoVehicle":[';
	    	 	
	    	 		if ($output2!='{"infoVehicle":[') {$output2 .= ",";}
	    	 		$output2 .= '{"vid":"'."0".'",';
	    	 		$output2 .= '"vehiculo":"'."0".'",';
	    	 		$output2 .= '"lat":"'."0".'",';
	    	 		$output2 .= '"lon":"'."0".'",';
	    	 		$output2 .= '"up":"'."0".'",';
	    	 		$output2 .= '"down":"'."0".'",';
	    	 		$output2 .= '"onboard":"'."0".'",';
	    	 		$output2 .= '"ingreso":"'."0".'",';
	    	 		$output2 .= '"fecha":"'."0000-00-00 00:00:00".'"}';
	    	 	
	    	 	$output2 .= "]}";
	    	 	echo $output2;
			}

		}


		public function allDrivers(){
			$userId = $_POST['userId'];
			//$userId  = json_decode($userIdJson, false, 512, JSON_BIGINT_AS_STRING);
			$query = $this->mysqli->query("SELECT *  FROM drivers WHERE users_idusers = '$userId'");

    	 	$output = '{"infoDriver":[';
    	 	while ($row = $query->fetch_array()) {
    	 		if ($output!='{"infoDriver":[') {$output .= ",";}
    	 		$output .= '{"id":"'.$row["iddrivers"].'",';
    	 		$output .= '"name_driver":"'.utf8_encode($row["name_driver"]).'",';
    	 		$output .= '"ap_driver":"'.utf8_encode($row["ap_driver"]).'",';
    	 		$output .= '"active":"'.$row["active"].'",';
    	 		$output .= '"date_driver":"'.$row["date_driver"].'"}';
    	 	}
    	 	$output .= "]}";
    	 	echo $output;
		}

		public function assignDriver(){
			$assignJson = $_POST['asignar'];
			$assign = json_decode($assignJson, true);

			if ($this->mysqli->query("INSERT INTO driver_events values(default, '$vid', '$iddrivers', current_timestamp)")) {
				echo "Se asigno el Conductor";
				$this->mysqli->query("UPDATE drivers SET active = 1 WHERE iddrivers = '$iddrivers' ");
			}
			else{
				echo "No se asigno el Conductor";
			}
		}

		public function deallocateDriver(){

		}

		public function sessionReport(){
			session_start();
			echo $_SESSION['submenu'];
		}

		public function logout(){
			session_start();
			session_unset();
			session_destroy();
		}

	}

	$instance = new model();
	//$instance->mainQuery();
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
		$instance->saveDriver();
	}
	elseif ($_POST['type']=="infoDriver") {
		//$instance->vehicleReport();
		$instance->allDrivers();
	}
	elseif ($_POST['type']=="repVehiculo") {
		$instance->vehicleReport();
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