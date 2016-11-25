<?php
// Using the Excel library
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\CSV;
use Box\Spout\Common\Type;
// Starting session to use global variables
session_start();
/**
 * File Upload Form
 * @param string $fechafact
 * @param string $fechain
 * @param string $fechafin
 * @param string $proveedor
 * @param string $cliente
 * @return string
 */
function neobis_file_uploader_form($fechafact, $fechain, $fechafin, $provider, $client){
	$output= "<html>";
	$output .="<body>";
	// Starting form
	$output .= "<form method='POST' enctype='multipart/form-data' action='index.php'>";
	$output .= "<div align='center'>";
	// Setting title
	$output .= "<fieldset><legend align='center'> Seleccione un archivo para subir </legend>";
	$output .= "<table align='center'>";
	$output .= "<tr>";
	// Showing rescued values from previous forms
	$output .= "<td>Fecha de facturación: ".$fechafact."</td>";
	$output .= "<td></td><td></td><td></td><td></td><td></td><td></td>";
	$output .= "<td>Cliente: ".$client."</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>Fecha de inicio: ".$fechain."</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>Fecha de fin: ".$fechafin."</td>";
	$output .= "<td></td><td></td><td></td><td></td><td></td><td></td>";
	$output .= "<td>Proveedor: ".$provider."</td>";
	$output .= "</tr>";
	$output .= "</table>";
	// Setting max file size 
	$output .= "<input type='hidden' name='MAX_FILE_SIZE' value='300000000' />";
	// Upload form command
	$output .= "<p> Elegir Archivo: <input type='file' name='file'/></p>";
	
	// Unsetting facture name for coming back purpouse
	unset($_SESSION["facturename"]);
	?>
		<script>
	function showFacture(str) {
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
	  xmlhttp.open('GET','facturename.php?q='+str,true);
	  xmlhttp.send();
	}
	</script>
		<?php 
	
	// Checkbox for facture name 
	$output .= "<p> <input type = 'checkbox' name = 'namefacture' value = '1' onchange='showFacture(this.value)'> Seleccionar nombre de la factura </p>";
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
 * @return string
 */
function neobis_select_provider_form(){
	
	// Unsetting dates for coming back purpouse
	unset($_SESSION["factdate"]);
	unset($_SESSION["indate"]);
	unset($_SESSION["findate"]);
	// Data base connection
	$connection=neobis_mysql_conection();
	// Searching for providers query
	$sql_proveedores = "SELECT nombre FROM proveedores";
	// Query excecution
	$proveedores = mysqli_query($connection, $sql_proveedores);
	$proveedor=array();
	
	// Going through results
	if (mysqli_num_rows($proveedores) > 0) {
		// Output data of each row
		while($row = mysqli_fetch_assoc($proveedores)) {
			// Saving results
			$proveedor[]=$row["nombre"];
		}
	}
	// Searching fot clients query
	$sql_clientes = "SELECT nombre FROM clientes";
	// Query excecution
	$clientes = mysqli_query($connection, $sql_clientes);
	$cliente=array();
	
	// Going through results
	if (mysqli_num_rows($clientes) > 0) {
		// Save results
		while($row = mysqli_fetch_assoc($clientes)) {
			$cliente[]=$row["nombre"];
		}
	}
	
	// Form creation
	$output= "<html>";
	$output .="<body>";
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
	$output .= "Fecha de facturación:";
	// Inserting Calendar
	$output .= "<input type='text' name='factdate' class='tcal' align='center'>";
	$output .= "  Fecha de inicio del periodo de facturación:";
	// Inserting Calendar
	$output .= "<input type='text' name='indate' class='tcal' align='center'>";
	$output .= "  Fecha de fin del periodo de facturación:";
	// Inserting Calendar
	$output .= "<input type='text' name='findate' class='tcal' align='center'>";


	// Client selection
	$output .= "<p> <select name='client' onchange='showProvider(this.value)'> </p>";
	$output .= " <option value = 'noselection'>-- select an option --</option>";
	foreach ($cliente as $client){
		$output .= "<p> <option value='".$client."'>".$client."</option></p>";
	}
	$output .= "</select>";
	// Getting information by JS over previous Select
	$output .= "<div id = 'txtHint'></div>";

	// Submit Button
	$output .= "<br><p> <input type='submit' name='dates' value='Enviar'></p>";
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
/**
 * Conection to Mysql data base
 * @return connection
 */
function neobis_mysql_conection(){
	// Creating Mysql connection
	$connection = mysqli_connect('localhost', 'root', 'root', 'Neobis', '8889');
	// Cheking for error
	if (!$connection){
		die("Connection Failed:".mysqli_connect_error());
	}
	return $connection;
} 
/**
 * Getting Data Base fields in Relation with Client-Provider
 * @param string $cliente
 * @param string $proveedor
 * @return Query excecution
 */
function neobis_get_fields($client, $provider){
	// Star connection
	$connection = neobis_mysql_conection();
	// Sql sentence 
	$sql= "SELECT campos.nombre, a.ce1, a.ce2, a.ce3, a.ce4, a.ce5
			FROM campos,(SELECT campos_base.campos_id AS cid, d.ce1, d.ce2, d.ce3, d.ce4, d.ce5
						FROM campos_base
						JOIN(SELECT cliente_proveedor.tipo_proveedores_id AS tpid, cliente_proveedor.col_extra_1 AS ce1, cliente_proveedor.col_extra_2 AS ce2,
							cliente_proveedor.col_extra_3 AS ce3, cliente_proveedor.col_extra_4 AS ce4, cliente_proveedor.col_extra_5 AS ce5
							FROM cliente_proveedor
							JOIN(SELECT proveedores.id as idproveedor, clientes.id as idcliente
								FROM proveedores, clientes
								WHERE proveedores.nombre LIKE '".$provider."' AND clientes.nombre LIKE '".$client."') AS s
							ON s.idproveedor=cliente_proveedor.proveedores_id AND s.idcliente=cliente_proveedor.clientes_id) AS d
						ON campos_base.tipo_proveedores_id=d.tpid) AS a
			WHERE a.cid=campos.id 
			ORDER BY campos.nombre DESC";
	
	// Query excecution
	$campos_sql=mysqli_query($connection, $sql);
	$campos=array();
	$extra=array();
	// Going through query results
	if (mysqli_num_rows($campos_sql) > 0) {
		// output data of each row, saving results
		while($row = mysqli_fetch_assoc($campos_sql)) {
			$campos[]=$row["nombre"];
			$extra[0] = $row["ce1"];
			$extra[1] = $row["ce2"];
			$extra[2] = $row["ce3"];
			$extra[3] = $row["ce4"];
			$extra[4] = $row["ce5"];
		}
	}
	
	// Going thtorugh extra columns array
	for($i=0; $i<count($extra);$i++){
		// Setting status for validation
		$status= TRUE;
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
		if($status == FALSE){
			// New field position
			$newlength = count($campos) +1;
			// Adding new field
			$campos[$newlength] = $extra[$i];
		}
	}
	return $campos;
}
/**
 * Selecting fields forms, assign file columns to standard fields
 * @param array $header
 * @param array $cliente
 * @param array $proveedor
 * @return Form
 */
function neobis_select_fields($header, $campos){	
	
	// Starting form
	$output= "<html>";
	$output .="<body>";
	$output .= "<form method='POST'>";
	// Setting form tittle
	$output .= "<fieldset><legend align='center'> Selección de Campos </legend>";
	$output .= "<div align='center'>";
	$output .="<table style='overflow-x:scrol;' >";
	$output .="<tr>";
	
	$connection = neobis_mysql_conection();
	
	// Creating tittle row
	foreach($campos as $field){
		$output .= "<th>".$field."</th>";
	}
	
	$output .="</tr>";
	$output .="<tr>";
	foreach($campos as $field){
		$getdescription_sql = "SELECT descripcion FROM campos WHERE nombre like '".$field."'";
		
		
		$description = mysqli_fetch_array(mysqli_query($connection, $getdescription_sql));
		$output .= "<td> <p align = 'center'>(".$description["descripcion"].") </p> </td>";
	}
	$output .="</tr>";
	$output .="<tr>";
	// Creating multiple selection form
	foreach($campos as $field){
		
		$output .= "<td><select name='".$field."'>";
		$output .= " <option value = 'noselection'>-- select an option --</option>";
		foreach($header as $title){
			$output .= "<option  value='".$title."'>".$title."</option>";
		}
		$output .= "</select>";
		$output .="</td>";
	}
	$output .="</tr>";
	$output .="</table>";

	
	
	// Submit button
	$output .="<input type='submit' name='fields' value='Siguiente'>"; 
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
// Not Used
function neobis_A2A($worksheet, $fechaUno, $fechaDos, $fecha, $cliente, $proveedor){
	$info=array();
	$highestCol = $worksheet->getHighestColumn();
	var_dump($highestCol);die();
	$highestCol= array_search($highestCol, neobis_excel_letters());
	$highestRow = $worksheet->getHighestRow();
	for($col = 0; $col < $highestCol; $col ++) {
		if($worksheet->getCellByColumnAndRow ( $col, 1)->getValue () == "Tipo" ){
			for ($i=0; $i<$highestRow;$i++){
				$info["Tipo"][] = $worksheet->getCellByColumnAndRow ( $col, $i)->getValue ().".AD";
			}
		}elseif ($worksheet->getCellByColumnAndRow ( $col, 1)->getValue () == "Costo" ){
			for ($i=0; $i<$highestRow;$i++){
				$info["Costo"][] = $worksheet->getCellByColumnAndRow ( $col, $i)->getValue ();
			}
		}elseif ($worksheet->getCellByColumnAndRow ( $col, 1)->getValue () == "Serie" ){
			for ($i=0; $i<$highestRow;$i++){
				$info["Serie"][] = $worksheet->getCellByColumnAndRow ( $col, $i)->getValue ();
			}
		}
	}
	$connection=neobis_mysql_conection();
	
	$sql = "SELECT A.idoperateur, cliente_proveedor.proveedores_id as proveedorid, cliente_proveedor.clientes_id as clienteid, cliente_proveedor.ceco_id as cecoid, cliente_proveedor.nomcompte_id as nomcompteid, cliente_proveedor.codevice_id as codeviceid
			FROM cliente_proveedor
			JOIN(
				SELECT proveedores.id as idproveedor, clientes.id as idcliente, proveedores.idoperateur
				FROM proveedores, clientes
				WHERE proveedores.nombre LIKE '".$proveedor."' AND clientes.nombre LIKE '".$cliente."') as A
			ON A.idproveedor=cliente_proveedor.proveedores_id AND A.idcliente = cliente_proveedor.clientes_id ";
	$ids = mysqli_query($connection, $sql);
	
	$id=array();
	if (mysqli_num_rows($ids) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($ids)) {
			$id["proveedorid"]=$row["proveedorid"];
			$id["clienteid"] = $row["clienteid"];
			$id["cecoid"] = $row["cecoid"];
			$id["nomcompteid"] = $row["nomcompteid"];
			$id["codeviceid"] = $row["codeviceid"];
			$id["idoperateur"] = $row["idoperateur"];
		}
	}
	$sql_ceco="SELECT nombre 
				FROM ceco
				WHERE id='".$id["cecoid"]."'";
	$cecos=mysqli_query($connection, $sql_ceco);
	if (mysqli_num_rows($cecos) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($cecos)) {
			$ceco=$row["nombre"];
		}
	}
	$sql_nomcompte="SELECT nombre 
				FROM nomcompte
				WHERE id='".$id["nomcompteid"]."'";
	$nomcomptes=mysqli_query($connection, $sql_nomcompte);
	if (mysqli_num_rows($nomcomptes) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($nomcomptes)) {
			$nomcompte=$row["nombre"];
		}
	}
	$sql_codevice="SELECT nombre 
				FROM codevice
				WHERE id='".$id["codeviceid"]."'";
	$codevices=mysqli_query($connection, $sql_ceco);
	if (mysqli_num_rows($codevices) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($codevices)) {
			$codevice=$row["nombre"];
		}
	}
	return $info;
}
// Not Used
function neobis_falabella_quintec_db_upload($filedir, $moisfacturation, $datefacture, $dateone, $datetwo,$idoperateur, $nomcompte, $ceco, $codedevise, $proveedor ){
	
	//Mysql connection
	$connection = neobis_mysql_conection();
	
	//Looking for max id
	$maxid_sql = "SELECT MAX(id) AS maxid
			FROM item";
	$maxid = mysqli_query($connection, $maxid_sql);
	$maxid = mysqli_fetch_array($maxid);
	$maxid = $maxid['maxid'];
	if($proveedor == 'Quintec-Arriendo'){
	$facturename = "Falabella.PC-Arriendo-".$moisfacturation;
	}elseif($proveedor == 'Quintec-Soporte'){
		$facturename = "Falabella.PC-Soporte-".$moisfacturation;
	}
	$reader = ReaderFactory::create(Type::XLSX);
	$reader->open($filedir);
	
	$delete_sql = "DELETE FROM item WHERE nofacture like '".$facturename."'";
	mysqli_query($connection, $delete_sql);
	$count = 0;
	$i=0;
	foreach ($reader->getSheetIterator() as $sheet) {
		$sheetname=$sheet->getName();
		if($sheetname ==="Base"){
			foreach ($sheet->getRowIterator() as $row) {
				if($i>7){
					if($proveedor == 'Quintec-Arriendo'){
						$montant_charge = $row[17] + $row[18];
					}elseif($proveedor == 'Quintec-Soporte'){
						$montant_charge = $row[19] + $row[20];
					}
					$insert = "INSERT INTO `item`(`moisfacturation`, `datefacturation`, `datefacture1`, `datefacture2`, `codedevise`, `idoperateur`, `nomcompte`, `centrefacturation`, `nofacture`, `noappel`, `libelle_charge`, `montant_charge`, `m_total`) 
					VALUES ('".$moisfacturation."', '".$datefacture."', '".$dateone."', '".$datetwo."', '".$codedevise."', '".$idoperateur."', '".$nomcompte."', '".$ceco."', '".$facturename."', '".$row[11]."', '".$row[7]."', '".$montant_charge."', '".$montant_charge."')";
					if(mysqli_query($connection, $insert)){
						$count++;
					} else {
						$error=  "Error: " . $sql . "<br>" . mysqli_error($connection);
					
					}
				}
				$i++;
			}  
		}elseif($sheetname ==="Incorporaciones"){
			$i=0;
			foreach ($sheet->getRowIterator() as $row) {
				if($i!=0){
					
					if($proveedor == 'Quintec-Arriendo'){
						$montant_charge = $row[25] + $row[26];
					}elseif($proveedor == 'Quintec-Soporte'){
						$montant_charge = $row[27] + $row[28];
					}
					$insert = "INSERT INTO `item`(`moisfacturation`, `datefacturation`, `datefacture1`, `datefacture2`, `codedevise`, `idoperateur`, `nomcompte`, `centrefacturation`, `nofacture`, `noappel`, `libelle_charge`, `montant_charge`, `m_total`)
					VALUES ('".$moisfacturation."', '".$datefacture."', '".$dateone."', '".$datetwo."', '".$codedevise."', '".$idoperateur."', '".$nomcompte."', '".$ceco."', '".$facturename."', '".$row[13]."', '".$row[8]."', '".$montant_charge."', '".$montant_charge."')";
					if(mysqli_query($connection, $insert)){
						$count++;
					} else {
						$error=  "Error: " . $sql . "<br>" . mysqli_error($connection);
					
					}	
						
				}
				$i=99999;
			}
		}else{
			break;		
		}
			
	}
	$reader->close();
	if(isset($error)){
		return $error;
	}else{
		return $facturename;
	}


}
// Not Used
function neobis_quintec_csv($facturename, $header){
	// Creating data base connection
	$connection = neobis_mysql_conection();
	// Getting sum of the facture
	$sum_sql="SELECT SUM(m_total) as Total
			FROM item
			WHERE nofacture LIKE '".$facturename."'";
	$sum = mysqli_query($connection, $sum_sql);
	$sum = mysqli_fetch_array($sum);
	$sum = $sum['Total'];
	$sum = round($sum, 4);
	//getting sum with IVA
	$sum_iva = $sum *1.19;
	$sum_iva = round($sum_iva, 4);
	//Getting separated IVA
	$iva = $sum_iva - $sum;
	$iva = round($iva, 4);
	//changing decimal with period to decimal with colon	
	$sum = explode(".", $sum);
	$sum = implode(",", $sum);
	$sum_iva = explode(".", $sum_iva);
	$sum_iva = implode(",", $sum_iva);
	$iva = explode(".", $iva);
	$iva = implode(",", $iva);
	
	//Updating data on DB to export CSV
	$update_sql = "UPDATE item
			SET m_total_facture = '".$sum."', m_total_ttc_facture = '".$sum_iva."', m_tva = '".$iva."'
			WHERE nofacture like '".$facturename."'";
	// Excecuting query and error reporting
	if(mysqli_query($connection, $update_sql)){
		$count++;
	} else {
		$error=  "Error: " . $sql . "<br>" . mysqli_error($connection);
			
	}

	//Getting information for importable CSV
	$csv_sql = "SELECT moisfacturation, datefacturation, datefacture1, datefacture2, codedevise, idoperateur, nomcompte, centrefacturation, nofacture, m_total_facture, m_total_ttc_facture, noappel, libelle_charge, montant_charge, m_total, m_tva
			FROM item
			WHERE nofacture like '".$facturename."'";
	// Query excecution
	$csv = mysqli_query($connection, $csv_sql);
	$import = array();
	$count=1;
	$import[0] = $header;
	if (mysqli_num_rows($csv) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($csv)) {
			
			$row['montant_charge'] = explode(".", $row['montant_charge']);
			$row['montant_charge'] = implode(",", $row['montant_charge']);
			$row['m_total'] = $row['montant_charge'];
			$import[$count]=$row;
			$count++;
		}
	}
	return $import;
}
/**
 * Showing transformation table for validation
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
function neobis_print_table($client, $provider, $filedir, $header, $selections, $campos,  $moisfacturation, $facturationdate, $dateone, $datetwo,$idoperateur, $nomcompte, $ceco, $codedevise){
	// Creating connection
	$connection = neobis_mysql_conection();
	// Creating file reader
	$reader = ReaderFactory::create(Type::XLSX);
	// Opening file
	$reader->open($filedir);
	$assoc=array();
	// Going through fields
	foreach($campos as $campo){
		// Sees if the field is set
		if(isset($selections[$campo])){
			// Create new array associating field with possition on the array
			$assoc[$campo] = array_search($selections[$campo], $header);
		}
	}
	// Checking for a facture name if it don't exist it assigns one
	if(isset($_SESSION["facturename"])){
		$facturename=$_SESSION["facturename"];
	}else{
		$facturename=$client."-".$provider."-".$moisfacturation;
		$_SESSION["facturename"] = $facturename;
	}
	// Clearing data base query creation
	$delete_sql = "DELETE FROM item WHERE nofacture like '".$facturename."'";
	// Query excecution
	mysqli_query($connection, $delete_sql);
	// Getting fields where to insert
	$insert_fields= neobis_insertinto_fields();
	//var_dump($insert_fields);echo"<br>";
	// Facture header Values
	$header_value = neobis_insertinto_values( $moisfacturation, $facturationdate, $dateone, $datetwo,$idoperateur, $nomcompte, $ceco, $codedevise, $facturename);
	// Going through file sheets
	foreach($reader->getSheetIterator() as $sheet){
		// Setting a counter
		$header_out=0;
		//$count_row = 0;
		// Going through rows from sheet
		foreach ($sheet->getRowIterator() as $row){
			if($header_out != 0){
				$sql_insert = "INSERT INTO `item` (";
				$sql_values = "VALUES (";
				// Going through fields
				foreach($campos as $campo){
					// Sees if the field is set
					if(isset($assoc[$campo])){
						// Create new array associating field with possition on the array
						if($campo == "libelle_charge"){
							$file[$campo] = $row[$assoc[$campo]].".AD";
						}else{
							$file[$campo] = $row[$assoc[$campo]];
						}
					}
				}
				$sum = $sum + $file["m_total"];
				$file=array_merge($header_value, $file);
				$file=neobis_replacing_colon($file);
				$first_entry = 0;
				foreach($insert_fields as $insert_field){
					if(isset($file[$insert_field])){
						if($first_entry != "0"){
							// Inserting colon
							$sql_insert .= ",";
							$sql_values .= ",";
						}
						// Table header
						// Inserting fields and values
						$sql_insert .= " `".$insert_field."`";
						$sql_values .= " '".$file[$insert_field]."'";
						$first_entry ++;	
					}else{
					//	$insert_fields=array_diff($insert_fields, [$insert_field]);
						continue;
					}
				}
				// Closing parenthesis
				$sql_insert .= ")";
				$sql_values .= ")";
				$insert = $sql_insert." ".$sql_values;
				// Uploading to data base
				mysqli_query($connection, $sql_insert." ".$sql_values);
			}
			$header_out=99999;
		}
	}
	// Rounding
	$sum = round($sum, 4);
	//getting sum with IVA
	$sum_iva = $sum *1.19;
	$sum_iva = round($sum_iva, 4);
	//Getting separated IVA
	$iva = $sum_iva - $sum;
	$iva = round($iva, 4);
	//changing decimal with period to decimal with colon
	$sum = explode(".", $sum);
	$sum = implode(",", $sum);
	$file["m_total_facture"]=$sum;
	$sum_iva = explode(".", $sum_iva);
	$sum_iva = implode(",", $sum_iva);
	$file["m_total_ttc_facture"]=$sum_iva;
	$iva = explode(".", $iva);
	$iva = implode(",", $iva);
	$file["m_tva"]=$iva;
	$count=0;

	//Updating data on DB to export CSV
	$update_sql = "UPDATE item
			SET m_total_facture = '".$sum."', m_total_ttc_facture = '".$sum_iva."', m_tva = '".$iva."'
			WHERE nofacture like '".$facturename."'";
	// Query excecution
	if(mysqli_query($connection, $update_sql)){
		$count++;
	}
	// Creating table header
	$encabezados=array();
	$table_search = " ";
	$colon_insert = 1;
	foreach($file as $key=>$data){
		
		$encabezados[] = $key;
		$table_search .= $key;
		if($colon_insert!=count($file)){
			$table_search .= ", ";
			$colon_insert ++;
		}else{
			$table_search .= " ";
		}
	}
	//var_dump($encabezados);die();
	// Getting table to show 
	$table_sql = "SELECT".$table_search."
			FROM item
			WHERE nofacture like '".$facturename."'
			LIMIT 10";
	// Query excecution
	$table = mysqli_query($connection, $table_sql);
	$table_show = array();
	if (mysqli_num_rows($table) > 0) {
		// output data of each row, saving results
		while($row = mysqli_fetch_assoc($table)) {
			$table_show[] = $row;
		}
	}
	//var_dump($encabezados);die();
	
	$count=1;
	
	// Starting table
	$show = "<html><table border = '1' align = 'center'>";
	$show .= "<tr>";
	// Writing header in table
	foreach ($encabezados as $encabezado){
		$show .= "<td>".$encabezado."</td>";
	}
	$show .= "</tr>";
	// writing content on table
	foreach($table_show as $line){
		$show .= "<tr>";
		//var_dump($cell);
		foreach($line as $cell){
			$show .= "<td>".$cell."</td>";
		}
		$show .= "</tr>";
	}
	// Closing table
	$show .= "</table></html>";
	// Return table
	return array($show, $encabezados);
	
}
/**
 * Get and returns fields to insert into database
 * @return array
 */
function neobis_insertinto_fields(){
	// Data base connection
	$connection = neobis_mysql_conection();
	// Crating array
	$fields = array();
	// SQL to get all posible fields
	$fields_sql = "SELECT * FROM campos";
	// Query excecution
	$result = mysqli_query($connection, $fields_sql);
	// Getting results
	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			$fields[] = $row['nombre'];
		}
	}
	// Base array definition
	$array = array("moisfacturation", "datefacturation", "datefacture1", "datefacture2", "codedevise", "idoperateur", "nomcompte", "centrefacturation", "nofacture", "m_tva");
	// array merge
	$fields = array_merge( $array, $fields);
	return $fields;
}
/**
 * Array with values of the standard fields to insert into database
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
function neobis_insertinto_values( $moisfacturation, $facturationdate, $dateone, $datetwo,$idoperateur, $nomcompte, $ceco, $codedevise, $nofacture){
	// Array with values of the standard fields to insert into database
	$array = array(
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
 *  Replaces period with colon on numbers
 * @param array $row
 */
function neobis_replacing_colon($row){
	// Checking if the field is set
	if(isset($row["montant_charge"])){
		// Take "." off 
		$row["montant_charge"] = explode(".", $row["montant_charge"]);
		// Unite with ","
		$row["montant_charge"] = implode(",", $row["montant_charge"]);
	}
	if(isset($row["m_total"])){	
		$row["m_total"] = explode(".", $row["m_total"]);
		$row["m_total"] = implode(",", $row["m_total"]);
	}
	if(isset($row["m_hors_voix"])){
		$row["m_hors_voix"] = explode(".", $row["m_hors_voix"]);
		$row["m_hors_voix"] = implode(",", $row["m_hors_voix"]);
	}	
	if(isset($row["m_hors_data"])){
		$row["m_hors_data"] = explode(".", $row["m_hors_data"]);
		$row["m_hors_data"] = implode(",", $row["m_hors_data"]);
	}	
	if(isset($row["m_remises_nondefini"])){
		$row["m_remises_nondefini"] = explode(".", $row["m_remises_nondefini"]);
		$row["m_remises_nondefini"] = implode(",", $row["m_remises_nondefini"]);
	}	
	if(isset($row["m_autre_nondefini"])){	
		$row["m_autre_nondefini"] = explode(".", $row["m_autre_nondefini"]);
		$row["m_autre_nondefini"] = implode(",", $row["m_autre_nondefini"]);
	}
	return $row;
}
/**
 * Update data base with the colon replacement
 * @param array $row
 * @param unknown $connection
 * @return boolean|NULL
 */
function neobis_upload_colon_replacement($row, $connection){
	// Creating query
	$sql = "UPDATE item 
	SET ";
	// Conditions to update
	if(isset($row["montant_charge"])){
		$sql .= "montant_charge = '".$row["montant_charge"]."'";
				
	}
	if(isset($row["m_total"])){
		$sql .= ", m_total = '".$row["m_total"]."'";
	}
	if(isset($row["m_hors_voix"])){
		$sql .= ", m_hors_voix = '".$row["m_hors_voix"]."'";
	}
	if(isset($row["m_hors_data"])){
		$sql .= ", m_hors_data = '".$row["m_hors_data"]."'";
	}
	if(isset($row["m_remises_nondefini"])){
		$sql .= ", m_remises_nondefini = '".$row["m_remises_nondefini"]."'";
	}
	if(isset($row["m_autre_nondefini"])){
		$sql .= ", m_autre_nondefini = '".$row["m_autre_nondefini"]."'";
	}
	$sql .= "WHERE nofacture like '".$row["nofacture"]."'";
	if(mysqli_query($connection, $sql)){
		return TRUE;
	}else{
		return NULL;
	}
	
}
/**
 * Button set to go back from to any point before and to download file
 * @param string $facturename
 * @param string $dir
 * @return string
 */
function neobis_back_fromtable($facturename, $dir){
	$output = "<html><body><div align = 'center'><form method='POST' action='index.php'>";
	$output .= "<button type='submit' name='dates' value='Enviar'>Me equivoqué de archivo</button>";
	$output .= "<button type='submit' name='file' value='Importar'>Volver a elegir campos</button>";
	$output .= "</form>";
	$output .= "<div align = 'center'><form method='POST' action='download.php'>";
	$output .="<button type='submit' value='Enviar'>Descargar</button>";
	return $output;
	
}
/**
 * Creating file variable with all the information
 * @param string $facturename
 * @return array
 */
function neobis_create_file_information($facturename, $headers){
	// Connetcing to te data base
	$connection = neobis_mysql_conection();
	// Creating and excecuting query
	$first = 0;
	$query = "SELECT ";
	foreach($headers as $header){
		if($first != 0){
			$query .=", ";
			
		}
		$query .= $header;
		$first = 9999;
	}
	$query.= " FROM item WHERE nofacture LIKE '".$facturename."'";
	$prueba = mysqli_query($connection, $query );
	$pos = 1;
	$csv[0] = $headers;
	// Going through query result
	if (mysqli_num_rows($prueba) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($prueba)) {
			// inserting row to file variable
			$csv[$pos] = $row;
			$pos++;
		}
	}
	return $csv;
}
/**
 * Checking for date errors
 * @param string $factdate
 * @param string $indate
 * @param string $findate
 * @return multitype:boolean string
 */
function neobis_date_error($factdate, $indate, $findate){
	// Error creation
	$error = "<div align = 'center'><h1>Error/es:</h1>";
	// boolean for die() creation
	 $boolean = FALSE;
	$result=array();
	// Checking if there is any date missing
	if (! preg_match ( "/(.*)-(.*)-(.*)/", $indate, $result ) || ! preg_match ( "/(.*)-(.*)-(.*)/", $findate, $result ) || ! preg_match ( "/(.*)-(.*)-(.*)/", $factdate, $result )) {
		$error .= "<br> No se seleccionaron las fechas requeridas";
		// boolean for die() creation
		$boolean = TRUE;
	}
	// Date sepatration for comparisson
	$factdate = explode("-", $factdate);
	$indate = explode("-", $indate);
	$findate = explode("-", $findate);

	// Date inconsistance comparisson
	if($factdate[2] < $indate[2] || $factdate[2] < $findate[2]){
		$error .= "<br>El año de facturación es menor al año del periodo de facturación ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	if($factdate[1] < $indate[1] || $factdate[1] < $findate[1]){
		$error .= "<br>El mes de facturación es menor al mes del periodo de facturación ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	if(mktime(0,0,0,$factdate[0], $factdate[1], $factdate[2]) > mktime(0,0,0,$indate[0], $indate[1], $indate[2]) && mktime(0,0,0,$factdate[0], $factdate[1], $factdate[2]) < mktime(0,0,0,$findate[0], $findate[1], $findate[2])){
		$error .= "<br>La fecha de facturación está dentro del periodo de facturación";	
		$boolean = TRUE;
	}
	if($indate[2] != $findate[2]){
		$error .= "<br>El año de inicio de periodo es diferente al año de fin periodo de facturación ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	if($indate[1] > $findate[1]){
		$error .= "<br>El mes de inicio de periodo es mayor al mes de fin periodo de facturación ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	if($indate[0] > $findate[0] && $indate[2] == $findate[2] && $indate[3] == $findate[3]){
		$error .= "<br>El día de inicio de periodo es mayor al día de fin periodo de facturación ";
		// boolean for die() creation
		$boolean = TRUE;
	}
	
	$error .= "<br><br><form method='POST'><input type='submit' name='Volver' value='Volver'></form>";
	$error .= "</div>";
	return array($boolean, $error);
}
/**
 * Checking if the relationship of client and provider selected exists
 * @param string $client
 * @param string $provider
 */
function neobis_client_provider_existance($client, $provider){
	$connection = neobis_mysql_conection();
	$clientid_sql = "SELECT id 
			FROM clientes
			WHERE nombre like '".$client."'";
	$providerid_sql = "SELECT id 
			FROM proveedores
			WHERE nombre like '".$provider."'";
	
	$result = mysqli_query($connection, $clientid_sql); 
	$clientid = mysqli_fetch_assoc($result);
	
	$result= mysqli_query($connection, $providerid_sql);
	$providerid = mysqli_fetch_assoc($result);
	$existance_sql = "SELECT id
			FROM cliente_proveedor
			WHERE proveedores_id = '".$providerid["id"]."' AND clientes_id = '".$clientid["id"]."'";
	
	$result = mysqli_query($connection, $existance_sql);
	$existance = mysqli_fetch_array($result);
	if($existance == NULL){
		return array($existance, "<div align = 'center'><h1>Error:</h1><br>La combinación de Cliente y Proveedor no existe<br><br><form method='POST'><input type='submit' name='Volver' value='Volver'></form>");
	}else{
		return array($existance, "No hay Error");
	}
}