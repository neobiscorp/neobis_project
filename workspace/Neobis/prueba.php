<?php
// Setting time limit to 15 minutes
set_time_limit(2100);
// Look for lib
require_once dirname ( __FILE__ ) . '/lib/Classes/PHPExcel.php';
require_once dirname ( __FILE__ ) . '/lib/Classes/PHPExcel/IOFactory.php';
require_once dirname ( __FILE__ ) . '/lib/lib.php';
require_once dirname ( __FILE__ ) . '/lib/spout-master/src/Spout/Autoloader/autoload.php';
// Using the Excel library
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\CSV;
use Box\Spout\Common\Type;
session_start();
// Neobis Logo
echo "<p align='center'><img  src='img/LOGONEOBISOK.jpeg' align='middle'></p>";
// arreglar para que lea lo qeu anda por URL
echo neobis_back_fromtable($_SESSION["facturename"], dirname(__FILE__)."/uploads/");
// Checking if there are selections saved
if(!isset($_SESSION["selections"])){
	$_SESSION["selections"] = $_POST;
}
// Getting table and file header
list($print, $encabezados) = neobis_print_table($_SESSION["client"], $_SESSION["provider"],  $_SESSION["filedir"], $_SESSION["header"], $_SESSION['selections'], $_SESSION["fields"],  $_SESSION["moisfacturation"], $_SESSION["facturationdate"], $_SESSION["dateone"], $_SESSION["datetwo"], $_SESSION["idoperateur"], $_SESSION["nomcompte"], $_SESSION["ceco"], $_SESSION["codedevise"]);
// Printing table for validation
echo $print;

// Creating file variable
$csv = neobis_create_file_information($_SESSION["facturename"], $encabezados);
// Create CSV writer
$writer= WriterFactory::create(Type::CSV);
// Saing file to this direction, name included
$writer->openToFile( dirname ( __FILE__ ) ."/downloads/".$_SESSION["facturename"].".csv");
// Setting delimiter
$writer->setFieldDelimiter(';');
// Adding information to file
$writer->addRows($csv);
// Closing writer
$writer->close();
// Adding buttons
echo neobis_back_fromtable($_SESSION["facturename"], dirname(__FILE__)."/uploads/");

// New button to going back to the begining
$backbutton = "<html><body><div align = 'center'><form method='POST' action = 'index.php'>";
$backbutton .= "<button type='submit' name='back' value='back'>Volver al inicio</button>";
$backbutton .= "</div></form></body></html>";
echo $backbutton;
die();