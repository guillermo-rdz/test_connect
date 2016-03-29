<?php 
	require_once "conexion.php";
	//error_reporting(E_ALL ^ E_NOTICE);
	//---------------------- Objeto de eventos ------------------------
	$json_l = $_POST['liveData'];
	$events = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
	//---------------------- Objeto de paradas ------------------------
	//$json_l = $_POST['stop'];
	//$stop_ev = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
	//---------------------- Objeto de Vehiculos -----------------------
	//$json_v = $_POST['vehiculo'];
	//$vehicles = json_decode($json_v, false, 512, JSON_BIGINT_AS_STRING);
	//---------------------- Id de los vehiculos -----------------------
	//$json_id = $_POST['vid'];
	//$vid = json_decode($json_id, false, 512, JSON_BIGINT_AS_STRING);

    class Data_frame extends conexion{
    	
    	public function _construct(){
			parent:: _construct();
		}

		public function save_Volatile($stop_ev){
			//$json_s = $_POST['stop'];
			//$stop_ev = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $stop_ev->vid;
			$lat = $stop_ev->lat;
			$lon = $stop_ev->lon;
			$eventTime = $stop_ev->eventTime;
			$type = $stop_ev->tipo;

			$query = $this->mysqli->query("INSERT INTO volatile_stop(idvolatile_stop, lat, lon, eventTime, vehicles_idvehicles) values(default, '$lat', '$lon', '$eventTime', '$vid') ON DUPLICATE KEY UPDATE vehicles_idvehicles=VALUES(vehicles_idvehicles)");
			/*$query = $this->mysql->query("SELECT lat, lon, eventTime, tipo, vehicles_idvehicles");
			while ($row = $query->fetch_array()) {
				if ($row['lat']==$lat) {
					# code...
				}
			}*/
		}

    	public function save_Frames($events){
    		//$json_l = $_POST['liveData'];
			//$events = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);

			/*$vid = $events->vid;
			$imei = $events->imei;
			$lat = $events->lat;
			$lon = $events->lon;
			$eventTime = $events->eventTime;*/
			$vid = $events->vid;
			$imei = $events->imei;
			$tx = $events->tx;
			if (empty($tx)) {
				//$tx = "0,0,0,0,0,0,0,0,0";
				echo "trama vacia";
			}
			//else{

				$tx = "0,0,0,0,0,0,0,0,0";
				//echo "condicion 2";
				$trama = explode(",", $tx);
				$up = $trama[0];
				$down = $trama[1];
				$onboard = $trama[2];
				$sensor_state = $trama[3];
				$error = $trama[4];
				$false_up = $trama[5];
				$false_down = $trama[6];
				$block_up = $trama[7];
				$block_down = $trama[8];
				echo $vid;
				$query1 = $this->mysqli->query("SELECT up, down, onboard, sensor_state, error, false_up, false_down, up_block, down_block, event_date from data_frame where vehicle_idvehicle = '$vid' order by event_date desc limit 1");
				//print_r($query1->num_rows);
				while ($row = $query1->fetch_array()) {
					if ($row['up'] == $up && $row['down']==$down && $row['onboard']==$onboard && $row['sensor_state']==$sensor_state && $row['error']==$error && $row['false_up']==$false_up && $row['false_down']==$false_down && $row['block_up']==$block_up && $row['block_down']==$block_down) {
						echo "Registros iguales";
					}
					else{
						$query2 = $this->mysqli->query("SELECT lat, lon, eventTime, tipo, vehicles_idvehicle from volatile_stop where vehicles_idvehicle = '$vid' order by eventTime desc limit 1");
						while ($row2->query2->fetch_array()) {
							$lat = $row2['lat'];
							$lon = $row2['lon'];
							$eventTime = $row2['eventTime'];
							if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$false_up', '$false_down', '$error', '$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
							//if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$abord', '$sensor_state', '$error','$false_up', '$false_down', '$block_up', '$block_down','$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
								echo "Se ingresaron los registros";
							}
							else{
						    	
								echo "No se ingresaron los registros";
							}
						}
					}
				}

			//}
				

    	}


    	 public function queryJson($vid){

    	 	$query = $this->mysqli->query("SELECT vehicles.name_vehicle, data_frame.up, data_frame.down, data_frame.aboart,data_frame.false_up, data_frame.false_down, data_frame.event_date, data_frame.lat, data_frame.lon from data_frame INNER JOIN vehicles on data_frame.vehicle_idvehicle = vehicles.idvehicle where vehicles.idvehicle = $vid order by event_date");

    	 	$output = '{"data":[';
    	 	while ($row = $query->fetch_array()) {
    	 		if ($output!='{"data":[') {$output .= ",";}
    	 		$output .= '{"Name_Vehicle":"'.$row["name_vehicle"].'",';
    	 		$output .= '"Up":"'.$row["up"].'",';
    	 		$output .= '"Down":"'.$row["down"].'",';
    	 		$output .= '"Abord":"'.$row["aboart"].'",';
    	 		$output .= '"False_Up":"'.$row["false_up"].'",';
    	 		$output .= '"False_Down":"'.$row["false_down"].'",';
    	 		$output .= '"Event_Date":"'.$row["event_date"].'",';
    	 		$output .= '"Lat":"'.$row["lat"].'",';
    	 		$output .= '"Lon":"'.$row["lon"].'"}';
    	 	}

    	 	$output .= "]}";
    	 	echo $output;
    	 }

    	 public function save_Vehicles($vehicles){
    	 	if (empty($vehicles)) {
    	 		//echo "No hay vechiculos";
    	 	}
    	 	else{
	    	 	for ($i=0; $i < count($vehicles); $i++) { 
	    	 		$id = $vehicles[$i]->id;
	    	 		$imei = $vehicles[$i]->imei;
	    	 		$name = $vehicles[$i]->name;
	    	 		//$capacitance = $vehicles[$i]->loquesea;
	    	 		//$max_capacitance = $vehicles[$i]->loquesea;
	    	 		if ($this->mysqli->query("INSERT INTO vehicles VALUES ('$id', '$name', default, default, '$imei', default)")) {
						echo "---Se ingresaron los registros---";
					}
					else{
				    	
						echo "---No se ingresaron los registros---";
					}

					$this->mysqli->query("UPDATE vehicles SET imei = '$imei', name_vehicle = '$name' WHERE idvehicle='$id'");
	    	 	}
	    	}
    	}
    }
    $instance = new Data_frame();
	$instance->save_Frames($events);
	//$instance->save_Vehicles($vehicles);
	//$instance->queryJson($vid);
	//$instance->save_Volatile();

	/*if ($_POST["tipo"]=="stop_ev") {
		$instance->save_Volatile();
	}

	elseif ($_POST["tipo"]=="frame_ev") {
		$instance->save_Frames();
	}*/

 ?>