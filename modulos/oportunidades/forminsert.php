<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
require_once("../../xajax/xajax.inc.php"); //incluimos la librelia xajax
$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];
$numeroagente=$claveagente;
$nivel=2;

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
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

<link rel="icon" href="images/icon.ico" />
<script type="text/javascript" src="js/cmxform.js"></script>

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
        minDate: '+0d',
		showOn: "button",
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy"
	});
});
</script>

<script>
function toggle(id) {
	if (document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
}
</script>

<script type="text/javascript">

function mostrarReferencia()
{
	if(document.frm.id_etapa.value==2)
	{
		document.getElementById('desdeotro').style.display='block';
	}
	else
	{
		document.getElementById('desdeotro').style.display='none';
	}
	if(document.frm.id_etapa.value==11)
	{
		document.getElementById('motivo').style.display='block';
	}
	else
	{
		document.getElementById('motivo').style.display='none';
	}
	if(document.frm.tipo_garante.value=='Física')
	{
		document.getElementById('garante_fisico').style.display='block';
	}
	else
	{
		document.getElementById('garante_fisico').style.display='none';
	}
	if(document.frm.tipo_garante.value=='Moral')
	{
		document.getElementById('garante_moral').style.display='block';
	}
	else
	{
		document.getElementById('garante_moral').style.display='none';
	}
}

function mostrarGarante()
{
	if(document.frm.tipo_garante.value=='Física')
	{
		document.getElementById('garante_fisico').style.display='block';
	}
	else
	{
		document.getElementById('garante_fisico').style.display='none';
	}
	if(document.frm.tipo_garante.value=='Moral')
	{
		document.getElementById('garante_moral').style.display='block';
	}
	else
	{
		document.getElementById('garante_moral').style.display='none';
	}
}

function mostrarFisico()
{
	if(document.frm.sel_garante_fisico.value=='Nuevo')
	{
		document.getElementById('captura_fisico').style.display='block';
	}
	else
	{
		document.getElementById('captura_fisico').style.display='none';
	}
}

function mostrarMoral()
{
	if(document.frm.sel_garante_moral.value=='Nuevo')
	{
		document.getElementById('captura_moral').style.display='block';
	}
	else
	{
		document.getElementById('captura_moral').style.display='none';
	}
}

</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script src="codigojquery.js" > </script>
	
    <script>
    $(function(){
        alturamaxima = $('.descripcion').height() + 'px';
		
		/*! altura cambio */
        alturaMinima = '40px';
        
		$('.descripcion').height(alturaMinima);	
        $('.contenedor .titulo').toggle( function (){
            $(this).prev().animate({height: alturamaxima},1000);
        }, function (){
            $(this).prev().animate({height: alturaMinima},1000);
		
        });
    });
    </script>
    
<script type="text/javascript">
function validarForm(frm) {
  if(frm.motivo_rechazo.value.length==0) { //comprueba que no esté vacío
    frm.motivo_rechazo.focus();
    alert('No has indicado a qué organización pertenece esta actividad');
    return false;
  }
  return true;
}
</script>

<script src="ajax.js"></script>

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
text-align:right;
width:140px;
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

#stylized checkbox{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:50px;
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

#stylized .select2{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:120px;
	margin:2px 0 20px 10px;
}

#stylized .input2 {
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:456px;
	margin:2px 10px 20px 10px;
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

<style>
        body{font-size:12px; font-family:Arial}
		/*! anchura */
        .contenedor{width:100%;}
        .titulo{cursor:pointer }
        .contenedor, .titulo, .descripcion{padding:3px}
        .descripcion{overflow:hidden}
    </style>

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
                <li class=""><a href="../organizaciones/detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="../organizaciones/expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Archivos</a></li>
                <li class="selected"><a href="#">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
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
                <!--<div class="cuadradito" id="<?php echo $myrowconorg[id_contacto]; ?>"></div>-->
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td><td>

<a href="" title="<?php echo $title; ?>"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a><span class="" id="<?php echo $myrowconorg[id_contacto]; ?>"> <img src="../../images/vcard.png" class="linkImage" id="ficha"/></span><?php echo $rel; ?>
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
				$rol=$myrowagente[tipo];

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
				?>    
					<div id="stylized" class="myform">
        			<form name="frm" method="POST" action="update.php">
                        <h1>Iniciar nuevo proceso de promoción</h1>
                        <p>Ingrese los datos del proceso</p>
                        
                        <?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
						{
							?>
                            <label>Promotor
                            </label>
                            <select id="promotor" name="promotor" class="input2">
                            <option value="0">Sin Asignar</option>
                            <?php
							if($myroworg[asignado]==1&&$rol=='Promotor'){$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' AND claveagente = '".$myroworg[clave_agente]."' ORDER BY apellidopaterno ASC";}else{$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC";}
                            $resultagt= mysql_query ($sqlagt,$db);
                            while($myrowagt=mysql_fetch_array($resultagt))
                            {
                                ?>
                                <option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
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
                        <select name="id_etapa" class="input2" id="id_etapa">
                            <?php
                            if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY numero_etapa ASC";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable OR id_responsable=0 ORDER BY numero_etapa ASC";}
                            $resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                                $sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
                                $resultcolor=mysql_query($sqlcolor,$db);
                                $myrowcolor=mysql_fetch_array($resultcolor);
                            ?>
                            <option value="<?php echo $myrowetapa[id_etapa]; ?>" <?php if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]&&$myrowetapa[id_etapa]!=1){ echo "disabled style='color:#999;'"; } ?>><?php echo $myrowetapa[etapa]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <div class="spacer"></div>
                        
                        <label>Tipo de Crédito
                        </label>
                        <select name="tipo_credito" id="tipo_credito">
                            <option value="" selected="selected">Sin especificar</option>
                            <option value="Revolvente">Revolvente</option>
                        </select>
                        
                        <label>Monto <span class="small">Monto del crédito solicitado</span>
                        </label>
                        <input type="text" name="monto_credito" id="monto_credito" />
                        <div class="spacer"></div>
                        
                        <label>Plazo:
                        </label>
                        <select name="plazo_credito" id="plazo_credito">
                            <option value="">Sin especificar</option>
                            <option value="24">24 meses</option>
                            <option value="60">60 meses</option>
                        </select>
                        <label title="Puede ser modificado en etapas siguiente del proceso">Interés <span class="small">Predeterminado</span>
                        </label>
                        <input type="text" name="interes_credito" id="interes_credito" value="20" disabled="disabled"/>
                        <div class="spacer"></div>
                        
                        <label>Destino del Crédito
                        </label>
                        <input type="text" class="input2" name="destino_credito" id="destino_credito" />
                      	<div class="spacer"></div>
                        
                        <label>Tipo Garante</label>
                        <select name="tipo_garante" id="tipo_garante" onchange="mostrarGarante(this);">
                            <option value="">Sin especificar</option>
                            <option value="Física">Persona Física</option>
                            <option value="Moral">Persona Moral</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <!--div garante físico!-->
                        <div id="garante_fisico" style="display:none; background-color:#eeeeee; padding-top:20px; margin:0 0 10px 0;">
                        <label>Garante físico: </label>
                        <select id="sel_garante_fisico" name="sel_garante_fisico" class="input2" onchange="mostrarFisico(this);">
                          <option value="" selected="selected">[Selecciona]</option>
						<?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqlfisico="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Física' ORDER BY `organizacion` ASC";}else{$sqlfisico="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Física' AND clave_agente='".$claveagente."' ORDER BY `organizacion` ASC";}
						$resultfisico=mysql_query($sqlfisico,$db);
						while($myrowfisico=mysql_fetch_array($resultfisico))
                        {
                        ?>
                          <option value="<?php echo $myrowfisico[clave_organizacion]; ?>"><?php echo $myrowfisico[organizacion]; ?></option>
                         <?php
						}
						?>
                          <option value="Nuevo" style="font-weight:bold; background-color:#FFFFCC; font-style:italic;">Nuevo Contacto</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <div id="captura_fisico" style="display:none;">
                        <label>Nombre(s)</label>
                        <input type="text" name="nombre_garante_fisico" id="nombre_garante_fisico" />
                        <label>Apellidos</label>
                        <input type="text" name="apellido_garante_fisico" id="apellido_garante_fisico" />
                      	<div class="spacer"></div>
                        </div>
                        
                        </div>
                        
                        <!--div garante moral!-->
                        <div id="garante_moral" style="display:none; background-color:#eeeeee; padding-top:20px; margin:0 0 10px 0;">
                        <label>Garante moral: </label>
                        <select id="sel_garante_moral" name="sel_garante_moral" class="input2" onchange="mostrarMoral(this);">
                          <option value="" selected="selected">[Selecciona]</option>
						<?php
						if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqlmoral="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Moral' ORDER BY `organizacion` ASC";}else{$sqlmoral="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Moral' AND clave_agente='".$claveagente."' ORDER BY `organizacion` ASC";}
						$resultmoral=mysql_query($sqlmoral,$db);
                        while($myrowmoral=mysql_fetch_array($resultmoral))
                        {
                        ?>
                          <option value="<?php echo $myrowmoral[clave_organizacion]; ?>"><?php echo $myrowmoral[organizacion]; ?></option>
                         <?php
						}
						?>
                          <option value="Nuevo" style="font-weight:bold; background-color:#FFFFCC; font-style:italic;">Nuevo Contacto</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <div id="captura_moral" style="display:none;">
                        <label>Nueva Organización</label>
                        <input type="text" class="input2" name="garante_moral" id="garante_moral" />
                      	<div class="spacer"></div>
                        <label>Nombre(s)<span class="small">Representante Legal</span></label>
                        <input type="text" name="nombre_moral_legal" id="nombre_moral_legal" />
                        <label>Apellidos<span class="small">Representante Legal</span></label>
                        <input type="text" name="apellido_moral_legal" id="apellido_moral_legal" />
                      	<div class="spacer"></div>
                        <label>Nombre(s)<span class="small">Principal Accionista</span></label>
                        <input type="text" name="nombre_moral_accionista" id="nombre_moral_accionista" />
                        <label>Apellidos<span class="small">Principal Accionista</span></label>
                        <input type="text" name="apellido_moral_accionista" id="apellido_moral_accionista" />
                      	<div class="spacer"></div>
                        </div>
                        
                        </div>
                        
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
                        <input type="hidden" name="a" id="a"  value="oP" /><!-- archivo: Organizaciones Oportunidades -->
                        <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                        </form>
                        </div>
                    <?php
					break;
				case 'U':
					$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
					$resultopt= mysql_query ($sqlopt,$db);
					while($myrowopt=mysql_fetch_array($resultopt))
					{
						//Responsable de la etapa en la que está la oportunidad
						$sqlactual="SELECT * FROM  `etapas` WHERE id_etapa = $myrowopt[id_etapa] ORDER BY numero_etapa ASC";
						$resultactual=mysql_query($sqlactual,$db);
						$myrowactual=mysql_fetch_array($resultactual);
						$numero_etapa=$myrowactual[numero_etapa];
						//Color de celda y vínculos
						if($myrowactual[id_responsable]!=$responsable)
						{
							$celda="#F0F0F0";
							$vinculo=0;
						}
						else
						{
							$celda="#FFFFFF";
							$vinculo=1;
						}
						
						?>
                        
                        <table style="width:100%; margin-bottom:5px;">
                        <tr>
                           <?php
                            //Por qué etapas ha pasado la oportunidad
							$sqlproc="SELECT DISTINCT(numero_etapa) FROM etapasoportunidades LEFT JOIN (etapas)
                 ON (etapasoportunidades.id_etapa=etapas.id_etapa) WHERE etapasoportunidades.clave_oportunidad='".$myrowopt[clave_oportunidad]."' ORDER BY etapasoportunidades.fecha, etapas.numero_etapa ASC";
							$sqletapa="SELECT * FROM  `etapas` ORDER BY numero_etapa ASC";
							$resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                                $sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
                                $resultcolor=mysql_query($sqlcolor,$db);
                                $myrowcolor=mysql_fetch_array($resultcolor);
                                $resultproc=mysql_query($sqlproc,$db);
								while($myrowproc=mysql_fetch_array($resultproc))
								{
									if($myrowetapa[numero_etapa]==$myrowproc[numero_etapa]){if($myrowproc[numero_etapa]==13){$color[$myrowproc[numero_etapa]]="FF7F7F";}else{$color[$myrowproc[numero_etapa]]=$myrowcolor[color];}}else{if($myrowetapa[numero_etapa]>$numero_etapa){$color[$myrowetapa[numero_etapa]]="D6D6D6";}else{$color[$myrowproc[numero_etapa]]="D6D6D6";}}
								}
								?>
                                <td class="list-column-center" style="width:9%; padding-right:5px;">
                                <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]];?>;">
                                    <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]; ?>"><span style="font-size:14px; color:#FFF;"><?php echo $myrowetapa[numero_etapa]; ?></span><br /><span style="color:#FFF; font-size:8px;"><?php echo $myrowcolor[responsable]; ?></span></div>
                                </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                    
					<?php if($vinculo==0){?>
                    
                    <div id="alerta" style="margin-bottom: 10px; padding: 3px 3px 3px 3px; background-color: #FFf2f2; width: 679px; line-height: 20px; border-radius: 3px;">
                    <img src="../../images/exclamation.png" class="linkImage"/><span style='color:#FF0000; font-size:10px;'>No puedes realizar cambios en esta etapa del proceso</span>
                    </div>
                    
                    <?php }?>
                        
                        <div id="stylized" class="myform">
        				<form name="frm" method="POST" action="update.php" onsubmit="">
                        <h1>Actualizar proceso de promoción</h1>
                        <?php if($responsable==3)
						{
							?>
                        	<div style="float:right; margin-top: -10px;"><img src="../../images/comment_16.png" class="linkImage" /><a href="editarregistro.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=I&a=oP&organizacion=<?php echo $claveorganizacion; ?>" title="Agregar una nota a la oportunidad" class="clsVentanaIFrame clsBoton" rel="Agregar Nota">Agregar nota</a></div>
                       		<?php 
						} 
						?>
                        <p></p>
                        
                        
                        <?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
						{
							?>
                            <label>Promotor
                            </label>
                            <select id="promotor" name="promotor" class="input2" <?php if($vinculo==0){echo "disabled='disabled'";}?>>
                            <option value="0">Sin Asignar</option>
                            <?php
                            $sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC";
                            $resultagt= mysql_query ($sqlagt,$db);
                            while($myrowagt=mysql_fetch_array($resultagt))
                            {
                                ?>
                                <option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($myrowagt[claveagente]==$myrowopt[usuario]){echo "selected";} if($vinculo==0){echo "disabled='disabled'";} ?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
                                <?php
                            }
                            ?>
                            </select>
                            <div class="spacer"></div>
                        	<?php
						}
						list ($barra, $campos) = barra($claveorganizacion,$myrowopt[clave_oportunidad],2);
						list ($link,$autorizacion) = vinculos($myrowopt[clave_organizacion],$myrowopt[id_oportunidad],$myrowopt[id_etapa],oP,2,$responsable);
						if(($myrowopt[id_etapa]==5)&&($campos!=100||$autorizacion==0))
						{
							?><label>Etapa</label><input type="text" name="actividad" id="actividad" class="input2" value="<?php echo $myrowactual[numero_etapa]." - ".$myrowactual[etapa]; ?>" disabled="disabled"/>
                            <div class="spacer"></div>
							<div id="alerta" style="margin: 0 0 10px 150px; padding: 5px 5px 5px 5px; background-color: #FFf2f2; width: 452px; line-height: 20px; border-radius: 3px;">
                            <img src="../../images/exclamation.png" class="linkImage"/><span style='color:#FF0000; font-size:10px;'>No se puede avanzar si el proceso no tiene la información completa</span>
                            <?php
							echo $barra;echo "<span class='subtext'>".number_format($campos,0)."%</span>";
							?>
                            </div>
							<?php   
						}
						else
						{
							?>
                            <label>Etapa</label>
                            <select name="id_etapa" class="input2" id="id_etapa" onchange="mostrarReferencia();"<?php if($vinculo==0){echo "disabled='disabled'";} ?>>
                            <?php
                            if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY numero_etapa ASC";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable OR id_responsable=0 ORDER BY numero_etapa ASC";}
							$resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                            	if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]){echo "V";}else{echo "F";}
								if($myrowetapa[id_etapa]!=11){echo "V";}else{echo "F";}
								if($myrowetapa[id_etapa]!=$myrowactual[id_etapa]){echo "V";}else{echo "F";}
								
							?>
                                <option value="<?php echo $myrowetapa[id_etapa]; ?>" <?php if($myrowopt[id_etapa]==$myrowetapa[id_etapa]){echo "selected";}?> <?php if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]&&$myrowetapa[id_etapa]!=11&&$myrowetapa[id_etapa]!=$myrowactual[id_etapa]){ echo "disabled style='color:#999;'"; } ?>><?php echo $myrowetapa[numero_etapa]." - ".$myrowetapa[etapa]; ?></option>
                            <?php
                            }
                            ?>
                            </select>
                            <?php
						}
						?>
                        <div class="spacer"></div>
                        
                        <?php
						//Mostrar motivo si la oportunidad está rechazada
						if($myrowopt[id_etapa]==11)
						{
							$sqlmotivos="SELECT * FROM motivosrechazo WHERE id_motivorechazo = '".$myrowopt[id_rechazo]."'";
							$resultmotivos=mysql_query($sqlmotivos,$db);
							$myrowmotivos=mysql_fetch_array($resultmotivos);
							?>
                            <label>Motivo de Rechazo: </label><input type="text" name="motivo_rechazo" id="motivo_rechazo" class="input2" disabled="disabled" value="<?php echo $myrowmotivos[motivo_rechazo]; ?>"/>
                        	<div class="spacer"></div>
                            <?php
						}
						
						?>
                        
                        <!--div oculto!-->
                        <div id="desdeotro" style="display:none; background-color:#eeeeee; padding-top:20px;">
                        <label>Programar cita para: </label><input type="text" name="actividad" id="actividad" class="input2"/>
                        <div class="spacer"></div>
                        <label>Descripción: </label><textarea name="descripcion" id="descripcion" cols="45" rows="5" class="input2"></textarea>
            			<div class="spacer"></div>
                        <label>Fecha: </label>
                          <input type="text" id="alternate" size="30" value="<?php if($_GET['fecha']){echo htmlentities(strftime("%A, %e %B, %Y", strtotime($_GET['fecha'])));}else{echo htmlentities(strftime("%A, %e %B, %Y", strtotime($date)));} ?>"/>
              <input type="text" name="date" id="date" value="<?php if($_GET['fecha']){echo $_GET['fecha'];}else{echo $date;} ?>" onchange="MostrarConsulta('consulta.php',this.value,'<?php echo $claveorganizacion; ?>'); return false"/>
              <input type="hidden" name="o" id="o" value="<?php if($_GET[o]){echo $_GET[o];}else{echo $_POST[o];}?>"  />
                          <div class="spacer"></div>
                          <label>Hora: </label>
                            <select id="hora_actividad" name="hora_actividad">
                              <option value="">--</option>
                              <option value="00">00</option>
                              <option value="01">01</option>
                              <option value="02">02</option>
                              <option value="03">03</option>
                              <option value="04">04</option>
                              <option value="05">05</option>
                              <option value="06">06</option>
                              <option value="07">07</option>
                              <option value="08">08</option>
                              <option value="09">09</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                            </select>
                            <select id="min_actividad" name="min_actividad">
                              <option>--</option>
                              <option value="00">00</option>
                              <option value="05">05</option>
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="20">20</option>
                              <option value="25">25</option>
                              <option value="30">30</option>
                              <option value="35">35</option>
                              <option value="40">40</option>
                              <option value="45">45</option>
                              <option value="50">50</option>
                              <option value="55">55</option>
                            </select>
                        <div class="spacer"></div> 
            			<div id="resultado" style="margin:0 0 20px 145px; padding: 0 0 20px 0;"></div>
                        </div>
                        
                        <!--div motivo rechazo!-->
                        <div id="motivo" style="display:none; background-color:#FFFFCC; padding-top:20px; margin:0 0 10px 0;">
                        <label>Motivo de Rechazo: </label>
                        <select id="motivo_rechazo" name="motivo_rechazo" class="input2">
                          <option value="" selected="selected">[Selecciona un motivo]</option>
						<?php
                        $sqlmotivos="SELECT * FROM  `motivosrechazo` WHERE `visible`=1 ORDER BY `id_motivorechazo` ASC";
						$resultmotivos=mysql_query($sqlmotivos,$db);
                        while($myrowmotivos=mysql_fetch_array($resultmotivos))
                        {
                        ?>
                          <option value="<?php echo $myrowmotivos[id_motivorechazo]; ?>"><?php echo $myrowmotivos[motivo_rechazo]; ?></option>
                         <?php
						}
						?>
                        </select>
                        <div class="spacer"></div>
                        </div>
                        
                        <label>Tipo de Crédito
                        </label>
                        <select name="tipo_credito" id="tipo_credito" <?php if($vinculo==0){echo "disabled='disabled'";}?>>
                            <option value="" <?php if($myrowopt[tipo_credito]==""){echo "selected";}?>>Sin especificar</option>
                            <option value="Revolvente" <?php if($myrowopt[tipo_credito]=="Revolvente"){echo "selected";}?>>Revolvente</option>
                        </select>
                        
                        <label>Monto <span class="small">Monto del crédito solicitado</span>
                        </label>
                        <input type="text" name="monto_credito" id="monto_credito" value="<?php echo $myrowopt[monto]; ?>" <?php if($vinculo==0){echo "disabled='disabled'";}?>/>
                        <div class="spacer"></div>
                        
                        <label>Plazo:
                        </label>
                        <select name="plazo_credito" id="plazo_credito" <?php if($vinculo==0){echo "disabled='disabled'";}?>>
                            <option value="" <?php if($myrowopt[plazo_credito]==""){echo "selected";}?>>Sin especificar</option>
                            <option value="24" <?php if($myrowopt[plazo_credito]=="24"){echo "selected";}?>>24 meses</option>
                            <option value="60" <?php if($myrowopt[plazo_credito]=="60"){echo "selected";}?>>60 meses</option>
                        </select>
                        <label title="Puede ser modificado en etapas siguiente del proceso">Interés <span class="small">Predeterminado</span>
                        </label>
                        <input type="text" name="interes_credito" id="interes_credito" value="20" disabled="disabled"/>
                        <div class="spacer"></div>
                        
                        <label>Destino del Crédito</label>
                        <input type="text" class="input2" name="destino_credito" id="destino_credito" value="<?php echo $myrowopt[destino_credito]; ?>" <?php if($vinculo==0){echo "disabled='disabled'";}?>/>
                        <div class="spacer"></div>
                        
                        <?php //Información de garante
						$sqlrelacion="SELECT * FROM relaciones LEFT JOIN (organizaciones) ON (relaciones.clave_relacion=organizaciones.clave_organizacion) WHERE relaciones.clave_oportunidad= '".$myrowopt[clave_oportunidad]."' AND relaciones.rol = 'Garante' ORDER BY relaciones.id_relacion ASC" ;
						//echo $sqlrelacion;
						$rsrelacion= mysql_query ($sqlrelacion,$db);
						$rel="";
						$rwrelacion=mysql_fetch_array($rsrelacion);
						if($rwrelacion)//Hay Garante
						{
							?>
                            <label>Garante</label>
                            <div id="garante" style="margin: 0 0 10px 150px; padding: 5px 5px 5px 5px; background-color: #e3e3e3; width: 452px; line-height: 20px; border-radius: 3px;">
                            <?php
							echo "<b>Organización: </b>".$rwrelacion[organizacion]." <span class='highlight'>Persona ".$rwrelacion[tipo_persona];"</span><br />";
							$sqlrel="SELECT * FROM relaciones WHERE clave_organizacion = '".$rwrelacion[clave_relacion]."'";
							$rsrel= mysql_query ($sqlrel,$db);
							while($rwrel=mysql_fetch_array($rsrel))
							{
								$sqlcon="SELECT * FROM contactos WHERE clave_contacto = '".$rwrel[clave_contacto]."'";
								$rscon= mysql_query ($sqlcon,$db);
								$rwcon=mysql_fetch_array($rscon);
								
								$sqlcol="SELECT * FROM roles WHERE id_rol = '".$rwrel[id_rol]."'";
								$rscol= mysql_query ($sqlcol,$db);
								$rwcol=mysql_fetch_array($rscol);
								$rel="<span class='highlight' style='background:".$rwcol[color]."' title='".$rwrel[rol]."'>".$rwrel[rol][0]."</span> ";
								
								echo "<img src='../../images/account_16.png' class='linkImage' />".$rwcon[nombre_completo]." ".$rel."<br />";
							}
							?>
                            </div>
                            <?php
						}
						
						?>
                        <div style="margin: 0 0 0 150px; padding: 0 0 0 0; width: 452px; height:30px; line-height: 20px; border-radius: 3px;">
                        <input type="checkbox" onclick="toggle('Comments')" name="cambiar" id="cambiar" style="width:20px; margin:3px 0 0 0;" <?php if($vinculo==0){echo "disabled='disabled'";}?>>
                        <label style="text-align:left; <?php if($vinculo==0){echo "color:#808080";}?>;">Cambiar Garante</label>
                        </div>
                        <div class="spacer"></div>
                        
                        <div id="Comments" style="display:none;">
                        <label>Tipo Garante</label>
                        <select name="tipo_garante" id="tipo_garante" onchange="mostrarGarante(this);" <?php if($vinculo==0){echo "disabled='disabled'";}?>>
                            <option value="">Sin especificar</option>
                            <option value="Física">Persona Física</option>
                            <option value="Moral">Persona Moral</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <!--div garante físico!-->
                        <div id="garante_fisico" style="display:none; background-color:#eeeeee; padding-top:20px; margin:0 0 10px 0;">
                        <label>Garante físico: </label>
                        <select id="sel_garante_fisico" name="sel_garante_fisico" class="input2" onchange="mostrarFisico(this);">
                          <option value="" selected="selected">[Selecciona]</option>
						<?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqlfisico="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Física' ORDER BY `organizacion` ASC";}else{$sqlfisico="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Física' AND clave_agente='".$claveagente."' ORDER BY `organizacion` ASC";}
						$resultfisico=mysql_query($sqlfisico,$db);
						while($myrowfisico=mysql_fetch_array($resultfisico))
                        {
                        ?>
                          <option value="<?php echo $myrowfisico[clave_organizacion]; ?>"><?php echo $myrowfisico[organizacion]; ?></option>
                         <?php
						}
						?>
                          <option value="Nuevo" style="font-weight:bold; background-color:#FFFFCC; font-style:italic;">Nuevo Contacto</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <div id="captura_fisico" style="display:none;">
                        <label>Nombre(s)</label>
                        <input type="text" name="nombre_garante_fisico" id="nombre_garante_fisico" />
                        <label>Apellidos</label>
                        <input type="text" name="apellido_garante_fisico" id="apellido_garante_fisico" />
                      	<div class="spacer"></div>
                        </div>
                        
                        </div>
                        
                        <!--div garante moral!-->
                        <div id="garante_moral" style="display:none; background-color:#eeeeee; padding-top:20px; margin:0 0 10px 0;">
                        <label>Garante moral: </label>
                        <select id="sel_garante_moral" name="sel_garante_moral" class="input2" onchange="mostrarMoral(this);">
                          <option value="" selected="selected">[Selecciona]</option>
						<?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqlmoral="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Moral' ORDER BY `organizacion` ASC";}else{$sqlmoral="SELECT * FROM  `organizaciones` WHERE `tipo_persona`='Moral' AND clave_agente='".$claveagente."' ORDER BY `organizacion` ASC";}
						$resultmoral=mysql_query($sqlmoral,$db);
                        echo $sqlfisico;
						while($myrowmoral=mysql_fetch_array($resultmoral))
                        {
                        ?>
                          <option value="<?php echo $myrowmoral[clave_organizacion]; ?>"><?php echo $myrowmoral[organizacion]; ?></option>
                         <?php
						}
						?>
                          <option value="Nuevo" style="background-color:#FFFFCC; font-style:italic;">Nuevo Contacto</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <div id="captura_moral" style="display:none;">
                        <label>Nueva Organización</label>
                        <input type="text" class="input2" name="garante_moral" id="garante_moral" />
                      	<div class="spacer"></div>
                        <label>Nombre(s)<span class="small">Representante Legal</span></label>
                        <input type="text" name="nombre_moral_legal" id="nombre_moral_legal" />
                        <label>Apellidos<span class="small">Representante Legal</span></label>
                        <input type="text" name="apellido_moral_legal" id="apellido_moral_legal" />
                      	<div class="spacer"></div>
                        <label>Nombre(s)<span class="small">Principal Accionista</span></label>
                        <input type="text" name="nombre_moral_accionista" id="nombre_moral_accionista" />
                        <label>Apellidos<span class="small">Principal Accionista</span></label>
                        <input type="text" name="apellido_moral_accionista" id="apellido_moral_accionista" />
                      	<div class="spacer"></div>
                        </div>
                        
                        </div>
                      </div>
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" /><!--organizacion-->
                <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
                <input type="hidden" name="etapa" id="etapa"  value="<?php echo $myrowopt[id_etapa]; ?>" /><!--oportunidad-->
                <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
                <input type="hidden" name="o" id="o"  value="U" /><!-- operación: Update -->
                        </form>
                        </div>
                        
                        <?php
                        $sqlnotas="SELECT * FROM notas WHERE id_oportunidad='".$myrowopt[id_oportunidad]."' ORDER BY fecha_captura DESC";
						$rsnotas= mysql_query($sqlnotas,$db);
						?>
                        <!--HISTORIAL DE MENSAJES-->
                        <fieldset class="fieldsethistorial" style="margin-top:10px;">
        				<legend>Historial de mensajes</legend>
                        <table class="recordList" style="margin-top: 12px; width:98%;">
                        <thead>
                        </thead>
                        <tbody>
                        <?php
                        while($rwnotas=mysql_fetch_array($rsnotas))
                        {
                            $nota=nl2br($rwnotas[nota]);
                            $sqlusuario="SELECT * FROM `usuarios` WHERE `claveagente` = '".$rwnotas[usuario_captura]."'";
                            $rsusuario= mysql_query($sqlusuario,$db);
                        	$rwusuario=mysql_fetch_array($rsusuario);
                            ?>
                            
                            <tr class="odd-row">
                            <td class="list-column-image-top"><img src="../../images/chat.png" /></td>
                            <td class="list-column-left" style="padding: 10px 5px 10px 5px;">
                            <span style="font-weight:bold;"><?php echo $rwusuario[nombre]." ".$rwusuario[apellidopaterno]; ?></span> escribió el <?php echo $rwnotas[fecha_captura]; ?><br /><br />                   
                            <div class="contenedor">
                            <div class="descripcion"><span class="subtext"><?php echo $nota; ?></span><br /><br /></div>			
                            <div class="titulo" style="color:#4693D7;text-decoration:none; font-size:11px;">ver nota entera</div>  
                            </div>
							</td>
                            </tr>
                            <?
                        }
                        
                        
                        ?>
                        
                        </tbody>
                        </table>
                        </fieldset>
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
		<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../../js/ventanas-modales.js"></script>
</body>
