<?php
// Using the Excel library
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\CSV;
use Box\Spout\Common\Type;
// Starting session to use global variables
if (! isset ( $_SESSION )) {
	session_start ();
}
/**
 * Conection to Mysql data base
 *
 * @return connection
 */
function neobis_mysql_conection() {
	// Creating Mysql connection Windows PC
	//$connection = mysqli_connect ( '127.0.0.1', 'root', '', 'Neobis' );
	// Creating Mysql connection MAC PC
	 $connection = mysqli_connect('localhost', 'root', 'root', 'Neobis', '8883');
	// Cheking for error
	if (! $connection) {
		die ( "Connection Failed:" . mysqli_connect_error () );
	}
	return $connection;
}
/**
 * File Upload Form
 *
 * @param string $fechafact        	
 * @param string $fechain        	
 * @param string $fechafin        	
 * @param string $proveedor        	
 * @param string $cliente        	
 * @return string
 */
function neobis_file_uploader_form($fechafact, $fechain, $fechafin, $provider, $client) {
	$output = "<html>";
	$output .= "<body>";
	// Starting form
	$output .= "<form method='POST' enctype='multipart/form-data' action='index.php'>";
	$output .= "<div align='center'>";
	// Setting title
	$output .= "<fieldset><legend align='center'> Seleccione un archivo para subir </legend>";
	$output .= "<table align='center'>";
	$output .= "<tr>";
	// Showing rescued values from previous forms
	$output .= "<td>Fecha de facturación: " . $fechafact . "</td>";
	$output .= "<td></td><td></td><td></td><td></td><td></td><td></td>";
	$output .= "<td>Cliente: " . $client . "</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>Fecha de inicio: " . $fechain . "</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>Fecha de fin: " . $fechafin . "</td>";
	$output .= "<td></td><td></td><td></td><td></td><td></td><td></td>";
	$output .= "<td>Proveedor: " . $provider . "</td>";
	$output .= "</tr>";
	$output .= "</table>";
	// Setting max file size
	$output .= "<input type='hidden' name='MAX_FILE_SIZE' value='3000000000' />";
	// Upload form command
	$output .= "<p> Elegir Archivo: <input type='file' name='file'/></p>";
	// Unsetting facture name for coming back purpouse
	unset ( $_SESSION ["facturename"] );
	?>
<script>
	function showFacture(str) {
	  if (str=="") {
	    document.getElementById('txtHint').innerHTML='';
	    return;
	  }
	  if (window.XMLHttpRequest) {
	    // code for IE7+, Firefox, Chrome, Opera, Safari
	    xmlhttp=new XMLHttpRequest();
	  } else { // code for IE6, IE5
	    xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
	  }
	  xmlhttp.onreadystatechange=function() {
	    if (this.readyState==4 && this.status==200) {
	      document.getElementById('txtHint').innerHTML=this.responseText;
	    }
	  }
	  xmlhttp.open('GET','facturename.php?q='+str,true);
	  xmlhttp.send();
	}
	</script>
<?php
	// Checkbox for facture name
	$output .= "<p> <input type = 'checkbox' name = 'namefacture' value = '1' onclick='showFacture(this.value)' id='factbox'> Seleccionar nombre de la factura </p>";
	// Getting information by JS over previous Checkbox
	$output .= "<div id='txtHint'></div>";
	// Back button
	$output .= "<input type='submit' name='Volver' value='Volver'>";
	// Import button
	$output .= "<input type='submit' name='file' value='Importar'>";
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
/**
 * Select Client-Provider Form
 *
 * @return string
 */
function neobis_select_provider_form() {
	// Unsetting dates for coming back purpouse
	unset ( $_SESSION ["factdate"] );
	unset ( $_SESSION ["indate"] );
	unset ( $_SESSION ["findate"] );
	// Data base connection
	$connection = neobis_mysql_conection ();
	// Searching for providers query
	$sql_proveedores = "SELECT nombre FROM proveedores";
	// Query excecution
	$proveedores = mysqli_query ( $connection, $sql_proveedores );
	$proveedor = array ();
	// Going through results
	if (mysqli_num_rows ( $proveedores ) > 0) {
		// Output data of each row
		while ( $row = mysqli_fetch_assoc ( $proveedores ) ) {
			// Saving results
			$proveedor [] = $row ["nombre"];
		}
	}
	// Searching fot clients query
	$sql_clientes = "SELECT nombre FROM clientes";
	// Query excecution
	$clientes = mysqli_query ( $connection, $sql_clientes );
	$cliente = array ();
	// Going through results
	if (mysqli_num_rows ( $clientes ) > 0) {
		// Save results
		while ( $row = mysqli_fetch_assoc ( $clientes ) ) {
			$cliente [] = $row ["nombre"];
		}
	}
	// Form creation
	$output = "<html>";
	$output .= "<body>";
	?>
<script>
function showProvider(str) {
  if (str=='') {
    document.getElementById('txtHint').innerHTML='';
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById('txtHint').innerHTML=this.responseText;
    }
  }
  xmlhttp.open('GET','selectprovider.php?q='+str,true);
  xmlhttp.send();
}
</script>
<?php
	$output .= "<form method='POST'>";
	// Setting form title
	$output .= "<fieldset><legend align='center'> Seleccione Poveedor </legend>";
	$output .= "<div align='center'>";
	// Getting CSS
	$output .= "<link rel='stylesheet' type='text/css' href='tcal.css' />";
	$output .= "<script type='text/javascript' src='tcal.js'></script>";
	$output .= "<br>Fecha de facturación:";
	// Inserting Calendar
	$output .= "<input type='text' name='factdate' class='tcal' align='center'><br><br>";
	$output .= "  Fecha de inicio del periodo de facturación:";
	// Inserting Calendar
	$output .= "<input type='text' name='indate' class='tcal' align='center'>";
	$output .= "  Fecha de fin del periodo de facturación:";
	// Inserting Calendar
	$output .= "<input type='text' name='findate' class='tcal' align='center'><br>";
	// Client selection
	$output .= "<p> <select name='client' onchange='showProvider(this.value)'> </p>";
	$output .= " <option value = 'noselection'>-- select an option --</option>";
	foreach ( $cliente as $client ) {
		$output .= "<p> <option value='" . $client . "'>" . $client . "</option></p>";
	}
	$output .= "</select>";
	// Getting information by JS over previous Select
	$output .= "<div id = 'txtHint'></div>";
	// Submit Button
	$output .= "<p> <input type='submit' name='dates' value='Enviar'></p>";
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
/**
 * Getting Data Base fields in Relation with Client-Provider
 *
 * @param string $cliente        	
 * @param string $proveedor        	
 * @return Query excecution
 */
function neobis_get_fields($client, $provider) {
	// Star connection
	$connection = neobis_mysql_conection ();
	// Sql sentence
	$sql = "SELECT campos.nombre, a.ce1, a.ce2, a.ce3, a.ce4, a.ce5
			FROM campos,(SELECT campos_base.campos_id AS cid, d.ce1, d.ce2, d.ce3, d.ce4, d.ce5
						FROM campos_base
						JOIN(SELECT cliente_proveedor.tipo_proveedores_id AS tpid, cliente_proveedor.col_extra_1 AS ce1, cliente_proveedor.col_extra_2 AS ce2,
							cliente_proveedor.col_extra_3 AS ce3, cliente_proveedor.col_extra_4 AS ce4, cliente_proveedor.col_extra_5 AS ce5
							FROM cliente_proveedor
							JOIN(SELECT proveedores.id as idproveedor, clientes.id as idcliente
								FROM proveedores, clientes
								WHERE proveedores.nombre LIKE '" . $provider . "' AND clientes.nombre LIKE '" . $client . "') AS s
							ON s.idproveedor=cliente_proveedor.proveedores_id AND s.idcliente=cliente_proveedor.clientes_id) AS d
						ON campos_base.tipo_proveedores_id=d.tpid) AS a
			WHERE a.cid=campos.id 
			ORDER BY campos.nombre DESC";
	// Query excecution
	$campos_sql = mysqli_query ( $connection, $sql );
	$campos = array ();
	$extra = array ();
	// Going through query results
	if (mysqli_num_rows ( $campos_sql ) > 0) {
		// output data of each row, saving results
		while ( $row = mysqli_fetch_assoc ( $campos_sql ) ) {
			$campos [] = $row ["nombre"];
			$extra [0] = $row ["ce1"];
			$extra [1] = $row ["ce2"];
			$extra [2] = $row ["ce3"];
			$extra [3] = $row ["ce4"];
			$extra [4] = $row ["ce5"];
		}
	}
	// Going thtorugh extra columns array
	for($i = 0; $i < count ( $extra ); $i ++) {
		// Setting status for validation
		$status = TRUE;
		// Checking if there is any field to add
		if ($extra [$i] != "NULL") {
			// Goig through fields array
			foreach ( $campos as $field ) {
				// Comparison of the two values
				if ($extra [$i] == $field) {
					// Changing status, Validation
					$status = FALSE;
				}
			}
		}
		// Getting status change
		if ($status == FALSE) {
			// New field position
			$newlength = count ( $campos ) + 1;
			// Adding new field
			$campos [$newlength] = $extra [$i];
		}
	}
	return $campos;
}
/**
 * Selecting fields forms, assign file columns to standard fields
 *
 * @param array $header        	
 * @param array $cliente        	
 * @param array $proveedor        	
 * @return Form
 */
function neobis_select_fields($header, $campos) {
	// Starting form
	$output = "<html>";
	$output .= "<body>";
	$output .= "<form method='POST'>";
	// Setting form tittle
	$output .= "<fieldset><legend align='center'> Selección de Campos </legend>";
	$output .= "<div align='center'>";
	$output .= "<table style='overflow-x:scrol;' >";
	$output .= "<tr>";
	$connection = neobis_mysql_conection ();
	// Creating tittle row
	foreach ( $campos as $field ) {
		$output .= "<th>" . $field . "</th>";
	}
	$output .= "</tr>";
	$output .= "<tr>";
	foreach ( $campos as $field ) {
		$getdescription_sql = "SELECT descripcion FROM campos WHERE nombre like '" . $field . "'";
		$description = mysqli_fetch_array ( mysqli_query ( $connection, $getdescription_sql ) );
		$output .= "<td> <p align = 'center'>(" . $description ["descripcion"] . ") </p> </td>";
	}
	$output .= "</tr>";
	$output .= "<tr>";
	// Creating multiple selection form
	foreach ( $campos as $field ) {
		$output .= "<td><select size='8' id='select_mul[]' name='" . $field . "[]' multiple>";
		$output .= " <option value = 'noselection'>-- select an option --</option>";
		foreach ( $header as $title ) {
			$output .= "<option  value='" . $title . "'>" . $title . "</option>";
		}
		$output .= "</select>";
		$output .= "</td>";
	}
	$output .= "</tr>";
	$output .= "</table>";
	// Submit button
	$output .= "<input type='submit' name='fields' value='Siguiente'>";
	$output .= "</div><div id= 'txtHint' ></div></fieldset></form></body></html>";
	return $output;
	die ();
}
/**
 * Showing transformation table for validation
 *
 * @param unknown $client        	
 * @param unknown $provider        	
 * @param unknown $filedir        	
 * @param unknown $header        	
 * @param unknown $selections        	
 * @param unknown $fields        	
 * @param unknown $moisfacturation        	
 * @param unknown $facturationdate        	
 * @param unknown $dateone        	
 * @param unknown $datetwo        	
 * @param unknown $idoperateur        	
 * @param unknown $nomcompte        	
 * @param unknown $ceco        	
 * @param unknown $codedevise        	
 * @return string
 */
function neobis_print_table($client, $provider, $filedir, $header, $selections, $campos, $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise) {
	// Creating connection
	$connection = neobis_mysql_conection ();
	// Creating file reader
	$reader = ReaderFactory::create ( Type::XLSX );
	// Opening file
	$reader->open ( $filedir );
	$assoc = array ();
	// cleaning the $selections variable to get an array with "Page - Field"
	unset ( $selections ["fields"] );
	foreach ( $selections as $subArray ) {
		foreach ( $subArray as $val ) {
			$newArray [] = $val;
		}
	}
	// check if there are multiple selections in a field
	foreach ( $selections as $item => $qwe ) {
		$ite [] = count ( array_keys ( $qwe ) );
	}
	// Create an array with each field and the number of selections
	$asd = array_keys ( $selections );
	$keys = array_combine ( $asd, $ite );
	// Next, if there are more than one selections per field, name them with number increments
	foreach ( $keys as $chain => $camp ) {
		$cadena [] = $chain;
		if ($camp >= 2) {
			for($k = 1; $k < $camp; $k ++) {
				$zxc = $chain . $k;
				array_push ( $cadena, $zxc );
			}
		}
	}
	$selections = array_combine ( $cadena, $newArray );
	// Save a string with the name of the sheet "noappel" is
	$N_campos = count ( $newArray );
	for($q = 0; $q < $N_campos; $q ++) {
		$string [$q] = $newArray [$q];
		$string [$q] = substr ( $string [$q], 0, strpos ( $string [$q], ' - ' ) );
	}
	foreach ( $cadena as $campo ) {
		// Sees if the field is set
		if (isset ( $selections [$campo] )) {
			// Create new array associating field with possition on the array
			$assoc [$campo] = array_search ( $selections [$campo], $header );
		}
	}
	// Checking for a facture name if it don't exist it assigns one
	if (isset ( $_SESSION ["facturename"] )) {
		$facturename = $_SESSION ["facturename"];
	} elseif ($client == "Falabella" && $provider == "Adessa IP") {
		$facturename = "Telefonia IP - " . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	} elseif ($client == "Falabella" && $provider == "Adessa PC") {
		$facturename = "Falabella-AdessaPC-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	} else {
		$facturename = $client . "-" . $provider . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	}
	// Clearing data base query creation
	$delete_sql = "DELETE FROM item WHERE nofacture like '" . $facturename . "'";
	// Query excecution
	mysqli_query ( $connection, $delete_sql );
	// Getting fields where to insert
	$insert_fields = neobis_insertinto_fields ();
	// INSERT FUNCTIONS BY PROVIDER HERE
	// Facture header Values
	$header_value = neobis_insertinto_values ( $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise, $facturename );
	// check the number of columns per page and count them
	foreach ( $reader->getSheetIterator () as $sheet ) {
		$rowcount = 0;
		foreach ( $sheet->getRowIterator () as $row ) {
			$rowcount ++;
			if ($rowcount == 1) {
				$length [] = count ( $row );
			}
		}
	}
	// count the number of sheets
	$sheets = count ( $length );
	// Calculate the acomulated column of the file per sheet
	$runningSum = 0;
	foreach ( $length as $number ) {
		$runningSum += $number;
		$total [] = $runningSum;
	}
	// creates an array with the position of every selection
	foreach ( $assoc as $cam => $N_ro ) {
		$N_row [] = $N_ro;
	}
	
	// checks the sheet that belong to the last field, and sets the correct position to work with that sheet
	if ($N_row [0] < $total [0]) {
		$oc3 = $assoc;
	}
	for($a = 0; $a < $N_campos; $a ++) {
		for($i = 0; $i < $sheets; $i ++) {
			
			if ($N_row [$a] > $total [$i] && $N_row [$a] < $total [($i + 1)]) {
				$N_row [$a] = $N_row [$a] - $total [$i];
				$oc3 = array_combine ( $cadena, $N_row );
				$assoc = $oc3;
			}
		}
	}
	// Going through file sheets
	foreach ( $reader->getSheetIterator () as $sheet ) {
		// Setting a counter
		$header_out = 0;
		// Going through rows from sheet
		if ($sheet->getName () === $string [0]) {
			foreach ( $sheet->getRowIterator () as $row ) {
				if ($header_out != 0) {
					$sql_insert = "INSERT INTO `item` (";
					$sql_values = "VALUES (";
					// Going through fields
					foreach ( $campos as $campo ) {
						// Sees if the field is set
						if (isset ( $assoc [$campo] )) {
							// Create new array associating field with possition on the array
							if ($campo == "libelle_charge") {
								// Hardcode for this stage
								if ($client == "Falabella" && $provider == "Adessa PC") {
									$file [$campo] = $row [$assoc [$campo]] . ".AD";
								} elseif ($client == "Falabella" && $provider == "Adessa IP") {
									$file [$campo] = str_replace ( 'Telefonia ', '', $row [$assoc [$campo]] );
								} elseif ($client == "Walmart" && $provider == "HP") {
									$file [$campo] = $row [$assoc [$campo]];
								}
							} else {
								$file [$campo] = $row [$assoc [$campo]];
							}
						}
					}
					$sum = $sum + $file ["m_total"];
					$file = array_merge ( $header_value, $file );
					$file = neobis_replacing_colon ( $file );
					$first_entry = 0;
					foreach ( $insert_fields as $insert_field ) {
						if (isset ( $file [$insert_field] )) {
							if ($first_entry != "0") {
								// Inserting colon
								$sql_insert .= ",";
								$sql_values .= ",";
							}
							// Table header
							// Inserting fields and values
							$sql_insert .= " `" . $insert_field . "`";
							$sql_values .= " '" . $file [$insert_field] . "'";
							$first_entry ++;
						} else {
							continue;
						}
					}
					// Closing parenthesis
					$sql_insert .= ")";
					$sql_values .= ")";
					$insert = $sql_insert . " " . $sql_values;
					// Uploading to data base
					mysqli_query ( $connection, $sql_insert . " " . $sql_values );
				}
				$header_out = 99999;
			}
			// Rounding
			$sum = round ( $sum, 4 );
			// getting sum with IVA
			$sum_iva = $sum * 1.19;
			$sum_iva = round ( $sum_iva, 4 );
			// Getting separated IVA
			$iva = $sum_iva - $sum;
			$iva = round ( $iva, 4 );
			// changing decimal with period to decimal with colon
			$sum = explode ( ".", $sum );
			$sum = implode ( ",", $sum );
			$file ["m_total_facture"] = $sum;
			$sum_iva = explode ( ".", $sum_iva );
			$sum_iva = implode ( ",", $sum_iva );
			$file ["m_total_ttc_facture"] = $sum_iva;
			$iva = explode ( ".", $iva );
			$iva = implode ( ",", $iva );
			$file ["m_tva"] = $iva;
			$count = 0;
			// Updating data on DB to export CSV
			$update_sql = "UPDATE item
			SET m_total_facture = '" . $sum . "', m_total_ttc_facture = '" . $sum_iva . "', m_tva = '" . $iva . "'
			WHERE nofacture like '" . $facturename . "'";
			// Query excecution
			if (mysqli_query ( $connection, $update_sql )) {
				$count ++;
			}
			// Creating table header
			$encabezados = array ();
			$table_search = " ";
			$colon_insert = 1;
			foreach ( $file as $key => $data ) {
				$encabezados [] = $key;
				$table_search .= $key;
				if ($colon_insert != count ( $file )) {
					$table_search .= ", ";
					$colon_insert ++;
				} else {
					$table_search .= " ";
				}
			}
			// Getting table to show
			$table_sql = "SELECT" . $table_search . "
			FROM item
			WHERE nofacture like '" . $facturename . "'
			LIMIT 10";
			// Query excecution
			$table = mysqli_query ( $connection, $table_sql );
			$table_show = array ();
			if (mysqli_num_rows ( $table ) > 0) {
				// output data of each row, saving results
				while ( $row = mysqli_fetch_assoc ( $table ) ) {
					$table_show [] = $row;
				}
			}
			$count = 1;
			// Starting table
			$show = "<html><table border = '1' align = 'center'>";
			$show .= "<tr>";
			// Writing header in table
			foreach ( $encabezados as $encabezado ) {
				$show .= "<td>" . $encabezado . "</td>";
			}
			$show .= "</tr>";
			// writing content on table
			foreach ( $table_show as $line ) {
				$show .= "<tr>";
				foreach ( $line as $cell ) {
					$show .= "<td>" . $cell . "</td>";
				}
				$show .= "</tr>";
			}
			// Closing table
			$show .= "</table></html>";
			// Return table
			return array (
					$show,
					$encabezados 
			);
		}
	}
}
/*
 * The following code its dedicated to Adessa Enlaces
 *
 * The difference with the other codes its that Enlaces require changing
 * the name of centrefacturation.
 * The other difference its that m_total its the sum of equal noappel's
 */
function neobis_print_table_AEnlaces($client, $provider, $filedir, $header, $selections, $campos, $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise) {
	// Creating connection
	$connection = neobis_mysql_conection ();
	// Creating file reader
	$reader = ReaderFactory::create ( Type::XLSX );
	// Opening file
	$reader->open ( $filedir );
	$assoc = array ();
	// cleaning the $selections variable to get an array with "Page - Field"
	unset ( $selections ["fields"] );
	foreach ( $selections as $subArray ) {
		foreach ( $subArray as $val ) {
			$newArray [] = $val;
		}
	}
	// check if there are multiple selections in a field
	foreach ( $selections as $item => $qwe ) {
		$ite [] = count ( array_keys ( $qwe ) );
	}
	// Create an array with each field and the number of selections
	$asd = array_keys ( $selections );
	$keys = array_combine ( $asd, $ite );
	// Next, if there are more than one selections per field, name them with number increments
	foreach ( $keys as $chain => $camp ) {
		$cadena [] = $chain;
		if ($camp >= 2) {
			for($k = 1; $k < $camp; $k ++) {
				$zxc = $chain . $k;
				array_push ( $cadena, $zxc );
			}
		}
	}
	$selections = array_combine ( $cadena, $newArray );
	// Save an array with the name of the sheet that every selection belongs
	$N_campos = count ( $newArray );
	for($q = 0; $q < $N_campos; $q ++) {
		$string [$q] = $newArray [$q];
		$string [$q] = substr ( $string [$q], 0, strpos ( $string [$q], ' - ' ) );
	}
	foreach ( $cadena as $campo ) {
		// Sees if the field is set
		if (isset ( $selections [$campo] )) {
			// Create new array associating field with possition on the array
			$assoc [$campo] = array_search ( $selections [$campo], $header );
		}
	}
	// Checking for a facture name if it don't exist it assigns one
	if (isset ( $_SESSION ["facturename"] )) {
		$facturename = $_SESSION ["facturename"];
	} else {
		$facturename = "Falabella-Enlaces-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	}
	// Clearing data base query creation
	$delete_sql = "DELETE FROM item WHERE nofacture like '" . $facturename . "'";
	// Query excecution
	mysqli_query ( $connection, $delete_sql );
	// Getting fields where to insert
	$insert_fields = neobis_insertinto_fields ();
	// INSERT FUNCTIONS BY PROVIDER HERE
	// Facture header Values
	$header_value = neobis_insertinto_values ( $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise, $facturename );
	// check the number of columns per page and count them
	foreach ( $reader->getSheetIterator () as $sheet ) {
		$rowcount = 0;
		foreach ( $sheet->getRowIterator () as $row ) {
			$rowcount ++;
			if ($rowcount == 1) {
				$length [] = count ( $row );
			}
		}
	}
	// count the number of sheets
	$sheets = count ( $length );
	// Calculate the acomulated column of the file per sheet
	$runningSum = 0;
	foreach ( $length as $number ) {
		$runningSum += $number;
		$total [] = $runningSum;
	}
	// creates an array with the position of every selection
	foreach ( $assoc as $cam => $N_ro ) {
		$N_row [] = $N_ro;
	}
	// checks the sheet that belong to the last field, and sets the correct position to work with that sheet
	if ($N_row [0] < $total [0]) {
		$oc3 = $assoc;
	}
	for($a = 0; $a < $N_campos; $a ++) {
		for($i = 0; $i < $sheets; $i ++) {
			if ($N_row [$a] > $total [$i] && $N_row [$a] < $total [($i + 1)]) {
				$N_row [$a] = $N_row [$a] - $total [$i];
				$oc3 = array_combine ( $cadena, $N_row );
				$assoc = $oc3;
			}
		}
	}
	// Going through file sheets
	foreach ( $reader->getSheetIterator () as $sheet ) {
		// Setting a counter
		$header_out = 0;
		// Going through rows from sheet
		if ($sheet->getName () === $string [0]) {
			foreach ( $sheet->getRowIterator () as $row ) {
				if ($header_out != 0) {
					$sql_insert = "INSERT INTO `item` (";
					$sql_values = "VALUES (";
					// Going through fields
					foreach ( $campos as $campo => $camp ) {
						// Sees if the field is set
						if (isset ( $assoc [$camp] )) {
							// Create new array associating field with possition on the array
							if ($camp == "centrefacturation") {
								// Hardcode for this stage
								if ($row [$assoc [$camp]] == "Entel") {
									$file [$camp] = "Enlaces.EntelSA";
								} elseif ($row [$assoc [$camp]] == "GTD") {
									$file [$camp] = "Enlaces.GTDTeleductos";
								} elseif ($row [$assoc [$camp]] == "Movistar") {
									$file [$camp] = "Enlaces.TelefonicaEmpresas";
								} elseif ($row [$assoc [$camp]] == "Telefónica") {
									$file [$camp] = "Enlaces.TelefonicaEmpresas";
								} elseif ($row [$assoc [$camp]] == "Telefónica Empresas") {
									$file [$camp] = "Enlaces.TelefonicaEmpresas";
								}elseif ($row [$assoc [$camp]] == "Claro") {
									$file [$camp] = "Enlaces.ClaroDatos";
								} else {
									// Hardcode for this stage
									$file [$camp] = str_replace ( ' ', '', "Enlaces." . $row [$assoc [$camp]] );
								}
							} else {
								$file [$camp] = $row [$assoc [$camp]];
							}
						}
					}
					$sum = $sum + $file ["m_total"];
					$file = array_merge ( $header_value, $file );
					$file = neobis_replacing_colon ( $file );
					$first_entry = 0;
					foreach ( $insert_fields as $insert_field ) {
						if (isset ( $file [$insert_field] )) {
							if ($first_entry != "0") {
								// Inserting colon
								$sql_insert .= ",";
								$sql_values .= ",";
							}
							// Table header
							// Inserting fields and values
							$sql_insert .= " `" . $insert_field . "`";
							$sql_values .= " '" . $file [$insert_field] . "'";
							$first_entry ++;
						} else {
							continue;
						}
					}
					// Closing parenthesis
					$sql_insert .= ")";
					$sql_values .= ")";
					$insert = $sql_insert . " " . $sql_values;
					// Uploading to data base
					mysqli_query ( $connection, $sql_insert . " " . $sql_values );
				}
				$header_out = 99999;
			}
			// Rounding
			$sum = round ( $sum, 4 );
			// getting sum with IVA
			$sum_iva = $sum * 1.19;
			$sum_iva = round ( $sum_iva, 4 );
			// Getting separated IVA
			$iva = $sum_iva - $sum;
			$iva = round ( $iva, 4 );
			// changing decimal with period to decimal with colon
			$sum = explode ( ".", $sum );
			$sum = implode ( ",", $sum );
			$file ["m_total_facture"] = $sum;
			$sum_iva = explode ( ".", $sum_iva );
			$sum_iva = implode ( ",", $sum_iva );
			$file ["m_total_ttc_facture"] = $sum_iva;
			$iva = explode ( ".", $iva );
			$iva = implode ( ",", $iva );
			$file ["m_tva"] = $iva;
			$count = 0;
			// Updating data on DB to export CSV
			$update_sql = "UPDATE item
			SET m_total_facture = '" . $sum . "', m_total_ttc_facture = '" . $sum_iva . "', m_tva = '" . $iva . "'
			WHERE nofacture like '" . $facturename . "'";
			// Query excecution
			if (mysqli_query ( $connection, $update_sql )) {
				$count ++;
			}
			// Updating data on DB to export CSV
			$update_sql2 = "UPDATE item
   SET m_total = REPLACE(m_total,',','.')";
			// Query excecution
			if (mysqli_query ( $connection, $update_sql2 )) {
				$count ++;
			}
			$update_sql3 = "UPDATE item, (Select `noappel`, TRUNCATE(SUM(`m_total`),4) AS mysum 
                 FROM item GROUP BY `noappel`) AS s
SET item.m_total = s.mysum
WHERE item.noappel = s.noappel";
			// Query excecution
			if (mysqli_query ( $connection, $update_sql3 )) {
				$count ++;
			}
			$update_sql4 = "UPDATE item
   SET m_total = REPLACE(m_total,'.',',')";
			// Query excecution
			if (mysqli_query ( $connection, $update_sql4 )) {
				$count ++;
			}
			// Creating table header
			$encabezados = array ();
			$table_search = " ";
			$colon_insert = 1;
			foreach ( $file as $key => $data ) {
				$encabezados [] = $key;
				$table_search .= $key;
				if ($colon_insert != count ( $file )) {
					$table_search .= ", ";
					$colon_insert ++;
				} else {
					$table_search .= " ";
				}
			}
			// Getting table to show
			$table_sql = "SELECT" . $table_search . "
			FROM item
			WHERE nofacture like '" . $facturename . "'
			LIMIT 10";
			// Query excecution
			$table = mysqli_query ( $connection, $table_sql );
			$table_show = array ();
			if (mysqli_num_rows ( $table ) > 0) {
				// output data of each row, saving results
				while ( $row = mysqli_fetch_assoc ( $table ) ) {
					$table_show [] = $row;
				}
			}
			$count = 1;
			// Starting table
			$show = "<html><table border = '1' align = 'center'>";
			$show .= "<tr>";
			// Writing header in table
			foreach ( $encabezados as $encabezado ) {
				$show .= "<td>" . $encabezado . "</td>";
			}
			$show .= "</tr>";
			// writing content on table
			foreach ( $table_show as $line ) {
				$show .= "<tr>";
				foreach ( $line as $cell ) {
					$show .= "<td>" . $cell . "</td>";
				}
				$show .= "</tr>";
			}
			// Closing table
			$show .= "</table></html>";
			// Return table
			return array (
					$show,
					$encabezados 
			);
		}
	}
}

/*
 * The following code its dedicated to Quintec
 *
 * The difference in this code with the others its that there are
 * multiple choices in the fields of m_total and mountant_charge
 */
function neobis_print_table_Quintec($client, $provider, $filedir, $header, $selections, $campos, $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise) {
	// Creating connection
	$connection = neobis_mysql_conection ();
	// Creating file reader
	$reader = ReaderFactory::create ( Type::XLSX );
	// Opening file
	$reader->open ( $filedir );
	$assoc = array ();
	// cleaning the $selections variable to get an array with "Page - Field"
	unset ( $selections ["fields"] );
	foreach ( $selections as $subArray ) {
		foreach ( $subArray as $val ) {
			$newArray [] = $val;
		}
	}
	// check if there are multiple selections in a field
	foreach ( $selections as $item => $qwe ) {
		$ite [] = count ( array_keys ( $qwe ) );
	}
	// Create an array with each field and the number of selections
	$asd = array_keys ( $selections );
	$keys = array_combine ( $asd, $ite );
	// Next, if there are more than one selections per field, name them with number increments
	foreach ( $keys as $chain => $camp ) {
		$cadena [] = $chain;
		if ($camp >= 2) {
			for($k = 1; $k < $camp; $k ++) {
				$zxc = $chain . $k;
				array_push ( $cadena, $zxc );
			}
		}
	}
	$selections = array_combine ( $cadena, $newArray );
	// Save an array with the name of the sheet that every selection belongs
	$N_campos = count ( $newArray );
	for($q = 0; $q < $N_campos; $q ++) {
		$string [$q] = $newArray [$q];
		$string [$q] = substr ( $string [$q], 0, strpos ( $string [$q], ' - ' ) );
	}
	foreach ( $cadena as $campo ) {
		// Sees if the field is set
		if (isset ( $selections [$campo] )) {
			// Create new array associating field with possition on the array
			$assoc [$campo] = array_search ( $selections [$campo], $header );
		}
	}
	// Checking for a facture name if it don't exist it assigns one
	if (isset ( $_SESSION ["facturename"] )) {
		$facturename = $_SESSION ["facturename"];
	} elseif ($client == "Falabella" && $provider == "Quintec Arriendo") {
		$facturename = "Falabella-Quintec-Arriendo-" . $string[0] . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	} elseif ($client == "Falabella" && $provider == "Quintec Soporte") {
		$facturename = "Falabella-Quintec-Soporte-" . $string[0] . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	} else {
		$facturename = $client . "-" . $provider . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	}
	// Clearing data base query creation
	$delete_sql = "DELETE FROM item WHERE nofacture like '" . $facturename . "'";
	// Query excecution
	mysqli_query ( $connection, $delete_sql );
	// Getting fields where to insert
	$insert_fields = neobis_insertinto_fields ();
	// INSERT FUNCTIONS BY PROVIDER HERE
	// Facture header Values
	$header_value = neobis_insertinto_values ( $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise, $facturename );
	// check the number of columns per page and count them
	foreach ( $reader->getSheetIterator () as $sheet ) {
		$rowcount = 0;
		foreach ( $sheet->getRowIterator () as $row ) {
			$rowcount ++;
			if ($rowcount == 1) {
				$length [] = count ( $row );
			}
		}
	}
	// count the number of sheets
	$sheets = count ( $length );
	// Calculate the acomulated column of the file per sheet
	$runningSum = 0;
	foreach ( $length as $number ) {
		$runningSum += $number;
		$total [] = $runningSum;
	}
	// creates an array with the position of every selection
	foreach ( $assoc as $cam => $N_ro ) {
		$N_row [] = $N_ro;
	}
	// checks the sheet that belong to the last field, and sets the correct position to work with that sheet
	if ($N_row [0] < $total [0]) {
		$oc3 = $assoc;
	}
	for($a = 0; $a < $N_campos; $a ++) {
		for($i = 0; $i < $sheets; $i ++) {
			if ($N_row [$a] > $total [$i] && $N_row [$a] < $total [($i + 1)]) {
				$N_row [$a] = $N_row [$a] - $total [$i];
				$oc3 = array_combine ( $cadena, $N_row );
				$assoc = $oc3;
			}
		}
	}
	var_dump($total);
	var_dump($N_row);
	var_dump($oc3);
	var_dump($string);
	var_dump($facturename);
	var_dump($moisfacturation);
	// Going through file sheets
	foreach ( $reader->getSheetIterator () as $sheet ) {
		// Setting a counter
		$header_out = 0;
		$sum=0;
		// Going through rows from sheet
		if ($sheet->getName () === $string [0]) {
			foreach ( $sheet->getRowIterator () as $row ) {
				if ($header_out != 0) {
					$sql_insert = "INSERT INTO `item` (";
					$sql_values = "VALUES (";
					// Going through fields
					foreach ( $campos as $campo ) {
						// Sees if the field is set
						if (isset ( $oc3 [$campo] )) {
							// Create new array associating field with possition on the array
							if ($campo == "m_total") {
								$file [$campo] = $row [$oc3 [$campo]] + $row [$oc3 ["m_total1"]];
							} elseif ($campo == "montant_charge") {
								$file [$campo] = $row [$oc3 [$campo]] + $row [$oc3 ["montant_charge1"]];
							} elseif ($campo == "libelle_charge"&& $provider == "Quintec Arriendo") {
								$file [$campo] = $row [$oc3 [$campo]];
							} elseif ($campo == "libelle_charge" && $provider == "Quintec Soporte") {
								$file [$campo] = "Soporte";
							} elseif ($campo == "m_total1") {
							} elseif ($campo == "montant_charge1") {
							} else {
								$file [$campo] = $row [$oc3 [$campo]];
							}
						}
					}
					$sum = $sum + $file ["m_total"];
					$file = array_merge ( $header_value, $file );
					$file = neobis_replacing_colon ( $file );
					$first_entry = 0;
					foreach ( $insert_fields as $insert_field ) {
						if (isset ( $file [$insert_field] )) {
							if ($first_entry != "0") {
								// Inserting colon
								$sql_insert .= ",";
								$sql_values .= ",";
							}
							// Table header
							// Inserting fields and values
							$sql_insert .= " `" . $insert_field . "`";
							$sql_values .= " '" . $file [$insert_field] . "'";
							$first_entry ++;
						} else {
							continue;
						}
					}
					// Closing parenthesis
					$sql_insert .= ")";
					$sql_values .= ")";
					$insert = $sql_insert . " " . $sql_values;
					// Uploading to data base
					mysqli_query ( $connection, $sql_insert . " " . $sql_values )or die(mysqli_error($db));
				}
				$header_out = 99999;
			}
			// }
			// Rounding
			$sum = round ( $sum, 4 );
			// getting sum with IVA
			$sum_iva = $sum * 1.19;
			$sum_iva = round ( $sum_iva, 4 );
			// Getting separated IVA
			$iva = $sum_iva - $sum;
			$iva = round ( $iva, 4 );
			// changing decimal with period to decimal with colon
			$sum = explode ( ".", $sum );
			$sum = implode ( ",", $sum );
			$file ["m_total_facture"] = $sum;
			$sum_iva = explode ( ".", $sum_iva );
			$sum_iva = implode ( ",", $sum_iva );
			$file ["m_total_ttc_facture"] = $sum_iva;
			$iva = explode ( ".", $iva );
			$iva = implode ( ",", $iva );
			$file ["m_tva"] = $iva;
			$count = 0;
			// Updating data on DB to export CSV
			$update_sql = "UPDATE item
			SET m_total_facture = '" . $sum . "', m_total_ttc_facture = '" . $sum_iva . "', m_tva = '" . $iva . "'
			WHERE nofacture like '" . $facturename . "'";
			// Query excecution
			if (mysqli_query ( $connection, $update_sql )) {
				$count ++;
			}
			// Creating table header
			$encabezados = array ();
			$table_search = " ";
			$colon_insert = 1;
			foreach ( $file as $key => $data ) {
				$encabezados [] = $key;
				$table_search .= $key;
				if ($colon_insert != count ( $file )) {
					$table_search .= ", ";
					$colon_insert ++;
				} else {
					$table_search .= " ";
				}
			}
			// Getting table to show
			$table_sql = "SELECT" . $table_search . "
			FROM item
			WHERE nofacture like '" . $facturename . "'
			LIMIT 10";
			// Query excecution
			$table = mysqli_query ( $connection, $table_sql )or die(mysqli_error($db));
			$table_show = array ();
			if (mysqli_num_rows ( $table ) > 0) {
				// output data of each row, saving results
				while ( $row = mysqli_fetch_assoc ( $table ) ) {
					$table_show [] = $row;
				}
			}
			$count = 1;
			// Starting table
			$show = "<html><table border = '1' align = 'center'>";
			$show .= "<tr>";
			// Writing header in table
			foreach ( $encabezados as $encabezado ) {
				$show .= "<td>" . $encabezado . "</td>";
			}
			$show .= "</tr>";
			// writing content on table
			foreach ( $table_show as $line ) {
				$show .= "<tr>";
				foreach ( $line as $cell ) {
					$show .= "<td>" . $cell . "</td>";
				}
				$show .= "</tr>";
			}
			// Closing table
			$show .= "</table></html>";
			// Return table
			return array (
					$show,
					$encabezados 
			);
		}
	}
}
/*
 *
 * Funcion Quintec Prueba
 *
 * This code will work with multiple choises in multiple sheets
 */
function neobis_print_table_Quintec_PRUEBA($client, $provider, $filedir, $header, $selections, $campos, $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise) {
	// Creating connection
	$connection = neobis_mysql_conection ();
	// Creating file reader
	$reader = ReaderFactory::create ( Type::XLSX );
	// Opening file
	$reader->open ( $filedir );
	$assoc = array ();
	// cleaning the $selections variable to get an array with "Page - Field"
	unset ( $selections ["fields"] );
	foreach ( $selections as $subArray ) {
		foreach ( $subArray as $val ) {
			$newArray [] = $val;
		}
	}
	// check if there are multiple selections in a field
	foreach ( $selections as $item => $qwe ) {
		$ite [] = count ( array_keys ( $qwe ) );
	}
	// Create an array with each field and the number of selections
	$asd = array_keys ( $selections );
	$keys = array_combine ( $asd, $ite );
	// Next, if there are more than one selections per field, name them with number increments
	foreach ( $keys as $chain => $camp ) {
		$cadena [] = $chain;
		if ($camp >= 2) {
			for($k = 1; $k < $camp; $k ++) {
				$zxc = $chain . $k;
				array_push ( $cadena, $zxc );
			}
		}
	}
	$selections = array_combine ( $cadena, $newArray );
	foreach ( $cadena as $campo ) {
		// Sees if the field is set
		if (isset ( $selections [$campo] )) {
			// Create new array associating field with possition on the array
			$assoc [$campo] = array_search ( $selections [$campo], $header );
		}
	}
	// Save an array with the name of the sheet that every selection belongs
	$N_campos = count ( $newArray );
	for($q = 0; $q < $N_campos; $q ++) {
		$string [$q] = $newArray [$q];
		$string [$q] = substr ( $string [$q], 0, strpos ( $string [$q], ' - ' ) );
	}
	// Checking for a facture name if it don't exist it assigns one
	if (isset ( $_SESSION ["facturename"] )) {
		$facturename = $_SESSION ["facturename"];
	} elseif ($client == "Falabella" && $provider == "Quintec Arriendo") {
		$facturename = "Falabella-PC-Arriendo-" . $string [0] . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	} elseif ($client == "Falabella" && $provider == "Quintec Soporte") {
		$facturename = "Falabella-PC-Soporte-" . $string [0] . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	} else {
		$facturename = $client . "-" . $provider . "-" . $moisfacturation;
		$_SESSION ["facturename"] = $facturename;
	}
	// Clearing data base query creation
	$delete_sql = "DELETE FROM item WHERE nofacture like '" . $facturename . "'";
	// Query excecution
	mysqli_query ( $connection, $delete_sql );
	// Getting fields where to insert
	$insert_fields = neobis_insertinto_fields ();
	// INSERT FUNCTIONS BY PROVIDER HERE
	// Facture header Values
	$header_value = neobis_insertinto_values ( $moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise, $facturename );
	// check the number of columns per page and count them
	foreach ( $reader->getSheetIterator () as $sheet ) {
		$rowcount = 0;
		foreach ( $sheet->getRowIterator () as $row ) {
			$rowcount ++;
			if ($rowcount == 1) {
				$length [] = count ( $row );
			}
		}
	}
	// count the number of sheets
	$sheets = count ( $length );
	// Calculate the acomulated column of the file per sheet
	$runningSum = 0;
	foreach ( $length as $number ) {
		$runningSum += $number;
		$total [] = $runningSum;
	}
	// creates an array with the position of every selection
	foreach ( $assoc as $cam => $N_ro ) {
		$N_row [] = $N_ro;
	}
	// checks the sheet that belong to the last field, and sets the correct position to work with that sheet
	for($a = 0; $a < $N_campos; $a ++) {
		for($i = 0; $i < $sheets; $i ++) {
			if ($N_row [0] < $total [0]) {
				$oc3 = $assoc;
			}
			if ($N_row [$a] > $total [$i] && $N_row [$a] < $total [($i + 1)]) {
				$N_row [$a] = $N_row [$a] - $total [$i];
				$oc3 = array_combine ( $cadena, $N_row );
				$assoc = $oc3;
			}
		}
	}
	// The name of the sheets that are going to be used
	$diff = array_unique ( $string );
	// count the number of this neded sheets
	$cuenta = count ( $diff );
	// Going through file sheets
	foreach ( $reader->getSheetIterator () as $sheet ) {
		// Setting a counter
		$header_out = 0;
		// Go through the different sheets
		for($h = 0; $h < $cuenta; $h ++) {
			if ($sheet->getName () === $diff [$h]) {
				// Going through rows from sheet
				foreach ( $sheet->getRowIterator () as $row ) {
					if ($header_out != 0) {
						$sql_insert = "INSERT INTO `item` (";
						$sql_values = "VALUES (";
						// Going through fields
						foreach ( $campos as $campo ) {
							// Sees if the field is set
							if (isset ( $oc3 [$campo] )) {
								// Create new array associating field with possition on the array
								if ($campo == "m_total" && substr ( $selections ["m_total"], 0, strpos ( $selections ["m_total"], ' - ' ) ) == $diff [$h]) {
									$file [$campo] = $row [$oc3 [$campo]] + $row [$oc3 ["m_total1"]];
								} elseif ($campo == "montant_charge" && substr ( $selections ["montant_charge"], 0, strpos ( $selections ["montant_charge"], ' - ' ) ) == $diff [$h]) {
									$file [$campo] = $row [$oc3 [$campo]] + $row [$oc3 ["montant_charge1"]];
								} elseif ($campo == "libelle_charge" && substr ( $selections ["libelle_charge"], 0, strpos ( $selections ["libelle_charge"], ' - ' ) ) == $diff [$h]) {
									$file [$campo] = $row [$oc3 [$campo]];
								} elseif ($campo == "m_total1" && substr ( $selections ["m_total1"], 0, strpos ( $selections ["m_total1"], ' - ' ) ) == $diff [$h]) {
								} elseif ($campo == "montant_charge1" && substr ( $selections ["montant_charge1"], 0, strpos ( $selections ["montant_charge1"], ' - ' ) ) == $diff [$h]) {
								} else {
									$file [$campo] = $row [$oc3 [$campo]];
								}
							}
						}
						$sum = $sum + $file ["m_total"];
						$file = array_merge ( $header_value, $file );
						$file = neobis_replacing_colon ( $file );
						$first_entry = 0;
						foreach ( $insert_fields as $insert_field ) {
							if (isset ( $file [$insert_field] )) {
								if ($first_entry != "0") {
									// Inserting colon
									$sql_insert .= ",";
									$sql_values .= ",";
								}
								// Table header
								// Inserting fields and values
								$sql_insert .= " `" . $insert_field . "`";
								$sql_values .= " '" . $file [$insert_field] . "'";
								$first_entry ++;
							} else {
								continue;
							}
						}
						// Closing parenthesis
						$sql_insert .= ")";
						$sql_values .= ")";
						$insert = $sql_insert . " " . $sql_values;
						// Uploading to data base
						mysqli_query ( $connection, $sql_insert . " " . $sql_values );
					}
					$header_out = 99999;
				}
				// }
				// Rounding
				$sum = round ( $sum, 4 );
				// getting sum with IVA
				$sum_iva = $sum * 1.19;
				$sum_iva = round ( $sum_iva, 4 );
				// Getting separated IVA
				$iva = $sum_iva - $sum;
				$iva = round ( $iva, 4 );
				// changing decimal with period to decimal with colon
				$sum = explode ( ".", $sum );
				$sum = implode ( ",", $sum );
				$file ["m_total_facture"] = $sum;
				$sum_iva = explode ( ".", $sum_iva );
				$sum_iva = implode ( ",", $sum_iva );
				$file ["m_total_ttc_facture"] = $sum_iva;
				$iva = explode ( ".", $iva );
				$iva = implode ( ",", $iva );
				$file ["m_tva"] = $iva;
				$count = 0;
				// Updating data on DB to export CSV
				$update_sql = "UPDATE item
			SET m_total_facture = '" . $sum . "', m_total_ttc_facture = '" . $sum_iva . "', m_tva = '" . $iva . "'
			WHERE nofacture like '" . $facturename . "'";
				// Query excecution
				if (mysqli_query ( $connection, $update_sql )) {
					$count ++;
				}
				// Creating table header
				$encabezados = array ();
				$table_search = " ";
				$colon_insert = 1;
				foreach ( $file as $key => $data ) {
					$encabezados [] = $key;
					$table_search .= $key;
					if ($colon_insert != count ( $file )) {
						$table_search .= ", ";
						$colon_insert ++;
					} else {
						$table_search .= " ";
					}
				}
				// Getting table to show
				$table_sql = "SELECT" . $table_search . "
			FROM item
			WHERE nofacture like '" . $facturename . "'
			LIMIT 10";
				// Query excecution
				$table = mysqli_query ( $connection, $table_sql );
				$table_show = array ();
				if (mysqli_num_rows ( $table ) > 0) {
					// output data of each row, saving results
					while ( $row = mysqli_fetch_assoc ( $table ) ) {
						$table_show [] = $row;
					}
				}
				$count = 1;
				// Starting table
				$show = "<html><table border = '1' align = 'center'>";
				$show .= "<tr>";
				// Writing header in table
				foreach ( $encabezados as $encabezado ) {
					$show .= "<td>" . $encabezado . "</td>";
				}
				$show .= "</tr>";
				// writing content on table
				foreach ( $table_show as $line ) {
					$show .= "<tr>";
					foreach ( $line as $cell ) {
						$show .= "<td>" . $cell . "</td>";
					}
					$show .= "</tr>";
				}
				// Closing table
				$show .= "</table></html>";
				// Return table
				return array (
						$show,
						$encabezados 
				);
			}
		}
	}
}
/**
 * Get and returns fields to insert into database
 *
 * @return array
 */
function neobis_insertinto_fields() {
	// Data base connection
	$connection = neobis_mysql_conection ();
	// Crating array
	$fields = array ();
	// SQL to get all posible fields
	$fields_sql = "SELECT * FROM campos";
	// Query excecution
	$result = mysqli_query ( $connection, $fields_sql );
	// Getting results
	if (mysqli_num_rows ( $result ) > 0) {
		// output data of each row
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fields [] = $row ['nombre'];
		}
	}
	// Base array definition
	$array = array (
			"moisfacturation",
			"datefacturation",
			"datefacture1",
			"datefacture2",
			"codedevise",
			"idoperateur",
			"nomcompte",
			"nofacture",
			"m_tva" 
	);
	// array merge
	$fields = array_merge ( $array, $fields );
	return $fields;
}
/**
 * Array with values of the standard fields to insert into database
 *
 * @param int $moisfacturation        	
 * @param string $facturationdate        	
 * @param string $dateone        	
 * @param string $datetwo        	
 * @param int $idoperateur        	
 * @param int $nomcompte        	
 * @param int $ceco        	
 * @param string $codedevise        	
 * @return array
 */
function neobis_insertinto_values($moisfacturation, $facturationdate, $dateone, $datetwo, $idoperateur, $nomcompte, $ceco, $codedevise, $nofacture) {
	// Array with values of the standard fields to insert into database
	$array = array (
			"moisfacturation" => $moisfacturation,
			"datefacturation" => $facturationdate,
			"datefacture1" => $dateone,
			"datefacture2" => $datetwo,
			"idoperateur" => $idoperateur,
			"nomcompte" => $nomcompte,
			"centrefacturation" => $ceco,
			"codedevise" => $codedevise,
			"nofacture" => $nofacture 
	);
	return $array;
}
/**
 * Replaces period with colon on numbers
 *
 * @param array $row        	
 */
function neobis_replacing_colon($row) {
	// Checking if the field is set
	if (isset ( $row ["montant_charge"] )) {
		// Take "." off
		$row ["montant_charge"] = explode ( ".", $row ["montant_charge"] );
		// Unite with ","
		$row ["montant_charge"] = implode ( ",", $row ["montant_charge"] );
	}
	if (isset ( $row ["m_total"] )) {
		$row ["m_total"] = explode ( ".", $row ["m_total"] );
		$row ["m_total"] = implode ( ",", $row ["m_total"] );
	}
	if (isset ( $row ["m_hors_voix"] )) {
		$row ["m_hors_voix"] = explode ( ".", $row ["m_hors_voix"] );
		$row ["m_hors_voix"] = implode ( ",", $row ["m_hors_voix"] );
	}
	if (isset ( $row ["m_hors_data"] )) {
		$row ["m_hors_data"] = explode ( ".", $row ["m_hors_data"] );
		$row ["m_hors_data"] = implode ( ",", $row ["m_hors_data"] );
	}
	if (isset ( $row ["m_remises_nondefini"] )) {
		$row ["m_remises_nondefini"] = explode ( ".", $row ["m_remises_nondefini"] );
		$row ["m_remises_nondefini"] = implode ( ",", $row ["m_remises_nondefini"] );
	}
	if (isset ( $row ["m_autre_nondefini"] )) {
		$row ["m_autre_nondefini"] = explode ( ".", $row ["m_autre_nondefini"] );
		$row ["m_autre_nondefini"] = implode ( ",", $row ["m_autre_nondefini"] );
	}
	return $row;
}
/**
 * Button set to go back from to any point before and to download file
 *
 * @param string $facturename        	
 * @param string $dir        	
 * @return string
 */
function neobis_back_fromtable($facturename, $dir) {
	$output = "<html><body><div align = 'center'><form method='POST' action='index.php'>";
	$output .= "<button type='submit' name='dates' value='Enviar'>Me equivoqué de archivo</button>";
	$output .= "<button type='submit' name='file' value='Importar'>Volver a elegir campos</button>";
	$output .= "</form>";
	// Download Button
	$output .= "<div align = 'center'><form method='POST'  action='download.php'>";
	$output .= "<button type='submit' value='Enviar'>Descargar</button></form>";
	return $output;
}
/**
 * Creating file variable with all the information
 *
 * @param string $facturename        	
 * @return array
 */
function neobis_create_file_information($facturename, $headers) {
	// Connetcing to te data base
	$connection = neobis_mysql_conection ();
	// Creating and excecuting query
	$first = 0;
	$query = "SELECT ";
	foreach ( $headers as $header ) {
		if ($first != 0) {
			$query .= ", ";
		}
		$query .= $header;
		$first = 9999;
	}
	$query .= " FROM item WHERE nofacture LIKE '" . $facturename . "'";
	$prueba = mysqli_query ( $connection, $query );
	$pos = 1;
	$csv [0] = $headers;
	// Going through query result
	if (mysqli_num_rows ( $prueba ) > 0) {
		// output data of each row
		while ( $row = mysqli_fetch_assoc ( $prueba ) ) {
			// inserting row to file variable
			$csv [$pos] = $row;
			$pos ++;
		}
	}
	return $csv;
}
/**
 * Checking for date errors
 *
 * @param string $factdate        	
 * @param string $indate        	
 * @param string $findate        	
 * @return multitype:boolean string
 */
function neobis_date_error($factdate, $indate, $findate) {
	// Error creation
	$error = "<div align = 'center'><h1>Error/es:</h1>";
	// boolean for die() creation
	$boolean = FALSE;
	$result = array ();
	// Checking if there is any date missing
	if (! preg_match ( "/(.*)-(.*)-(.*)/", $indate, $result ) || ! preg_match ( "/(.*)-(.*)-(.*)/", $findate, $result ) || ! preg_match ( "/(.*)-(.*)-(.*)/", $factdate, $result )) {
		$error .= "<br> No se seleccionaron las fechas requeridas.";
		// boolean for die() creation
		$boolean = TRUE;
	} elseif ($indate == $findate) {
		$error .= "<br> Fecha de inicio de periodo es igual a la fecha de fin de periodo.";
	}
	// Date sepatration for comparisson
	$factdate = explode ( "-", $factdate );
	$indate = explode ( "-", $indate );
	$findate = explode ( "-", $findate );
	
	// Date inconsistance comparisson
	if ($factdate [2] < $indate [2] || $factdate [2] < $findate [2]) {
		$error .= "<br>El año de facturación es menor al año del periodo de facturación. ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	// The following date error needs to be fixed
	// if($factdate[1] < $indate[1] || $factdate[1] < $findate[1]){
	// $error .= "<br>El mes de facturación es menor al mes del periodo de facturación.";
	// boolean for die() creation
	// $boolean = TRUE;
	// }
	if (mktime ( 0, 0, 0, $factdate [1], $factdate [0], $factdate [2] ) > mktime ( 0, 0, 0, $indate [1], $indate [0], $indate [2] ) && mktime ( 0, 0, 0, $factdate [1], $factdate [0], $factdate [2] ) < mktime ( 0, 0, 0, $findate [1], $findate [0], $findate [2] )) {
		$error .= "<br>La fecha de facturación está dentro del periodo de facturación.";
		$boolean = TRUE;
	}
	if ($indate [2] != $findate [2]) {
		$error .= "<br>El año de inicio de periodo es diferente al año de fin periodo de facturación. ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	if ($indate [1] > $findate [1]) {
		$error .= "<br>El mes de inicio de periodo es mayor al mes de fin periodo de facturación. ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	// The following date error needs to be fixed
	// if($indate[0] > $findate[0] && $indate[2] == $findate[2] && $indate[3] == $findate[3]){
	// $error .= "<br>El día de inicio de periodo es mayor al día de fin periodo de facturación. ";
	// boolean for die() creation
	// $boolean = TRUE;
	// }
	$error .= "<br><br><form method='POST'><input type='submit' name='Volver' value='Volver'></form>";
	$error .= "</div>";
	return array (
			$boolean,
			$error 
	);
}