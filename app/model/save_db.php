<?php 
	$json = $_POST['liveData'];

	require_once "conexion.php";


	$objeto = json_decode($json, false, 512, JSON_BIGINT_AS_STRING);


    class Data_frame extends conexion{
    	
    	public function _construct(){
			parent:: _construct();
		}

    	 public function save_Volatile($objeto){

//--------------------------------Ingreso de datos a la base de datos de la tabla volatil---------------------------

			//echo $objeto->vid;
			echo $vid = $objeto->vid;
			echo $imei = $objeto->imei;
			echo $lat = $objeto->lat;
			echo $lon = $objeto->lon;
			//$eventTime = $objeto->eventTime;
			//$eventTime = date("Y-m-d H:i:s");
			$eventTime = $objeto->eventTime;
			$tx = $objeto->tx;
			if (empty($tx)) {
				$tx = "0,0,0,0,0";
			}
			//$tx = "0,1,2,3,4";
			$trama = explode(",", $tx);
			$up = $trama[0];
			$down = $trama[1];
			$abord = $trama[2];
			$false_up = $trama[3];
			$false_down = $trama[4];

		    	
		    	//$this->mysqli->query("INSERT INTO data_frame2 VALUES (default, '$up', '$down', '$abord', '$false_up', '$false_down', '$eventTime', '$lat', '$lon', '$imei', '$vid'");
		    	$this->mysqli->query("INSERT INTO data_frame2 VALUES (default, '$up', '$down', '$abord', '$false_up', '$false_down', '$eventTime', '$lat', '$lon', '$imei', '$vid')");
		    	//$this->mysqli->query("INSERT INTO data_frame2 VALUES (default, ".$up.", ".$down.", ".$abord.", ".$false_up.", ".$false_down.", ".$eventTime.", ".$lat.", ".$lon.", ".$imei.", ".$vid."");
					//echo "Se ingresaron los registros";
				

    	 }

    	 public function query_Volatile(){
    	 	$query = $this->mysqli->query("SELECT up, down, abord FROM prueba2 LIMIT 2");

    	 	while ($row = $query->fetch_array()) {
    	 		var_dump($row["up"]);
    	 	}

    	 }
    }
    $instance = new Data_frame();
	$instance->save_Volatile($objeto);
	//$instance->query_Volatile();

 ?>