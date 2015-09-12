<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];

if($_GET[agente]){$sqlidusr="SELECT * FROM usuarios WHERE claveagente LIKE '".$_GET[agente]."'"; $resultidusr = mysql_query($sqlidusr, $db); $myrowidusr=mysql_fetch_array($resultidusr); $claveusuario=$myrowidusr[idagente];}
else{$claveusuario=$_GET[usuario];}

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
      
      <div id="titulo">Usuarios y Promotores</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="#">Resumen</a></li>
                <li class=""><a href="cuotas.php?usuario=<?php echo $claveusuario;?>">Metas</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
                <li class=""><a href="venta.php?organizacion=<?php echo $claveorganizacion;?>">Venta</a></li>
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
			
			$animateChart = $_GET['animate'];
			//Set default value of 1
			if ($animateChart=="")
				$animateChart = "1";

			$ruta = "../ventas/Data/";
			$name_file = $myrowusr[claveagente].".xml";
			echo renderChart("../../Charts/MSLine.swf", $ruta.$name_file, "", $myrowusr[claveagente], 600, 300, false, false);	
		
		}
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
