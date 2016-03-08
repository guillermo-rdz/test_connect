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
    	 	/*for ($i=0; $i < count($objeto); $i++) { 
				$eventTime = $objeto[$i]->vid;
				echo $eventTime;
			}*/
			echo $objeto[0]->vid;
		    	/*
		    	if ($this->mysqli->query("INSERT INTO prueba2 VALUES (default,'$name','$arreglo_trama[0]', '$arreglo_trama[1]', '$arreglo_trama[2]')")) {
					echo "Se ingresaron los registros";
				}
				else{
					echo "No se ingresaron los registros";
				}*/

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