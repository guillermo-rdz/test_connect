<?php 
	date_default_timezone_set('America/Mexico_City'); 
	require_once 'lib/PHPExcel/PHPExcel.php';
	$conexion = new mysqli('localhost','root','qaz','db_frames2');
	if (mysqli_connect_errno()) {
    	printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
    	exit();
	}

	$id = 106;
	$start = "2016-04-12";
	$end = "2016-04-14";
	$userId = 8;

	$query = "SELECT v.idvehicle, d.name_driver, r.name_route, v.name_vehicle, f.up, f.down, f.onboard, f.up as ingreso, f.lat, f.lon, f.event_date as fecha
			FROM data_frame as f 
			LEFT JOIN vehicles as v on f.vehicle_idvehicle = v.idvehicle
			LEFT JOIN driver_events as de on v.idvehicle = de.vehicles_idvehicle
			LEFT JOIN drivers as d on d.iddrivers = de.drivers_iddrivers
			LEFT JOIN vehicle_route as vr on v.idvehicle = vr.vehicles_idvehicle
			LEFT JOIN route as r on r.idroute = vr.route_idroute
			WHERE date(f.event_date) between '$start' and '$end' #and v.idvehicle = '$id'
			ORDER BY f.event_date DESC";

	$result = $conexion->query($query);

	if ($result->num_rows > 1) {
		if (PHP_SAPI == 'cli')
			die('Este archivo solo se puede ver desde un navegador web');

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator("Grupo IT") //Autor
							 ->setLastModifiedBy('$userId') //Ultimo usuario que lo modificó
							 ->setTitle("Reporte Excel")
							 ->setSubject("Reporte Excel")
							 ->setDescription("Reporte de vehiculos")
							 ->setKeywords("reporte vehiculos")
							 ->setCategory("Vehiculos");

		$title = "Reporte de vehiculos";
		$titleColumns = array('Vid', 'Vehiculo', 'Conductor', 'Ruta', 'Subidas', 'Bajadas', 'Abordo', 'Ingreso', 'Latitud', 'Longitud', 'Fecha');

		$excel->setActiveSheetIndex(0)
        	  ->mergeCells('A1:K1');

        $excel->setActiveSheetIndex(0)
			  ->setCellValue('A1',$title)
        	  ->setCellValue('A2',  $titleColumns[0])
		      ->setCellValue('B2',  $titleColumns[1])
        	  ->setCellValue('C2',  $titleColumns[2])
              ->setCellValue('D2',  $titleColumns[3])
              ->setCellValue('E2',  $titleColumns[4])
              ->setCellValue('F2',  $titleColumns[5])
              ->setCellValue('G2',  $titleColumns[6])
              ->setCellValue('H2',  $titleColumns[7])
              ->setCellValue('I2',  $titleColumns[8])
              ->setCellValue('J2',  $titleColumns[9])
              ->setCellValue('K2',  $titleColumns[10]);

        $i = 3;
        while ($row = $result->fetch_array()) {
        	$excel->setActiveSheetIndex(0)
        		  ->setCellValue('A'.$i,  $row['idvehicle'])
        		  ->setCellValue('B'.$i,  utf8_encode($row['name_vehicle']))
        		  ->setCellValue('C'.$i,  utf8_encode($row['name_driver']))
        		  ->setCellValue('D'.$i,  utf8_encode($row['name_route']))
        		  ->setCellValue('E'.$i,  $row['up'])
        		  ->setCellValue('F'.$i,  $row['down'])
        		  ->setCellValue('G'.$i,  $row['onboard'])
        		  ->setCellValue('H'.$i,  $row['ingreso'])
        		  ->setCellValue('I'.$i,  $row['lat'])
        		  ->setCellValue('J'.$i,  $row['lon'])
        		  ->setCellValue('K'.$i,  $row['fecha']);
       	$i++;
        }

        $styleTitle = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      =>16,
            	'color'     => array(
	            	'rgb' => '000000'
    	       	)
            ),
	        'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'	=> array('argb' => 'FFFFFFFF')
			),
            'borders' => array(
               	'allborders' => array(
                	'style' => PHPExcel_Style_Border::BORDER_NONE
               	)
            ), 
            'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'rotation'   => 0,
        			'wrap'       => TRUE
    		)
        );

        $styleTitleColumns = array(
            'font' => array(
                'name'      => 'Arial',
                'bold'      => true,                          
                'color'     => array(
                    'rgb' => '000000'
                )
            ),
            'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,
        		'startcolor' => array(
            		'rgb' => 'FFFFFF'
        		),
        		'endcolor'   => array(
            		'argb' => 'FFFFFFFF'
        		)
			),
            'borders' => array(
        		'allborders' => array(
            	'style' => PHPExcel_Style_Border::BORDER_THIN
               	)
            	/*'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )*/
            ),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'       => TRUE
    		));

			$styleInfo = new PHPExcel_Style();
			$styleInfo->applyFromArray(
				array(
	           		'font' => array(
	               	'name'      => 'Arial',               
	               	'color'     => array(
	                   	'rgb' => '000000'
	               	)
	           	),
	           	'fill' 	=> array(
					'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
					'color'		=> array('argb' => 'FFFFFFFF')
				),
	           	'borders' => array(
	           		'allborders' => array(
	            	'style' => PHPExcel_Style_Border::BORDER_THIN
	               	)
	               	/*'left'     => array(
	                   	'style' => PHPExcel_Style_Border::BORDER_THIN ,
		                'color' => array(
	    	            	'rgb' => '000000'
	                   	)
	               	)  */           
	           	)
	        ));

	        $excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleTitle);
			$excel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($styleTitleColumns);		
			//$excel->getActiveSheet()->setSharedStyle($styleInfo, "A4:D".($i-1));

			for($i = 'A'; $i <= 'K'; $i++){
			$excel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
			}

			$excel->getActiveSheet()->setTitle('Vehiculos');
			$excel->setActiveSheetIndex(0);
			//$excel->getActiveSheet(0)->freezePaneByColumnAndRow(0,9);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Reportevehiculos.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$objWriter->save('php://output');
			exit;

	}
	else{
		echo "No hay resultados";
	}
?>