<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$responsable=$_SESSION[Rol];
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

<script type="text/javascript">
function avanzar(id,o,a,av,org)
{
	var a = confirm("¿Est\xe1 seguro de avanzar con las condicines actuales del proceso?");
	if(a)
	{
		var dir = "../oportunidades/update.php?id="+id+"&o="+o+"&a="+a+"&av="+av+"&organizacion="+org;
		window.open(dir,'_self');
	}
	else return false;
}
</script>

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
                <li class=""><a href="expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Archivos</a></li>
                <li class="selected"><a href="#">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
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
        <form name="frmbusquedaopt" method="post" action="" enctype="application/x-www-form-urlencoded">
          <div><input  type="hidden" id="dato" name="dato" onkeyup=";" onblur="" />
            <label>Mostrar: </label>
            <select name="estatusopt" size="1" onchange="buscaropt(); return false">
                <option value="" selected="selected">Todas</option>
                <option value="Abiertas">Abiertas</option>
                <option value="Cerradas">Cerradas</option>
                <option value="MesCerradas">Cerradas este mes</option>
                <option value="MesCaptura">Capturadas este mes</option>
                <option value="MesCierre">Para cerrar este mes</option>
                <option value="MesProximoCierre">Para cerrar mes próximo</option>
                <option value="SemanaCierre">Para cerrar esta semana</option>
                <option value="SemanaProximaCierre">Para cerrar la semana próxima</option>
                <option value="7">Etapa: Cierre (Venta ganada)</option>
                <option value="6">Etapa: Cierre (Venta perdida)</option>
                <option value="5">Etapa: Aceptación del Cliente</option>
                <option value="4">Etapa: Seguimiento</option>
                <option value="3">Etapa: Propuesta</option>
                <option value="2">Etapa: Presentación de Productos</option>
                <option value="1">Etapa: Concertar Cita</option>
            </select> 
          </div>
          <input  type="hidden" id="organizacion" name="organizacion" value="<?php echo $claveorganizacion; ?>" onkeyup=";" onblur="" />
        </form>
</fieldset>
   
<div id="resultado">
<fieldset class="fieldsethistorial">
<legend>Oportunidades</legend>

<?php
//Definir consulta de oportunidades
if($_SESSION["Tipo"]=="Promotor")
{
	 $_pagi_sql="SELECT * FROM `oportunidades` WHERE `usuario` = '".$claveagente."' AND `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha_captura` ASC";
	 $sqlabiertos="SELECT * FROM `oportunidades` WHERE `usuario` = '".$claveagente."' AND `clave_organizacion` = '".$claveorganizacion."' AND (`id_etapa`!='10' AND `id_etapa`!='11') ORDER BY `fecha_captura` ASC";
}
else
{
	$_pagi_sql="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha_captura` ASC";
	$sqlabiertos="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$claveorganizacion."' AND (`id_etapa`<>'10' AND `id_etapa`<>'11') ORDER BY `fecha_captura` ASC";
}
$resultopt= mysql_query($_pagi_sql,$db);
$resultabietos= mysql_query($sqlabiertos,$db);
$totopt=mysql_num_rows($resultabietos);
//Color de celda y vínculos
if($totopt!=0){$vinculoadd=0;}
else{$vinculoadd=1;}

if($vinculoadd==0){?><img src="../../images/add.png" class="linkImage"/><a class="rojo" href="#">Agregar Oportunidad </a> <span class="highlight" style="background-color: #FFE680; margin-bottom: 10px; text-align: center; color:#333; padding:3px 3px 3px 3px;">No puedes agregar más oportunidades, sólo se permite un proceso abierto a la vez</span><?php }else{?><img src="../../images/add.png" class="linkImage" /><a class="action" href="../oportunidades/forminsert.php?organizacion=<?php echo $claveorganizacion; ?>&o=I">Agregar Oportunidad</a><?php }?>

<table class="recordList" style="margin-top: 12px;">
<thead>
<tr>
<th class="list-column-left" scope="col">Oportunidad</th>
<th class="list-column-left" scope="col">Etapa</th>
</tr>
</thead>
<tbody>
<?php
//Ciclo que recorre los datos de las oportunidades halladas en la consulta
while($myrowopt=mysql_fetch_array($resultopt))
{
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
		if($myrowetp[id_responsable]==$responsable||$responsable==3)
		{
			$celda="#FFFFFF";
			$vinculo=1;
		}
		else
		{
			$celda="#F0F0F0";
			$vinculo=0;
		}
	}//Fin de while de etapa
	
	if($myrowopt[id_etapa]==11){$color="C1C1C1";}elseif($myrowopt[id_etapa]==10){$color="9FC733";}else{$color=$myrowcolor[color];}
	?>
    <tr class="odd-row" style="background-color:<?php echo $celda; ?>;">
<td class="list-column-left">
                        <?php if($myrowopt[marcado]==1){echo "<span class='label-overdue'>".$myrowopt[motivo]."</span>";} ?>
						<?php if($vinculo==1){?><a href="../oportunidades/forminsert.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=U&a=oP&organizacion=<?php echo $claveorganizacion;?>"><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?> </a> <?php } else { echo $nombre_oportunidad." ".$tipo.$monto.$plazo; } ?>
                        <br /><span class="subtext">Destino del crédito: <?php if($myrowopt[destino_credito]){echo $myrowopt[destino_credito];}else{echo "Sin especificar";} ?></span><?php list ($link,$autorizacion)= vinculos($claveorganizacion,$myrowopt[id_oportunidad],$myrowopt[id_etapa],oP,2,$responsable); echo $link; ?>
                        <?php list ($barra, $campos) = barra($claveorganizacion,$myrowopt[clave_oportunidad],2); echo $barra;echo "<span class='subtext'>".number_format($campos,0)."%</span>"; ?>
                        </td>
<td class=" list-column-left"><span class="highlight" style="background-color:#<?php echo $color;?>;">
                        <?php echo $etapa; ?></span></td>
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
