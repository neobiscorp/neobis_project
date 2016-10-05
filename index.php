<?php
set_time_limit(900);
// Look for lib
require_once dirname ( __FILE__ ) . '/lib/Classes/PHPExcel.php';
require_once dirname ( __FILE__ ) . '/lib/Classes/PHPExcel/IOFactory.php';
require_once dirname ( __FILE__ ) . '/lib/lib.php';
require_once dirname ( __FILE__ ) . '/lib/spout-master/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\CSV;
use Box\Spout\Common\Type;

//inicio de secion para paso de informacion a la siguiente pagina
session_start();

//Logo Neobis
echo "<p align='center'><img  src='img/LOGONEOBISOK.jpeg' align='middle'></p>";

if($_POST["fechas"] == "Enviar") {
	//primer formulario llenado
	
	
	

	//llenado de informacion necesaria para la siguiente pagina
	$_SESSION['proveedor']=$_POST ['proveedor'];
	$_SESSION['factdate']=$_POST ['factdate'];
	$_SESSION['cliente']=$_POST ['cliente'];
	$_SESSION['indate']=$_POST ['indate'];
	$_SESSION['findate']=$_POST ['findate'];

	echo neobis_file_uploader_form($_SESSION['factdate'], $_SESSION['indate'], $_SESSION['findate'], $_SESSION['proveedor'], $_SESSION['cliente']);
	die();
}elseif($_POST["Volver"]){
	echo neobis_select_provider_form();
	die();
}elseif($_POST["fichero"]=="Importar"){
	
	if (!$_FILES ['file'] ['name']){
	
		die("No hay archivo, vuelva a intentar");
	}
	//die("archivo cargado correctamente");
	
	
	
	
	$filename = explode ( "_", $_FILES ['file'] ['name'] );
	
		//collecting data for file
		$connection = neobis_mysql_conection();
		
		//get idoperateur
		$idoperateur_sql= "SELECT idoperateur FROM proveedores WHERE nombre LIKE '".$_SESSION['proveedor']."'";
		$idoperateur = mysqli_query($connection, $idoperateur_sql);
		$idoperateur=mysqli_fetch_array($idoperateur);
		$idoperateur = $idoperateur['idoperateur'];
		//get ceco, nomcompte, codevice id
		$info_sql = "SELECT ceco_id, nomcompte_id, codedevise_id
					 FROM cliente_proveedor
					 JOIN (
   						 SELECT clientes.id AS clienteid, proveedores.id AS proveedorid
  						 FROM clientes, proveedores
   						 WHERE clientes.nombre LIKE '".$_SESSION['cliente']."' AND proveedores.nombre LIKE '".$_SESSION['proveedor']."') AS a
					 ON proveedores_id = a.proveedorid AND clientes_id = a.clienteid";
		$info = mysqli_query($connection, $info_sql);;
		$info = mysqli_fetch_array($info);
		
		//get CECO
		$ceco_sql = "SELECT nombre FROM ceco WHERE id='".$info['ceco_id']."'";
		$ceco = mysqli_query($connection, $ceco_sql);
		$ceco = mysqli_fetch_array($ceco);
		$ceco = $ceco['nombre'];
		// get nomcompte
		$nomcompte_sql = "SELECT nombre FROM nomcompte WHERE id='".$info['nomcompte_id']."'";
		$nomcompte = mysqli_query($connection, $nomcompte_sql);
		$nomcompte = mysqli_fetch_array($nomcompte);
		$nomcompte = $nomcompte['nombre'];
		//get codevice
		$codedevise_sql="SELECT nombre FROM codedevise WHERE id='".$info['codedevise_id']."'";
		$codedevise = mysqli_query($connection, $codedevise_sql);
		$codedevise = mysqli_fetch_array($codedevise);
		$codedevise = $codedevise['nombre'];
		//fecha de facturacion
		$facturationdate = $_SESSION['factdate'];
		$facturationdate = explode("-", $facturationdate);
		$facturationdate = $facturationdate[2]."/".$facturationdate[1]."/".$facturationdate[0]; 
		
		//fecha de inicio de periodo de facturacion
		$dateone= $_SESSION['indate'];
		$dateone=explode("-", $dateone);
		$dateone=$dateone[2]."/".$dateone[1]."/".$dateone[0];

		//fecha de termino e periodo de facturacion
		$datetwo= $_SESSION['findate'];
		$datetwo=explode("-", $datetwo);
		$datetwo=$datetwo[2]."/".$datetwo[1]."/".$datetwo[0];
		
		//mes de facturacion
		$moisfacturation = $_SESSION['factdate'];
		$moisfacturation = explode("-", $moisfacturation);
		$moisfacturation = $moisfacturation[2].$moisfacturation[1];
		
		$filename = explode ( ".", $_FILES ['file'] ['name'] );
		
		//file destination
		$base_filedir= dirname(__FILE__).'/uploads';
		$filedir = $base_filedir."/".$_FILES['file']['name'] ;
		
		//datos
		$totalfactura=0;
		$filas=array();
		$factdata = array();
		
		// load file
		if(move_uploaded_file($_FILES['file']['tmp_name'], $filedir)){
			
			$fields=neobis_get_fields($_SESSION['cliente'], $_SESSION['proveedor']);
			$_SESSION["campos"]=$fields;
			$campos=array();
			$extra=array();
			if (mysqli_num_rows($fields) > 0) {
				// output data of each row
				while($row = mysqli_fetch_assoc($fields)) {
					$campos[]=$row["nombre"];
					$extra[0] = $row["ce1"];
					$extra[1] = $row["ce2"];
					$extra[2] = $row["ce3"];
					$extra[3] = $row["ce4"];
					$extra[4] = $row["ce5"];
				}
			}
			$count=0;
			$factdata[$count]=array("moisfacturation", "datefaturation", "datefacture1", "datefacture2", "codedevise", "idoperateur", "nomcompte", "centrefacturation", $campos[7], $campos[8],$campos[9],$campos[10], $campos[11], $campos[0], $campos[1], $campos[4]);
			$count++;
			$filename = explode ( ".", $_FILES ['file'] ['name'] );
			
			//Proceso de factuas para demo de quintec
			if($filename [0]== "Quintec"){
				//ingresar funcion de procesado de factura de quintec para prueba
				$facturename = neobis_falabella_quintec_db_upload($filedir, $moisfacturation, $facturationdate, $dateone, $datetwo,$idoperateur, $nomcompte, $ceco, $codedevise, $_SESSION['proveedor']);
				$data = neobis_quintec_csv($facturename, $factdata[0]);
				
				$writer= WriterFactory::create(Type::CSV);
				$writer->setFieldDelimiter(';');
				if($_SESSION['proveedor'] == 'Quinte-Arriendo'){
					$writer->openToFile( dirname(__FILE__)."/uploads/QuintecArriendo.csv");
				}elseif($_SESSION['proveedor']=='Quintec-Soporte'){
					$writer->openToFile(dirname(__FILE__)."/uploads/QuintecSoporte.csv");
				}
				$writer->addRows($data);
				$writer->close();
				
				die("Quintec Creado!");
			}
			//proceso de actur para demo de Adessa PC
			//Reader Creation
			$reader = ReaderFactory::create(Type::XLSX);
			$reader->open($filedir);
			$i=0;
			$j=0;
			$facturename = "Falabella.Ad.Pc-".$moisfacturation;
			$delete_sql = "DELETE FROM item WHERE nofacture like '".$facturename."'";
			mysqli_query($connection, $delete_sql);
			
			foreach ($reader->getSheetIterator() as $sheet) {
				$sheetname=$sheet->getName();
				
				if($sheetname ==="1.SI FACTURAR"){
					foreach ($sheet->getRowIterator() as $row) {
						if($i!=0){
							$insert = "INSERT INTO `item`(`moisfacturation`, `datefacturation`, `datefacture1`, `datefacture2`, `codedevise`, `idoperateur`, `nomcompte`, `centrefacturation`, `nofacture`, `noappel`, `libelle_charge`, `montant_charge`, `m_total`) 
								VALUES ('".$moisfacturation."', '".$facturationdate."', '".$dateone."', '".$datetwo."', '".$codedevise."', '".$idoperateur."', '".$nomcompte."', '".$ceco."', '".$facturename."', '".$row[7]."', '".$row[3]."Ad', '".$row[9]."', '".$row[9]."')";
							if(mysqli_query($connection, $insert)){
								$j++;
							}
							
						}
						$i=99999;
						
					}
						
				}else{
					break;
				}
						
				
			}
			
			$import = neobis_quintec_csv($facturename, $factdata[0]);
			
			
		/*	$montant_charge = explode(".", $row[9]);
			$montant_charge = implode(",", $montant_charge);
			$factdata[$count] = array($moisfacturation,$facturationdate, $dateone, $datetwo, $codedevise, $idoperateur, $nomcompte, $ceco, "Falabella.Ad.PC-".$moisfacturation, "16571,66", "19720,2754", $row[7],  $row[3].".AD", $montant_charge, $montant_charge, "3148,0646" );
			$count++;*/
			
			
			
			
			
			$reader->close();
			$writer= WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile( dirname ( __FILE__ ) ."/uploads/A2AAdessaPc.csv");
			$writer->addRows($import);
			$writer->close();
	
			
			
			die("A2A Creado!");
			// selección de hoja está hecho para un archivo en específico.
			var_dump(neobis_A2A($sheet, $_SESSION["indate"], $_SESSION["findate"], $_SESSION["factdate"], $_SESSION["cliente"], $_SESSION["proveedor"]));
		
		
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
			$worksheet=$objExcel->getSheet(0);
		
			$header=neobis_extract_header($worksheet);
			$fields=neobis_get_fields($_SESSION['cliente'], $_SESSION['proveedor']);
			$_SESSION["campos"]=$fields;

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