<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
require '../../php-excel.class.php';

extract($_POST);
if($action == "exportar")
{
	$contactos=0;
	foreach( $_POST['Asignados'] as $asignado)
	{
		$sqltabla="SELECT * FROM tablas WHERE id_tabla = '".$asignado."'";
		$rstabla= mysql_query($sqltabla,$db);
		$rwtabla=mysql_fetch_array($rstabla);

		mysql_query("SET NAMES utf8");
		mysql_query("SET character_set_results = 'utf8',
		character_set_client = 'utf8',
		character_set_connection = 'utf8',
		character_set_database = 'utf8',
		character_set_server = 'utf8'");
		
		// Fetch Record from Database
		$output = "";
		$DB_TBLName = $rwtabla[nombre_tabla]; //MySQL Table Name
		$sql = "SELECT * FROM $DB_TBLName WHERE fecha BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."'";
		$result = mysql_query($sql,$db) or die("No puede ejecutarse la consulta:<br>" . mysql_error(). "<br>" . mysql_errno());
		$columns_total = mysql_num_fields($result);
		$filtros=$DB_TBLName." del ".$_POST[date_fin]." al ".$_POST[date_fin];
		
		// Get The Field Name
		
		for ($i = 0; $i < $columns_total; $i++) {
		$heading = mysql_field_name($result, $i);
		$output .= '"'.$heading.'",';
		}
		$output .="\n";
		
		// Get Records from the table
		
		while ($row = mysql_fetch_array($result)) {
		for ($i = 0; $i < $columns_total; $i++) {
		$output .='"'.$row["$i"].'",';
		}
		$output .="\n";
		}
		
		$sqlexportacion="INSERT INTO `exportacionestablas`(`id_exportaciontabla`, `id_tabla`, `filtros`, `usuario`, `fecha`, `archivo`) VALUES (NULL,'$asignado','$filtros','$claveagente',NOW(),'')";
		mysql_query ($sqlexportacion,$db);
		
		// Download the file
		
		$filename = $filtros.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		
		echo $output;
		exit;
	}
	//Enviar mail
	$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$_POST[agente]."'";
	$rspromotor= mysql_query($sqlpromotor,$db);
	$rwpromotor=mysql_fetch_array($rspromotor);
}

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];
$numeroagente=$claveagente;
$Fecha=getdate();

$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];
$Anio_anterior=$Anio-1;
$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";



$_pagi_sql = "SELECT * FROM `tablas` WHERE exportar='1' ORDER BY `nombre_tabla` ASC";
$_pagi_cuantos = 10;
include("paginator.inc.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date_ini" ).datepicker({
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy"
	});
	
	$( "#date_fin" ).datepicker({
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
	});
});
</script>

</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    	<div id="logo"></div>
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard" title="Mi Tablero"></a></li>
          <li><a href="../organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <div id="titulo">Exportación de Datos</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="index.php">Tablas</a></li>
            </ul> 
        </div>
      </div>
      
    </div>
  </div>
</div>
<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentmid">
        <div class="midtxt">
        
        <div id="resultado">
        <fieldset class="fieldsetgde">
        <legend>Mostrando <?php echo $_pagi_info; ?> Tablas</legend>
            <form action="<?php echo $PHP_SELF; ?>" method="post">
            <?php if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
			{
			?>
            <i>Para los elementos que están seleccionados,</i>
            <label>Fecha Inicial: </label><input type="text" name="date_ini" id="date_ini" value="<?php if($_POST['fecha_ini']){echo $_POST['fecha_ini'];}else{echo $date;} ?>" />
            <label>Fecha Final: </label><input type="text" name="date_fin" id="date_fin" value="<?php if($_POST['fecha_fin']){echo $_POST['fecha_fin'];}else{echo $date;} ?>" />
            <img src="../../images/export_16.png" class="linkImage" />    
            <input type='submit' name='action' value="exportar" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
            <?php
			}
			?>
        <table class="recordList">
        <tbody>
		
		<?php
		$c=0;
		while($myrowtbl=mysql_fetch_array($_pagi_result))
		{
			list($dias, $meses) = diferencia_dias($date,$myrowopt[fecha_captura]);
			
			$sqlarchivo="SELECT count(`id_archivo`)as total, aprobado FROM `archivos` WHERE id_oportunidad = '".$myrowtbl[id_oportunidad]."' GROUP BY (`aprobado`)";
			$sqlultimo="SELECT * FROM exportacionestablas WHERE id_tabla='".$myrowtbl['id_tabla']."' ORDER BY fecha DESC LIMIT 1";
			$rsultimo= mysql_query ($sqlultimo,$db);
			$rwultimo=mysql_fetch_array($rsultimo);						
			?>
        	<tr class="odd-row">
            <td class="list-column-checkbox"><input name="Asignados[]" type="radio" value="<?php echo $myrowtbl['id_tabla']; ?>" /></td>
			<td class="list-column-left">
                        <?php if($myrowopt[marcado]==1){echo "<span class='label-overdue'>".$myrowopt[motivo]."</span>";} ?>
                        <span class="task-title"><a href="../oportunidades/forminsert.php?id=<?php echo $myrowtbl[id_tabla]; ?>&o=U&a=P&fecha=<?php echo $fecha_cierre_esperado;?>&organizacion=<?php echo $myrowopt[clave_organizacion];?>">
                            <?php echo $myrowtbl[nombre_tabla]; ?></a></span>
                        <br />
                        <span class="subtext"><?php echo $myrowtbl[descripcion]; ?></span>
                        </td>
<td class="list-column-left"><span class="highlight" style="background-color:<?php if($id_etapa!=10&&$id_etapa!=11){echo $resaltado;}else{echo "#C1C1C1";}?>;" title="<?php echo htmlentities(strftime('%A, %d de %B', strtotime($rwultimo[fecha]))); ?>"><?php echo $rwultimo[fecha]; ?></span>
<span class="subtext">Filtro: <?php echo $rwultimo[filtros]; ?></span>
</td>
</tr>
		<?php
		}
		?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; 
?>
</form>
</fieldset>
</div>

            
        
        
        </div>
      </div>
    </div>
  </div>
</div>
<div id="footerbg">
  <div id="footerblank">
    <div id="footer">
      <div id="footerlinks">
      	<a href="" class="footerlinks">Dashboard</a> | 
        <a href="" class="footerlinks">Contactos</a> | 
        <a href="" class="footerlinks">Actividades</a> | 
        <a href="" class="footerlinks">Ventas</a> | 
        <a href="" class="footerlinks">Casos</a>
      </div>
      <div id="copyrights">© anabiosis. Todos los derechos reservados.</div>
    </div>
  </div>
</div>

</body>
</html>
