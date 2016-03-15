<?php 
	require_once "conexion";
	$json_v = $_POST["vehiculo"];
	$vehiclesArray = json_decode($json_v, false, 512, JSON_BIGINT_AS_STRING);

	class Vehicles extends conexion{
		public function _construct(){
			parent:: _construct();
		}

		public function save_Vehicles($vehicles){
    	 	for ($i=0; $i < count($vehicles); $i++) { 
    	 		$id = $vehicles[$i]->id;
    	 		$imei = $vehicles[$i]->imei;
    	 		$name = $vehicles[$i]->name;
    	 		//$capacitance = $vehicles[$i]->loquesea;
    	 		//$max_capacitance = $vehicles[$i]->loquesea;
    	 		if ($this->mysqli->query("INSERT INTO vehicles VALUES ('$id', '$name', default, default, '$imei')")) {
					//echo "Se ingresaron los registros";
				}
				else{
			    	
					//echo "No se ingresaron los registros";
				}

				$this->mysqli->query("UPDATE vehicles SET imei = '$imei', name_vehicle = '$name' WHERE idvehicle='$id'");
    	 	}
    	 }
	}

	$instance = new Vehicles();
	$instance->save_Vehicles($vehiclesArray);

 ?>