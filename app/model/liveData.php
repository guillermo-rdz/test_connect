<?php  
	
	require_once "conexion";
	$json_l = $_POST["liveData"];
	$event = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);

	class Events extends conexion{
		public function _construct(){
			parent:: _construct();
		}

		public function save_Event($event){
			$vid = $event->vid;
			$imei = $event->imei;
			$lat = $event->lat;
			$lon = $event->lon;
			$eventTime = $event->eventTime;
			$tx = $event->tx;

			if (empty($tx)){
				echo "trama vacia";
			}
			else{
			$trama = explode(",", $tx);
			$up  = $trama[0];
			$down  = $trama[1];
			$onboard  = $trama[2];
			$false_up  = $trama[3];
			$false_down  = $trama[4];
			$error = 0; //$error = $trama[5];

			if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$abord', '$false_up', '$false_down', '$error', '$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
					echo "Se ingresaron los registros";
				}
			else{    	
					echo "No se ingresaron los registros";
				}
			}
		}

	}

	$instance = new liveData();
	$instance->save_Event($event);

?>