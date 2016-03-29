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
			$value = $_POST['submenu'];
			$_SESSION['submenu'] = $value;
		}

		public function mainQuery(){

			//$idvehicle = $_POST['idvehicle'];
			//$array_vehicles  = json_decode($array_vehicles, false, 512, JSON_BIGINT_AS_STRING);
    	 	$query = $this->mysqli->query("SELECT v.idvehicle, v.name_vehicle, d.name_driver, v.route, d.turn, max(f.up) as mxup, f.down, f.onboard, f.sensor_state, max(f.up) as total from driver_events as de INNER JOIN vehicles as v on de.vehicles_idvehicle = v.idvehicle INNER JOIN drivers as d on de.drivers_iddrivers = d.iddrivers INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle group by d.name_driver");
    	 	$output = '{"data":[';
    	 	while ($row = $query->fetch_array()) {
    	 		if ($output!='{"data":[') {$output .= ",";}
    	 		$output .= '{"Vid":"'.$row["idvehicle"].'",';
    	 		$output .= '"Vehiculo":"'.$row["name_vehicle"].'",';
    	 		$output .= '"Conductor":"'.$row["name_driver"].'",';
    	 		$output .= '"Ruta":"'.$row["route"].'",';
    	 		$output .= '"Turno":"'.$row["turn"].'",';
    	 		$output .= '"Subidas":"'.$row["mxup"].'",';
    	 		$output .= '"Bajadas":"'.$row["down"].'",';
    	 		$output .= '"Abordo":"'.$row["onboard"].'",';
    	 		$output .= '"Estado del sensor":"'.$row["sensor_state"].'",';
    	 		$output .= '"Total":"'.$row["total"].'"}';
    	 	}

    	 	$output .= "]}";
    	 	echo $output;
    	 	//echo json_encode($output, JSON_NUMERIC_CHECK);

    	 }

		public function sessionToken(){
			session_start();
			echo $_SESSION['token'];
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
	elseif ($_POST['type']=="reportes") {
		$instance->reports();
	}
	elseif ($_POST['type']=="logout") {
		$instance->logout();
	}
	else{
		echo "Error al llamar a la funcion";
	}
	
 ?>