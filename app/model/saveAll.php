<?php 
	require_once "conexion.php";

	class SaveAll extends conexion{
		
		public function _construct(){
			parent:: _construct();
		}

		public function saveUsers(){
			$usersJson = $_POST['usuarios'];
			$users = json_decode($usersJson, false, 512, JSON_BIGINT_AS_STRING);

			for ($i=0; $i < count($users); $i++) { 
				$id = $users[$i]->id;
				$name = utf8_decode($users[$i]->username);
				$active = 0;
				$status = 1;

				if ($this->mysqli->query("INSERT INTO users values('$id', '$name', '$active', '$status')")) {
					//echo "Se ingresaron usuarios";
				}
				else{
					//echo "No se ingresaron usuarios";
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
    	 		if ($this->mysqli->query("INSERT INTO vehicles VALUES ('$id', '$name', default, default, '$imei')")) {
					echo "---Se ingresaron los registros---";
				}
				else{
			    	
					echo "---No se ingresaron los registros---";
				}

				$this->mysqli->query("UPDATE vehicles SET imei = '$imei', name_vehicle = '$name' WHERE idvehicle='$id'");
    	 	}
    	}

    	/*public function saveFrames(){
    		$json_l = $_POST['data'];
			$events = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
			$vid = $events->vid;
			$imei = $events->imei;
			$tx = $events->tx;
			if (empty($tx)) {
				//$tx = "0,0,0,0,0,0,0,0,0";
				echo "trama vacia";
				if ($this->mysqli->query("INSERT INTO volatile_stop VALUES(default, '$lat', '$lon', '$eventTime', '$vid')")) {
					echo "Se ingreso ";
				}
			}
			else{

				//$tx = "3,1,2,1,0,0,0,0,0";
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
					echo "----Hay registros----";
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

			}
				

    	}*/

    	public function saveAllFrames(){
    		$infoJson = $_POST['data'];
    		$info = json_decode($infoJson, false, 512, JSON_BIGINT_AS_STRING);

    		$vid = $info->vid;
    		$imei = $info->imei;
    		$lat = $info->lat;
    		$lon = $info->lon;
    		$eventTime = $info->eventTime;
    		//Elementos de la tama
    		echo $tx = $info->tx;
    		//$tx = "3,1,2,1,0,0,0,0,0";

			//Si no hay trama guardar evento de parada
			if (empty($tx)){
				echo "Trama vacia";
				if ($this->mysqli->query("INSERT INTO volatile_stop VALUES ('$vid', '$lat', '$lon', '$eventTime')")) {
					echo "Se ingreso el evento de parada";
				}
				else{
					echo "No se ingreso el evento de parada";
					if ($this->mysqli->query("UPDATE volatile_stop SET vehicles_idvehicle = '$vid' lat = '$lat', lon='$lon', eventTime = '$eventTime'")) {
						echo "Se actulizo estado de parada";
					}
					else{
						echo "No se actualizo, ocurrio algun error";
					}
				}
			}
			//Si hay trama para comparar con la parada 
			else{
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
				$query = $this->mysqli->query("SELECT * FROM volatile_stop");
				//Si hay evento de parada se fusionan
				if ($query->num_rows > 0) {
					while ($row = $query->fetch_array()) {
						$latV = $row['lat'];
						$lonV = $row['lon'];
						$eventTimeV = $row['eventTime'];
						if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$sensor_state', '$error', '$false_up', '$false_down', '$block_up', '$block_down', '$eventTimeV','$latV', '$lonV', '$imei', '$vid')")) {
							echo "Se ingreso la trama con la parada";
							$this->mysqli->query("DELETE from volatile_stop WHERE vehicles_idvehicle = '$vid' ");
						}
						else{
							echo "No se inserto la trama con la parada";
						}

					}
				}
				//Si no hay evento de parada que se guarde el paquete entrante
				else{
					$query2 = $this->mysqli->query("SELECT *  FROM data_frame");
					while ($row2 = $query2->fetch_array()) {
						if ($up == $row2['up'] && $down == $row2['down'] && $onboard == $row2['onboard'] && $sensor_state == $row2['sensor_state'] && $error == $row2['error'] && $false_up == $row2['false_up'] && $false_down == $row2['false_down'] && $block_up == $row2['up_block'] && $block_down == $row2['down_block']) {
							echo "Tramas iguales";
						}
						else{
							if($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$sensor_state', '$error', '$false_up', '$false_down', '$block_up', '$block_down', '$eventTime','$lat', '$lon', '$imei', '$vid'")){
								echo "Se guardo la trama completa";
							}
							else{
								//echo "Se descarto la trama o ocurrio algun error";
							}
						}
					}
				}
			}

    	}
    }

	$instance = new SaveAll();
	//$instance->saveUsers()
	if ($_POST['type']=="usuarios") {
		$instance->saveUsers();
	}
	elseif ($_POST['type']=="evento") {
		$instance->saveAllFrames();
	}
	/*elseif ($_POST['tramas']) {
		$instance->saveFrames();
	}*/
	else{
		echo "No se pudo acceder a la función";
	}
 ?>