<?php

if(isset($_POST['exportar']))
{
	$filtros="Rechazados del ".$_POST[date_fin]." al ".$_POST[date_fin];	
	$output = $_POST[output];
	//Agregar acción a la bitácora
	
	//Descargar el archivo
	$filename = $filtros.".xls";
	header("Content-type: application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");//Prevent Caching
	header("Expires: 0");//Expires and 0 mean that the browser will not cache the page on your hard drive
	
	
	
	
	/*header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);*/
	echo $output;
	exit;
}

?>
