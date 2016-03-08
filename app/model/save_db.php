<?php 
	//$json = $_POST['json'];
	require_once "conexion.php";

	$json = '[{
	"name": "primero",
	"trackers": "00,32,54,78"
	}, {
		"name": "segundo",
		"trackers": "12,13,14,15"
	}]';


    $objeto = json_decode($json, false, 512, JSON_BIGINT_AS_STRING);

    //var_dump($objeto);
    class Data_frame extends conexion{
    	
    	public function _construct(){
			parent:: _construct();
		}

    	 public function save_Volatile($objeto){

//--------------------------------Ingreso de datos a la base de datos de la tabla volatil---------------------------
    	 	for ($i=0; $i <count($objeto) ; $i++) { 
		    	$trama = $objeto[$i]->trackers;
		    	$name = $objeto[$i]->name;

		    	$arreglo_trama = explode(",", $trama);

		    	//echo $name.",".$arreglo_trama[0]."-".$arreglo_trama[1]."-".$arreglo_trama[2]."-".$arreglo_trama[3]."<br>";
		    	
		    	if ($this->mysqli->query("INSERT INTO prueba2 VALUES (default,'$name','$arreglo_trama[0]', '$arreglo_trama[1]', '$arreglo_trama[2]')")) {
					echo "Se ingresaron los registros";
				}
				else{
					echo "No se ingresaron los registros";
				}
		    }

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