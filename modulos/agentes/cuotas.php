<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];

if($_GET[agente]){$sqlidusr="SELECT * FROM usuarios WHERE idagente LIKE '".$_GET[usuario]."'"; $resultidusr = mysql_query($sqlidusr, $db); $myrowidusr=mysql_fetch_array($resultidusr); $claveusuario=$myrowidusr[idagente];}
else{$claveusuario=$_GET[usuario]; $sqlidusr="SELECT * FROM usuarios WHERE idagente LIKE '".$_GET[usuario]."'"; $resultidusr = mysql_query($sqlidusr, $db); $myrowidusr=mysql_fetch_array($resultidusr); $claveagt=$myrowidusr[claveagente];}

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
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="StyleSheet" href="estilos.css" type="text/css">

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
      
      <div id="titulo">Cuotas</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="detalles.php?usuario=<?php echo $claveusuario;?>">Resumen</a></li>
                <li class="selected"><a href="#">Metas</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="#">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="#">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
                <li class=""><a href="venta.php?organizacion=<?php echo $claveorganizacion;?>">Venta</a></li>
            </ul>
            <ul class="pageActions">
            	<li class="item"><img src="../../images/add.png" class="linkImage" /><a href="../metas/forminsert.php?usuario=<?php echo $claveusuario;?>&o=I">Ingresar metas</a></li>  
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
		
		$sqlusr="SELECT * FROM `usuarios` WHERE `idagente` LIKE '".$claveusuario."'";
		$resultusr= mysql_query ($sqlusr,$db);

		while($myrowusr=mysql_fetch_array($resultusr))
		{
			$agente=$myrowusr[apellidopaterno]." ".$myrowusr[apellidomaterno]." ".$myrowusr[nombre];
			$clave=$myrowusr[clave_unica];
			$claves=explode(",",$myroworg[clave_unica]);
			
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
				
			
			//Domicilios del Usuario
			$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC";
			$resultdomorg= mysql_query ($sqldomorg,$db);
			
			//Grupos de producto y facturación
			$sqlgpopto="SELECT * FROM `gruposproducto` WHERE `id_grupoproducto` = '".$myrowusr[id_grupoproducto]."'"; 
			$resultgpopto= mysql_query ($sqlgpopto,$db);
			$myrowgpopto=mysql_fetch_array($resultgpopto);
			
			$sqlgpofac="SELECT * FROM `gruposfacturacion` WHERE `id_grupofacturacion` = '".$myrowusr[id_grupofacturacion]."'"; 
			$resultgpofac= mysql_query ($sqlgpofac,$db);
			$myrowgpofac=mysql_fetch_array($resultgpofac);
			
			?>
            <div id="lateral">
			<div id="projectbg">
			  	<div id="projectthumnail">
				<?php if($myrowusr[foto]){?><img class="picture-normal" src="../../fotos/<?php echo $myrowusr[foto]; ?>" width="70" alt="" /><?php } else {?> <img class="picture-normal" src="../../images/person_avatar_70.png" width="70" height="70" alt="" /> <?php }?>
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><a href=""><?php echo $agente; ?></a></h1><br />
				</div>
			  </div>
              <div id="projectdetallestxtblank">
				<div id="projectdetallestxt">
                
                    <ul class="contact-details">
					<?php
                    if($myrowusr[telcasa]){?><li class="phone"><?php echo format_Telefono($myrowusr[telcasa]); ?> <span class="type">Casa</span></li><? }
                    if($myrowusr[teloficina]){?><li class="phone"><?php echo format_Telefono($myrowusr[teloficina]); ?> Ext. <?php echo $myrowusr[extoficina]; ?><span class="type">Oficina</span></li><? }
					if($myrowusr[nextel]){?><li class="phone"><?php echo format_Telefono($myrowusr[nextel]); ?> <span class="type">Nextel</span></li><? }
					if($myrowusr[idnextel]){?><li class="phone"><?php echo $myrowusr[idnextel]; ?> <span class="type">ID Nextel</span></li><? }
					if($myrowusr[teldirecto]){?><li class="phone"><?php echo format_Telefono($myrowusr[teldirecto]); ?> <span class="type">Directo</span></li><? }?>
                    </ul>
                    
                    <ul class="contact-details">
                    <?php
                    if($myrowusr[email])
                    {?><li class="email"><a href="mailto:<?php echo $myrowusr[email]; ?>"><?php echo $myrowusr[email]; ?></a></li><?php }
					if($myrowusr[emailotro])
                    {?><li class="email"><a href="mailto:<?php echo $myrowusr[emailotro]; ?>"><?php echo $myrowusr[emailotro]; ?></a></li><?php }?>
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
            <li><img src="../../images/edit_16.png" class="linkImage" /><a href="forminsert.php?idusuario=<?php echo $claveusuario;?>&o=U">Editar Usuario
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>
        
        <fieldset class="fieldsetlateral">
        <legend>Acerca de</legend>
            <ul class="contact-details">
                <li class="ingreso">Fecha de Ingreso: <span class="type"><?php echo $myrowusr[ingreso]; ?></span></li>
                <li class="goal">Meta: <?php echo $myrowgpofac[porcentaje]; ?>%</li>
            </ul>
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
			
			$strXML = "<chart caption='Venta Anual' subcaption='' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='10' chartRightMargin='35' bgColor='FFFFFF' bgAngle='270' bgAlpha='10,10' showBorder='0'>
			<categories>
			<category label='Trimestre 1'/>
			<category label='Trimestre 2'/>
			<category label='Trimestre 3'/>
			<category label='Trimestre 4'/>
			</categories>";
			
			$strXMLmeta = "<dataset seriesName='Metas ".$Anio."' color='9FC733' anchorBorderColor='9FC733' anchorBgColor='9FC733'>";
			$strXMLminimo = "<dataset seriesName='Mínimos ".$Anio."' color='FF0000' anchorBorderColor='FF0000' anchorBgColor='FF0000'>";
			$strXMLventas = "<dataset seriesName='Ventas ".$Anio."' color='00CCFF' anchorBorderColor='00CCFF' anchorBgColor='00CCFF'>";
			
			$sqlcuota = "SELECT * FROM cuotas WHERE clave_agente ='".$claveagt."' AND anio='".$Anio."'";
			$resultcuota = mysql_query($sqlcuota, $db) or die(mysql_error());
			$cuota = mysql_fetch_array($resultcuota, MYSQL_ASSOC);
			$totalcuota+=$cuota[unidades];
			
			$strTabla = "<table class='recordList' style='margin-top: 12px;'>
			  <tr>
				<th class='list-column-left'>".$Anio."</th>
				<th colspan='3' class='list-column-left'>Trimestre 1</td>
				<th colspan='3' class='list-column-left'>Trimestre 2</td>
				<th colspan='3' class='list-column-left'>Trimestre 3</td>
				<th colspan='3' class='list-column-left'>Trimestre 4</td>
				<th class='list-column-left'>Total</th>
			  </tr>
			</thead>
			<tbody>";
			$strTablaMeta="<tr class='odd-row'><td class='list-column-left'>Meta</td>";
			$strTablaMin="<tr class='odd-row'><td class='list-column-left'>Mínimo</td>";
			$strTablaVenta="<tr class='odd-row'><td class='list-column-left'>Venta</td>";
			$inc=-2;
			for($t=1;$t<=4;$t++)
			{
				$sqlcuota = "SELECT * FROM cuotas WHERE clave_agente ='".$claveagt."' AND trimestre='".$t."' AND anio='".$Anio."'";
				$resultcuota = mysql_query($sqlcuota, $db) or die(mysql_error());
				$cuota = mysql_fetch_array($resultcuota, MYSQL_ASSOC);
				$totalcuotameta+=$cuota[meta];
				$totalcuotamin+=$cuota[minimo];
				$strTablaMeta.="<td colspan='3' class='list-column-left'>".number_format($cuota[meta],2)."</td>";
				$strTablaMin.= "<td colspan='3' class='list-column-left'>".number_format($cuota[minimo],2)."</td>";
				$strXMLmeta .= "<set value='".round($cuota[meta],0)."'/>";
				$strXMLminimo .= "<set value='".round($cuota[minimo],0)."'/>";
				for($m=1;$m<=3;$m++)
				{
					$mesvta=(2*$t)+$m+$inc;
					$sqlvta = "SELECT * FROM ventas WHERE clave_agente ='".$claveagt."' AND mes='".$mesvta."' AND anio='".$Anio."'";
					//$resultvta = mysql_query($sqlvta, $db) or die(mysql_error());
					//$venta = mysql_fetch_array($resultvta, MYSQL_ASSOC);
					//$strTablaVenta.= "<td class='list-column-left'>".number_format($venta[monto],2)."</td>";
					$strTablaVenta.= "<td class='list-column-left'>".$mesvta."</td>";
					$strXMLventa .= "<set value='".round($mesvta,0)."'/>";
				}
				$inc++;
			}
			$strTablaMeta.="<td class='list-column-left'>".number_format($totalcuotameta,2)."</td></tr>";$strXMLmeta .= "</dataset>";
			$strTablaMin.="<td class='list-column-left'>".number_format($totalcuotamin,2)."</td></tr>";$strXMLminimo .= "</dataset>";
			$strTablaVenta.="<td class='list-column-left'>".number_format($cuota[meta],2)."</td></tr>";$strXMLventa .= "</dataset>";
			$strTabla .= $strTablaMeta.$strTablaMin.$strTablaVenta."</tbody></table>";
			$strXML.=$strXMLmeta.$strXMLminimo."</chart>";;
			$totalcuotameta=0;
			$totalcuotamin=0;
			
			
			$animateChart = $_GET['animate'];
			//Set default value of 1
			if ($animateChart=="")
				$animateChart = "1";

			$ruta = "Data/";
			$name_file = $claveagt.".xml";
			$file = fopen($ruta.$name_file,"w+");
			fwrite ($file,$strXML);
			fclose($file);
			
			echo renderChart("../../Charts/MSLine.swf", $ruta.$name_file, "", $myrowusr[claveagente], 600, 300, false, false);
		}
		?>
        <!--<div id="chartContainer">FusionCharts XT will load here!</div>
		<script type="text/javascript">
		var xml = <?php echo $ruta.$name_file ?>
		var myChart = new FusionCharts( "Column3D", "myChartId", "600", "300" );
		myChart.setXMLUrl("Data\msanchez.xml");
		myChart.render("chartContainer"); 
		</script>-->

        </fieldset>
        <?php
		
		echo $strTabla;
		
		?>
        
        
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
