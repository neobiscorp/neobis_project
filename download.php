<?php
// Setting time limit to 15 minutes
set_time_limit(2100);
// Session start for passing variables throgh pages
session_start();
// File name with extension
$filename = $_SESSION["facturename"].".csv";
$filenames = explode(" ", $filename);
$filename ="";
foreach ($filenames as $part){
	$filename .= $part;
}

// Headers to download file
header('Content-Disposition: attachment; filename='.$filename);
header("Content-Type: text/csv");
header("Content-Length:". filesize(dirname(__FILE__)."/downloads/".$_SESSION["facturename"].".csv"));
// reading file 
readfile(dirname(__FILE__)."/downloads/".$_SESSION["facturename"].".csv");




die();
