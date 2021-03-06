﻿<?php
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
      

  <div id="titulo">Tasa de Conversión</div>

      
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
               
                <?php if($_SESSION["Tipo"]!="Promotor")
				{
				?>
                <label>Agente: </label>
                <select name="promotor" size="1" onchange="">
                    <option value="" selected="selected">Todos</option>
                    <?php
					$sqlagt="SELECT * FROM usuarios ORDER BY claveagente";
                    $resultagt= mysql_query ($sqlagt,$db);
                    while($myrowagt=mysql_fetch_array($resultagt))
                    {
					?>
                        <option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                    <?php
					}
					?>
                </select>
				<?php
                }
				?>
                <label>Fecha Inicial: </label>
                <input type="text" name="date_ini" id="date_ini" value="<?php if($_POST['fecha_ini']){echo $_POST['fecha_ini'];}else{echo $date;} ?>" />
                <label>Fecha Final: </label>
                <input type="text" name="date_fin" id="date_fin" value="<?php if($_POST['fecha_fin']){echo $_POST['fecha_fin'];}else{echo $date;} ?>" />
                <input type="submit" value="Buscar">
              </div>
              
            </form>
        </fieldset>

        <div id="resultadoorg">
        <fieldset class="fieldsetgde">
        <legend>Tasa de Conversión</legend>
        <span class="highlight" style="width:930px; background-color:#e3e3e3; text-align:right; color:#333; margin:10px 0 10px 0;"><i>Promotor: </i><b><?php if($_POST[promotor]){echo $_POST[promotor];}else{echo "Todos";} ?></b><i> Fecha Inicial: </i><b><?php echo $_POST[date_ini]; ?></b><i> Fecha Final: </i><b><?php echo $_POST[date_fin]; ?></b><i> Fecha de impresión: </i><b><?php echo $date; ?></b></span>
        <?php		
		$filtro="";
		$sqlprocedencia = "call indice_conversion('".$_POST[date_ini]."','".$_POST[date_fin]."','".$_POST[promotor]."');";
		//echo $sqlprocedencia;
		$rsprocedencia = mysql_query($sqlprocedencia,$db);	
		$strXML = "<chart caption='' subcaption='' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='10' chartRightMargin='35' bgColor='FFFFFF' bgAngle='270' bgAlpha='10,10' showBorder='0'>";
		$strXMLcat = "<categories>";
		$strXMLpos = "<dataset seriesName='Conversión positiva' color='9FC733' anchorBorderColor='9FC733' anchorBgColor='9FC733'>";
		$strXMLneg = "<dataset seriesName='Conversión negativa' color='FF0000' anchorBorderColor='FF0000' anchorBgColor='FF0000'>";
		while($rwprocedencia = mysql_fetch_array($rsprocedencia))
		{ 
			$strXMLcat.= "<category label='".$rwprocedencia[Mes]."'/>";
			$strXMLpos.= "<set value='".$rwprocedencia[Cerrados]."'/>";
			$strXMLneg.= "<set value='".$rwprocedencia[NoCerrados]."'/>";
		}
		$strXMLcat.= "</categories>";
		$strXMLpos.= "</dataset>";
		$strXMLneg.= "</dataset>";
		$strXML.=$strXMLcat.$strXMLpos.$strXMLneg."</chart>";
		mysql_free_result($rsprocedencia);
		
		$animateChart = $_GET['animate'];
		//Set default value of 1
		if ($animateChart=="")
			$animateChart = "1";

		$ruta = "Data/";
		if($_POST[promotor]){$name_file = $promotor."_tasaconversion.xml";}else{$name_file = "todos_tasaconversion.xml";}
		$file = fopen($ruta.$name_file,"w+");
		fwrite ($file,$strXML);
		fclose($file);
		$archivo=$ruta.$name_file;
		
		$grafico="<div id='chartContainer'>FusionCharts XT will load here!</div> 
		<script type='text/javascript'>
        var myChart = new FusionCharts( 'MSLine', 'myChartId', '930', '300' );
        myChart.setXMLUrl('".$archivo."');
        myChart.render('chartContainer'); 
        </script>";
             
		echo $grafico;
		echo $tabla;
		/*$html.= $grafico.$tabla;
		$mpdf=new mPDF();
		$mpdf->WriteHTML($html);	
		$mpdf->Output($_POST[promotor]."_".$date.".pdf",'F');*/
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

</body>
</html>
