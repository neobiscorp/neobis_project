<?php
// Setting time limit to 15 minutes
set_time_limit ( 2100 );
header ( 'Content-Type: text/html; charset=UTF-8' );
// Look for lib
require_once dirname ( __FILE__ ) . '/lib/lib.php';
require_once dirname ( __FILE__ ) . '/lib/spout-master/src/Spout/Autoloader/autoload.php';
// Using the Excel library
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\CSV;
use Box\Spout\Common\Type;

// Session start for passing variables throgh pages
if (! isset ( $_SESSION )) {
	session_start ();
}
// Neobis Logo
echo "<p align='center'><img  src='img/LOGONEOBISOK.jpeg' align='middle'></p>";
// Entering to the different views
if ($_POST ["dates"] == "Enviar") {
	// Checking if dates are allready set
	if (! isset ( $_SESSION ["factdate"] ) || ! isset ( $_SESSION ["indate"] ) || ! isset ( $_SESSION ["findate"] )) {
		// Filling in session variables, with the rescued information from the form.
		$_SESSION ["provider"] = $_POST ["provider"];
		$_SESSION ["factdate"] = $_POST ["factdate"];
		$_SESSION ["client"] = $_POST ["client"];
		$_SESSION ["indate"] = $_POST ["indate"];
		$_SESSION ["findate"] = $_POST ["findate"];
	}
	// Checking for errors
	list ( $boolean, $error ) = neobis_date_error ( $_SESSION ["factdate"], $_SESSION ["indate"], $_SESSION ["findate"] );
	if ($boolean) {
		echo $error;
		die ();
	}
	// Comming back from other pages
	unset ( $_SESSION ["filename"] );
	unset ( $_SESSION ["filedir"] );
	// Showing the uploading form
	echo neobis_file_uploader_form ( $_SESSION ["factdate"], $_SESSION ["indate"], $_SESSION ["findate"], $_SESSION ["provider"], $_SESSION ["client"] );
	die ();
} elseif ($_POST ["Volver"] == "Volver") {
	// Back from the uploading form to the selection of client-provider
	echo neobis_select_provider_form ();
	die ();
} elseif ($_POST ["file"] == "Importar") {
	// After saving the file in the system we show a table to validate the work of the program
	// Cheking file is not missing
	if (! isset ( $_SESSION ["filename"] )) {
		if (! $_FILES ["file"] ["name"]) {
			echo "<p align = 'center'><h1>Error/es:</h1><br>No hay archivo, vuelva a intentar</p>";
			echo "<html><body><div align = 'center'><form method='POST'><button type='submit' name='dates' value='Enviar'>Volver</button></div></form></body></html>";
			die ();
		}
	}
	// Cheking if there is any file name saved before
	if (! isset ( $_SESSION ["filename"] )) {
		// Assigns file name to SESSION variable
		$_SESSION ["filename"] = $_FILES ["file"] ["name"];
	}
	// check file extention (It doesnt work when the file got a dot inside its name)
	$extention = explode ( ".", $_SESSION ["filename"] );
	if ($extention [1] != "xlsx" && $extention [1] != "xls") {
		echo "<div align = 'center'>El archivo no puede ser leido, usar archivo '.xlsx' <form method='POST'><button type='submit' name='dates' value='Enviar'>Volver</button></div></form>";
		die ();
	}
	// File destination
	$base_filedir = dirname ( __FILE__ ) . "/uploads";
	$filedir = $base_filedir . "/" . $_SESSION ["filename"];
	// Checking if there is any file directory saved before
	if (! isset ( $_SESSION ["filedir"] )) {
		$_SESSION ["filedir"] = $filedir;
	}
	// Uploading file
	if (move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $filedir )) {
		$upload = TRUE;
	} else {
		$upload = FALSE;
	}
	// Collecting data for file
	// Creating connection variable for queries
	$connection = neobis_mysql_conection ();
	// Getting idoperateur
	$idoperateur_sql = "SELECT idoperateur FROM proveedores WHERE nombre LIKE '" . $_SESSION ["provider"] . "'";
	// Execcuting query
	$idoperateur = mysqli_query ( $connection, $idoperateur_sql );
	// Getting query results
	$idoperateur = mysqli_fetch_array ( $idoperateur );
	// Assign idoperateur to session variable
	$_SESSION ["idoperateur"] = $idoperateur ["idoperateur"];
	// Getting ceco, nomcompte, codevice id
	$info_sql = "SELECT ceco_id, nomcompte_id, codedevise_id
					 FROM cliente_proveedor
					 JOIN (
   						 SELECT clientes.id AS clienteid, proveedores.id AS proveedorid
  						 FROM clientes, proveedores
   						 WHERE clientes.nombre LIKE '" . $_SESSION ["client"] . "' AND proveedores.nombre LIKE '" . $_SESSION ["provider"] . "') AS a
					 ON proveedores_id = a.proveedorid AND clientes_id = a.clienteid";
	$info = mysqli_query ( $connection, $info_sql );
	$info = mysqli_fetch_array ( $info );
	// Getting CECO
	$ceco_sql = "SELECT nombre FROM ceco WHERE id='" . $info ["ceco_id"] . "'";
	$ceco = mysqli_query ( $connection, $ceco_sql );
	$ceco = mysqli_fetch_array ( $ceco );
	$_SESSION ["ceco"] = $ceco ["nombre"];
	// Getting nomcompte
	$nomcompte_sql = "SELECT nombre FROM nomcompte WHERE id='" . $info ["nomcompte_id"] . "'";
	$nomcompte = mysqli_query ( $connection, $nomcompte_sql );
	$nomcompte = mysqli_fetch_array ( $nomcompte );
	$_SESSION ["nomcompte"] = $nomcompte ["nombre"];
	// Getting codevice
	$codedevise_sql = "SELECT nombre FROM codedevise WHERE id='" . $info ["codedevise_id"] . "'";
	$codedevise = mysqli_query ( $connection, $codedevise_sql );
	$codedevise = mysqli_fetch_array ( $codedevise );
	$_SESSION ["codedevise"] = $codedevise ["nombre"];
	// Getting facturation date
	$facturationdate = $_SESSION ["factdate"];
	// Separating date by "-"
	$facturationdate = explode ( "-", $facturationdate );
	// Joining date array with "/"
	$facturationdate = $facturationdate [2] . "/" . $facturationdate [1] . "/" . $facturationdate [0];
	// Assigning the facturation date to a session variable
	$_SESSION ["facturationdate"] = $facturationdate;
	// Getting facturation period start date
	$dateone = $_SESSION ["indate"];
	$dateone = explode ( "-", $dateone );
	$dateone = $dateone [2] . "/" . $dateone [1] . "/" . $dateone [0];
	$_SESSION ["dateone"] = $dateone;
	// Getting facturation period finish date
	$datetwo = $_SESSION ["findate"];
	$datetwo = explode ( "-", $datetwo );
	$datetwo = $datetwo [2] . "/" . $datetwo [1] . "/" . $datetwo [0];
	$_SESSION ["datetwo"] = $datetwo;
	// Facturation month
	$moisfacturation = $_SESSION ["factdate"];
	$moisfacturation = explode ( "-", $moisfacturation );
	$moisfacturation = $moisfacturation [2] . $moisfacturation [1];
	$_SESSION ["moisfacturation"] = $moisfacturation;
	// Cheking if there was a facture name input
	$result = array ();
	if (isset ( $_POST ['namefacture'] )) {
		if (preg_match ( "/[[:alnum:]]/", $_POST ['facture'], $result )) {
			// If there was a facture name, assign it to a session variable
			$_SESSION ['facturename'] = $_SESSION ["client"] . "-" . $_SESSION ["provider"] . "-" . $_SESSION ["moisfacturation"] . "-" . $_POST ['facture'];
		} else {
			echo "<div align = 'center'><h1>Error:</h1><br>No seleccionaste un nombre de factura </div>";
			echo "<html><body><div align = 'center'><form method='POST'><button type='submit' name='dates' value='Enviar'>Volver</button></div></form></body></html>";
			die ();
		}
	}
	// Getting file name separated
	$filename = explode ( ".", $_SESSION ["filename"] );
	// Validating extention
	if ($filename [1] == "csv" || $filename [1] == "xls" || $filename [1] == "xlsx") {
		// Loading file
		if ($upload = TRUE) {
			$sheetcount = 0;
			// File reader creation
			$reader = ReaderFactory::create ( Type::XLSX );
			// Getting file to read
			$reader->open ( $_SESSION ["filedir"] );
			// Reading file sheet by sheet
			foreach ( $reader->getSheetIterator () as $sheet ) {
				// Counting sheets
				$sheetcount ++;
			}
			$k = 0;
			foreach ( $reader->getSheetIterator () as $sheet ) {
				// Getting sheet name
				$sheetname = $sheet->getName ();
				$rowcount = 0;
				foreach ( $sheet->getRowIterator () as $row ) {
					$length = count ( $row );
					$rowcount ++;
					// Reading only the first row
					if ($rowcount == 1) {
						// Condition to change name of row to identify sheet
						if ($sheetcount > 0) {
							// Counting number of columns
							// Going through the columns changing the
							for($i = 0; $i < $length; $i ++) {
								// Changing value to match Sheet - Vaue
								$options [$k] = $sheetname . " - " . $row [$i];
								$k ++;
							}
						}
						// Asign header to a session variable
						$_SESSION ["header"] = $options;
					}
				}
			}
			// Closing file reader
			$reader->close ();
			// Getting fields that match client-provider
			$_SESSION ["fields"] = neobis_get_fields ( $_SESSION ["client"], $_SESSION ["provider"] );
			// Select field form display
			echo neobis_select_fields ( $_SESSION ["header"], $_SESSION ["fields"] );
			die ();
		} else {
			// Loading the file error
			echo "Error en la carga del archivo";
			die ();
		}
	}
	die ();
} elseif ($_POST ["fields"] == "Siguiente") {
	// Checking if there are selections saved
	unset ( $_SESSION ["selections"] );
	unset ( $_SESSION ["facturename"] );
	if (! isset ( $_SESSION ["selections"] )) {
		$_SESSION ["selections"] = $_POST;
	}
	// Showing table section
	foreach ( $_POST as $selection ) {
		if ($selection == "noselection") {
			echo "<div align = 'center'><h1>Error:</h1><br>No seleccionaste uno de los campos </div>";
			echo "<html><body><div align = 'center'><form method='POST'><button type='submit' name='file' value='Importar'>Volver</button></div></form></body></html>";
			die ();
		}
	}
	echo neobis_back_fromtable ( $_SESSION ["facturename"], dirname ( __FILE__ ) . "/uploads/" );
	// Getting table and file header Exclusive to Falabella Adessa Enlaces
	if ($_SESSION ["client"] == "Falabella" && $_SESSION ["provider"] == "Adessa Enlaces") {
		list ( $print, $encabezados ) = neobis_print_table_AEnlaces ( $_SESSION ["client"], $_SESSION ["provider"], $_SESSION ["filedir"], $_SESSION ["header"], $_SESSION ['selections'], $_SESSION ["fields"], $_SESSION ["moisfacturation"], $_SESSION ["facturationdate"], $_SESSION ["dateone"], $_SESSION ["datetwo"], $_SESSION ["idoperateur"], $_SESSION ["nomcompte"], $_SESSION ["ceco"], $_SESSION ["codedevise"] );
		// Printing table for validation
		echo $print;
	} elseif ($_SESSION ["client"] == "Falabella" && ($_SESSION ["provider"] == "Quintec Arriendo" || $_SESSION ["provider"] == "Quintec Soporte")) {
		// Getting table and file header
		list ( $print, $encabezados ) = neobis_print_table_Quintec ( $_SESSION ["client"], $_SESSION ["provider"], $_SESSION ["filedir"], $_SESSION ["header"], $_SESSION ['selections'], $_SESSION ["fields"], $_SESSION ["moisfacturation"], $_SESSION ["facturationdate"], $_SESSION ["dateone"], $_SESSION ["datetwo"], $_SESSION ["idoperateur"], $_SESSION ["nomcompte"], $_SESSION ["ceco"], $_SESSION ["codedevise"] );
		// Printing table for validation
		echo $print;
	} else {
		// Getting table and file header
		list ( $print, $encabezados ) = neobis_print_table ( $_SESSION ["client"], $_SESSION ["provider"], $_SESSION ["filedir"], $_SESSION ["header"], $_SESSION ['selections'], $_SESSION ["fields"], $_SESSION ["moisfacturation"], $_SESSION ["facturationdate"], $_SESSION ["dateone"], $_SESSION ["datetwo"], $_SESSION ["idoperateur"], $_SESSION ["nomcompte"], $_SESSION ["ceco"], $_SESSION ["codedevise"] );
		// Printing table for validation
		echo $print;
	}
	// Creating file variable
	$csv = neobis_create_file_information ( $_SESSION ["facturename"], $encabezados );
	// Create CSV writer
	$writer = WriterFactory::create ( Type::CSV );
	// Saing file to this direction, name included
	$writer->openToFile ( dirname ( __FILE__ ) . "/downloads/" . $_SESSION ["facturename"] . ".csv" );
	// Setting delimiter
	$writer->setFieldDelimiter ( ';' );
	// Adding information to file
	$writer->addRows ( $csv );
	// Closing writer
	$writer->close ();
	// Adding buttons
	echo neobis_back_fromtable ( $_SESSION ["facturename"], dirname ( __FILE__ ) . "/uploads/" );
	// New button to going back to the begining
	$backbutton = "<html><body><div align = 'center'><form method='POST' action = 'index.php'>";
	$backbutton .= "<button type='submit' name='back' value='back'>Volver al inicio</button>";
	$backbutton .= "</div></form></body></html>";
	echo $backbutton;
	die ();
}
// showing client-provider selection form
echo neobis_select_provider_form ();
die ();