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
      

  <div id="titulo">Reporte</div>

      
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
        
        <?php
		$tabla="<table id='' class='recordList'>
            <thead>
			<tr>
			<th class='list-column-center' scope='col'>No.</th>
			<th class='list-column-center' scope='col'>Promotor</th>
			<th class='list-column-center' scope='col'>Organización</th>
			</tr>
			<tbody>";
		if($_POST[promotor])
		{
			$sqlorg="SELECT * FROM organizaciones WHERE clave_agente='".$_POST[promotor]."' ORDER BY clave_agente ASC";
		}
		else
		{
			$sqlorg="SELECT * FROM organizaciones ORDER BY clave_agente ASC";
		}
		
		$rsorg = mysql_query($sqlorg,$db);
		$i=1;
		while($rworg = mysql_fetch_array($rsorg))
		{
			$tabla.="<tr class='even-row'><td class='list-column-right'>".$i."</td><td class='list-column-left'>"; if($rworg[procedencia]==""){$tabla.="No asignado";} else{$tabla.=$rworg[clave_agente];}$tabla.="</td><td class='list-column-left'><a class='keytext' href='../organizaciones/detalles.php?organizacion=".$rworg[clave_organizacion]." style='text-transform:uppercase;'>".$rworg[organizacion]."</a></td></tr>";
			$i++;
		}
		$tabla.="</tbody>
			</table>";
		?>

        <div id="resultadoorg">
        <fieldset class="fieldsetgde">
        <legend>Organizaciones por Promotor</legend>
        <span class="highlight" style="width:930px; background-color:#e3e3e3; text-align:right; color:#333; margin:10px 0 10px 0;"><i>Promotor: </i><b><?php if($_POST[promotor]){echo $_POST[promotor];}else{echo "Todos";} ?></b><i> Fecha de impresión: </i><b><?php echo $date; ?></b></span>
        <?php		
		$filtro="";
		$sqlpromotores = "select count(*) as NoOrganizaciones,
coalesce(concat(usr.apellidopaterno,' ',coalesce(usr.apellidomaterno,''),' ',usr.nombre),'Sin asignar') as Agente
from organizaciones org left join usuarios usr 
on org.clave_agente = usr.claveagente
where org.clave_agente like '%%'
group by org.clave_agente;";
		$rspromotores = mysql_query($sqlpromotores,$db);	
		$strXML = "<chart caption='' xAxisName='Promotor' yAxisName='Organizaciones' numberPrefix='' showValues='1' bgColor='FFFFFF' borderColor='E3E3E3' palette='3' borderThickness='1' showShadow='0' borderAlpha='100' plotGradientColor='' showPercentValues='1'>";
		while($rwpromotores = mysql_fetch_array($rspromotores))
		{ 
			$strXML.= "<set label='".$rwpromotores[Agente]."' value='".$rwpromotores[NoOrganizaciones]."'  />";
		}
		$strXML.="</chart>";
		mysql_free_result($rsprocedencia);
		
		$animateChart = $_GET['animate'];
		//Set default value of 1
		if ($animateChart=="")
			$animateChart = "1";

		$ruta = "Data/";
		if($_POST[promotor]){$name_file = $promotor."_promotor.xml";}else{$name_file = "todos_promotor.xml";}
		$file = fopen($ruta.$name_file,"w+");
		fwrite ($file,$strXML);
		fclose($file);
		$archivo=$ruta.$name_file;
		
		$grafico="<div id='chartContainer'>FusionCharts XT will load here!</div> 
		<script type='text/javascript'>
        var myChart = new FusionCharts( 'Bar2D', 'myChartId', '930', '300' );
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

<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../../js/ventanas-modales.js"></script>

</body>
</html>
