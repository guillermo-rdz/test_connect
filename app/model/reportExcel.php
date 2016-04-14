<?php 
	date_default_timezone_set('America/Mexico_City'); 
	require_once 'lib/PHPExcel/PHPExcel.php';
	$conexion = new mysqli('localhost','root','qaz','db_frames2');
	if (mysqli_connect_errno()) {
    	printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
    	exit();
	}

	$vid = 106;
	$start = "2016-04-12";
	$end = "2016-04-14";
	$userId = 8;

	$query = "SELECT v.idvehicle as vid, v.name_vehicle as vehicle, f.lat, f.lon, f.up, f.down, f.onboard, f.up as ingreso, f.event_date as fecha
	FROM vehicles as v
	INNER JOIN data_frame as f on v.idvehicle = f.vehicle_idvehicle
	WHERE date (f.event_date) between '$start' and '$end' and v.idvehicle = '$vid'
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
		$titleColumns = array('Vid', 'Vehiculo', 'Latitud', 'Longitud', 'Subidas', 'Bajadas', 'Abordo', 'Ingreso', 'Fecha');

		$excel->setActiveSheetIndex(0)
        	  ->mergeCells('A1:I1');

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
              ->setCellValue('I2',  $titleColumns[8]);

        $i = 3;
        while ($row = $result->fetch_array()) {
        	$excel->setActiveSheetIndex(0)
        		  ->setCellValue('A'.$i,  $row['vid'])
        		  ->setCellValue('B'.$i,  utf8_encode($row['vehicle']))
        		  ->setCellValue('C'.$i,  $row['lat'])
        		  ->setCellValue('D'.$i,  $row['lon'])
        		  ->setCellValue('E'.$i,  $row['up'])
        		  ->setCellValue('F'.$i,  $row['down'])
        		  ->setCellValue('G'.$i,  $row['onboard'])
        		  ->setCellValue('H'.$i,  $row['ingreso'])
        		  ->setCellValue('I'.$i,  $row['fecha']);
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

	        $excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleTitle);
			$excel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($styleTitleColumns);		
			//$excel->getActiveSheet()->setSharedStyle($styleInfo, "A4:D".($i-1));

			for($i = 'A'; $i <= 'I'; $i++){
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