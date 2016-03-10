<?php 
	$json_l = $_POST['liveData'];//------------------ Objeto de eventos ----------------------
	$json_v = $_POST['vehiculos'];//----------------- Objeto de Vehiculos --------------------
	$json_u = $_POST['usuarios'];//----------------- Objeto de Vehiculos --------------------

	require_once "conexion.php";


	//$events = json_decode($json_l, false, 512, JSON_BIGINT_AS_STRING);
	$vehicles = json_decode($json_v, false, 512, JSON_BIGINT_AS_STRING);
	//$users = json_decode($json_u, false, 512, JSON_BIGINT_AS_STRING);


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

				//$tx = "0,1,2,3,4";
				$trama = explode(",", $tx);
				$up = $trama[0];
				$down = $trama[1];
				$abord = $trama[2];
				$false_up = $trama[3];
				$false_down = $trama[4];

				if ($this->mysqli->query("INSERT INTO data_frame2 VALUES (default, '$up', '$down', '$abord', '$false_up', '$false_down', '$eventTime', '$lat', '$lon', '$imei', '$vid')")) {
					echo "Se ingresaron los registros";
				}
				else{
			    	
					echo "No se ingresaron los registros";
				}
			}
				

    	 }

    	 public function query_Volatile($vehicles){
    	 	for ($i=0; $i <count($vehicles) ; $i++) { 
    	 		$id = $vehicles[$i]->id;

    	 		$query = $this->mysqli->query("SELECT * FROM vehicle2 WHERE idvehicle = $id");
    	 		//$row = $query->fetch_array();
    	 		
	    	 	while ($row = $query->fetch_array()) {
	    	 		//echo $row["idvehicle"];
	    	 		$encode[$i] = $row;
	    	 	}
    	 	}
    	 	//print_r(json_encode($encode));
    	 	echo json_encode($encode);
    	 }

    	 public function save_Vehicles($vehicles){
    	 	for ($i=0; $i < count($vehicles); $i++) { 
    	 		//print_r($objeto[$i]->name);
    	 		//print_r($objeto[$i]->imei);
    	 		$id = $vehicles[$i]->id;
    	 		$imei = $vehicles[$i]->imei;
    	 		$name = $vehicles[$i]->name;
    	 		if ($this->mysqli->query("INSERT INTO vehicle2 VALUES ('$id', '$name', default, default, '$imei')")) {
					//echo "Se ingresaron los registros";
				}
				else{
			    	
					echo "No se ingresaron los registros";
				}
    	 	}
    	 }

    	 public function save_Users($users){
    	 	for ($i=0; $i < count($users); $i++) { 
    	 		$id = $users[$i]->id;
    	 		$email = $users[$i]->email;

    	 		if ($this->mysqli->query("INSERT INTO users VALUES ('$id', '$email')")) {
					//echo "Se ingresaron los registros";
				}
				else{
			    	
					echo "No se ingresaron los registros";
				}
    	 	}	
    	 }
    }
    $instance = new Data_frame();
	$instance->save_Volatile($events);
	$instance->query_Volatile($vehicles);
	$instance->save_Vehicles($vehicles);
	$instance->save_Users($users);

 ?>