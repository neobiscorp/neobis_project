<?php
// Look for lib
include dirname ( __FILE__ ) . '\lib\Classes\PHPExcel.php';
include dirname ( __FILE__ ) . '\lib\Classes\PHPExcel\IOFactory.php';
include dirname ( __FILE__ ) . '\lib\lib.php';



if (isset ( $_POST ['providers']) && isset($_POST['factdate'])) {
	//primer formulario llenado
	//inicio de secion para paso de informacion a la siguiente pagina
	session_start();
	//llenado de informacion necesaria para la siguiente pagina
	$_SESSION['providers']=$_POST ['providers'];
	$_SESSION['factdate']=$_POST ['factdate'];
	echo neobis_file_uploader_form();
	die();
} elseif (! isset ( $_POST ['providers'] )) {
	
} elseif(!isset ( $_POST ['providers']) || !isset($_POST['factdate'])){
	// que pasa si no estan los campos en el formulario
}
echo neobis_select_provider_form();
die ();
