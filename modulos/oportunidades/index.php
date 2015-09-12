<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];

$numeroagente=number_format($claveagente);

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

//Oportunidades atrasadas cierre anterior a la fecha actual y abiertas
$sqloverdueopt="SELECT * FROM `oportunidades` WHERE `fecha_cierre_esperado` < '".$date."' AND (`id_etapa`!=6 OR `id_etapa`!=6) AND `clave_organizacion`='".$claveorganizacion."' ";
$resultadoopt = mysql_query($sqloverdueopt, $db);
$overdueopt = mysql_num_rows($resultadoopt);

//Actividades atrasadas
$sqloverdueact="SELECT * FROM `actividades` WHERE `fecha` < '".$date."' AND `completa`!=1 AND `clave_organizacion`='".$claveorganizacion."' ";
$resultadoact = mysql_query($sqloverdueact, $db);
$overdueact = mysql_num_rows($resultadoact);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>
<link rel="icon" href="images/icon.ico" />

</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard"></a></li>
          <li><a href="../organizaciones/index.php" class="contactos"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades"></a></li>
          <li><a href="modulos/oportunidades/oportunidades.php" class="oportunidades"></a></li>
          <li><a href="" class="ventas"></a></li>
          <li><a href="" class="casos"></a></li>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>) | <a href="../../index.php" class="sesionlinks">Inicio</a> | <a href="" class="sesionlinks">Mi cuenta</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <div id="titulo">Nombre de la Organización</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="../organizaciones/detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class="selected"><a href="#">Oportunidades <span class="count important"><?php echo $overdueopt; ?></span></a></li>
                <li class="../organizaciones/actividades.php?organizacion=<?php echo $claveorganizacion;?>"><a href="#">Actividades <span class="count important"><?php echo $overdueact; ?></span></a></li>
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
		
		$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
		$resultorg= mysql_query ($sqlorg,$db);

		while($myroworg=mysql_fetch_array($resultorg))
		{
			$empresa=$myroworg[organizacion];
			$clave=$myroworg[clave_unica];
			
			//Obtener la venta actual por cliente
			$sqlventa = "SELECT SUM(ValorNeto) as total FROM ventas WHERE K_Agente = '".$numeroagente."' AND K_Cliente = '".$clave."' AND (Fecha >= '".$primerdia."' AND Fecha <= '".$ultimodia."')";
			$resultventa = mysql_query($sqlventa, $db) or die(mysql_error());
			$venta = mysql_fetch_array($resultventa, MYSQL_ASSOC);
			
			//Telefonos de Organización
			$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC";
			$resulttelorg= mysql_query ($sqltelorg,$db);
			
			//Emails de Organización
			$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC";
			$resultemailorg= mysql_query ($sqlemailorg,$db);
			
			//Direcciones Web de Organización
			$sqlweborg="SELECT * FROM `direccionesweb` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_direccionweb ASC";
			$resultweborg= mysql_query ($sqlweborg,$db);
			
			//Domicilios de la Organización
			$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC";
			$resultdomorg= mysql_query ($sqldomorg,$db);
			
			//Contactos de la Organización
			$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_contacto ASC";
			$resultconorg= mysql_query ($sqlconorg,$db);
			?>
            <div id="lateral">
			<div id="projectbg">
			  	<div id="projectthumnail"><img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
				</div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><?php echo $empresa; ?></h1><br />
				</div>
			  </div>
              <div id="projectdetallestxtblank">
				<div id="projectdetallestxt">
                    	<ul class="contact-details">
						<?php
                        while($myrowtelorg=mysql_fetch_array($resulttelorg))
                        {
                            ?>
							<li class="phone"><?php echo $myrowtelorg[telefono]; ?> <span class="type"><?php echo $myrowtelorg[tipo_telefono]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowemailorg=mysql_fetch_array($resultemailorg))
                        {
                            ?>
							<li class="email"><a href="<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a><span class="type"><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowweborg=mysql_fetch_array($resultweborg))
                        {
                            ?>
							<li class="address"><a href="<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowdomorg=mysql_fetch_array($resultdomorg))
                        {
                            ?>
							<li class="address"><span class="type"><?php echo $myrowdomorg[tipo_domicilio];?></span><br /><?php echo $myrowdomorg[domicilio]; ?><br /><?php echo $myrowdomorg[ciudad]; ?><br /><?php echo $myrowdomorg[estado]; ?><br /><?php echo $myrowdomorg[cp]; ?><br /><?php echo $myrowdomorg[pais]; ?></li>
							<?php
                        }
                        ?>
                    	</ul>
				</div>
			  </div>
			</div>
        
        <fieldset class="fieldsetlateral">
        <legend>Acerca de</legend>
		<img src="../../images/add.png" class="linkImage" /><a href="#">Añadir una descripción</a>
		</fieldset>
            
		<fieldset class="fieldsetlateral">
        <legend>Contactos</legend>
        	<img src="../../images/add.png" class="linkImage" /><a href="#">Añadir una descripción</a>
            <table>
			<?php
            while($myrowconorg=mysql_fetch_array($resultconorg))
            {
                ?>
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td>
                    <td>
                        <a href="<?php echo $myrowconorg[id_contacto]; ?>"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a>
                        <span class="party-info-card party" id="<?php echo $myrowconorg[id_contacto]; ?>"><img src="../../images/vcard.png" class="linkImage" />
                        </span>
                      <br />
                            <span class="subtext"><?php echo $myrowconorg[puesto]; ?></span>
                    </td>
                </tr>
                
				<?php
            }
            ?>
            </table>

		</fieldset>
        
        <fieldset class="fieldsetlateral">
        <legend>Actividades</legend>
		<img src="../../images/add.png" class="linkImage" /><a href="#">Añadir una actividad</a>
		</fieldset>
            </div>
            
            <div id="derecho">
              <fieldset class="fieldsethistorial">
                <legend>Oportunidades</legend>

		<img src="https://d365sd3k9yw37.cloudfront.net/a/1349783504/theme/default/images/16x16/add.png" class="linkImage" /><a class="action" href="forminsert.php?organizacion=<?php echo $claveorganizacion; ?>&o=I">Agregar Oportunidad</a><table class="recordList" style="margin-top: 12px;">
<thead>
<tr>
<th class="list-column-left" scope="col">Oportunidad</th>
<th class="list-column-left" scope="col">Etapa</th>
<th class="list-column-right" scope="col">Valor</th>
<th class="list-column-left" scope="col">Usuario</th>
<th class="list-column-left" scope="col">Cierre</th>
</tr>
</thead>
<tbody>
<?php

$sqlopt="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$claveorganizacion."'";
$resultopt= mysql_query($sqlopt,$db);
while($myrowopt=mysql_fetch_array($resultopt))
{
	$nombre_oportunidad = $myrowopt[nombre_oportunidad];
	$descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
	$monto = $myrowopt[monto];
	$id_etapa = $myrowopt[id_etapa];
	$fecha_cierre_esperado = $myrowopt[fecha_cierre_esperado];
	//$dias = diferencia_dias($fecha_cierre_esperado,$date);
	
	//Definir fecha 1 
	$minuendo=explode("-",$fecha_cierre_esperado);
	$ano1 = $minuendo[0]; 
	$mes1 = $minuendo[1]; 
	$dia1 = $minuendo[2]; 
	//Definir fecha 2 
	$sustraendo=explode("-",$date);
	$ano2 = $sustraendo[0]; 
	$mes2 = $sustraendo[1]; 
	$dia2 = $sustraendo[2];
	//Calcular timestamp de las dos fechas 
	$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
	$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
	//Restar a una fecha la otra 
	$segundos_diferencia = $timestamp1 - $timestamp2; 
	//Convertir segundos en días 
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
	//Obtener el valor absoulto de los días (quito el posible signo negativo) 
	//$dias_diferencia = abs($dias_diferencia); 
	//Quitar los decimales a los días de diferencia 
	$dias_diferencia = floor($dias_diferencia);
	
	if($dias_diferencia<=0)
	{
		$resaltado="#FF7F7F";
	}
	elseif($dias_diferencia<=7)
	{
		$resaltado="#FFCC00";
	}
	else
	{
		$resaltado="#86CE79";
	}
	
	

	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
	$resultetp= mysql_query($sqletp,$db);
	while($myrowetp=mysql_fetch_array($resultetp))
	{
		$etapa = $myrowetp[etapa];
		$probabilidad = $myrowetp[probabilidad];
	}
	?>
    <tr class="odd-row">
<td class="list-column-left">
                        <a href="forminsert.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=U">
                            <?php echo $nombre_oportunidad; echo $dias_diferencia; ?>
                        </a>
                        <br />
                        <?php echo $descripcion_oportunidad; ?></td>
<td class=" list-column-left">
                        <?php echo $etapa; ?> (<?php echo $probabilidad; ?>%)</td>
<td class=" list-column-right"><?php echo $monto; ?></td>
<td class=" list-column-left">
                        Usuario</td>
<td class=" list-column-left"><span class="highlight" style="background-color:<?php echo $resaltado; ?>;"><?php echo htmlentities(strftime("%a, %b, %d", strtotime($fecha_cierre_esperado))); ?></span></td>
</tr>
    <?
}


?>

</tbody>
</table>
		
		<?php
		}
		?>

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
