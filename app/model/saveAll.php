<?php 
	require_once "conexion.php";

	class SaveAll extends conexion{
		
		public function _construct(){
			parent:: _construct();
		}

		public function saveUsers(){
			$usersJson = $_POST['usuarios']
			$users = json_decode($usersJson, true);

			for ($i=0; $i < count($users); $i++) { 
				$id = $users[0];
				$name = utf8_decode($users[1]);
				$active = 0;

				if ($this->mysqli->query("INSERT INTO users values('$id', '$name', '$active')")) {
					echo "Se ingresaron usuarios";
				}
				else{
					echo "No se ingresaron usuarios";
				}
			}
		}

		public function saveVehicles(){
			$json_v = $_POST['vehiculo'];
			$vehicles = json_decode($json_v, false, 512, JSON_BIGINT_AS_STRING);

    	 	for ($i=0; $i < count($vehicles); $i++) { 
    	 		$id = $vehicles[$i]->id;
    	 		$imei = $vehicles[$i]->imei;
    	 		$name = utf8_decode($vehicles[$i]->name);
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

    	public function saveFrames(){
    		$json_l = $_POST['liveData'];
			$events = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $events->vid;
			$imei = $events->imei;
			$tx = $events->tx;
			if (empty($tx)) {
				//$tx = "0,0,0,0,0,0,0,0,0";
				echo "trama vacia";
			}
			//else{

				$tx = "3,1,2,1,0,0,0,0,0";
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
				//$query1 = $this->mysqli->query("SELECT up, down, onboard, sensor_state, error, false_up, false_down, up_block, down_block, event_date from data_frame where vehicle_idvehicle = '$vid' order by event_date desc limit 1");
				$query1 = $this->mysqli->query("SELECT * from data_frame where vehicle_idvehicle = '$vid' order by event_date and iddata_frame desc limit 1");
				if ($query1->num_rows > 0) {
					echo "----Hay registros-----";
					$query2 = $this->mysqli->query("SELECT lat, lon, eventTime, vehicles_idvehicle from volatile_stop where vehicles_idvehicle = '$vid' order by eventTime desc limit 1");
						while ($row1 = $query1->fetch_array()) {
							if($row1['up']==$up && $row1['down']==$down && $row1['onboard']==$onboard && $row1['sensor_state']==$sensor_state && $row1['error']==$error && $row1['false_up']==$false_up && $row1['false_down']==$false_down && $row1['up_block']==$block_up && $row1['down_block']==$block_down){
								echo "Registros iguales----No se ingreso la trama";
							}
							else{
								while ($row2 = $query2->fetch_array()) {
									$lat = $row2['lat'];
									$lon = $row2['lon'];
									$eventTime = $row2['eventTime'];
									//if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$false_up', '$false_down', '$error', '$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
									if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$sensor_state', '$error','$false_up', '$false_down', '$block_up', '$block_down','$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
										echo "IF-----Se ingreso la trama";
									}
									else{
									    	
										echo "IF-----No se ingreso la trama";
									}
								}

							}
								
						}
				}
					
				
				else{
						echo "No hay registros";
						$query2 = $this->mysqli->query("SELECT lat, lon, eventTime, vehicles_idvehicle from volatile_stop where vehicles_idvehicle = '$vid' order by eventTime desc limit 1");
						while ($row2 = $query2->fetch_array()) {
							$lat = $row2['lat'];
							$lon = $row2['lon'];
							$eventTime = $row2['eventTime'];
							//if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$false_up', '$false_down', '$error', '$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
							if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$sensor_state', '$error','$false_up', '$false_down', '$block_up', '$block_down','$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
								echo "Se ingreso la trama";
							}
							else{	
								echo "No se ingreso la trama";
							}
						}
				}
				//}

			//}
				

    	}
    }

	$instance = new SaveAll();
	if ($_POST['usuarios']) {
		$instance->saveUsers();
	}
	elseif ($_POST['vehiculo']) {
		$instance->saveVehicles();
	}
	elseif ($_POST['tramas']) {
		$instance->saveFrames();
	}
	else{
		echo "No se pudo acceder a la función";
	}
 ?>