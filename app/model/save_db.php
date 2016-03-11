<?php 
	//$json_l = $_POST['liveData'];//------------------ Objeto de eventos ----------------------
	//$json_v = $_POST['vehiculos'];//----------------- Objeto de Vehiculos --------------------
	$json_id = $_POST['vid'];//---------------------- Id de los vehiculos -----------------------

	require_once "conexion.php";


	//$events = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
	//$vehicles = json_decode($json_v, false, 512, JSON_BIGINT_AS_STRING);
	$vid = json_decode($json_id, false, 512, JSON_BIGINT_AS_STRING);


    class Data_frame extends conexion{
    	
    	public function _construct(){
			parent:: _construct();
		}

    	 public function save_Volatile($events){

//--------------------------------Ingreso de datos a la base de datos de la tabla volatil---------------------------

			//echo $objeto->vid;
			$vid = $events->vid;
			$imei = $events->imei;
			$lat = $events->lat;
			$lon = $events->lon;
			//$eventTime = $objeto->eventTime;
			//$eventTime = date("Y-m-d H:i:s");
			$eventTime = $events->eventTime;
			$tx = $events->tx;
			if (empty($tx)) {
				echo "Trama vácia";
			}
			else{

				//$tx = "0,1,2,3,4,5";
				$trama = explode(",", $tx);
				$up = $trama[0];
				$down = $trama[1];
				$abord = $trama[2];
				$false_up = $trama[3];
				$false_down = $trama[4];
				$error = 0;

				if ($this->mysqli->query("INSERT INTO data_frame VALUES (default, '$up', '$down', '$abord', '$false_up', '$false_down', '$error', '$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
					echo "Se ingresaron los registros";
				}
				else{
			    	
					echo "No se ingresaron los registros";
				}
			}
				

    	 }


    	 public function queryJson($vid){

    	 	$query = $this->mysqli->query("SELECT vehicles.name_vehicle, data_frame.up, data_frame.down, data_frame.aboart,data_frame.false_up, data_frame.false_down, data_frame.event_date, data_frame.lat, data_frame.lon from data_frame INNER JOIN vehicles on data_frame.vehicle_idvehicle = vehicles.idvehicle where vehicles.idvehicle = '$vid' order by event_date");

    	 	$output = '{"data":[';
    	 	while ($row = $query->fetch_array()) {
    	 		//$rawdata[$i] = $row;
    	 		//$i++;
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
    	 	for ($i=0; $i < count($vehicles); $i++) { 
    	 		//print_r($objeto[$i]->name);
    	 		//print_r($objeto[$i]->imei);
    	 		$id = $vehicles[$i]->id;
    	 		$imei = $vehicles[$i]->imei;
    	 		$name = $vehicles[$i]->name;
    	 		if ($this->mysqli->query("INSERT INTO vehicles VALUES ('$id', '$name', default, default, '$imei')")) {
					//echo "Se ingresaron los registros";
				}
				else{
			    	
					echo "No se ingresaron los registros";
				}
    	 	}
    	 }

    }
    $instance = new Data_frame();
	//$instance->save_Volatile($events);
	$instance->queryJson($vid);
	//$instance->save_Vehicles($vehicles);

 ?>