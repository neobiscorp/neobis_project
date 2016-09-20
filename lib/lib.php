<?php
/**
 *  Letter definition for Excel filling
 */
function neobis_excel_letters() {
	return $letras = array (
			1 => 'A',
			2 => 'B',
			3 => 'C',
			4 => 'D',
			5 => 'E',
			6 => 'F',
			7 => 'G',
			8 => 'H',
			9 => 'I',
			10 => 'J',
			11 => 'K',
			12 => 'L',
			13 => 'M',
			14 => 'N',
			15 => 'O',
			16 => 'P',
			17 => 'Q',
			18 => 'R',
			19 => 'S',
			20 => 'T',
			21 => 'U',
			22 => 'V',
			23 => 'W',
			24 => 'X',
			25 => 'Y',
			26 => 'Z',
			27 => ' AA',
			28 => ' AB',
			29 => ' AC',
			30 => ' AD',
			31 => ' AE',
			32 => ' AF',
			33 => ' AG',
			34 => ' AH',
			35 => ' AI',
			36 => ' AJ',
			37 => ' AK',
			38 => ' AL',
			39 => ' AM',
			40 => ' AN',
			41 => ' AO',
			42 => ' AP',
			43 => ' AQ',
			44 => ' AR',
			45 => ' AS',
			46 => ' AT',
			47 => ' AU',
			48 => ' AV',
			49 => ' AW',
			50 => ' AX',
			51 => ' AY',
			52 => ' AZ',
			53=>' BA',
			54=>' BB',
			55=>' BC',
			56=>' BD',
			57=>' BE',
			58=>' BF',
			59=>' BG',
			60=>' BH',
			61=>' BI',
			62=>' BJ',
			63=>' BK',
			64=>' BL',
			65=>' BM',
			66=>' BN',
			67=>' BO',
			68=>' BP',
			69=>' BQ',
			70=>' BR',
			71=>' BS',
			72=>' BT',
			73=>' BU',
			74=>' BV',
			75=>' BW',
			76=>' BX',
			77=>' BY',
			78=>' BZ'
				
	)
	;
}
function neobis_insert_data_infobase(string $table, array $row_data){
	
	$sql="INSERT INTO `info_base`
	( `moisfacturation`, `datefacturation`, `datefacture1`, `datefacture2`, `codedevise`, `idoperateur`,
	 `nomcompte`, `centrefacturation`, `nofacture`, `m_total_facture`, `m_total_ttc_facture`, `noappel`, `libelle_charge`, `m_total`,
	  `montant_charge`, `m_hors`, `m_remises`, `m_autre`) 
	  VALUES ([value-2],[value-3],[value-4],[value-5],[value-6],
	  [value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],
	  [value-15],[value-16],[value-17],[value-18],[value-19])";
}
function neobis_file_uploader_form($fechafact, $fechain, $fechafin, $proveedor, $cliente){
	$output= "<html>";
	$output .="<body>";
	$output .= "<form method='POST' enctype='multipart/form-data' action='index.php'>";
	$output .= "<div align='center'>";
	$output .= "<fieldset><legend align='center'> Seleccione un archivo para subir </legend>";
	$output .= "<table align='center'>";
	$output .= "<tr>";
	$output .= "<td>Fecha de facturación: ".$fechafact."</td>";
	$output .= "<td></td><td></td><td></td><td></td><td></td><td></td>";
	$output .= "<td>Cliente: ".$cliente."</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>Fecha de inicio: ".$fechain."</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>Fecha de fin: ".$fechafin."</td>";
	$output .= "<td></td><td></td><td></td><td></td><td></td><td></td>";
	$output .= "<td>Proveedor: ".$proveedor."</td>";
	$output .= "</tr>";
	$output .= "</table>";
	$output .= "<input type='hidden' name='MAX_FILE_SIZE' value='300000000' />";
	$output .= "<p> Elegir Archivo: <input type='file' name='file'/></p>";
	$output .= neobis_boton("index.php", "Volver");
	$output .= "<input type='submit' name='fichero' value='Volver'>";
	$output .= "<input type='submit' name='fichero' value='Importar'>";
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
function neobis_data_upload_ricoh($worksheet){
	$count=0;
	$highetsRow = $worksheet->getHighestRow ();
	for($row = 2; $row <= $highetsRow; $row ++) {
		
		$dato = $worksheet->getCellByColumnAndRow ( 0, $row )->getValue ();
		$numserieuno = $worksheet->getCellByColumnAndRow ( 1, $row )->getValue ();
		$numseriedos = $worksheet->getCellByColumnAndRow ( 2, $row )->getValue ();
		$reempserie = $worksheet->getCellByColumnAndRow ( 3, $row )->getValue ();
		$cont_mono_antes = $worksheet->getCellByColumnAndRow ( 4, $row )->getValue ();
		$cont_mono_actual = $worksheet->getCellByColumnAndRow ( 5, $row )->getValue ();
		$cont_color_antes = $worksheet->getCellByColumnAndRow ( 6, $row )->getValue ();
		$cont_color_actual = $worksheet->getCellByColumnAndRow ( 7, $row )->getValue ();
		$uso_mono = $worksheet->getCellByColumnAndRow ( 8, $row )->getValue ();
		$uso_color = $worksheet->getCellByColumnAndRow ( 9, $row )->getValue ();
		$min_mono = $worksheet->getCellByColumnAndRow ( 10, $row )->getValue ();
		$min_color = $worksheet->getCellByColumnAndRow ( 11, $row )->getValue ();
		$costo_consumo_usd = $worksheet->getCellByColumnAndRow ( 12, $row )->getValue ();
		$costo_min_mono_usd = $worksheet->getCellByColumnAndRow ( 13, $row )->getValue ();
		$costo_min_color_usd = $worksheet->getCellByColumnAndRow ( 14, $row )->getValue ();
		$costo_total_usd = $worksheet->getCellByColumnAndRow ( 15, $row )->getValue ();
		$costo_total_clp= $worksheet->getCellByColumnAndRow ( 16, $row )->getValue ();
		$categoria = $worksheet->getCellByColumnAndRow ( 17, $row )->getValue ();
		$estado_info_adessa = $worksheet->getCellByColumnAndRow ( 18, $row )->getValue ();
		$negocio = $worksheet->getCellByColumnAndRow ( 19, $row )->getValue ();
		$obs_tecnico = $worksheet->getCellByColumnAndRow (20, $row)->getValue();
		$prop_adessa = $worksheet->getCellByColumnAndRow ( 21, $row )->getValue ();
		$modelo = $worksheet->getCellByColumnAndRow ( 22, $row )->getValue ();
		
		//DB connection
		$connection = mysqli_connect('localhost', 'root@localhost', '', 'neobis');
		if (!$connection){
			die("Connection Failed:".mysqli_connect_error());
		}			
		
		
		// Data not empty validation
		if(empty($dato)){
			$dato="N/A";
		}
		if(empty($estado_info_adessa)){
			$estado_info_adessa="N/A";
		}
		if(empty($prop_adessa)){
			$prop_adessa="N/A";
		}
		if (empty($obs_tecnico)){
			$obs_tecnico ="N/A";
		}
		
		//Sql Query
		$sql= "INSERT INTO
			`falab_ricoh_imp`
			(`dato`, `numserieuno`, `numseriedos`, `reempserie`, `cont_mono_antes`, `cont_mono_actual`, `cont_color_antes`, `cont_color_actual`, `uso_mono`, `uso_color`, `min_mono`, `min_color`, `costo_consumo_usd`, `costo_min_mono_usd`, `costo_min_color_usd`, `costo_total_usd`, `costo_total_clp`, `categoria`, `estado_info_adessa`, `negocio`, `obs_tecnico`, `prop_adessa`, `modelo`) 
			VALUES 
			('".$dato."', '".$numserieuno."', '".$numseriedos."', '".
			$reempserie."', '".$cont_mono_antes."', '".$cont_mono_actual."', '".$cont_color_antes."', '".$cont_color_actual."', '".
			$uso_mono."', '".$uso_color."', '".$min_mono."', '".$min_color."', '".$costo_consumo_usd."', '".
			$costo_min_mono_usd."', '".$costo_min_color_usd."', '".$costo_total_usd."', '".$costo_total_clp."','".
			$categoria."', '".$estado_info_adessa."', '".$negocio."', '".$obs_tecnico."', '".$prop_adessa."', '".$modelo."')";
		
		
		//Send Information
		if(mysqli_query($connection, $sql)){
			$count++;
		} else {
   			$error=  "Error: " . $sql . "<br>" . mysqli_error($connection);
   			
		}		
	
	}
	if(isset($error)){
		return $error;
	}else{
		return "Se han agregado ".$count." lineas";
	}
}
function neobis_select_provider_form(){
	
	// Conexion Base de Datos
	$connection=neobis_mysql_conection();
	//Busqueda de proveedores
	$sql_proveedores = "SELECT nombre FROM proveedores";
	$proveedores = mysqli_query($connection, $sql_proveedores);
	$proveedor=array();
	if (mysqli_num_rows($proveedores) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($proveedores)) {
			$proveedor[]=$row["nombre"];
		}
	}
	//Busqueda de Clientes
	$sql_clientes = "SELECT nombre FROM clientes";
	$clientes = mysqli_query($connection, $sql_clientes);
	$cliente=array();
	if (mysqli_num_rows($clientes) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($clientes)) {
			$cliente[]=$row["nombre"];
		}
	}
	
	//Formulario
	$output= "<html>";
	$output .="<body>";
	$output .= "<form method='POST'>";
	$output .= "<fieldset><legend align='center'> Seleccione Poveedor </legend>";
	$output .= "<div align='center'>";
	$output .= "<link rel='stylesheet' type='text/css' href='tcal.css' />";
	$output .= "<script type='text/javascript' src='tcal.js'></script>";
	$output .= "Fecha de facturación:";
	$output .= "<input type='text' name='indate' class='tcal' align='center'>";
	$output .= "  Fecha de inicio del periodo de facturación:";
	$output .= "<input type='text' name='findate' class='tcal' align='center'>";
	$output .= "  Fecha de fin del periodo de facturación:";
	$output .= "<input type='text' name='factdate' class='tcal' align='center'>";


	//Select clientes
	$output .= "<p> <select name='cliente'> </p>";
	foreach ($cliente as $client){
		$output .= "<p> <option value='".$client."'>".$client."</option></p>";
	}
	$output .= "</select>";
	
	//Select proveedores
	$output .= "<select name='proveedor'>";
	foreach ($proveedor as $prov){
	$output .= "<p> <option value='".$prov."'>".$prov."</option></p>";
	}
	$output .= "</select>";
	
	
	//Boton enviar
	$output .= "<br><p> <input type='submit' name='fechas' value='Enviar'></p>";
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
function neobis_boton($action,$value ){

	$output = "<form method='POST' action='".$action."'>";
	$output .= "<inpyt type='submit' name ='submit' value='".$value."'></form>";
	return $output;
}
function neobis_mysql_conection(){
	$connection = mysqli_connect('localhost', 'root', 'root', 'Neobis', '8889');
	if (!$connection){
		die("Connection Failed:".mysqli_connect_error());
	}
	return $connection;
} 
function neobis_extract_header($worksheet){
	$count=0;
	$header=array();
	$highestCol = $worksheet->getHighestColumn();
	$highestCol= array_search($highestCol, neobis_excel_letters());
	for($col = 0; $col < $highestCol; $col ++) {
		$header[] = $worksheet->getCellByColumnAndRow ( $col, 1)->getValue ();
	}
	return $header;	
}
function neobis_get_fields($cliente, $proveedor){
	$connection = neobis_mysql_conection();
	$sql= "SELECT campos.nombre, a.ce1, a.ce2, a.ce3, a.ce4, a.ce5
			FROM campos,(SELECT campos_base.campos_id AS cid, d.ce1, d.ce2, d.ce3, d.ce4, d.ce5
						FROM campos_base
						JOIN(SELECT cliente_proveedor.tipo_proveedores_id AS tpid, cliente_proveedor.col_extra_1 AS ce1, cliente_proveedor.col_extra_2 AS ce2,
							cliente_proveedor.col_extra_3 AS ce3, cliente_proveedor.col_extra_4 AS ce4, cliente_proveedor.col_extra_5 AS ce5
							FROM cliente_proveedor
							JOIN(SELECT proveedores.id as idproveedor, clientes.id as idcliente
								FROM proveedores, clientes
								WHERE proveedores.nombre LIKE '".$proveedor."' AND clientes.nombre LIKE '".$cliente."') AS s
							ON s.idproveedor=cliente_proveedor.proveedores_id AND s.idcliente=cliente_proveedor.clientes_id) AS d
						ON campos_base.tipo_proveedores_id=d.tpid) AS a
			WHERE a.cid=campos.id";
	
	
	$campos_sql=mysqli_query($connection, $sql);
	return $campos_sql;
}
/**
 * http://stackoverflow.com/questions/17714705/how-to-use-checkbox-inside-select-option
 * @param unknown $header
 * @param unknown $cliente
 * @param unknown $proveedor
 * @return string
 */
function neobis_select_fields($header, $campos_sql){
	
	$campos=array();
	$extra=array();
	if (mysqli_num_rows($campos_sql) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($campos_sql)) {
			$campos[]=$row["nombre"];
			$extra[0] = $row["ce1"];
			$extra[1] = $row["ce2"];
			$extra[2] = $row["ce3"];
			$extra[3] = $row["ce4"];
			$extra[4] = $row["ce5"];
		}
	}
	
	$encabezados = array();
	$desplegable = array();
	$output= "<html>";
	
	$output .="<body>";
	$output .= "<form method='POST'>";
	$output .= "<fieldset><legend align='center'> Selección de Campos </legend>";
	$output .= "<div align='center'>";
	$output .="<table style='overflow-x:scrol;' >";
	$output .="<tr>";
	
	foreach($campos as $field){
		$output .= "<th>".$field."</th>";
	}
	
	$output .="</tr>";
	$output .="<tr>";
	foreach($campos as $field){
		
		$output .= "<td><select multiple='multiple' name='".$field."'>";
		foreach($header as $title){
			$output .= "<option  value='".$title."'>".$title."</option>";
		}
		$output .= "</select>";
		$output .="</td>";
	}
	$output .="</tr>";
	$output .="</table>";

	
	
	
	$output .="<input type='submit' name='table' value='Siguiente'>"; 
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
function neobis_get_multiple_select($campos_sql) {
	$campos=array();
	if (mysqli_num_rows ( $campos_sql ) > 0) {
		// output data of each row
		while ( $row = mysqli_fetch_assoc ( $campos_sql ) ) {
			$campos [] = $row ["nombre"];
			$extra [0] = $row ["ce1"];
			$extra [1] = $row ["ce2"];
			$extra [2] = $row ["ce3"];
			$extra [3] = $row ["ce4"];
			$extra [4] = $row ["ce5"];
		}
	}
	$request=array();
	foreach($campos as $campo){
		foreach ($_POST[$campo] as $selection){
			$request[$campo]=$_POST[$campo];
		}
	}
	return $request;
}
































