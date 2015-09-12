<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$nivel=2;
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
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

</head>
<body>
    <?php include('../../header.php');
    $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
    $resultorg= mysql_query ($sqlorg,$db);
    $myroworg=mysql_fetch_array($resultorg);
    ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class="selected"><a href="#">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
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
        <legend>Contactos</legend>
            <table>
			<?php
            while($myrowconorg=mysql_fetch_array($resultconorg))
            {
                //Relaciones de los contactos
				$sqlrelacion="SELECT * FROM `relaciones` WHERE `clave_contacto` LIKE '".$myrowconorg[clave_contacto]."' ORDER BY id_rol ASC" ;
				$rsrelacion= mysql_query ($sqlrelacion,$db);
				$rel="";
				while($rwrelacion=mysql_fetch_array($rsrelacion))
				{
					$sqlcolor="SELECT * FROM roles WHERE id_rol = '".$rwrelacion[id_rol]."'";
					$rscolor= mysql_query ($sqlcolor,$db);
					$rwcolor=mysql_fetch_array($rscolor);
					$rel.="<span class='highlight' style='background:".$rwcolor[color]."' title='".$rwrelacion[rol]."'>".$rwrelacion[rol][0]."</span> ";
				}
				$title="Directo: ".format_Telefono($myrowconorg[telefono_oficina])." \nCelular: ".format_Telefono($myrowconorg[telefono_celular])." \nEmail: ".$myrowconorg[email];
				$title= htmlentities($title);
				
				?>
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td>
                    <td>
                        <a href="" title="<?php echo $title; ?>"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a>
                        <span class="party-info-card party" id="<?php echo $myrowconorg[id_contacto]; ?>"><img src="../../images/vcard.png" class="linkImage" /></span><?php echo $rel; ?>
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
            
            
            
<fieldset class="fieldsethistorial">
    <legend>Filtar lista por</legend>
        <form name="frmbusquedaact" method="post" action="" enctype="application/x-www-form-urlencoded">
          <div>
            <label>Estatus: </label>
            <select name="estatus" size="1" onchange="buscaract(); return false">
                <option value="" selected="selected">Selecciona</option>
                <option value="1">Completas</option>
                <option value="2">Pendientes</option>
            </select> 
            <label>Tipo de actividad: </label>
            <select name="tipo_actividad" size="1" onchange="buscaract(); return false">
                <option value="" selected="selected">Selecciona</option>
                <option value="Llamada">Llamada</option>
                <option value="Email">Email</option>
                <option value="Seguimiento" >Seguimiento</option>
                <option value="Cita">Cita</option>
            </select>
          </div>
          <input  type="hidden" id="organizacion" name="organizacion" value="<?php echo $claveorganizacion; ?>" onkeyup=";" onblur="" />
        </form>
</fieldset>
        
        <div id="resultado">
        <fieldset class="fieldsethistorial">
        <legend>Próximas <?php echo $_pagi_info; ?> Actividades</legend>
        <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="../actividades/forminsert.php?o=I&a=oA&organizacion=<?php echo $claveorganizacion; ?>">agregar actividad</a></div>
        
        <form action="modulos/actividades/update.php" method="post">
        </span></span>
        <table class="recordList">
		<tbody>
		
		<?php
		//Consulta inicial
		if($_SESSION["Tipo"]=="Usuario")
		{
			 $sqlact="SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' AND `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha` ASC";
		}
		else
		{
			$sqlact="SELECT * FROM `actividades` WHERE `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha` ASC";
		}
        $resultact= mysql_query ($sqlact,$db);
		
		while($myrowact=mysql_fetch_array($resultact))
		{
			$fecha=explode("-",$myrowact[fecha]);
			$time=explode(":",$myrowact["hora"]);
			$hora=$time[0].":".$time[1];
			$sqlorgact="SELECT * FROM `organizaciones` WHERE `clave_agente` = '".$claveagente."' AND `clave_organizacion` = '".$myrowact[clave_organizacion]."'";
			$resultorgact= mysql_query ($sqlorgact,$db);
			$myroworgact=mysql_fetch_array($resultorgact);
			$organizacion=$myroworgact[organizacion];

			?>
        	<tr class="odd-row">
            <td class="list-column-image"><?php if ($myrowact[completa]==2){?><img src="../../images/incompleta.png" /><!--check <input type="checkbox" onclick="" /> --><?php } else {?> <img src="../../images/completa.png" /> <!--check <input type="checkbox" onclick="" disabled="disabled" checked="checked"/> --><?php }?></td>
			<td class=" list-column-left">
            <?php if(strtotime($myrowact[fecha]) < strtotime($date)&&$myrowact[completa]==2){echo "<span class='label-overdue'>Atrasado</span>";}?>
            <span id="taskDescription6429310">
            <span class="task-title"><span class="highlight" style="background-color:<?php echo $myrowact[color]; ?>;"><?php echo $myrowact[tipo]; ?> </span> <a href="../actividades/forminsert.php?organizacion=<?php echo $claveorganizacion; ?>&id=<?php echo $myrowact[id_actividad]; ?>&o=U&a=oA&fecha=<?php echo $myrowact[fecha]; ?>"><?php echo $myrowact[subtipo]; ?></a></span></span><span class="subtext"> para <a href="../organizaciones/detalles.php?organizacion=<?php echo $myrowact[clave_organizacion]; ?>"><?php echo $myrowact[organizacion]; ?></a></span><span class="more-detail"><?php echo $myrowact[descripcion]; ?>
</span><img src="../../images/clock_16.png" class="linkImage" height="12px;" /><span class="nowraptext subtext"><?php echo htmlentities(strftime("%a, %b, %e", strtotime($myrowact[fecha]))); ?></span> a las <span class="nowraptext subtext"><?php echo $hora; ?></span></td>
            </tr>
		<?php
		}
		?>
</tbody>
</table>
<!--<p align="center"><input type="submit" name="submit" class="" value="Completar" /></p>-->
<input type="hidden" name="o" id="o" value="C" />
<input type="hidden" name="a" id="a" value="A" />
<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $claveorganizacion; ?>" />
</form>
</fieldset>
</div>

        </div>
        <?php
		}
		?>

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
