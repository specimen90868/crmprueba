<?php
error_reporting(E_ERROR);
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");
require_once("../../mpdf/mpdf.php");
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
      

  <div id="titulo">Organizaciones y Contactos</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class="selected"><a href="#">Reportes</a></li>
        </ul> 
        <ul class="pageActions">
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="forminsert.php">Lista de Reportes</a></li>  
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
                <input type="submit" value="Buscar">
              </div>
              
            </form>
        </fieldset>
        <?php
		$tabla="<table id='' class='recordList'>
            <thead>
			<tr>
			<th class='list-column-center' scope='col'>Procedencia</th>
			<th class='list-column-center' scope='col'>Organización</th>
			</tr>
			<tbody>";
		$sqlorg="SELECT * FROM organizaciones WHERE clave_agente='".$_POST[promotor]."' ORDER BY procedencia ASC";
		$rsorg = mysql_query($sqlorg,$db);
		while($rworg = mysql_fetch_array($rsorg))
		{
			$tabla.="<tr class='even-row'><td class='list-column-left'>"; if($rworg[procedencia]==""){$tabla.="No indicado";} else{$tabla.=$rworg[procedencia];}$tabla.="</td><td class='list-column-left'><a class='keytext' href='../organizaciones/detalles.php?organizacion=".$rworg[clave_organizacion]." style='text-transform:uppercase;'>".$rworg[organizacion]."</a></td></tr>";
		}
		$tabla.="</tbody>
			</table>";
		?>

        <div id="resultadoorg">
        <fieldset class="fieldsetgde">
        <legend>Organizaciones por Procedencia</legend>
        <span class="highlight" style="width:930px; background-color:#e3e3e3; text-align:right; color:#333; margin:10px 0 10px 0;"><i>Promotor: </i><b><?php echo $_POST[promotor]; ?></b><i> Fecha de impresión: </i><b><?php echo $date; ?></b></span>
        <?php		
		$filtro="";
		$sqlprocedencia = "call procedencia('".$_POST[promotor]."');";
		$rsprocedencia = mysql_query($sqlprocedencia,$db);	
		$strXML = "<chart caption='' xAxisName='Medio de Procedencia' yAxisName='Organizaciones' numberPrefix='' showValues='1' bgColor='FFFFFF' borderColor='E3E3E3' palette='3' borderThickness='1' showShadow='0' borderAlpha='100' plotGradientColor=''>";
		while($rwprocedencia = mysql_fetch_array($rsprocedencia))
		{ 
			$strXML.= "<set label='".$rwprocedencia[Procedencia]."' value='".$rwprocedencia[Cantidad]."'  />";
		}
		$strXML.="</chart>";
		mysql_free_result($rsprocedencia);
		
		$animateChart = $_GET['animate'];
		//Set default value of 1
		if ($animateChart=="")
			$animateChart = "1";

		$ruta = "Data/";
		if($_POST[promotor]){$name_file = $promotor."_procedencia.xml";}else{$name_file = "todos_procedencia.xml";}
		$file = fopen($ruta.$name_file,"w+");
		fwrite ($file,$strXML);
		fclose($file);
		$archivo=$ruta.$name_file;
		?>
        
        
        <?php
       	$grafico="<div id='chartContainer'>FusionCharts XT will load here!</div> 
		<script type='text/javascript'>
        var myChart = new FusionCharts( 'Bar2D', 'myChartId', '930', '300' )\;
        myChart.setXMLUrl('".$archivo."');
        myChart.render('chartContainer'); 
        </script>";
	    //echo $grafico;
		echo $tabla;
		$html.= $grafico.$tabla;
		$mpdf=new mPDF();
		$mpdf->WriteHTML($html);	
		$mpdf->Output($date,'D');
		exit;
		?>
        
      
        </fieldset>
        Descargar pdf 
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
