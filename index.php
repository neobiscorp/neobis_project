<?php

// Look for lib
include dirname ( __FILE__ ) . '\lib\Classes\PHPExcel.php';
include dirname ( __FILE__ ) . '/lib/Classes/PHPExcel/IOFactory.php';
include dirname ( __FILE__ ) . '/lib/lib.php';

//inicio de secion para paso de informacion a la siguiente pagina
session_start();
echo "<p align='center'><img  src='img/LOGONEOBISOK.jpeg' align='middle'></p>";

if($_POST["fechas"] == "Enviar") {
	//primer formulario llenado
	
	
	

	//llenado de informacion necesaria para la siguiente pagina
	$_SESSION['proveedor']=$_POST ['proveedor'];
	$_SESSION['factdate']=$_POST ['factdate'];
	$_SESSION['cliente']=$_POST ['cliente'];
	$_SESSION['indate']=$_POST ['indate'];
	$_SESSION['findate']=$_POST ['findate'];
	var_dump($_SESSION['cliente']);
	echo neobis_file_uploader_form($_SESSION['factdate'], $_SESSION['indate'], $_SESSION['findate'], $_SESSION['proveedor'], $_SESSION['cliente']);
	die();
}elseif($_POST["Volver"]){
	echo neobis_select_provider_form();
	die();
}elseif($_POST["fichero"]=="Importar"){

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
			
			// selección de hoja está hecho para un archivo en específico.
			$worksheet=$objExcel->getSheet(1);
		
			$header=neobis_extract_header($worksheet);
			$fields=neobis_get_fields($_SESSION['cliente'], $_SESSION['proveedor']);
			$_SESSION["campos"]=$fields;
			var_dump($_SESSION["campos"]);
			echo neobis_select_fields($header, $fields);
			die();
		}else{
			echo "Error en la carga del archivo";
			die();
		}
	}
	
}elseif($_POST["table"]=="Siguiente"){
	$fields=neobis_get_fields($_SESSION['cliente'], $_SESSION['proveedor']);
	var_dump(neobis_get_multiple_select($fields));
	die("en construcción");
}

echo neobis_select_provider_form();

die ();