<?php 
	require_once "conexion";
	$json_id = $_POST["vid"];
	$vid = json_decode($jso_id, 512, JSON_BIGINT_AS_STRING);


	class Query extends conexion{
    	public function _construct(){
    		parent:: _construct();
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
		
	}

	$instance = new Query();
	$instance->queryJson($vid);

 ?>