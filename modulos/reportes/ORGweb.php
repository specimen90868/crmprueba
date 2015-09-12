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
		$casignados=0;$cnasignado=0;$cdescartado=0;$ctotal=0;
		//CONTACTANOS
		$sqcontactanos="SELECT * FROM contactanos WHERE fecha BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."'";
		$rscontactanos= mysql_query($sqcontactanos,$dbanabiosis);
		$ctotal=mysql_num_rows($rscontactanos);
		$i=0;
		while($rwcontactanos=mysql_fetch_array($rscontactanos))
		{
			$registros[$i]=$rwcontactanos[serial];
			$i++;
		}
		//PRECALIFICA
		$sqprecalifica="SELECT * FROM formularioprecalifica WHERE fecha_registro BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."'";
		$rsprecalifica= mysql_query($sqprecalifica,$dbanabiosis);
		$ptotal=mysql_num_rows($rsprecalifica);
		while($rwprecalifica=mysql_fetch_array($rsprecalifica))
		{
			$registros[$i]=$rwprecalifica[id_precalifica];
			$i++;
		}
		for($j=0;$j<count($registros);$j++){$claves.="organizaciones.clave_web='".$registros[$j]."'"; if($j<count($registros)-1){$claves.=" OR ";}}
		
		$rtotal=$ctotal+$ptotal;//TODOS LOS REGISTROS DE AMBOS FORMULARIOS
		
		$pasignados=0;$pnasignado=0;$pdescartado=0;
		
		$sqpreasig="SELECT count(`id_precalifica`)as total, asignado FROM `formularioprecalifica` WHERE fecha_registro BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' GROUP BY (`asignado`)";
		$rspreasig= mysql_query($sqpreasig,$dbanabiosis);
		while($rwpreasig=mysql_fetch_array($rspreasig))
		{
			if($rwpreasig[asignado]==0){$pnasignado=$rwpreasig[total];}else{$pasignados=$rwpreasig[total];}
		}
		
		//FORMULARIO DE CONTACTANOS: ASIGNADOS
		$sqCONasig="SELECT * FROM `contactanos` WHERE fecha BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' AND `asignado`='1'";
		$rsCONasig= mysql_query($sqCONasig,$dbanabiosis);
		$CONasig=mysql_num_rows($rsCONasig);
		
		//FORMULARIO DE CONTACTANOS: NO ASIGNADOS
		$sqCONnasig="SELECT * FROM `contactanos` WHERE fecha BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' AND `asignado`='0' AND `draft`='0'";
		$rsCONnasig= mysql_query($sqCONnasig,$dbanabiosis);
		$CONnasig=mysql_num_rows($rsCONnasig);
		
		//FORMULARIO DE CONTACTANOS: DESCARTADOS
		$sqCONdesc="SELECT * FROM `contactanos` WHERE fecha BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' AND `draft`='1'";
		$rsCONdesc= mysql_query($sqCONdesc,$dbanabiosis);
		$CONdesc=mysql_num_rows($rsCONdesc);
		
		//FORMULARIO DE PRECALIFICA: ASIGNADOS
		$sqPREasig="SELECT * FROM `formularioprecalifica` WHERE fecha_registro BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' AND `asignado`='1'";
		$rsPREasig= mysql_query($sqPREasig,$dbanabiosis);
		$PREasig=mysql_num_rows($rsPREasig);
		
		//FORMULARIO DE PRECALIFICA: NO ASIGNADOS
		$sqPREnasig="SELECT * FROM `formularioprecalifica` WHERE fecha_registro BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' AND `asignado`='0' AND `descartado`='0'";
		$rsPREnasig= mysql_query($sqPREnasig,$dbanabiosis);
		$PREnasig=mysql_num_rows($rsPREnasig);
		
		//FORMULARIO DE PRECALIFICA: DESCARTADOS
		$sqPREdesc="SELECT * FROM `formularioprecalifica` WHERE fecha_registro BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' AND `descartado`='1'";
		$rsPREdesc= mysql_query($sqPREdesc,$dbanabiosis);
		$PREdesc=mysql_num_rows($rsPREdesc);
		
		$asignados=$CONasig+$PREasig;
		$noasignados=$CONnasig+$PREnasig;
		$descartados=$CONdesc+$PREdesc;
		$totalestatus=$asignados+$noasignados+$descartados;
		
		$sqetapas="SELECT * FROM etapas ORDER BY numero_etapa ASC";
		$rsetapas = mysql_query($sqetapas,$db);
		$sqpromotores="SELECT DISTINCT(usuario) FROM oportunidades WHERE fecha_captura BETWEEN '".$_POST[date_ini]."' AND '".$_POST[date_fin]."' ORDER BY usuario ASC";
		
		//RECHAZADOS
		$sqOPTdesc="SELECT * FROM `oportunidades` LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE (".$claves.") AND oportunidades.id_etapa='11' ORDER BY oportunidades.usuario ASC";
		$rsOPTdesc= mysql_query($sqOPTdesc,$db);
		$OPTdesc=mysql_num_rows($rsOPTdesc);
		?>
		
        <!--TABLA DE OPORTUNIDADES POR ETAPA Y PROMOTOR-->
        <div style="width:55%; float:left; padding:10px; border:1px dashed #ccc; margin-right:10px;">
        <table class='recordList' style="margin-bottom:10px; width:98%;">
        <thead>
        <tr>
        <th class='list-column-center' scope='col'>Etapa</th>
        <?php
		$rspromotores = mysql_query($sqpromotores,$db);
		while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
		{
		?>
        <th class='list-column-center' scope='col'><?php echo $rwpromotores[usuario]; ?></th>
		<?php
        }
		mysql_free_result($rspromotores);
        ?>
        <th class='list-column-center' scope='col'>Total</th>
        </tr>
        </thead>
        <tbody>
            <?php
			while($rwetapas=mysql_fetch_array($rsetapas))//FILAS CON ETAPAS
            {
				?>
				<tr>
				<td class='list-column-left'>
				<?php if($rwetapas[id_etapa]==11)
				{
					?>
					<p style='margin:0 0 0 0;'><?php echo $rwetapas[etapa]; ?></p>
					<ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
					<?php
					while($rwOPTdesc=mysql_fetch_array($rsOPTdesc))//CONTACTANOS
					{
						$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$rwOPTdesc[clave_organizacion]."'";
						$rsorg = mysql_query($sqlorg,$db);
						$rworg=mysql_fetch_array($rsorg);
						?>
						<li><a href="../../modulos/oportunidades/forminsert.php?id=<?php echo $rwOPTdesc[id_oportunidad]; ?>&o=U&a=oP&organizacion=<?php echo $rwOPTdesc[clave_organizacion]; ?>"><?php echo $rworg[organizacion]; ?></a> <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwOPTdesc[usuario]; ?></span></li>
					<?php
					}
					?>
                    </ul>
                    <?php
				}
				else{echo $rwetapas[etapa];} ?></td>
				<?php
				$rspromotores = mysql_query($sqpromotores,$db);
				while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
				{
					$sqloportunidades="SELECT COUNT(id_oportunidad) as total FROM oportunidades LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE oportunidades.usuario='".$rwpromotores[usuario]."' AND oportunidades.id_etapa='".$rwetapas[id_etapa]."' AND (".$claves.")";
					$rsoportunidades = mysql_query($sqloportunidades,$db);
					$oportunidades=mysql_fetch_assoc($rsoportunidades);
					$totpromotor[$rwpromotores[usuario]]+=$oportunidades[total];
					$totetapa[$rwetapas[id_etapa]]+=$oportunidades[total];
					?>
					<td class='list-column-right'><?php echo $oportunidades[total]; ?></td>
					<?php
				}
				?>
                <td class='list-column-right'><b><?php echo $totetapa[$rwetapas[id_etapa]]; ?></b></td>
				</tr>
				<?php
				
			}
			$total=0;
			$tdtotales="<td class='list-column-left'></td>";
			$rspromotores = mysql_query($sqpromotores,$db);
			while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
			{
				$tdtotales.="<td class='list-column-right'><b>".$totpromotor[$rwpromotores[usuario]]."</b></td>";
				$total+=$totpromotor[$rwpromotores[usuario]];	
			}
			$tdtotales.="<td class='list-column-right'><b>".$total."</b></td>";
			?>
            <tr><?php echo $tdtotales; ?></tr>
        </tbody>
        </table>

		<?php
		
		$sqmotivo="SELECT * FROM motivosrechazo ORDER BY id_motivorechazo ASC";
		$rsmotivo = mysql_query($sqmotivo,$db);
		$sqpromotores="SELECT DISTINCT(usuario) FROM oportunidades LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE id_etapa='11' AND (".$claves.") ORDER BY usuario ASC";
		?>
		<!--TABLA DE RECHAZADOS POR MOTIVO Y PROMOTOR-->
        <table class='recordList' style="margin-bottom:10px; width:98%;">
        <thead>
        <tr>
        <th class='list-column-center' scope='col'>Motivo de rechazo</th>
        <?php
		$rspromotores = mysql_query($sqpromotores,$db);
		while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
		{
			?>
			<th class='list-column-center' scope='col'><?php echo $rwpromotores[usuario]; ?></th>
			<?php
        }
		mysql_free_result($rspromotores);
        ?>
        <th class='list-column-center' scope='col'>Total</th>
        </tr>
        </thead>
        <tbody>
            <?php
			while($rwmotivo=mysql_fetch_array($rsmotivo))//FILAS CON MOTIVOS DE RECHAZO
            {
				?>
				<tr>
				<td class='list-column-left'>
                	<p style='margin:0 0 0 0;'><?php echo $rwmotivo[motivo_rechazo]; ?></p>
					<ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
					<?php
					$sqOPTmotivo="SELECT * FROM `oportunidades` LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE (".$claves.") AND oportunidades.id_rechazo='".$rwmotivo[id_motivorechazo]."' ORDER BY oportunidades.usuario ASC";
					$rsOPTmotivo= mysql_query($sqOPTmotivo,$db);
					$OPTmotivo=mysql_num_rows($rsOPTmotivo);
					while($rwOPTmotivo=mysql_fetch_array($rsOPTmotivo))
					{
						$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$rwOPTmotivo[clave_organizacion]."'";
						$rsorg = mysql_query($sqlorg,$db);
						$rworg=mysql_fetch_array($rsorg);
						?>
						<li><a href="../../modulos/oportunidades/forminsert.php?id=<?php echo $rwOPTmotivo[id_oportunidad]; ?>&o=U&a=oP&organizacion=<?php echo $rwOPTmotivo[clave_organizacion]; ?>"><?php echo $rworg[organizacion]; ?></a> <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwOPTmotivo[usuario]; ?></span></li>
					<?php
					}
					?>
                    </ul>
                </td>
				<?php
				$rspromotores = mysql_query($sqpromotores,$db);
				while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
				{
					$sqlrechazos="SELECT COUNT(id_oportunidad) as total FROM oportunidades LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE usuario='".$rwpromotores[usuario]."' AND id_rechazo='".$rwmotivo[id_motivorechazo]."' AND (".$claves.")";
					$rsrechazos = mysql_query($sqlrechazos,$db);
					$rechazos=mysql_fetch_assoc($rsrechazos);
					$totpromotorR[$rwpromotores[usuario]]+=$rechazos[total];
					$totmotivoR[$rwmotivo[id_motivorechazo]]+=$rechazos[total];
					?>
					<td class='list-column-right'><?php echo $rechazos[total]; ?></td>
					<?php
				}
				?>
				<td class='list-column-right'><b><?php echo $totmotivoR[$rwmotivo[id_motivorechazo]]; ?></b></td>
				</tr>
				<?php
			}
			//RECHAZADOS SIN MOTIVO REGISTRADO
			?>
			<tr>
			<td class='list-column-left'>
				<p style='margin:0 0 0 0;'>Sin motivo especificado</p>
				<ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
				<?php
				$sqOPTmotivo="SELECT * FROM `oportunidades` LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE id_etapa='11' AND (".$claves.") AND oportunidades.id_rechazo='' ORDER BY oportunidades.usuario ASC";
				$rsOPTmotivo= mysql_query($sqOPTmotivo,$db);
				$OPTmotivo=mysql_num_rows($rsOPTmotivo);
				while($rwOPTmotivo=mysql_fetch_array($rsOPTmotivo))
				{
					$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$rwOPTmotivo[clave_organizacion]."'";
					$rsorg = mysql_query($sqlorg,$db);
					$rworg=mysql_fetch_array($rsorg);
					?>
					<li><a href="../../modulos/oportunidades/forminsert.php?id=<?php echo $rwOPTmotivo[id_oportunidad]; ?>&o=U&a=oP&organizacion=<?php echo $rwOPTmotivo[clave_organizacion]; ?>"><?php echo $rworg[organizacion]; ?></a> <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwOPTmotivo[usuario]; ?></span></li>
				<?php
				}
				?>
				</ul>
			</td>
			<?php
			$rspromotores = mysql_query($sqpromotores,$db);
			while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
			{
				$sqlrechazos="SELECT COUNT(id_oportunidad) as total FROM oportunidades LEFT JOIN (organizaciones) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE usuario='".$rwpromotores[usuario]."' AND id_etapa='11' AND id_rechazo='' AND (".$claves.")";
				$rsrechazos = mysql_query($sqlrechazos,$db);
				$rechazos=mysql_fetch_assoc($rsrechazos);
				$totpromotorR[$rwpromotores[usuario]]+=$rechazos[total];
				$totmotivoR[$rwmotivo[id_motivorechazo]]+=$rechazos[total];
				?>
				<td class='list-column-right'><?php echo $rechazos[total]; ?></td>
				<?php
			}
			?>
			<td class='list-column-right'><b><?php echo $totmotivoR[$rwmotivo[id_motivorechazo]]; ?></b></td>
			</tr>
			<?php
			$total=0;
			$tdtotales="<td class='list-column-left'></td>";
			$rspromotores = mysql_query($sqpromotores,$db);
			while($rwpromotores=mysql_fetch_array($rspromotores))//COLUMNAS CON PROMOTORES
			{
				$tdtotales.="<td class='list-column-right'><b>".$totpromotorR[$rwpromotores[usuario]]."</b></td>";
				$total+=$totpromotorR[$rwpromotores[usuario]];	
			}
			$tdtotales.="<td class='list-column-right'><b>".$total."</b></td>";
			?>
            <tr><?php echo $tdtotales; ?></tr>
        </tbody>
        </table>
		</div>
        
		<div style="width:35%; float:left; padding:10px; border:1px dashed #ccc;">
        <table class='recordList' style="width:98%; margin-bottom:10px;">
        <thead>
        <tr>
        <th class='list-column-center' scope='col'>Formulario de Procedencia</th>
        <th class='list-column-image' scope='col'>Totales</th>
        </tr>
        </thead>
        <tbody>
        <tr class='even-row'>
        	<td class='list-column-left'>
            <p style="margin:0 0 0 0;">Contáctanos</p>
				<ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
				<?php
                $i=0;
				$rscontactanos= mysql_query($sqcontactanos,$dbanabiosis);
				while($rwcontactanos=mysql_fetch_array($rscontactanos))
                {
                	$registros[$i]=$rwcontactanos[serial];
					?>
                    <li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwcontactanos[serial]; ?>&a=C"><?php echo $rwcontactanos[empresa]; ?></a></li>
                	<?php
					$i++;
                }
                ?>
                </ul>
            </td>
            <td class='list-column-right'><?php echo $ctotal; ?></td>
        </tr>
        <tr class='even-row'>
        	<td class='list-column-left'>
            <p style="margin:0 0 0 0;">Precalifica</p>
				<ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
				<?php
                $rsprecalifica= mysql_query($sqprecalifica,$dbanabiosis);
				while($rwprecalifica=mysql_fetch_array($rsprecalifica))
                {
                if($rwprecalifica[tipo_persona]=="01"){$empresa=$rwprecalifica[nombre];}else{$empresa=$rwprecalifica[razon_social];}
				$registros[$i]=$rwprecalifica[id_precalifica];
				?>
                <li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwprecalifica[id_precalifica]; ?>&a=P"><?php echo $empresa; ?></a></li>
                <?php
                $i++;
				}
                ?>
                </ul>
            </td>
            <td class='list-column-right'><?php echo $ptotal; ?></td>
        </tr>
        <tr class='even-row'>
        	<td class='list-column-left'>Total</td>
            <td class='list-column-right'><?php echo $rtotal; ?></td>
        </tr>
        </tbody>
        </table>

        <table class='recordList' style="width:98%;">
        <thead>
        <tr>
        <th class='list-column-center' scope='col'>Estatus</th>
        <th class='list-column-center' scope='col'>Total</th>
        </tr>
        </thead>
        <tbody>
        <tr class='even-row'>
        	<td class='list-column-left'>
            <p style="margin:0 0 0 0;">Asignados</p>
                <ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
				<?php
                while($rwCONasig=mysql_fetch_array($rsCONasig))//CONTACTANOS
                {
                ?>
                	<li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwCONasig[serial]; ?>&a=C"><?php echo $rwCONasig[empresa]; ?></a> <span class="highlight" style="background-color:#C1C1C1;">C</span></li>
                <?php
                }
				while($rwPREasig=mysql_fetch_array($rsPREasig))//PRECALIFICA
                {
                if($rwPREasig[tipo_persona]=="01"){$empresa=$rwPREasig[nombre];}else{$empresa=$rwPREasig[razon_social];}
				?>
                	<li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwPREasig[id_precalifica]; ?>&a=P"><?php echo $empresa; ?></a> <span class="highlight" style="background-color:#C1C1C1;">P</span></li>
                <?php
                }
				
                ?>
                </ul>
            </td>
            <td class='list-column-right'><?php echo $asignados; ?></td>
        </tr>
         <tr class='even-row'>
        	<td class='list-column-left'>
            <p style="margin:0 0 0 0;">No asignados</p>
                <ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
				<?php
				while($rwCONnasig=mysql_fetch_array($rsCONnasig))//CONTACTANOS
                {
                ?>
                	<li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwCONnasig[serial]; ?>&a=C"><?php echo $rwCONnasig[empresa]; ?></a> <span class="highlight" style="background-color:#C1C1C1;">C</span></li>
                <?php
                }
				//echo $sqPREnasig;
				while($rwPREnasig=mysql_fetch_array($rsPREnasig))//PRECALIFICA
                {
                if($rwPREnasig[tipo_persona]=="01"){$empresa=$rwPREnasig[nombre];}else{$empresa=$rwPREnasig[razon_social];}
				?>
                	<li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwPREnasig[id_precalifica]; ?>&a=P"><?php echo $empresa; ?></a> <span class="highlight" style="background-color:#C1C1C1;">P</span></li>
                <?php
                }
				
                ?>
                </ul>
            </td>
            <td class='list-column-right'><?php echo $noasignados; ?></td>
        </tr>
         <tr class='even-row'>
        	<td class='list-column-left'>
            <p style="margin:0 0 0 0;">Descartados</p>
                <ul class="toggle" style="background:#fff; list-style: decimal-leading-zero; display: block; width: 90%; text-align: left; padding: 0px 0px 0px 30px;">
				<?php
                while($rwCONdesc=mysql_fetch_array($rsCONdesc))//CONTACTANOS
                {
                ?>
                	<li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwCONdesc[serial]; ?>&a=C"><?php echo $rwCONdesc[empresa]; ?></a> <span class="highlight" style="background-color:#C1C1C1;">C</span></li>
                <?php
                }
				while($rwPREdesc=mysql_fetch_array($rsPREdesc))//PRECALIFICA
                {
                if($rwPREdesc[tipo_persona]=="01"){$empresa=$rwPREdesc[nombre];}else{$empresa=$rwPREdesc[razon_social];}
				?>
                	<li><a href="../../modulos/asignacion/detalles.php?id=<?php echo $rwPREdesc[id_precalifica]; ?>&a=P"><?php echo $empresa; ?></a> <span class="highlight" style="background-color:#C1C1C1;">P</span></li>
                <?php
                }
				
                ?>
                </ul>
            </td>
            <td class='list-column-right'><?php echo $descartados; ?></td>
        </tr>
        <tr class='even-row'>
        	<td class='list-column-right'>Total</td>
            <td class='list-column-right'><?php echo $totalestatus; ?></td>
        </tr>
        </tbody>
        </table>
        
        <!--CONTACTOS ASIGNADOS SIN PROCESO ABIERTO-->
        <table class='recordList' style="width:98%;">
        <thead>
        <tr>
        <th class='list-column-center' scope='col'>Registros asignados sin proceso abierto</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sqlsin="SELECT organizaciones.organizacion,organizaciones.clave_agente, organizaciones.clave_organizacion, COUNT(oportunidades.id_oportunidad) AS cuenta FROM `organizaciones` LEFT JOIN (oportunidades) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE (".$claves.") GROUP BY organizaciones.clave_organizacion ORDER BY cuenta,organizaciones.clave_agente,organizaciones.organizacion ASC";
		$rssin = mysql_query($sqlsin,$db);
		$totalsin=0;
		while($rwsin=mysql_fetch_array($rssin))
		{
			if($rwsin[cuenta]==0)
			{
				$totalsin++;
				?>
				 <tr class='even-row'><td class='list-column-left'><a href="../../modulos/organizaciones/detalles.php?organizacion=<?php echo $rwsin[clave_organizacion]; ?>&a=P"><?php echo $rwsin[organizacion]; ?></a> <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwsin[clave_agente]; ?></span></td></tr>
				<?php
			}
		}
		?>
        <tr class='even-row'>
            <td class='list-column-right'><?php echo $totalsin; ?></td>
        </tr>
        </tbody>
        </table>
        
        </div>

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
