<?php
// Look for lib
include dirname ( __FILE__ ) . '\lib\Classes\PHPExcel.php';
include dirname ( __FILE__ ) . '\lib\Classes\PHPExcel\IOFactory.php';
include dirname ( __FILE__ ) . '\lib\lib.php';

echo "<p align='center'><img  src='img/LOGONEOBISOK.jpeg' align='middle'></p>";
//inicio de secion para paso de informacion necesaria
session_start();

if (!$_FILES ['file'] ['name']){
	
	die("No hay archivo, vuelva a intentar");
}
	
	// validacion del nombre
$filename = explode ( ".", $_FILES ['file'] ['name'] );
if ($filename [1] == "csv" || $filename [1] == "xls" || $filename [1] == "xlsx") {
	
	//file destination
	$base_filedir= dirname(__FILE__).'\uploads';
	$filedir = $base_filedir."\\".$_FILES['file']['name'] ;
	
	// load file
	if(move_uploaded_file($_FILES['file']['tmp_name'], $filedir)){

		$objExcel = PHPExcel_IOFactory::load($filedir);
		
		// selección de hoja
		$worksheet=$objExcel->getSheet(0);
		
		echo neobis_data_upload_ricoh($worksheet);
	}else{
		echo "Error en la carga del archivo";
		die();
	}
}