<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

switch ($_POST[o])
{
	case 'I':
		//Subir la foto del agente, si existe
		if($_FILES['archivofoto']['name']!="")
		{
		  	//echo "Hay archivo";
		  	$extension = explode(".",$_FILES['archivofoto']['name']); 
			$num = count($extension)-1; 
			if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF") 
			{ 
				//echo "Formato correcto";
				if($_FILES['archivofoto']['size'] < 800000) 
				{
					//echo "Tamaño permitido";
					$Foto="F".time()."D".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofoto="../../fotos/".$Foto;
					echo $nombrearchivo;
					if (move_uploaded_file($_FILES['archivofoto']['tmp_name'], $nombrearchivofoto))
					{
					   $enviada=1;
					}
					else
					{
					   echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>";
					} 			        	
				} 
				else 
				{ 
					echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; 
				} 
			} 
			else 
			{ 
				echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; 
			} 
		}
		
		//Subir la firma del agente, si existe
		if($_FILES['archivofirma']['name']!="")
		{
		  	//echo "Hay firma";
		  	$extension = explode(".",$_FILES['archivofirma']['name']); 
			$num = count($extension)-1; 
			if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF") 
			{ 
				//echo "Formato correcto";
				if($_FILES['archivofirma']['size'] < 800000) 
				{
					//echo "Tamaño permitido";
					$Firma="S".time()."D".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofirma="../../firmas/".$Firma;
					echo $nombrearchivo;
					if (move_uploaded_file($_FILES['archivofirma']['tmp_name'], $nombrearchivofirma))
					{
					   $enviada=1;
					}
					else
					{
					   echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>";
					} 			        	
				} 
				else 
				{ 
					echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; 
				} 
			} 
			else 
			{ 
				echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; 
			} 
		}
		$grupos=$_POST[grupos];
		$grupo="";
		for ($i=0;$i<count($grupos);$i++)    
		{     
			$grupo.= $grupos[$i];if($i<count($grupos)-1){$grupo.= ",";}  
		}
		$plazas=$_POST[plazas];
		$plaza="";
		for ($j=0;$j<count($plazas);$j++)    
		{     
			$plaza.= $plazas[$j];if($j<count($plazas)-1){$plaza.= ",";}  
		} 
		$sqlusuario="INSERT INTO `usuarios`(`idagente`, `tipo`, `numeroagente`, `claveagente`, `contrasenia`, `nombre`, `apellidopaterno`, `id_grupofacturacion`, `id_grupoproducto`, `apellidomaterno`, `titulo`, `puesto`, `fechanacimiento`, `idgrupo`, `estatus`, `telcasa`, `teloficina`, `extoficina`, `nextel`, `idnextel`, `teldirecto`, `email`, `emailotro`, `firma`, `foto`, `claves`,`id_responsable`,`ingreso`) VALUES ('','$_POST[tipo]','$_POST[claveagente]','$_POST[claveagente]','$_POST[contrasenia]','$_POST[nombre]','$_POST[apellidopaterno]','$_POST[id_grupofacturacion]','$_POST[id_grupoproducto]','$_POST[apellidomaterno]','$_POST[titulo]','$_POST[puesto]','$_POST[date]','$grupo','$_POST[estatus]','$_POST[telcasa]','$_POST[teloficina]','$_POST[extoficina]','$_POST[nextel]','$_POST[idnextel]','$_POST[teldirecto]','$_POST[email]','$_POST[emailotro]','$Firma','$Foto','$_POST[claves]','$_POST[rol]','$_POST[ingreso]')";
		//Ejecutar las consultas
		mysql_query("SET NAMES 'utf8'");
		mysql_query($sqlusuario,$db);
		header("Location: http://crm.premo.mx/modulos/agentes/index.php"); 
		exit;
		break;
	case 'U':
		$sqlusu="SELECT * FROM usuarios WHERE idagente='".$_POST[usuario]."'";
		$resultusu= mysql_query ($sqlusu,$db);
		$myrowusu=mysql_fetch_array($resultusu);
		
		//Subir la foto del agente, si existe
		if($_FILES['archivofoto']['name']!="")
		{
		  	//echo "Hay archivo";
		  	$extension = explode(".",$_FILES['archivofoto']['name']); 
			$num = count($extension)-1; 
			if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF") 
			{ 
				//echo "Formato correcto";
				if($_FILES['archivofoto']['size'] < 800000) 
				{
					//echo "Tamaño permitido";
					$Foto="F".time()."D".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofoto="../../fotos/".$Foto;
					$fotoanterior="../../fotos/".$myrowusu[foto];
					if (move_uploaded_file($_FILES['archivofoto']['tmp_name'], $nombrearchivofoto))
					{
					   $enviada=1;
					   unlink($fotoanterior);
					   $sqlupdatefoto="UPDATE `usuarios` SET `foto`='$Foto' WHERE `idagente` = '".$_POST[usuario]."'";
					   mysql_query($sqlupdatefoto,$db);
					}
					else
					{
					   echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>";
					} 			        	
				} 
				else 
				{ 
					echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; 
				} 
			} 
			else 
			{ 
				echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; 
			} 
		}
		
		//Subir la firma del agente, si existe
		if($_FILES['archivofirma']['name']!="")
		{
		  	//echo "Hay firma";
		  	$extension = explode(".",$_FILES['archivofirma']['name']); 
			$num = count($extension)-1; 
			if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF") 
			{ 
				//echo "Formato correcto";
				if($_FILES['archivofirma']['size'] < 800000) 
				{
					//echo "Tamaño permitido";
					$Firma="S".time()."D".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofirma="../../firmas/".$Firma;
					$firmaanterior="../../firmas/".$myrowusu[firma];
					if (move_uploaded_file($_FILES['archivofirma']['tmp_name'], $nombrearchivofirma))
					{
					   $enviada=1;
					   unlink($firmaanterior);
					   $sqlupdatefirma="UPDATE `usuarios` SET `firma`='$Firma' WHERE `idagente` = '".$_POST[usuario]."'";
					   mysql_query($sqlupdatefirma,$db);
					}
					else
					{
					   echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>";
					} 			        	
				} 
				else 
				{ 
					echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; 
				} 
			} 
			else 
			{ 
				echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; 
			} 
		}
		$grupos=$_POST[grupos];
		$grupo="";
		for ($i=0;$i<count($grupos);$i++)    
		{     
			$grupo.= $grupos[$i];if($i<count($grupos)-1){$grupo.= ",";}  
		}
		$plazas=$_POST[plazas];
		$plaza="";
		for ($j=0;$j<count($plazas);$j++)    
		{     
			$plaza.= $plazas[$j];if($j<count($plazas)-1){$plaza.= ",";}  
		} 
		$sqlupdate="UPDATE `usuarios` SET `tipo`='$_POST[tipo]',`numeroagente`='$_POST[claveagente]',`claveagente`='$_POST[claveagente]',`contrasenia`='$_POST[contrasenia]',`nombre`='$_POST[nombre]',`apellidopaterno`='$_POST[apellidopaterno]',`id_grupofacturacion`='$_POST[id_grupofacturacion]',`id_grupoproducto`='$_POST[id_grupoproducto]',`apellidomaterno`='$_POST[apellidomaterno]',`titulo`='$_POST[titulo]',`puesto`='$_POST[puesto]',`fechanacimiento`='$_POST[date]',`idgrupo`='$grupo',`estatus`='$_POST[estatus]',`telcasa`='$_POST[telcasa]',`teloficina`='$_POST[teloficina]',`extoficina`='$_POST[extoficina]',`nextel`='$_POST[nextel]',`idnextel`='$_POST[idnextel]',`teldirecto`='$_POST[teldirecto]',`email`='$_POST[email]',`emailotro`='$_POST[emailotro]',`claves`='$_POST[claves]',`id_responsable`='$_POST[rol]',`ingreso`='$_POST[ingreso]' WHERE `idagente` = '".$_POST[usuario]."'";
		//echo $sqlupdate;
		//Ejecutar las consultas
		mysql_query("SET NAMES 'utf8'");
		mysql_query($sqlupdate,$db);
		//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
		header("Location: http://crm.premo.mx/modulos/agentes/index.php"); 
		exit;
		break;
}
?>
