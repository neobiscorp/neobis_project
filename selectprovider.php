<?php

require_once dirname ( __FILE__ ) . '/lib/lib.php';
if($_GET['noselection']){
	echo "";
	die();
}
$connection = neobis_mysql_conection();
$clientid_sql = "SELECT id FROM clientes WHERE nombre LIKE '".$_GET['q']."'";
$result = mysqli_query($connection, $clientid_sql);
$clientid = mysqli_fetch_assoc($result);
$provider_sql = "SELECT proveedores.nombre as nombre
FROM proveedores
JOIN cliente_proveedor
ON cliente_proveedor.proveedores_id = proveedores.id
WHERE cliente_proveedor.clientes_id = '".$clientid['id']."'";

$providers = mysqli_query($connection, $provider_sql);
$provider=array();

// Going through results
if (mysqli_num_rows($providers) > 0) {
	// Output data of each row
	while($row = mysqli_fetch_assoc($providers)) {
		// Saving results
		$provider[]=$row["nombre"];
	}
}
// Provider selection
$output .= "<select name='provider'>";
$output .= " <option value = 'noselection'>-- select an option --</option>";
foreach ($provider as $prov){
	$output .= "<p> <option value='".$prov."'>".$prov."</option></p>";
}
$output .= "</select>";
/*
// Submit Button
$output .= "<br><p> <input type='submit' name='dates' value='Enviar'></p>";
$output .= "</div></fieldset></form></body></html>";*/
echo $output;