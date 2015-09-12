<?php
//error_reporting(E_ERROR);
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];

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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="charset=UTF-8" /> 
<meta http-equiv="Content-Language" content="es-ES" />

<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/jquery-1.2.1.pack.js"></script>
<script language="JavaScript"  src="../../js/FusionCharts.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">
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

<script type="text/javascript" language="javascript">
function btninput_click() {
window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#exportdetails').html()));
        }
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
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
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
      

  <div id="titulo">Precalifica: Registros de Calidad</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class="selected"><a href="#">Reportes</a></li>
        </ul> 
        <ul class="pageActions">
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="index.php">Lista de Reportes</a></li>  
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
        
        <fieldset class="fieldsetgde">
            <legend>Filtar lista por</legend>
            <form name="frmbusqueda" action="<?php echo $PHP_SELF; ?>"  method="post">
              <div>
                <label>Fecha Inicial: </label>
                <input type="text" name="date_ini" id="date_ini" value="<?php if($_POST['fecha_ini']){echo $_POST['fecha_ini'];} ?>" />
                <label>Fecha Final: </label>
                <input type="text" name="date_fin" id="date_fin" value="<?php if($_POST['fecha_fin']){echo $_POST['fecha_fin'];} ?>" />
                <input name="todos" type="checkbox" value="todos"/><label>Todos</label>
                <input type="submit" value="Buscar">
              </div>
              
            </form>
            
        </fieldset>
        
        <?php
		$tabla="<div id='exportdetails'><table class='recordList'>
            <thead>
			<tr>
			<th class='list-column-center' scope='col'>No.</th>
			<th class='list-column-center' scope='col'>Organización</th>
			<th class='list-column-center' scope='col'>Ventas mensuales</th>
			<th class='list-column-center' scope='col'>Monto requerido</th>
			<th class='list-column-center' scope='col'>Fecha registro</th>
			<th class='list-column-center' scope='col'>Estatus</th>
			</tr>
			<tbody>";
		if($_POST[todos]||!$_POST[date_ini]||!$_POST[date_fin])
		{
			$sqlorg="select * from precalifica ORDER BY fecha DESC";
			
			$sqletapa = "select (select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000) as Cantidad,
truncate(((select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000) * 100) /
(select count(*) from precalifica),2) as Porcentaje,
'Calidad' as RegCalidad
union
select (select count(*) from precalifica) -
(select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000) as Cantidad,
truncate(
((select count(*) from precalifica) -
(select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000)) * 100 / 
(select count(*) from precalifica),2) as Porcentaje,
'NoCalidad' as RegCalidad;";
		}
		else
		{
			$sqlorg="select * from precalifica where ventas_mensuales >= 150000 and monto >= 300000 and 
fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."'";
			
			$sqletapa = "select (select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000 and 
fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."') as Cantidad,
truncate(((select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000 and 
fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."') * 100) /
(select count(*) from precalifica where fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."'),2) as Porcentaje,
'Calidad' as RegCalidad
union
select (select count(*) from precalifica where fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."') -
(select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000 and 
fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."') as Cantidad,
truncate(
((select count(*) from precalifica where fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."') -
(select count(*) from precalifica where ventas_mensuales >= 150000 and monto >= 300000 and 
fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."')) * 100 / 
(select count(*) from precalifica where fecha between '".$_POST[date_ini]."' and '".$_POST[date_fin]."'),2) as Porcentaje,
'NoCalidad' as RegCalidad;";
			
		}
		$rsorg = mysql_query($sqlorg,$db);
		$i=1;
		while($rworg = mysql_fetch_array($rsorg))
		{
			if($rworg[asignado]==1)
			{
				$sqlagt="SELECT * FROM `organizaciones` WHERE `clave_web` = '".$rworg[id_precalifica]."'";
				$rsagt = mysql_query($sqlagt,$db);
				$rwagt = mysql_fetch_array($rsagt);
				$estatus="<span class='highlight' style='background-color:#9FC733;'>".$rwagt[clave_agente]."</span>";
			}
			else
			{
				$estatus="<span class='highlight'>descartado</span>";
			}
			$tabla.="<tr class='even-row'><td class='list-column-right'>".$i."</td><td class='list-column-left'><a class='keytext' href='../asignacion/detalles.php?id=".$rworg[id_precalifica]."&a=P' style='text-transform:uppercase;'>".$rworg[empresa]."</a></td><td class='list-column-left'>".$rworg[ventas_mensuales]."</td><td class='list-column-left'>".$rworg[monto]."</td><td class='list-column-left'>".$rworg[fecha]."</td><td class='list-column-left'>".$estatus."</td></tr>";
			$i++;
		}
		$tabla.="</tbody>
			</table></div>";
		?>

		<div>
        <img src="../../images/page_white_excel.png" class="linkImage" style="margin-left: 820px;"/>
		<input type="button" id="btninput" value="Exportar a Excel" onclick="return  btninput_click()" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
    	</div>

        <div id="resultadoorg">
        <fieldset class="fieldsetgde">
        <legend>Precalifica: Registros de Calidad</legend>
        <span class="highlight" style="width:930px; background-color:#e3e3e3; text-align:right; color:#333; margin:10px 0 10px 0;"><i> Fecha Inicial: </i><b><?php echo $_POST[date_ini]; ?></b><i> Fecha Final: </i><b><?php echo $_POST[date_fin]; ?></b><i> Fecha de impresión: </i><b><?php echo $date; ?></b></span>
        <?php		
		$filtro="";
		$rsetapa = mysql_query($sqletapa,$db);	
		$strXML = "<chart caption='' palette='2' animation='1' formatNumberScale='0' numberPrefix='' pieSliceDepth='30' startingAngle='125' showPercentValues='1'>";
		while($rwetapa = mysql_fetch_array($rsetapa))
		{ 
			$strXML.= "<set label='".$rwetapa[RegCalidad]."' value='".$rwetapa[Cantidad]."'  />";
		}
		$strXML.="</chart>";
		mysql_free_result($rsprocedencia);
		
		$animateChart = $_GET['animate'];
		//Set default value of 1
		if ($animateChart=="")
			$animateChart = "1";

		$ruta = "Data/";
		if($_POST[promotor]){$name_file = "precalifica.xml";}else{$name_file = "precalifica.xml";}
		$file = fopen($ruta.$name_file,"w+");
		fwrite ($file,$strXML);
		fclose($file);
		$archivo=$ruta.$name_file;
		
		$grafico="<div id='chartContainer'>FusionCharts XT will load here!</div> 
		<script type='text/javascript'>
        var myChart = new FusionCharts( 'Pie3D', 'myChartId', '930', '300' );
        myChart.setXMLUrl('".$archivo."');
        myChart.render('chartContainer'); 
        </script>";
             
		echo $grafico;
		echo $tabla;
		?>
        
      
        </fieldset>
        <!--<a href="<?php echo $_POST[promotor]."_".$date.".pdf"; ?>" target="_blank">Descargar pdf</a>--> 
        </div><!--fin de resultadoorg-->       	
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

<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>

</body>
</html>
