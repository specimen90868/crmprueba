<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
//include ("../../includes/FusionCharts.php");

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
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>

<link rel="icon" href="images/icon.ico" />

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
  $(function() {
    if ($.browser.msie && $.browser.version.substr(0,1)<7)
    {
      $('.tooltip').mouseover(function(){
            $(this).children('span').show();
          }).mouseout(function(){
            $(this).children('span').hide();
          })
    }
  });
</script>

<style type="text/css">
.tooltip
{
  position: relative;
  background: #eaeaea;
  cursor: help;
  display: inline-block;
  text-decoration: none;
  color: #222;
  outline: none;
}

.tooltip span
{
  visibility: hidden;
  position: absolute;
  bottom: 30px;
  left: 50%;
  z-index: 999;
  width: 230px;
  margin-left: -127px;
  padding: 10px;
  border: 2px solid #ccc;
  opacity: .9;
  background-color: #ddd;
  background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  -moz-border-radius: 4px;
  border-radius: 4px;
  -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
  box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
  text-shadow: 0 1px 0 rgba(255,255,255,.4);
}

.tooltip:hover
{
  border: 0; /* IE6 fix */
}

.tooltip:hover span
{
  visibility: visible;
}

.tooltip span:before,
.tooltip span:after
{
  content: "";
  position: absolute;
  z-index: 1000;
  bottom: -7px;
  left: 50%;
  margin-left: -8px;
  border-top: 8px solid #ddd;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 0;
}

.tooltip span:before
{
  border-top-color: #ccc;
  bottom: -8px;
}
</style>


</head>
<body>
      <?php include('../../header.php'); ?>
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="#">Resumen</a></li>
                <li class=""><a href="expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Archivos</a></li>
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
				<img class="picture-normal" src="../../images/<?php if($myroworg[tipo_organizacion]=='Garante'){ echo "gar_avatar_70.png"; ?> <?php } else{ echo "org_avatar_70.png"; } ?>" width="70" height="70" alt="picture" />
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
                        }//Fin de while: teléfonos
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowemailorg=mysql_fetch_array($resultemailorg))
                        {
                            ?>
							<li class="email"><a href="mailto:<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a><span class="type"><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }//Fin de while: correos
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowweborg=mysql_fetch_array($resultweborg))
                        {
                            ?>
							<li class="address"><a href="http://<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></li>
							<?php
                        }//Fin de while: websites
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowdomorg=mysql_fetch_array($resultdomorg))
                        {
                            ?>
							<li class="address"><span class="type"><?php echo $myrowdomorg[tipo_domicilio];?></span><br /><?php echo $myrowdomorg[domicilio]; ?><br /><?php echo $myrowdomorg[ciudad]; ?> <?php echo $myrowdomorg[estado]; ?> <?php echo $myrowdomorg[cp]; ?> <?php echo $myrowdomorg[pais]; ?></li>
							<?php
                        }//Fin de while: domicilios
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
				}//Fin de while: relaciones
				$title="Directo: ".format_Telefono($myrowconorg[telefono_oficina])." \nCelular: ".format_Telefono($myrowconorg[telefono_celular])." \nEmail: ".$myrowconorg[email];
				$title= htmlentities($title);  
				
				?>
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td>
                    <td>

<a href="" title="<?php echo $title; ?>" class=""><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a><span class="" id="<?php echo $myrowconorg[id_contacto]; ?>"> <img src="../../images/vcard.png" class="linkImage" id="ficha"/></span><?php echo $rel; ?>
                      <br />
                      <span class="subtext"><?php echo $myrowconorg[puesto]; ?></span><br />
                      <span class="subtext"><?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo $myrowconorg[dia_cumpleanios]." de ".$meses_espanol[$myrowconorg[mes_cumpleanios]];} else{echo "No registrado"; }?></span> <img src="<?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo '../../images/cake.png';} else {echo '../../images/cakebn.png';} ?> " class="linkImage" />
                    </td>
                </tr>
               
				<?php
            }//Fin de while: contactos
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
            }//Fin de while: agente
            ?>
            </tbody>
            </table>
		<?php
		}//Fin de if: asignado
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
            }//Fin de while
            ?>
            </tbody>
            </table>
		<?php
		}//Fin de else
		?>
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
                
        <?php
		if($myroworg[clave_web]!="0")//El contacto proviene de algunos de los formularios de la web
		{
			?>
			<fieldset class="fieldsethistorial">
			<legend>Datos del registro</legend>
			<?php
			//Mostrar los datos de origen del registro
			$sqlcontactanos="SELECT * FROM `contactanos` WHERE `serial` = '".$myroworg[clave_web]."'";
			$rscontactanos= mysql_query($sqlcontactanos,$dbanabiosis);
			
			$sqlprecalifica="SELECT * FROM `formularioprecalifica` WHERE `id_precalifica` = '".$myroworg[clave_web]."'";
			$rsprecalifica= mysql_query ($sqlprecalifica,$dbanabiosis);//**********Consulta en el servidor omnis: medida temporal**********
			if(mysql_num_rows($rscontactanos))
			{
				//El contacto procede del formulario Contactános
				$myrowdatos=mysql_fetch_array($rscontactanos);
				?>
                <br />
				<b>Nombre completo: </b><?php echo $myrowdatos[nombre]; ?><br />
                <b>Teléfono: </b><?php echo $myrowdatos[telefono]; ?><br />
				<b>Correo: </b><a href="mailto:<?php echo $myrowdatos[email]; ?>"><?php echo $myrowdatos[email]; ?></a><br />
                <b>Empresa: </b><?php echo $myrowdatos[empresa]; ?><br />
                <b>Asunto: </b><?php echo $myrowdatos[asunto]; ?>
				<?php
			}
			else
			{
				//El contacto procede del forumulario Precalifica
				$myrowdatos=mysql_fetch_array($rsprecalifica);
				$antiguedad = array();
				$antiguedad["A"] = "0 a 2 años";
				$antiguedad["B"] = "2 a 4 años";
				$antiguedad["C"] = "4 a 8 años";
				$antiguedad["D"] = "Más de 8 años";
				?>
				<fieldset class="fieldsethistorial">
				<legend>Datos Personales</legend>
				<b>Nombre completo: </b><?php echo $myrowdatos[nombre]; ?><br />
				<b>Puesto: </b><?php echo $myrowdatos[puesto]; ?><br />
				<img src="../../images/mail_16.png" class="linkImage"/><a href="mailto:<?php echo $myrowdatos[correo_electronico]; ?>"><?php echo $myrowdatos[correo_electronico]; ?></a> <img src="../../images/tel_16.png" class="linkImage"/><?php echo $myrowdatos[telefono_oficina]; ?> <img src="../../images/cel_16.png" class="linkImage"/><?php echo $myrowdatos[celuar]; ?>
				</fieldset>
				
				<fieldset class="fieldsethistorial">
				<legend>Datos de la empresa</legend>
				<b>Razón Social: </b><?php if($myrowdatos[tipo_persona]=="01"){echo "Persona Física";}else{echo $myrowdatos[razon_social];} ?><br />
				<b>Antigüedad de la empresa: </b><?php echo $antiguedad[$myrowdatos[antiguedad]]; ?><br />
				<img src="../../images/sales_16.png" class="linkImage"/><b>Ventas anuales: </b><?php echo number_format($myrowdatos[ventas_anuales],0); ?>
				</fieldset>
				
				<fieldset class="fieldsethistorial">
				<legend>Datos del préstamo</legend>
				<img src="../../images/tel_16.png" class="linkImage"/><b>Monto del crédito: </b><?php echo number_format($myrowdatos[monto_credito],0); ?><br />
				<b>Destino del crédito: </b><?php echo $myrowdatos[destino_credito]; ?><br />
				</fieldset>
				<?php
				
				
				
			}
			?>
			</fieldset>
			<?php
		}//Fin de if clave_web
		?>
        
        
		
        </div><!--Fin de divderecho-->
        
		<?php
		}//Fin de while: organizaciones
		?>
        </div><!--Fin de midtxt-->
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
