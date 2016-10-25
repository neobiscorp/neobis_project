<?php
// Setting time limit to 15 minutes
set_time_limit(900);
// Session start for passing variables throgh pages
session_start();
$filename = $_SESSION["facturename"].".csv";
header('Content-Disposition: attachment; filename='.$filename);
header("Content-Type: text/csv");
header("Content-Length:". filesize(dirname(__FILE__)."/downloads/".$filename));

readfile(dirname(__FILE__)."/downloads/".$filename);

die();
