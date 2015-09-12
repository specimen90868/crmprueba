<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];

//$numeroagente=number_format($claveagente);
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

$meses_espanol = array(
    '1' => 'Enero',
    '2' => 'Febrero',
    '3' => 'Marzo',
    '4' => 'Abril',
    '5' => 'Mayo',
    '6' => 'Junio',
    '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre',
    ); 

//Oportunidades atrasadas cierre anterior a la fecha actual y abiertas
$sqloverdueopt="SELECT * FROM `oportunidades` WHERE `fecha_cierre_esperado` < '".$date."' AND (`id_etapa`!=6 AND `id_etapa`!=7) AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoopt = mysql_query($sqloverdueopt, $db);
$overdueopt = mysql_num_rows($resultadoopt);

//Actividades atrasadas
$sqloverdueact="SELECT * FROM `actividades` WHERE `fecha` < '".$date."' AND `completa`!=1 AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoact = mysql_query($sqloverdueact, $db);
$overdueact = mysql_num_rows($resultadoact);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="../../css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>

<!--Tooltip-->
<script languaje="javascript" src="XMLHttpRequest.js" type="text/javascript"></script>
<script languaje="javascript" src="scripts.js" type="text/javascript"></script>
<link rel="StyleSheet" href="estilos.css" type="text/css">

<link rel="icon" href="images/icon.ico" />

<script src="../../js/tooltip/jquery.min.js" type="text/javascript"></script>
<script src="../../js/tooltip/jqueryTooltip.js" type="text/javascript"></script>
<style>
/* Tooltips */
a.tooltip:hover { 
text-decoration:none;
} 

a.tooltip span {
display:none; 
margin:0 0 0 10px; 
padding:5px 5px; 
} 

a.tooltip:hover span {
display:inline; 
position:absolute; 
border:1px solid #cccccc; 
background:#ffffff; 
color:#666666; 
}

</style>

</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    	<div id="logo"></div>
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard" title="Mi Tablero"></a></li>
          <li><a href="index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
	  $resultorg= mysql_query ($sqlorg,$db);
	  $myroworg=mysql_fetch_array($resultorg);
	  ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="#">Resumen</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
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
			$claves=explode(",",$myroworg[clave_unica]);
			//echo count($claves);
			
			$clavescliente="(";
			for($i=0; $i<count($claves); $i++)//Venta para varias claves
			{
				$clavescliente.="`K_Cliente`='$claves[$i]'";
				if($i<count($claves)-1){$clavescliente.=" OR ";}else{$clavescliente.=")";}
			}
			
			//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			
			//Semaforización de oportunidades
			if($dias>90){$resaltadocontacto="#FFBBBB";}
			elseif($dias>=31&&$dias<=90){$resaltadocontacto="#FFE784";}
			else{$resaltadocontacto="#BFE6B9";}
			
			//Obtener la venta actual por cliente para varias claves
			/*foreach ($claves as $K_Cliente) {
				$sqlventa = "SELECT SUM(ValorNeto) as total FROM ventas WHERE K_Agente = '".$numeroagente."' AND K_Cliente = '".$K_Cliente."' AND (Fecha >= '".$primerdia."' AND Fecha <= '".$ultimodia."')";*/
				


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
			  	<div id="projectthumnail">
				<?php
                /*$sqlarchivos="SELECT * FROM archivos WHERE tipo_registro='O' AND clave_registro='$claveorganizacion' AND tipo_archivo='Logotipo' AND (ext_archivo='JPG' OR ext_archivo='BMP' OR ext_archivo='PNG' OR ext_archivo='GIF')";
				$resultarc= mysql_query ($sqlarchivos,$db);
				while($myrowarc=mysql_fetch_array($resultarc))
				{
					$logotipo=$myrowarc[archivo];
				}
				?>
                <?php if($logotipo){?><a href="subirarchivo.php?organizacion=<?php echo $claveorganizacion; ?>" class="clsVentanaIFrame clsBoton" rel="Hola, esto es un iframe"><img class="picture-normal" src="../../logos/<?php echo $logotipo; ?>" width="70" alt="picture" /></a><?php } else {?> <a href="subirarchivo.php?organizacion=<?php echo $claveorganizacion; ?>&t=L" class="clsVentanaIFrame clsBoton" rel="Hola, esto es un iframe"><img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" /></a> <?php }*/?>
				<img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><a href="http://google.com/search?q=<?php echo $empresa; ?>" target="_blank"><?php echo $empresa; ?></a></h1><br />
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
							<li class="email"><a href="mailto:<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a><span class="type"><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowweborg=mysql_fetch_array($resultweborg))
                        {
                            ?>
							<li class="address"><a href="http://<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowdomorg=mysql_fetch_array($resultdomorg))
                        {
                            ?>
							<li class="address"><span class="type"><?php echo $myrowdomorg[tipo_domicilio];?></span><br /><?php echo $myrowdomorg[domicilio]; ?><br /><?php echo $myrowdomorg[ciudad]; ?> <?php echo $myrowdomorg[estado]; ?> <?php echo $myrowdomorg[cp]; ?> <?php echo $myrowdomorg[pais]; ?></li>
							<?php
                        }
                        ?>
                    	</ul>
                        
                        <ul class="formActions compact" style="margin-top: 10px;">
            <li><img src="https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png" class="linkImage" /><a href="formedit.php?organizacion=<?php echo $claveorganizacion;?>">Editar Organización
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>
        
        <fieldset class="fieldsetlateral">
        <legend>Acerca de</legend>
        <?php
		$sqlchecklist="SELECT * FROM `checklist` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_checklist ASC";
		$resultchecklist= mysql_query ($sqlchecklist,$db);
		$check = mysql_num_rows($resultchecklist);
		?>
		<?php if($check!=0){?><img src="../../images/add.png" class="linkImage" /><a href="checklistedit.php?organizacion=<?php echo $claveorganizacion;?>">Modificar checklist</a><?php }else{?><img src="../../images/exclamation.png" class="linkImage" /><a href="checklist.php?organizacion=<?php echo $claveorganizacion;?>">Llenar checklist</a><?php }?>
		</fieldset>
            
		<fieldset class="fieldsetlateral">
        <legend>Contactos</legend>
        	<img src="../../images/add.png" class="linkImage" /><a href="#">Añadir un contacto</a>
            <table>
			<?php
            while($myrowconorg=mysql_fetch_array($resultconorg))
            {
                ?>
                <!--<div class="cuadradito" id="<?php echo $myrowconorg[id_contacto]; ?>"></div>-->
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td>
                    <td>
                        
<!--<a href="ficha.php?id=<?php echo $myrowconorg[id_contacto]; ?>" class="clsVentanaIFrame clsBoton" rel="Editar Contacto"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a>-->

<?php
$ficha="<div id='projectbg'>
			  	<div id='projectthumnail'>
				<img class='picture-normal' src='../../images/org_avatar_70.png' width='70' height='70' alt='picture' />
                </div>
			  <div id='projecttxtblank'>
				<div id='projecttxt'><h1><a href='http://google.com/search?q=<?php echo $empresa; ?>' target='_blank'><?php echo $empresa; ?></a></h1><br />
				</div>
			  </div>
              <div id='projectdetallestxtblank'>
				<div id='projectdetallestxt'>
                    	<ul class='contact-details'>
						<?php
                        while($myrowtelorg=mysql_fetch_array($resulttelorg))
                        {
                            ?>
							<li class='phone'><?php echo $myrowtelorg[telefono]; ?> <span class='type'><?php echo $myrowtelorg[tipo_telefono]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class='contact-details'>
						<?php
                        while($myrowemailorg=mysql_fetch_array($resultemailorg))
                        {
                            ?>
							<li class='email'><a href='mailto:<?php echo $myrowemailorg[correo]; ?>'><?php echo $myrowemailorg[correo]; ?></a><span class='type'><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class='formActions compact' style='margin-top: 10px;'>
            <li><img src='https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png' class='linkImage' /><a href='formedit.php?organizacion=<?php echo $claveorganizacion;?>'>Editar Organización
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>";
?>

<a href=""><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a><span class="" id="<?php echo $myrowconorg[id_contacto]; ?>"> <img src="../../images/vcard.png" class="linkImage" id="ficha"/></span>
                      <br />
                      <span class="subtext"><?php echo $myrowconorg[puesto]; ?></span><br />
                      <img src="<?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo '../../images/cake.png';} else {echo '../../images/cakebn.png';} ?> " /> <?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo $myrowconorg[dia_cumpleanios]." de ".$meses_espanol[$myrowconorg[mes_cumpleanios]];} else{echo "No registrado"; }?>
                    </td>
                </tr>
               
				<?php
            }
            ?>
            </table>

		</fieldset>
            </div>
            
            <div id="derecho">
            <table style="width:100%; margin-bottom:5px;">
                    <tr>
                        <td class="list-column-center" style="width:50%; padding-right:5px;">
                            <div class="roundedpanel" style="height:65px; background-color:<?php echo $resaltadocontacto; ?>;">
                                <div class="roundedpanel-content">
                                    Último Contacto<br />
                                        <b style="font-size:16px;"><?php if($myroworg[fecha_ultimo_contacto]){echo htmlentities(strftime("%e de %B de %Y", strtotime($myroworg[fecha_ultimo_contacto])));}else{echo "Ninguno";} ?></b>
                                </div>
                            </div>
                        </td>
                        <td class="list-column-center" style="width:50%; padding-left:5px; padding-right:5px;">
                            <div class="roundedpanel" style="height:65px;">
                                <div class="roundedpanel-content">
                                    Consumo Mensual
                                    <br />
                                        <b style="font-size:16px;"><?php if($venta['total']) {echo "$ ".number_format($venta['total']);} else { echo "Ninguno"; }?></b>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
        
        <fieldset class="fieldsethistorial">
        <legend>Gráfico de consumo</legend>

		<?php
			
			$animateChart = $_GET['animate'];
			//Set default value of 1
			if ($animateChart=="")
				$animateChart = "1";
		 
		 $strXML = "<chart caption='Consumo Mensual' subcaption='' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='10' chartRightMargin='35' bgColor='FFFFFF,CC3300' bgAngle='270' bgAlpha='10,10'>
	<categories>
	<category label='Ene'/>
	<category label='Feb'/>
	<category label='Mar'/>
	<category label='Abr'/>
	<category label='May'/>
	<category label='Jun'/>
	<category label='Jul'/>
	<category label='Ago'/>
	<category label='Sep'/>
	<category label='Oct'/>
	<category label='Nov'/>
	<category label='Dic'/>
	</categories>";
	
	 $strTabla = "<table class='recordList' style='margin-top: 12px;'>
		  <thead>
		  <tr>
		    <th class='list-column-left' scope='col'></th>
			<th class='list-column-left' scope='col'>Ene</th>
		    <th class='list-column-left' scope='col'>Feb</th>
		    <th class='list-column-left' scope='col'>Mar</th>
		    <th class='list-column-left' scope='col'>Abr</th>
		    <th class='list-column-left' scope='col'>May</th>
		    <th class='list-column-left' scope='col'>Jun</th>
		    <th class='list-column-left' scope='col'>Jul</th>
		    <th class='list-column-left' scope='col'>Ago</th>
		    <th class='list-column-left' scope='col'>Sep</th>
		    <th class='list-column-left' scope='col'>Oct</th>
		    <th class='list-column-left' scope='col'>Nov</th>
		    <th class='list-column-left' scope='col'>Dic</th>
		    </tr>
			</thead>
			<tbody>";
		 
		//Venta por mes, año anterior
		$strXML .= "<dataset seriesName='".$Anio_anterior."' color='1D8BD1' anchorBorderColor='1D8BD1' anchorBgColor='1D8BD1'>";
		$strTabla .= "<tr class='odd-row'><td class='list-column-left' style='color:#1D8BD1;'><b>".$Anio_anterior."</b></td>";
		
		for($ma=1;$ma<=12;$ma++)
		{
			$qultimodia_ant= $Anio_anterior."-".$ma."-".ultimo_dia($m,$Anio_anterior)." 00:00:00";
			$qprimerdia_ant= $Anio_anterior."-".$ma."-01 00:00:00";
			
			$sqlventa_ant = "SELECT SUM(ValorNeto) as total_ant FROM ventas WHERE ".$clavescliente." AND K_Agente = '".$numeroagente."' AND (Fecha >= '".$qprimerdia_ant."' AND Fecha <= '".$qultimodia_ant."')";
			
			//echo $sqlventa_ant;
			$resultventa_ant = mysql_query($sqlventa_ant, $db) or die(mysql_error());
			$venta_ant = mysql_fetch_array($resultventa_ant, MYSQL_ASSOC);
			
			$strXML .= "<set value='".$venta_ant['total_ant']."'/>";
			$strTabla.= "<td class='list-column-left'>".number_format($venta_ant['total_ant'])."</td>";
		}
		$strXML .= "</dataset>";
		$strTabla .= "</tr>";
		
		//Venta por mes, año actual
		$strXML .= "<dataset seriesName='".$Anio."' color='F1683C' anchorBorderColor='F1683C' anchorBgColor='F1683C'>";
		$strTabla .= "<tr class='odd-row'><td class='list-column-left' style='color:#F1683C;'><b>".$Anio."</b></td>";
		
		for($m=1;$m<=$Mes;$m++)
		{
			$qultimodia= $Anio."-".$m."-".ultimo_dia($m,$Anio)." 00:00:00";
			$qprimerdia= $Anio."-".$m."-01 00:00:00";
			
			$sqlventa_act = "SELECT SUM(ValorNeto) as total FROM ventas WHERE ".$clavescliente." AND K_Agente = '".$numeroagente."' AND (Fecha >= '".$qprimerdia."' AND Fecha <= '".$qultimodia."')";
			//echo $sqlventa_act;
			
			$resultventa = mysql_query($sqlventa_act, $db) or die(mysql_error());
			$venta = mysql_fetch_array($resultventa, MYSQL_ASSOC);

			$strXML .= "<set value='".$venta['total']."'/>";
			$strTabla.= "<td class='list-column-left'>".number_format($venta['total'])."</td>";
		}
		$strXML .= "</dataset></chart>";
		$strTabla .= "</tr></table>";
		
		$sqlventa = "SELECT SUM(ValorNeto) as total FROM ventas WHERE K_Agente = '".$numeroagente."' AND K_Cliente = '".$clave."' AND (Fecha >= '".$primerdia."' AND Fecha <= '".$ultimodia."')";
		$resultventa = mysql_query($sqlventa, $db) or die(mysql_error());
		$venta = mysql_fetch_array($resultventa, MYSQL_ASSOC);
			
		}
		$ruta = "Data/";
		$name_file = $claveorganizacion.".xml";
		$file = fopen($ruta.$name_file,"w+");
		fwrite ($file,$strXML);
		fclose($file);
		echo renderChart("../../Charts/MSLine.swf", $ruta.$name_file, "", "FactorySum", 600, 300, false, false);
		?>

        </fieldset>

		<?php echo $strTabla; ?>
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
		<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../../js/ventanas-modales.js"></script>
</body>
