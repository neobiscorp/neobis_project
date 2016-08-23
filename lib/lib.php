<?php
// Letter definition for Excel filling
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
function neobis_file_uploader_form(){
	$output= "<html>";
	$output .="<body>";
	$output .= "<form method='POST' enctype='multipart/form-data' action='excelprocess.php'>";
	$output .= "<div align='center'>";
	$output .= "<fieldset><legend> Seleccione un archivo para subir </legend>";
	$output .= "<input type='hidden' name='MAX_FILE_SIZE' value='300000000' />";
	$output .= "<p> Elegir Archivo: <input type='file' name='file'/></p>";
	$output .= "<p> <input type='submit' name='submit' value='Importar'></p>";
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
		$connection = mysqli_connect('localhost', 'root', '', 'neobis');
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
	$output= "<html>";
	$output .="<body>";
	$output .= "<form method='POST'>";
	$output .= "<fieldset><legend> Seleccione Poveedor </legend>";
	$output .= "<div align='center'>";
	$output .= "<p> Fecha de facturación:</p>";
	$output .= "<p><input type='text' name='factdate' placeholder='Ej: 201608'></p>";
	$output .= "<p> <select name='providers'></p>";
	$output .= "<p> <option value='ricoh'>Ricoh</option></p>";
	$output .= "<p> <option value='lexmark'>Lexmark</option></p>";
	$output .= "<br><p> <input type='submit' name='enviar' value='Enviar'></p>";
	$output .= "</div></fieldset></form></body></html>";
	return $output;
}
function neobis_boton_volver(){
	
}