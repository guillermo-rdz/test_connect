<?php 
	require_once "conexion.php";
	date_default_timezone_set('America/Mexico_City');
	class SaveFalse extends conexion{
		
		public function _construct(){
			parent:: _construct();
		}
		//16.769691, -93.166436
		public function saveFalseFrame(){
			$jsonFrame = $_POST["liveData"];
			$frame = json_decode($jsonFrame, false, 512, JSON_BIGINT_AS_STRING);
			//print_r($frame);
			$tx = $frame->tx;
			$imei = $frame->imei;
			$vid = $frame->vid;
			$lat = $frame->lat;//+1/rand(1, 7);
			$lon = $frame->lon;//+1/rand(1, 7);
			//$arrayFrame = "0000,0000,000,0,00,0000,0000,0000,0001";
			$now = time();
			$fecha = date("Y-m-d H:i:s");

			if (empty($tx)) {
				echo "trama vacia";
			}
			else{

				$frame = explode(",", $tx);

				$up = $frame[0];
				$down = $frame[1]+rand(1, 3);
				$onboard = $frame[2];
				$sensor_state = $frame[3];
				$error = $frame[4];
				$false_up = $frame[5];
				$false_down = $frame[6];
				$block_up = $frame[7];
				$block_down = $frame[8];

				//if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$sensor_state', '$error','$false_up', '$false_down', '$block_up', '$block_down','$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
				if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$onboard', '$sensor_state', '$error','$false_up', '$false_down', '$block_up', '$block_down',current_timestamp, '$lat', '$lon', '$imei', '$vid')")) {
					echo "Se ingreso la trama".$fecha;
				}
				else{
									    	
					echo "No se ingreso la trama";
				}

				/*if ($this->mysqli->query("INSERT INTO volatile_stop values(default, '$lat', '$lon', current_timestamp, '$vid')")) {
				echo "Se ingreso la parada";
				}
				else{
					//echo "No se ingresaron registros";
					if ($this->mysqli->query("UPDATE volatile_stop SET  lat='$lat', lon='$lon', eventTime=current_timestamp WHERE vehicles_idvehicle='$vid' ")) {
						echo "Se actualizo el registro";
					}
					else{
						echo "No se actualizo el registro";
					}
				}

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
				}*/
			}
			//echo "Respuesta del servidor";
		}
	}

	$instance = new SaveFalse();

	$instance->saveFalseFrame(); 
	
 ?>