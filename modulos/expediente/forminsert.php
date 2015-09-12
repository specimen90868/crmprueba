<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
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

<link rel="StyleSheet" href="estilos.css" type="text/css">
<link rel="icon" href="images/icon.ico" />

<style>
.myform{
margin:0 auto;
width:660px;
padding:10px;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}

/* ----------- stylized ----------- */
#stylized{
	border:solid 1px #e3e3e3;
	background:#fff;
}
#stylized h1 {
font-size:14px;
font-weight:bold;
margin-bottom:8px;
}
#stylized p{
	font-size:11px;
	color:#666666;
	margin-bottom:20px;
	border-bottom:solid 1px #e3e3e3;
	padding-bottom:10px;
}
#stylized label{
	display:block;
	font-weight:bold;
	text-align:left;
	width:659px;
	float:left;
}
#stylized .small{
color:#666666;
display:block;
font-size:11px;
font-weight:normal;
text-align:right;
width:140px;
}
#stylized input{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 0 20px 10px;
}

#stylized select{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:156px;
	margin:2px 0 20px 10px;
}

#stylized .input2 {
	float:left;
	font-size:12px;
	padding:2px 2px;
	border:solid 1px #E3E3E3;
	width:654px;
	margin:5px 10px 20px 0;
}

#stylized .input3 {
	float:left;
	font-size:12px;
	padding:2px 2px;
	border:solid 1px #E3E3E3;
	width:654px;
	margin:5px 10px 5px 0;
	color: #FF7F7F;
}

#stylized button{
clear:both;
margin-left:150px;
width:125px;
height:31px;
background:#666666 url(img/button.png) no-repeat;
text-align:center;
line-height:31px;
color:#FFFFFF;
font-size:11px;
font-weight:bold;
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
		  if($_SESSION["Tipo"]=="Sistema"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
	  $resultorg= mysql_query ($sqlorg,$db);
	  $myroworg=mysql_fetch_array($resultorg);
	  ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="../organizaciones/detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="../organizaciones/expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Archivos</a></li>
                <li class="selected"><a href="../organizaciones/oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="../organizaciones/actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
            </ul>
            <ul class="pageActions">
            	<li class="item"><img src="../../images/add.png" class="linkImage" /><a href="../organizaciones/oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Lista de Oportunidades</a></li>  
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
				<img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><a href="http://google.com/search?q=<?php echo $empresa; ?>" target="_blank"><?php echo $empresa; ?></a></h1><br />
                <?php
                if($myroworg[tipo_persona])
                {
                    ?>
                    <span class="highlight">Persona <?php echo $myroworg[tipo_persona]; ?></span>
                    <?php
                }
                else
                {
                    ?>
                    <img src="../../images/warning_16.png" class="linkImage" /><span class="highlight" style="background-color: #FFE680; margin-bottom: 10px; text-align: center; color:#333; padding:3px 3px 3px 3px;">Falta tipo de persona del acreditado</span>
                    <?php
                }
                ?>
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
            <li><img src="https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png" class="linkImage" /><a href="../organizaciones/formedit.php?organizacion=<?php echo $claveorganizacion;?>">Editar Organización
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>

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

<a href=""><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a><span class="" id="<?php echo $myrowconorg[id_contacto]; ?>"> <img src="../../images/vcard.png" class="linkImage" id="ficha"/></span>
                      <br />
                      <span class="subtext"><?php echo $myrowconorg[puesto]; ?></span><br />
                      <span class="subtext"><?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo $myrowconorg[dia_cumpleanios]." de ".$meses_espanol[$myrowconorg[mes_cumpleanios]];} else{echo "No registrado"; }?></span> <img src="<?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo '../../images/cake.png';} else {echo '../../images/cakebn.png';} ?> " class="linkImage" />
                    </td>
                </tr>
               
				<?php
            }
            ?>
            </table>

		</fieldset>
        
        <fieldset class="fieldsetlateral">
        <legend>Datos del Agente</legend>
        <?php if($myroworg[asignado]==1)
		{
			?>
            <table id="" class="recordList">
            <tbody>
            <?php 
            $sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
            $resultagente= mysql_query ($sqlagente,$db);
            while($myrowagente=mysql_fetch_array($resultagente))
            {
                $agente=$myrowagente[apellidopaterno]." ".$myrowagente[apellidomaterno]." ".$myrowagente[nombre];
                ?>
                <tr class="even-row">
                <td class="list-column-picture">
                    
                <?php
                if($myrowagente[foto]){?><img class="picture-thumbnail" src="../../fotos/<?php echo $myrowagente[foto]; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" /> <?php }?>
                </td>
                <td class=" list-column-left">
                    <a href=""><?php echo $agente; ?></a><br /> <?php echo format_Telefono($myrowagente[teloficina]); ?> <span class="highlight" style="background-color: #9FC733;"><?php echo $myrowagente[extoficina]; ?></span><br /><a target="" href="mailto:<?php echo $myrowagente[email]; ?>"><?php echo $myrowagente[email]; ?></a><br />
                </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
		<?php
		}
		else
		{
			?>
            <table id="" class="recordList">
            <tbody>
            <?php 
            $sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
            $resultagente= mysql_query ($sqlagente,$db);
            while($myrowagente=mysql_fetch_array($resultagente))
            {
                $agente=$myrowagente[apellidopaterno]." ".$myrowagente[apellidomaterno]." ".$myrowagente[nombre];
                ?>
                <tr class="even-row">
                <td class="list-column-picture">
                <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" />
                </td>
                <td class=" list-column-left">El contacto aún no ha sido asignado</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
		<?php
		}
		?>
        </fieldset>
        
            </div>
            
            <div id="derecho">

			<?php
            switch($_GET[o])
            {
				case 'I':
					$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
					$resultopt= mysql_query ($sqlopt,$db);
					$myrowopt=mysql_fetch_array($resultopt);
					$nombre_oportunidad = $myrowopt[productos];
					$descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
					if($myrowopt[tipo_credito]){$tipo = $myrowopt[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
					if($myrowopt[monto]){$monto = " por ".number_format($myrowopt[monto]);}else{$monto=" Monto: sin especificar, ";}
					if($myrowopt[plazo_credito]){$plazo = " a ".$myrowopt[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
					$id_etapa = $myrowopt[id_etapa];
					
					//Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
					$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
					$resultetp= mysql_query($sqletp,$db);
					while($myrowetp=mysql_fetch_array($resultetp))
					{
						$etapa = $myrowetp[etapa];
						$anterior= $myrowetp[etapa_anterior];
						$siguiente= $myrowetp[etapa_siguiente];
						$probabilidad = $myrowetp[probabilidad];
						$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
						$resultcolor=mysql_query($sqlcolor,$db);
						$myrowcolor=mysql_fetch_array($resultcolor);
						
						//Color de celda y vínculos
						if($myrowetp[id_responsable]!=$responsable)
						{
							$celda="#F0F0F0";
							$vinculo=0;
						}
						else
						{
							$celda="#FFFFFF";
							$vinculo=1;
						}
					}//Fin de while de etapa
					
					//Verificar si se solicita algún expediente en la etapa de la oportunidad
					$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$id_etapa."' OR id_etapa='".$anterior."'";
					$resultexp= mysql_query ($sqlexp,$db);
					$myrowexp=mysql_fetch_array($resultexp);
				?>    
					<table style="width:100%; margin-bottom:5px;">
                        <tr>
                           <?php
                            //Por qué etapas ha pasado la oportunidad
							$sqlproc="SELECT * FROM  `etapasoportunidades` WHERE clave_oportunidad = '".$myrowopt[clave_oportunidad]."' ORDER BY id_etapa ASC";
							$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";
                            $resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                                $sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
                                $resultcolor=mysql_query($sqlcolor,$db);
                                $myrowcolor=mysql_fetch_array($resultcolor);
                                $resultproc=mysql_query($sqlproc,$db);
								while($myrowproc=mysql_fetch_array($resultproc))
								{
									if($myrowetapa[numero_etapa]==$myrowproc[id_etapa]){$color[$myrowproc[id_etapa]]=$myrowcolor[color];}else{if($myrowetapa[numero_etapa]>$myrowopt[id_etapa]){$color[$myrowetapa[numero_etapa]]="D6D6D6";}else{$color[$myrowproc[id_etapa]]="D6D6D6";}}
								}
								?>
                                <td class="list-column-center" style="width:9%; padding-right:5px;">
                                <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                    <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]; ?>"><span style="font-size:12px; color:#FFF;"><?php echo $myrowetapa[numero_etapa]; ?></span><br /><span style="color:#FFF; font-size:8px;"><?php echo $myrowcolor[responsable]; ?></span></div>
                                </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                    
                    <?php if($vinculo==0){?><span class="highlight" style="background-color: #FFBBBB; width: 679px; margin-bottom: 10px; text-align: center; padding:3px 3px 3px 3px; color:#333;">No puedes realizar cambios en esta etapa del proceso</span><?php }
                    
                    //Verificar si el garante fue capturado y si éste es el mismo que el acreditado
					$sqlgarante="SELECT relaciones.clave_organizacion,relaciones.clave_contacto,relaciones.clave_relacion,relaciones.rol, relaciones.clave_oportunidad FROM relaciones LEFT JOIN (organizaciones) ON (relaciones.clave_relacion=organizaciones.clave_organizacion) WHERE relaciones.clave_oportunidad= '".$myrowopt[clave_oportunidad]."' AND relaciones.rol = 'Garante' ORDER BY relaciones.id_relacion ASC";
					$rsgarante= mysql_query ($sqlgarante,$db);
					$rwgarante=mysql_fetch_array($rsgarante);
					
                    //Validar si el tipo de persona del acreditado fue, capturado
                    if($myroworg[tipo_persona]&&mysql_num_rows($rsgarante)!=0)//Cargar formulario de carga de documentos de acuerdo al tipo de persona del acreditado
                    {
                        ?>
                        <div id="stylized" class="myform">
                        <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                        <h1>Subir <?php echo $myrowexp[expediente]; ?></h1> <img src="../../images/warning_16.png" class="linkImage" /><span class="highlight" style="background-color: #FFE680; margin-bottom: 10px; text-align: center; color:#333; padding:3px 3px 3px 3px;">Los archivos no debe superar los 4Mb, y ser de tipo: pdf, jpeg, jpg, png o gif</span>
                        <p><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?></p>

                        <?php
                        $err="";
                        if (file_exists("Data/log.txt"))
                        {
                        ?>
                        <span class='highlight' style='background-color: #EDEDED; width: 643px; margin-bottom: 10px; padding:13px 3px 3px 13px; color:#333;'>
                        <?php
                        $file = fopen("Data/log.txt", "r");
                        while(!feof($file))
                        {
                            echo fgets($file). "<br />";
                        }
                        fclose($file);
                        ?>
                        </span>
                        <?php
                        unlink("Data/log.txt");
                        }
						
						//Agrupando los archivos en categorías
						$sqlcategorias="SELECT DISTINCT(tiposarchivos.id_categoriaarchivo),categoriasarchivos.categoria FROM tiposarchivos JOIN categoriasarchivos ON (tiposarchivos.id_categoriaarchivo=categoriasarchivos.id_categoriaarchivo) WHERE tiposarchivos.id_expediente = '".$myrowexp[id_expediente]."' AND tiposarchivos.tipo_persona = '".$myroworg[tipo_persona]."' AND categoriasarchivos.rol_persona!='Garante' ORDER BY categoriasarchivos.id_categoriaarchivo ASC";
						$resultcategorias= mysql_query ($sqlcategorias,$db);
                        while($myrowcategorias=mysql_fetch_array($resultcategorias))
                        {
							?>
							<fieldset style="background-color:#f9f9f9;">
                            <legend><?php echo $myrowcategorias[categoria]; ?></legend>
                            <!--<p><span style="background-color:#e3e3e3;"><?php echo $myrowcategorias[categoria]; ?></span></p>-->
                            <?php
							//Consultar los archivos solicitados por cada categoría
							$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente = '".$myrowexp[id_expediente]."' AND tipo_persona = '".$myroworg[tipo_persona]."' AND id_categoriaarchivo = '".$myrowcategorias[id_categoriaarchivo]."' ORDER BY requerido DESC, tipo_archivo ASC";
							//echo $sqltipos;
							$resulttipos= mysql_query ($sqltipos,$db);
							while($myrowtipos=mysql_fetch_array($resulttipos))
							{
							$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowexp[id_expediente]";
							$resultcolor=mysql_query($sqlcolor,$db);
							$myrowcolor=mysql_fetch_array($resultcolor);
							//Verificar si hay archivo cargado
							$sqlarchivo="SELECT * FROM archivos WHERE id_tipoarchivo='".$myrowtipos[id_tipoarchivo]."' AND id_oportunidad='".$_GET[id]."'";
							$resultarchivo= mysql_query ($sqlarchivo,$db);
							$myrowarchivo=mysql_fetch_array($resultarchivo);
							//Revisar historial del archivo
							$sqlhist="SELECT * FROM historialarchivos WHERE clave_archivo='".$myrowarchivo[clave_archivo]."' AND id_oportunidad='".$_GET[id]."' ORDER BY fecha_actividad DESC LIMIT 1";
							$rshist= mysql_query ($sqlhist,$db);
							$rwhist=mysql_fetch_array($rshist);
							?>
							<label><?php echo $myrowtipos[tipo_archivo];
							if($myrowtipos[requerido]=="1"){echo " <img src='../../images/required.gif'  class='linkImage' />";}else{" (opcional)";}
							if($myrowarchivo){
							?>
							 <img src="../../images/acrobat_16.png"  class="linkImage" /> <a href="../../expediente/<?php echo $myrowarchivo[nombre]; ?>" target="_blank"><span class="highlight" style="background-color:#<?php if($myrowarchivo[aprobado]==2){echo "FFBBBB"; }else{echo $myrowcolor[color];}?>; font-weight:normal;"> <?php echo $myrowarchivo[nombre_original]; ?> </span></a> <?php }
							?>
							</label>
							<?php
							if($myrowarchivo[aprobado]==2){?><input name="motivo<?php echo $myrowarchivo[id_archivo]; ?>" id="motivo<?php echo $myrowarchivo[id_archivo]; ?>" cols="45" rows="2" value="<?php if($myrowarchivo[aprobado]==2){if($rwhist[motivo]){echo $rwhist[motivo];}else{echo "No indicado";}}?>" disabled="disabled" title="Motivo de Rechazo" class="input3" style="color:#FF7F7F;"/><?php }
							?>
							<div class="spacer"></div>
							<input name="archivo<?php echo $myrowtipos[id_tipoarchivo];?>" type="file" class="input2" id="archivo<?php echo $myrowtipos[id_tipo];?>" <?php if($vinculo==0){echo "disabled='disabled'"; }?> style="width:620px;"/>
							<div class="spacer"></div>
							<?php
							}
							?>
                            </fieldset>
                            <?php
						}//Fin de while categorias de archivos

                        //Si el Acreditado es diferente al garante, se solicitan nuevos documentos para el último
						if($rwgarante[clave_organizacion]!=$rwgarante[clave_relacion])
						{
							//Agrupando los archivos en categorías
							$sqlcategorias="SELECT DISTINCT(tiposarchivos.id_categoriaarchivo),categoriasarchivos.categoria FROM tiposarchivos JOIN categoriasarchivos ON (tiposarchivos.id_categoriaarchivo=categoriasarchivos.id_categoriaarchivo) WHERE tiposarchivos.id_expediente = '".$myrowexp[id_expediente]."' AND tiposarchivos.tipo_persona = '".$myroworg[tipo_persona]."' AND categoriasarchivos.rol_persona='Garante' ORDER BY categoriasarchivos.id_categoriaarchivo ASC";
							$resultcategorias= mysql_query ($sqlcategorias,$db);
							while($myrowcategorias=mysql_fetch_array($resultcategorias))
							{
								?>
								<fieldset style="background-color:#f9f9f9;">
								<legend><?php echo $myrowcategorias[categoria]; ?></legend>
								<!--<p><span style="background-color:#e3e3e3;"><?php echo $myrowcategorias[categoria]; ?></span></p>-->
								<?php
								//Consultar los archivos solicitados por cada categoría
								$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente = '".$myrowexp[id_expediente]."' AND tipo_persona = '".$myroworg[tipo_persona]."' AND id_categoriaarchivo = '".$myrowcategorias[id_categoriaarchivo]."' ORDER BY requerido DESC, tipo_archivo ASC";
								//echo $sqltipos;
								$resulttipos= mysql_query ($sqltipos,$db);
								while($myrowtipos=mysql_fetch_array($resulttipos))
								{
								$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowexp[id_expediente]";
								$resultcolor=mysql_query($sqlcolor,$db);
								$myrowcolor=mysql_fetch_array($resultcolor);
								//Verificar si hay archivo cargado
								$sqlarchivo="SELECT * FROM archivos WHERE id_tipoarchivo='".$myrowtipos[id_tipoarchivo]."' AND id_oportunidad='".$_GET[id]."'";
								$resultarchivo= mysql_query ($sqlarchivo,$db);
								$myrowarchivo=mysql_fetch_array($resultarchivo);
								//Revisar historial del archivo
								$sqlhist="SELECT * FROM historialarchivos WHERE clave_archivo='".$myrowarchivo[clave_archivo]."' AND id_oportunidad='".$_GET[id]."' ORDER BY fecha_actividad DESC LIMIT 1";
								$rshist= mysql_query ($sqlhist,$db);
								$rwhist=mysql_fetch_array($rshist);
								?>
								<label><?php echo $myrowtipos[tipo_archivo];
								if($myrowtipos[requerido]=="1"){echo " <img src='../../images/required.gif'  class='linkImage' />";}else{" (opcional)";}
								if($myrowarchivo){
								?>
								 <img src="../../images/acrobat_16.png"  class="linkImage" /> <a href="../../expediente/<?php echo $myrowarchivo[nombre]; ?>" target="_blank"><span class="highlight" style="background-color:#<?php if($myrowarchivo[aprobado]==2){echo "FFBBBB"; }else{echo $myrowcolor[color];}?>; font-weight:normal;"> <?php echo $myrowarchivo[nombre_original]; ?> </span></a> <?php }
								?>
								</label>
								<?php
								if($myrowarchivo[aprobado]==2){?><input name="motivo<?php echo $myrowarchivo[id_archivo]; ?>" id="motivo<?php echo $myrowarchivo[id_archivo]; ?>" cols="45" rows="2" value="<?php if($myrowarchivo[aprobado]==2){if($rwhist[motivo]){echo $rwhist[motivo];}else{echo "No indicado";}}?>" disabled="disabled" title="Motivo de Rechazo" class="input3" style="color:#FF7F7F;"/><?php }
								?>
								<div class="spacer"></div>
								<input name="archivo<?php echo $myrowtipos[id_tipoarchivo];?>" type="file" class="input2" id="archivo<?php echo $myrowtipos[id_tipo];?>" <?php if($vinculo==0){echo "disabled='disabled'"; }?> style="width:620px;"/>
								<div class="spacer"></div>
								<?php
								}
								?>
								</fieldset>
								<?php
							}//Fin de while categorias de archivos
						}//Fin de if documentos de garante

                        ?>
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
                        <input type="hidden" name="id" id="id"  value="<?php echo $_GET[id]; ?>" />
                        <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Organizaciones Oportunidades -->
                        <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                        <input type="hidden" name="e" id="e"  value="<?php echo $_GET[e]; ?>" /><!-- Id de Expediente -->
                        </form>
                        </div>
                        <?php
                    }
                    else//Informar al promotor que no se cargará la lista hasta especificar el tipo de persona del acreditado
                    {
                        if(!$myroworg[tipo_persona])
						{
							$informacion.="Tipo de persona del acreditado<br>";
						}
						if(mysql_num_rows($rsgarante)==0)
						{
							$informacion.="Garante del acreditado";
						}
						?>
                        <span class="highlight" style="background-color: #FFBBBB; width: 679px; margin-bottom: 10px; text-align: center; padding:3px 3px 3px 3px; color:#333;">No puede cargarse la lista de documentos, pues no se ha especificado la siguente información: <br><b><i><?php echo $informacion; ?></i></b></span>
                        <?php   
                    }   
					break;
				case 'U':
					$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
					$resultopt= mysql_query ($sqlopt,$db);
					while($myrowopt=mysql_fetch_array($resultopt))
					{
						//Responsable de la etapa en la que está la oportunidad
						$sqletapa="SELECT * FROM  `etapas` WHERE id_etapa = $myrowopt[id_etapa] ORDER BY id_etapa";
						$resultetapa=mysql_query($sqletapa,$db);
						$myrowetapa=mysql_fetch_array($resultetapa);
						//Color del responsable por etapa
						$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
						$resultcolor=mysql_query($sqlcolor,$db);
						$myrowcolor=mysql_fetch_array($resultcolor);
						
						if($myrowetapa[id_responsable]!=$responsable){?><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>;"><?php echo $myrowetapa[etapa]; ?></span><?php }else{}
						
						?>
                        
                        <table style="width:100%; margin-bottom:5px;">
                        <tr>
                           <?php
                            //Por qué etapas ha pasado la oportunidad
							$sqlproc="SELECT * FROM  `etapasoportunidades` WHERE clave_oportunidad = '".$myrowopt[clave_oportunidad]."' ORDER BY id_etapa ASC";
							$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";
                            $resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                                $sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
                                $resultcolor=mysql_query($sqlcolor,$db);
                                $myrowcolor=mysql_fetch_array($resultcolor);
                                $resultproc=mysql_query($sqlproc,$db);
								while($myrowproc=mysql_fetch_array($resultproc))
								{
									if($myrowetapa[numero_etapa]==$myrowproc[id_etapa]){$color[$myrowproc[id_etapa]]=$myrowcolor[color];}else{if($myrowetapa[numero_etapa]>$myrowopt[id_etapa]){$color[$myrowetapa[numero_etapa]]="D6D6D6";}else{$color[$myrowproc[id_etapa]]="D6D6D6";}}
								}
								?>
                                <td class="list-column-center" style="width:10%; padding-right:5px;">
                                <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                    <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]." (".$myrowetapa[probabilidad]."%)"; ?>"><span style="font-size:14px; color:#FFF;"><?php echo $myrowetapa[numero_etapa]; ?></span><br /><span style="color:#FFF; font-size:9px;"><?php echo $myrowcolor[responsable]; ?></span></div>
                                </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                        
                        <div id="stylized" class="myform">
                        <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                        <h1>Actualizar proceso de promoción</h1>
                        <p></p>
                        
                        <?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
						{
							?>
                            <label>Promotor
                            </label>
                            <select id="promotor" name="promotor" class="input2">
                            <option value="0">Sin Asignar</option>
                            <?php
                            $sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC";
                            $resultagt= mysql_query ($sqlagt,$db);
                            while($myrowagt=mysql_fetch_array($resultagt))
                            {
                                ?>
                                <option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($myrowagt[claveagente]==$myrowopt[usuario]){echo "selected";}?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
                                <?php
                            }
                            ?>
                            </select>
                            <div class="spacer"></div>
                        	<?php
						}
						?>
                        
                        <label>Etapa
                        </label>
                        <?php
                        /*if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
						{	
                        	$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";
						}
						else//Es Promotor
						{
							$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable ORDER BY id_etapa";
							?><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>;">
                        <?php echo $etapa; ?> (<?php echo $probabilidad; ?>%)</span><?php
						}*/
						?>
                        
                        <select name="id_etapa" class="input2" id="id_etapa">
						<?php
						if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable ORDER BY id_etapa";}
						$resultetapa=mysql_query($sqletapa,$db);
						while($myrowetapa=mysql_fetch_array($resultetapa))
						{
						?>
							<option value="<?php echo $myrowetapa[id_etapa]; ?>" <?php if($myrowopt[id_etapa]==$myrowetapa[id_etapa]){echo "selected";}?> <?php if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]){ echo "disabled style='color:#999;'"; } ?>><?php echo $myrowetapa[numero_etapa]." - ".$myrowetapa[etapa]; ?></option>
						<?php
						}
						?>
                        </select>
                        <div class="spacer"></div>
                        
                        <label>Tipo de Crédito
                        </label>
                        <select name="tipo_credito" id="tipo_credito">
                            <option value="" <?php if($myrowopt[tipo_credito]==""){echo "selected";}?>>Sin especificar</option>
                            <option value="Revolvente" <?php if($myrowopt[tipo_credito]=="Revolvente"){echo "selected";}?>>Revolvente</option>
                        </select>
                        
                        <label>Monto <span class="small">Monto del crédito solicitado</span>
                        </label>
                        <input type="text" name="monto_credito" id="monto_credito" value="<?php echo $myrowopt[monto]; ?>"/>
                        <div class="spacer"></div>
                        
                        <label>Plazo:
                        </label>
                        <select name="plazo_credito" id="plazo_credito">
                            <option value="" <?php if($myrowopt[plazo_credito]==""){echo "selected";}?>>Sin especificar</option>
                            <option value="24" <?php if($myrowopt[plazo_credito]=="24"){echo "selected";}?>>24 meses</option>
                            <option value="60" <?php if($myrowopt[plazo_credito]=="60"){echo "selected";}?>>60 meses</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <label>Destino del Crédito
                        </label>
                        <input type="text" class="input2" name="destino_credito" id="destino_credito" />
                      
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" /><!--organizacion-->
                <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
                <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
                <input type="hidden" name="o" id="o"  value="<?php echo $_GET[o]; ?>" /><!-- operación: Update -->
                        </form>
                        </div>
                    	<?php
                    }
				break;
			}
			?>
        </div>
        
		
		<?php
		}
		?>
        </div><!--fin de midtxt-->
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
