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

    public function vehicleReport(){
            $infoJ = $_POST['info'];
            $info = json_decode($infoJ, false, 512, JSON_BIGINT_AS_STRING);
            $id = $info->id;
            $start = $info->tini;
            $end = $info->tfin;
            $userId = $info->userId;

            $now = time();
            $hoy = date("Y-m-d",$now);
            //echo "$fecha2";

            $query = $this->mysqli->query("SELECT v.idvehicle, v.name_vehicle, d.iddrivers, d.users_idusers, de.date_assign as assign, de.date_unassigned as unassigned
            FROM driver_events as de
            INNER JOIN vehicles as v on v.idvehicle = de.vehicles_idvehicle
            INNER JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
            WHERE v.idvehicle = '$id' AND d.users_idusers = '$userId'");

            if ($query->num_rows > 0) {
                $output = '{"infoReport":[';
                while ($row = $query->fetch_array()) {
                    $did = $row['iddrivers'];
                    $assign = $row['assign'];
                    $unassign = $row['unassigned'];
                    if (is_null($unassign)) {
                        $unassign = $hoy;
                    }
                    $fecha = $this->compare($start, $end, $assign, $unassign);
                    $inicio = $fecha['inicio'];
                    $fin = $fecha['fin'];

                    $query2 = $this->mysqli->query("SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date
                    FROM data_frame as f 
                    LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
                    LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
                    LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
                    LEFT JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
                    LEFT JOIN route as r on r.idroute = vr.route_idroute
                    WHERE date(f.event_date) between '$inicio' and '$fin' and v.idvehicle = '$id' and iddrivers = '$did'
                    ORDER BY f.event_date DESC");
                    //echo "Inicio  ".$start."  Fin   ".$end."  Assign   ".$assign."  Unassign   ".$unassign."--";
                    if ($query2->num_rows > 0) {
                        //echo "Si entro al 2 IF";
                        while ($row = $query2->fetch_array()) {
                            if ($output!='{"infoReport":[') {$output .= ",";}
                            $output .= '{"id":"'.$row["idvehicle"].'",';
                            //$output .= '"conductor":"'.utf8_encode($row["name_driver"]).'",';
                            //$output .= '"vehiculo":"'.utf8_encode($row["name_vehicle"]).'",';
                            $output .= '"nombre1":"'.utf8_encode($row["name_vehicle"]).'",';
                            $output .= '"nombre2":"'.utf8_encode($row["name_driver"]).'",';
                            $output .= '"nombre3":"'.utf8_encode($row["name_route"]).'",';
                            $output .= '"subidas":"'.$row["up"].'",';
                            $output .= '"bajadas":"'.$row["down"].'",';
                            $output .= '"abordo":"'.$row["onboard"].'",';
                            $output .= '"ingresos":"'.$row["ingreso"].'",';
                            $output .= '"lat":"'.$row["lat"].'",';
                            $output .= '"lon":"'.$row["lon"].'",';
                            $output .= '"fecha":"'.$row["event_date"].'",';
                            $output .= '"mensaje":"Información de vehiculos"}';
                        }
                    }
                }
                $output .= "]}";
                echo $output;
            }
        }

 ?>