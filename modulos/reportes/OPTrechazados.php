<?php

//error_reporting(E_ERROR);
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];

//print_r($_POST); 

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

/*extract($_POST);
if(isset($_POST['submit']))
{
	$filtros="Rechazados del ".$_POST[date_fin]." al ".$_POST[date_fin];	
	// Fetch Record from Database
	$output = $_POST[output];
	//Agregar acción a la bitácora
	
	//Descargar el archivo
	$filename = $filtros.".xls";
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");//Prevent Caching
	header("Expires: 0");//Expires and 0 mean that the browser will not cache the page on your hard drive
	echo $output;
	//exit;
}*/


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

<script>
/*$(document).ready(function () {
    $(".toggle").click(function () {
        $(".rightButton").toggle();
    });
	$(".toggle2").click(function () {
        $(".rightButton2").toggle();
    });
});*/

$(document).ready(function() {
 
// choose text for the show/hide link - can contain HTML (e.g. an image)
var showText='Mostrar';
var hideText='Ocultar';
 
// initialise the visibility check
var is_visible = false;
 
// append show/hide links to the element directly preceding the element with a class of "toggle"
$('.toggle').prev().append(' (<a href="#" class="toggleLink">'+showText+'</a>)');
 
// hide all of the elements with a class of 'toggle'
$('.toggle').hide();
 
// capture clicks on the toggle links
$('a.toggleLink').click(function() {
 
// switch visibility
is_visible = !is_visible;
 
// change the link depending on whether the element is shown or hidden
$(this).html( (!is_visible) ? showText : hideText);
 
// toggle the display - uncomment the next line for a basic "accordion" style
//$('.toggle').hide();$('a.toggleLink').html(showText);
$(this).parent().next('.toggle').toggle('slow');
 
// return false so any link destination is not followed
return false;
 
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
      

  <div id="titulo">Procesos Rechazados</div>

      
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
        
        <form name="frmbusqueda" action="<?php echo $PHP_SELF; ?>"  method="post">
        <fieldset class="fieldsetgde">
            <legend>Filtar lista por</legend>
            
              <div>
                <label>Fecha Inicial: </label>
                <input type="text" name="date_ini" id="date_ini" value="<?php if($_POST['fecha_ini']){echo $_POST['fecha_ini'];}else{echo $date;} ?>" />
                <label>Fecha Final: </label>
                <input type="text" name="date_fin" id="date_fin" value="<?php if($_POST['fecha_fin']){echo $_POST['fecha_fin'];}else{echo $date;} ?>" />
                <input type="submit" value="Buscar"  name="submit">
              </div>
        </fieldset>
        </form>

        <div id="resultadoorg">
        <fieldset class="fieldsetgde">
        <legend>Procesos Rechazados</legend>
        <span class="highlight" style="width:930px; background-color:#e3e3e3; text-align:right; color:#333; margin:10px 0 10px 0;"><i>Promotor: </i><b><?php if($_POST[promotor]){echo $_POST[promotor];}else{echo "Todos";} ?></b><i> Fecha Inicial: </i><b><?php echo $_POST[date_ini]; ?></b><i> Fecha Final: </i><b><?php echo $_POST[date_fin]; ?></b><i> Fecha de impresión: </i><b><?php echo $date; ?></b></span>
        <?php		
		
		//RECHAZADOS
		$sqOPTdesc="SELECT * FROM `oportunidades` LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE oportunidades.id_etapa='11' AND fecha_cierre_real BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' ORDER BY oportunidades.usuario ASC";
		$rsOPTdesc= mysql_query($sqOPTdesc,$db);
		$OPTdesc=mysql_num_rows($rsOPTdesc);
		//echo $sqOPTdesc;
		?>
        
        <!--TABLA DE OPORTUNIDADES RECHAZADAS-->
        <table class='recordList' style="width:98%;">
        <thead>
        <tr>
        <th class='list-column-left' scope='col'>Procesos rechazados</th>
        </tr>
        </thead>
        <tbody>
        
        <?php
		
		$output="<table>
        <tr>
		<td>ORGANIZACION</td><td>PROMOTOR</td><td>PROCEDENCIA</td><td>MOTIVO RECHAZO</td><td>ABIERTO</td><td>CERRADO</td><td>NOMBRE</td><td>TELEFONOS</td><td>EMAIL</td><td>ASUNTO</td><td>ACTIVIDAD</td><td>ANTIGÜEDAD</td><td>MONTO</td><td>GARANTÍA</td><td>VENTAS</td><td>VALOR GARANTIA</td>
        </tr>";
		
		while($rwOPTdesc=mysql_fetch_array($rsOPTdesc))
		{
			//Consultar motivo de rechazo del proceso
			$sqlMotivo="SELECT * FROM motivosrechazo WHERE id_motivorechazo='".$rwOPTdesc[id_rechazo]."'";
            $rsMotivo = mysql_query($sqlMotivo,$db);
			$rwMotivo=mysql_fetch_array($rsMotivo);
			
			$output.="<tr>";
			
			if($rwOPTdesc[clave_web])//Si el contacto procede de Website
			{
				//Buscar registro en PRECALIFICA
				$sqlPrecalifica="SELECT * FROM precalifica WHERE id_precalifica='".$rwOPTdesc[clave_web]."'";
				$rsPrecalifica = mysql_query($sqlPrecalifica,$db);
				if(mysql_num_rows($rsPrecalifica))
				{
					$origen="Precalifica";
					$procedencia="<span class='highlight' style='background-color:#C1C1C1;'>Precalifica</span>";
					$rwPrecalifica=mysql_fetch_array($rsPrecalifica);
					$datos_registro="<b>Nombre:</b> ".$rwPrecalifica[nombre_completo]."<br><b>Teléfono:</b> ".$rwPrecalifica[telefono]."<br><b>Celular:</b> ".$rwPrecalifica[celular]."<br><b>E-mail:</b> <a href=mailto:'".$rwPrecalifica[email]."'>".$rwPrecalifica[email]."</a><br><br><b>Actividad Principal:</b> ".$rwPrecalifica[actividad_principal]."<br><b>Antigüedad:</b> ".$rwPrecalifica[antiguedad]."<br><b>Asunto:</b> ".$rwPrecalifica[como_te_podemos_ayudar]."<br><b>Monto:</b> ".$rwPrecalifica[monto]."<br><b>Garantía:</b> ".$rwPrecalifica[garantia_hipotecaria]."<br><b>Ventas Mensuales:</b> ".$rwPrecalifica[ventas_mensuales]."<br><b>Valor Garantía:</b> ".$rwPrecalifica[valor_garantia]."";
					$output.="<td>".$rwOPTdesc[organizacion]."</td><td>".$rwOPTdesc[usuario]."</td><td>".$origen."</td><td>".$rwMotivo[motivo_rechazo]."</td><td>".$rwOPTdesc[fecha_captura]."</td><td>".$rwOPTdesc[fecha_cierre_real]."</td><td>".$rwPrecalifica[nombre_completo]."</td><td>".$rwPrecalifica[telefono].", ".$rwPrecalifica[celular]."</td><td>".$rwPrecalifica[email]."</td><td>".$rwPrecalifica[como_te_podemos_ayudar]."</td><td>".$rwPrecalifica[actividad_principal]."</td><td>".$rwPrecalifica[antiguedad]."</td><td>".$rwPrecalifica[monto]."</td><td>".$rwPrecalifica[garantia_hipotecaria]."</td><td>".$rwPrecalifica[ventas_mensuales]."</td><td>".$rwPrecalifica[valor_garantia]."</td>";
				}
				else
				{
					//Buscar registro en CONTACTANOS
					$sqlContactanos="SELECT * FROM contactanos WHERE serial='".$rwOPTdesc[clave_web]."'";
					$rsContactanos = mysql_query($sqlContactanos,$db);
					if(mysql_num_rows($rsContactanos))
					{
						$origen="Contacto";
						$procedencia="<span class='highlight' style='background-color:#C1C1C1;'>Contacto</span>";
						$rwContactanos=mysql_fetch_array($rsContactanos);
						$datos_registro="<b>Nombre:</b> ".$rwContactanos[nombre]."<br><b>Teléfono:</b> ".$rwContactanos[telefono]."<br><b>E-mail:</b> <a href=mailto:'".$rwContactanos[email]."'>".$rwContactanos[email]."</a><br><b>Asunto:</b> ".$rwContactanos[asunto]."";
						$output.="<td>".$rwOPTdesc[organizacion]."</td><td>".$rwOPTdesc[usuario]."</td><td>".$origen."</td><td>".$rwMotivo[motivo_rechazo]."</td><td>".$rwOPTdesc[fecha_captura]."</td><td>".$rwOPTdesc[fecha_cierre_real]."</td><td>".$rwContactanos[nombre]."</td><td>".$rwContactanos[telefono]."</td><td>".$rwContactanos[email]."</td><td>".$rwPrecalifica[asunto]."</td><td></td><td></td><td></td><td></td><td></td><td></td>";
					}
				}
			}
			else//El contacto fue capturado, no procede de los formularios de registro
			{
				$origen="Directo:".$rwOPTdesc[procedencia];
				$procedencia="<span class='highlight' style='background-color:#C1C1C1;'>".$rwOPTdesc[procedencia]."</span>";
				
				//Buscar registro en contactanos
				$sqlContactos="SELECT * FROM contactos WHERE clave_organizacion='".$rwOPTdesc[clave_organizacion]."'";
				$rsContactos = mysql_query($sqlContactos,$db);
				$rwContactos=mysql_fetch_array($rsContactos);
				
				$nombre = $rwContactos[nombre]." ".$rwContactos[apellidos];
				$telefonos.= $rwContactos[telefono_casa].", ".$rwContactos[telefono_oficina].", ".$rwContactos[telefono_celular];
				
				//Buscar correos de organización y de contactos
				$sqlMail="SELECT * FROM correos WHERE clave_registro='".$rwOPTdesc[clave_organizacion]."' OR clave_registro='".$rwContactos[clave_contacto]."'";
				$rsMail = mysql_query($sqlMail,$db);
				while($rwMail=mysql_fetch_array($rsMail))
				{
					$correos.= $rwMail[correo].", ";
				}
				
				$datos_registro="<b>Nombre:</b> ".$nombre."<br><b>Teléfonos:</b> ".$telefonos."<br><b>E-mail:</b> ".$correos."<br><b>Asunto:</b> ".$rwContactanos[asunto]."";
				
				$output.="<td>".$rwOPTdesc[organizacion]."</td><td>".$rwOPTdesc[usuario]."</td><td>".$origen."</td><td>".$rwMotivo[motivo_rechazo]."</td><td>".$rwOPTdesc[fecha_captura]."</td><td>".$rwOPTdesc[fecha_cierre_real]."</td><td>".$nombre."</td><td>".$telefonos."</td><td>".$correos."</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
			}
			
			//Consultar etapas por las que paso el proceso antes de ser rechazado
			$sqlProceso="SELECT * FROM `etapasoportunidades` LEFT JOIN (etapas) ON (etapasoportunidades.id_etapa=etapas.id_etapa) WHERE  etapasoportunidades.clave_oportunidad='".$rwOPTdesc[clave_oportunidad]."' ORDER BY etapasoportunidades.fecha ASC";
            $rsProceso = mysql_query($sqlProceso,$db);
			while($rwProceso=mysql_fetch_array($rsProceso))
			{
				//Color del responsable de la etapa
				$sqlResponsable="SELECT * FROM `responsables` WHERE id_responsable='".$rwProceso[id_responsable]."'";
           	 	$rsResponsable = mysql_query($sqlResponsable,$db);
				$rwResponsable=mysql_fetch_array($rsResponsable);
				$proceso.= "<span class='count important' style='background-color:#".$rwResponsable[color].";'>".$rwProceso[numero_etapa]."</span> ".$rwProceso[etapa]."";
			}
			?>       
            <tr class='even-row'><td class='list-column-left'><a class="keytext" href="../../modulos/oportunidades/forminsert.php?id=<?php echo $rwOPTdesc[id_oportunidad]; ?>&o=U&a=oP&organizacion=<?php echo $rwOPTdesc[clave_organizacion]; ?>"><?php echo $rwOPTdesc[organizacion]; ?></a> <span class="highlight" style="background-color:#9FC733;"><?php echo $rwOPTdesc[usuario]; ?></span> <?php echo $procedencia; ?> <span class="highlight" style="background-color:#FFBBBB;"><?php echo $rwMotivo[motivo_rechazo]; ?></span> Abierto el <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwOPTdesc[fecha_captura]; ?></span> Cerrado el: <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwOPTdesc[fecha_cierre_real]; ?></span><br><br><?php echo $datos_registro; ?><br><br><?php echo $proceso; ?></td>
            </tr>
			<?php
			$nombre = "";
			$telefonos = "";
			$correos = "";
			$datos_registro = "";
			$proceso = "";
			$output .= "</tr>";
		}
		$output.="</table>";
		?>
        </tbody>
        </table>
        
        </fieldset>
        <!--<a href="<?php echo $_POST[promotor]."_".$date.".pdf"; ?>" target="_blank">Descargar pdf</a>--> 
        </div><!--fin de resultadoorg-->
        
        <form action = "exportar_excel.php" method = "post">
        <input type="hidden" name="output" value="<?php echo $output; ?>">
        <input type="hidden" name="date_ini" value="<?php echo $_POST[date_ini]; ?>">
        <input type="hidden" name="date_fin" value="<?php echo $_POST[date_fin]; ?>">
        <img src="../../images/export_16.png" class="linkImage" />    
        <input type='submit' name='exportar' value="exportar resultados" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
        </form>       	
        
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
