<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
$claveagente=$_SESSION[Claveagente];
$nivel=2;

if($_GET[busqueda])
{
	switch($_GET[tipo])
	{
		case ''://Todos
			$_pagi_sql="SELECT * FROM usuarios WHERE (apellidopaterno LIKE '%$_GET[busqueda]%' OR apellidomaterno LIKE '%$_GET[busqueda]%' OR nombre LIKE '%$_GET[busqueda]%') ORDER BY apellidopaterno ASC";
			break;
		case '1'://Activo
			$_pagi_sql="SELECT * FROM usuarios WHERE (apellidopaterno LIKE '%$_GET[busqueda]%' OR apellidomaterno LIKE '%$_GET[busqueda]%' OR nombre LIKE '%$_GET[busqueda]%') AND estatus='$_GET[tipo]' ORDER BY apellidopaterno ASC";
			
			break;
		case '0'://Inactivo
			$_pagi_sql="SELECT * FROM usuarios WHERE (apellidopaterno LIKE '%$_GET[busqueda]%' OR apellidomaterno LIKE '%$_GET[busqueda]%' OR nombre LIKE '%$_GET[busqueda]%') AND estatus='$_GET[tipo]' ORDER BY apellidopaterno ASC";
			break;
	}
}
else
{
	if($_GET[tipo]==""){$_pagi_sql = "SELECT * FROM usuarios ORDER BY apellidopaterno ASC";}
	else{$_pagi_sql = "SELECT * FROM usuarios WHERE estatus='$_GET[tipo]' ORDER BY apellidopaterno ASC";}
}
$_pagi_cuantos = 10;
$_pagi_nav_num_enlaces= 30;
include("paginator.inc.php");

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
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

</head>
<body>

<?php include('../../header.php'); ?>

  <div id="titulo">Usuarios del Sistema</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
            	<li class=""><a href="../configuracion/index.php">General</a></li>
                <li class=""><a href="../agentes/index.php">Agentes</a></li>
                <li class=""><a href="../metas/index.php">Metas</a></li>
                <li class="selected"><a href="#">Expedientes</a></li>
            </ul>
            <?php
			if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
			{
			?>
			<ul class="pageActions">
				<li class="item"><img src="../../images/add.png" class="linkImage" /><a href="forminsert.php?o=I">Nuevo Usuario</a></li>  
			</ul>
			<?php
			}
			?>
            
             
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
            <form name="frmbusqueda" action="" onsubmit="buscarusr(); return false">
              <div>Termino a buscar:
                <input type="text" id="dato" name="dato" onkeyup=";" onblur=""  value="<?php echo $_SESSION[Search]; ?>"/>
                <select name="tipo_registro" size="1" onchange="">
                    <option value=""  <?php if($_SESSION[Type]==""){echo 'selected="selected"';} ?> >Todos</option>
                    <option value="0" <?php if($_SESSION[Type]=="0"){echo 'selected="selected"';} ?> >Inactivos</option>
                    <option value="1" <?php if($_SESSION[Type]=="1"){echo 'selected="selected"';} ?> >Activos</option>
				</select> 
                <input type="submit" value="Buscar">
                
              </div>
              
            </form>
        </fieldset>

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> usuarios</legend>
            <table id="" class="recordList">
            <tbody>
			<?php
            while($myrowusu=mysql_fetch_array($_pagi_result))
            {
				$agente=$myrowusu[apellidopaterno]." ".$myrowusu[apellidomaterno]." ".$myrowusu[nombre];
				$sqlcolor="SELECT * FROM  `gruposproducto` WHERE id_grupoproducto='".$myrowusu[id_grupoproducto]."'";
				$resultcolor= mysql_query ($sqlcolor,$db);
				$myrowcolor=mysql_fetch_array($resultcolor);
				$colorgrupo=color_grupo($myrowusu[id_grupoproducto]);
				?>
				<tr class="even-row">
                <td class="list-column-picture">
               	<?php
				if($myrowusu[foto])//Hay foto para el usuario
				{
					?>
					<img class="picture-thumbnail" src="../../fotos/<?php echo $myrowusu[foto]; ?>" width="32" height="32" alt=""/>
					<?php
				}
				elseif($myrowusu[email])
				{
					$default = $dominio."images/person_avatar_32.png";
					$grav_url = get_gravatar($myrowusu[email],32,$default,'',false,'');
					?>
					<img class="picture-thumbnail" src="<?php echo $grav_url; ?>" width="32" height="32" alt="" />
					<?php
				}
				else
				{
					?>
					<img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" />
					<?php
				}
                ?>
                </td>
                <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?agente=<?php echo $myrowusu[claveagente]; ?>"><?php echo $agente; ?></a> <span class="highlight" <?php if($myrowusu[id_grupoproducto]!=0){echo "style='background-color:#".$colorgrupo."'";}?>;"><?php echo $myrowusu[claves]; ?></span>
                    <br />
                    <span class="subtext">Etiquetado como:
                        <span class="nobreaktext"><?php echo $myrowusu[tipo]; ?></span>
                    </span>
                    <span class="subtext">Grupo:
                        <span class="nobreaktext"><?php echo $myrowusu[tipo]; ?></span>
                    </span>
                </td>
                
                <td class=" list-column-left">
                    <a target="" href="mailto:<?php echo $myrowusu[email]; ?>"><?php echo $myrowusu[email]; ?></a><br /><?php if($myrowusu[teldirecto]){echo format_Telefono($myrowusu[teldirecto])." (".$tipotelorg.")"; }?><br />
                </td>
                </tr>
				<?php
            }
			?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>

        </fieldset>
        </div>
		<?php include('../../directorio.php'); ?>        
        
        </div><!--FIN DE MIDTXT-->
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
