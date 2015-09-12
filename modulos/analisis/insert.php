<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_POST[organizacion];

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

//Definir Location
$i_header="Location: http://crm.premo.mx/";

switch($_POST[a])
{
	case 'D'://Se accedio desde el Dashboard
		$i_header.="index.php";
		break;
	case 'oP'://Se accedio desde Oportunidades de Organización
		$i_header.="modulos/organizaciones/oportunidades.php";
		break;
	case 'P'://Se accedio desde la lista de Oportunidades
		$i_header.="modulos/oportunidades/oportunidades.php";
		break;		
}

$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
$resultorg= mysql_query ($sqlorg,$db);

while($myroworg=mysql_fetch_array($resultorg))
{
	$empresa=$myroworg[organizacion];
	$clave=$myroworg[clave_unica];
	$claves=explode(",",$myroworg[clave_unica]);
}

switch($_POST[o])
{
	case 'I'://Insertar análisis
        $claveanalisis = generateKey();
    
        //SUBIR BURO SOLICITANTE
        if($_FILES['HIS_buro_solicitante']['name']!="")
		{
		  	//echo "Hay archivo";
		  	$extension = explode(".",$_FILES['HIS_buro_solicitante']['name']); 
			$num = count($extension)-1; 
			     if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF"||$extension[$num]=="PDF"||$extension[$num]=="pdf") 
			{ 
				//echo "Formato correcto";
				if($_FILES['HIS_buro_solicitante']['size'] < 800000) 
				{
					//echo "Tamaño permitido 8Mb";
					$BuroSolicitante="B".time()."R".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofoto="../../expediente/".$Foto;
					echo $nombrearchivo;
					if (move_uploaded_file($_FILES['HIS_buro_solicitante']['tmp_name'], $nombrearchivofoto))
					{
					   $enviada=1;
					}
					else{ echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>"; } 			        	
				} 
				else { echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; } 
			} 
			else { echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; } 
		}
        //SUBIR BURO DE REPRESENTANTE
        if($_FILES['HIS_buro_representante']['name']!="")
		{
		  	//echo "Hay archivo";
		  	$extension = explode(".",$_FILES['HIS_buro_representante']['name']); 
			$num = count($extension)-1; 
			     if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF"||$extension[$num]=="PDF"||$extension[$num]=="pdf") 
			{ 
				//echo "Formato correcto";
				if($_FILES['HIS_buro_representante']['size'] < 800000) 
				{
					//echo "Tamaño permitido 8Mb";
					$BuroRepresentante="B".time()."R".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofoto="../../expediente/".$Foto;
					echo $nombrearchivo;
					if (move_uploaded_file($_FILES['HIS_buro_representante']['tmp_name'], $nombrearchivofoto))
					{
					   $enviada=1;
					}
					else{ echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>"; } 			        	
				} 
				else { echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; } 
			} 
			else { echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; } 
		}
        //SUBIR BURO DE ACCIONISTA
        if($_FILES['HIS_buro_accionista']['name']!="")
		{
		  	//echo "Hay archivo";
		  	$extension = explode(".",$_FILES['HIS_buro_accionista']['name']); 
			$num = count($extension)-1; 
			     if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF"||$extension[$num]=="PDF"||$extension[$num]=="pdf") 
			{ 
				//echo "Formato correcto";
				if($_FILES['HIS_buro_accionista']['size'] < 800000) 
				{
					//echo "Tamaño permitido 8Mb";
					$BuroAccionista="B".time()."R".rand(100,999).rand(10,99).".".$extension[$num];
					$nombrearchivofoto="../../expediente/".$Foto;
					echo $nombrearchivo;
					if (move_uploaded_file($_FILES['HIS_buro_accionista']['tmp_name'], $nombrearchivofoto))
					{
					   $enviada=1;
					}
					else{ echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>"; } 			        	
				} 
				else { echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; } 
			} 
			else { echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; } 
		}
    
    
		$sqlanalisis="INSERT INTO `analisis`(`id_analisis`, `clave_analisis`, `id_oportunidad`, `clave_organizacion`, `POD_facultades_empresa`, `POD_poderes_representante`, `POD_facultades_garante`, `HIS_original_solicitante`, `HIS_puntual_solicitante`, `HIS_vigente_solicitante`, `HIS_29_solicitante`, `HIS_89_solicitante`, `HIS_90_solicitante`, `HIS_calificacion_solicitante`, `HIS_maximo_solicitante`, `HIS_mes_solicitante`, `HIS_original_representante`, `HIS_puntual_representante`, `HIS_vigente_representante`, `HIS_29_representante`, `HIS_89_representante`, `HIS_90_representante`, `HIS_calificacion_representante`, `HIS_maximo_representante`, `HIS_mes_representante`, `HIS_original_accionista`, `HIS_puntual_accionista`, `HIS_vigente_accionista`, `HIS_29_accionista`, `HIS_89_accionista`, `HIS_90_accionista`, `HIS_calificacion_accionista`, `HIS_maximo_accionista`, `HIS_mes_accionista`, `HIS_incidencias_solicitante`, `HIS_incidencias_solicitante_detalle`, `HIS_incidencias_representante`, `HIS_incidencias_representante_detalle`, `HIS_incidencias_accionista`, `HIS_incidencias_accionista_detalle`, `FLU_cuenta`, `FLU_banco`, `FLU_mes1`, `FLU_inicial_mes1`, `FLU_depositos_mes1`, `FLU_retiros_mes1`, `FLU_promedio_mes1`, `FLU_mes2`, `FLU_inicial_mes2`, `FLU_depositos_mes2`, `FLU_retiros_mes2`, `FLU_promedio_mes2`, `FLU_mes3`, `FLU_inicial_mes3`, `FLU_depositos_mes3`, `FLU_retiros_mes3`, `FLU_promedio_mes3`, `FLU_mes4`, `FLU_inicial_mes4`, `FLU_depositos_mes4`, `FLU_retiros_mes4`, `FLU_promedio_mes4`, `FLU_mes5`, `FLU_inicial_mes5`, `FLU_depositos_mes5`, `FLU_retiros_mes5`, `FLU_promedio_mes5`, `FLU_mes6`, `FLU_inicial_mes6`, `FLU_depositos_mes6`, `FLU_retiros_mes6`, `FLU_promedio_mes6`, `FLU_promedio_inicial`, `FLU_promedio_depositos`, `FLU_promedio_retiros`, `FLU_promedio_promedio`, `GAR_propietario`, `GAR_relacion`, `GAR_domicilio`, `GAR_ciudad`, `GAR_estado`, `GAR_cp`, `GAR_construccion`, `GAR_terreno`, `GAR_valor`, `LIS_listas_empresa`, `LIS_listas_empresa_detalle`, `LIS_listas_representante`, `LIS_listas_representante_detalle`, `LIS_listas_accionista`, `LIS_listas_accionista_detalle`, `LIS_listas_garante`, `LIS_listas_garante_detalle`, `LIS_google_empresa`, `LIS_google_empresa_detalle`, `LIS_google_representante`, `LIS_google_representante_detalle`, `LIS_google_accionista`, `LIS_google_accionista_detalle`, `LIS_google_garante`, `LIS_google_garante_detalle`, `usuario`, `fecha`,`OTR_comentarios`,`HIS_buro_solicitante`,`HIS_buro_representante`,`HIS_buro_accionista`) VALUES (NULL,'$claveanalisis','$_POST[oportunidad]','$claveorganizacion', '$_POST[POD_facultades_empresa]', '$_POST[POD_poderes_representante]', '$_POST[POD_facultades_garante]', '$_POST[HIS_original_solicitante]', '$_POST[HIS_puntual_solicitante]', '$_POST[HIS_vigente_solicitante]', '$_POST[HIS_29_solicitante]', '$_POST[HIS_89_solicitante]', '$_POST[HIS_90_solicitante]', '$_POST[HIS_calificacion_solicitante]', '$_POST[HIS_maximo_solicitante]', '$_POST[HIS_mes_solicitante]', '$_POST[HIS_original_representante]', '$_POST[HIS_puntual_representante]', '$_POST[HIS_vigente_representante]', '$_POST[HIS_29_representante]', '$_POST[HIS_89_representante]', '$_POST[HIS_90_representante]', '$_POST[HIS_calificacion_representante]', '$_POST[HIS_maximo_representante]', '$_POST[HIS_mes_representante]', '$_POST[HIS_original_accionista]', '$_POST[HIS_puntual_accionista]', '$_POST[HIS_vigente_accionista]', '$_POST[HIS_29_accionista]', '$_POST[HIS_89_accionista]', '$_POST[HIS_90_accionista]', '$_POST[HIS_calificacion_accionista]', '$_POST[HIS_maximo_accionista]', '$_POST[HIS_mes_accionista]', '$_POST[HIS_incidencias_solicitante]', '$_POST[HIS_incidencias_solicitante_detalle]', '$_POST[HIS_incidencias_representante]', '$_POST[HIS_incidencias_representante_detalle]', '$_POST[HIS_incidencias_accionista]', '$_POST[HIS_incidencias_accionista_detalle]', '$_POST[FLU_cuenta]', '$_POST[FLU_banco]', '$_POST[FLU_mes1]', '$_POST[FLU_inicial_mes1]', '$_POST[FLU_depositos_mes1]', '$_POST[FLU_retiros_mes1]', '$_POST[FLU_promedio_mes1]', '$_POST[FLU_mes2]', '$_POST[FLU_inicial_mes2]', '$_POST[FLU_depositos_mes2]', '$_POST[FLU_retiros_mes2]', '$_POST[FLU_promedio_mes2]', '$_POST[FLU_mes3]', '$_POST[FLU_inicial_mes3]', '$_POST[FLU_depositos_mes3]', '$_POST[FLU_retiros_mes3]', '$_POST[FLU_promedio_mes3]', '$_POST[FLU_mes4]', '$_POST[FLU_inicial_mes4]', '$_POST[FLU_depositos_mes4]', '$_POST[FLU_retiros_mes4]', '$_POST[FLU_promedio_mes4]', '$_POST[FLU_mes5]', '$_POST[FLU_inicial_mes5]', '$_POST[FLU_depositos_mes5]', '$_POST[FLU_retiros_mes5]', '$_POST[FLU_promedio_mes5]', '$_POST[FLU_mes6]', '$_POST[FLU_inicial_mes6]', '$_POST[FLU_depositos_mes6]', '$_POST[FLU_retiros_mes6]', '$_POST[FLU_promedio_mes6]', '$_POST[FLU_promedio_inicial]', '$_POST[FLU_promedio_depositos]', '$_POST[FLU_promedio_retiros]', '$_POST[FLU_promedio_promedio]', '$_POST[GAR_propietario]', '$_POST[GAR_relacion]', '$_POST[GAR_domicilio]', '$_POST[GAR_ciudad]', '$_POST[GAR_estado]', '$_POST[GAR_cp]', '$_POST[GAR_construccion]', '$_POST[GAR_terreno]', '$_POST[GAR_valor]', '$_POST[LIS_listas_empresa]', '$_POST[LIS_listas_empresa_detalle]', '$_POST[LIS_listas_representante]', '$_POST[LIS_listas_representante_detalle]', '$_POST[LIS_listas_accionista]', '$_POST[LIS_listas_accionista_detalle]', '$_POST[LIS_listas_garante]', '$_POST[LIS_listas_garante_detalle]', '$_POST[LIS_google_empresa]', '$_POST[LIS_google_empresa_detalle]', '$_POST[LIS_google_representante]', '$_POST[LIS_google_representante_detalle]', '$_POST[LIS_google_accionista]', '$_POST[LIS_google_accionista_detalle]', '$_POST[LIS_google_garante]', '$_POST[LIS_google_garante_detalle]', '$claveagente', NOW(),'$_POST[OTR_comentarios]','$BuroSolicitante','$BuroRepresentante','$BuroAccionista')";
	mysql_query($sqlanalisis,$db);
	//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
	header($i_header."?organizacion=".urlencode($claveorganizacion)); 
	exit;
break;

case 'U':
	$sqlanalisis="UPDATE `analisis` SET 
`POD_facultades_empresa`='$_POST[POD_facultades_empresa]',
`POD_poderes_representante`='$_POST[POD_poderes_representante]',
`POD_facultades_garante`='$_POST[POD_facultades_garante]',
`HIS_original_solicitante`='$_POST[HIS_original_solicitante]',
`HIS_puntual_solicitante`='$_POST[HIS_puntual_solicitante]',
`HIS_vigente_solicitante`='$_POST[HIS_vigente_solicitante]',
`HIS_29_solicitante`='$_POST[HIS_29_solicitante]',
`HIS_89_solicitante`='$_POST[HIS_89_solicitante]',
`HIS_90_solicitante`='$_POST[HIS_90_solicitante]',
`HIS_calificacion_solicitante`='$_POST[HIS_calificacion_solicitante]',
`HIS_maximo_solicitante`='$_POST[HIS_maximo_solicitante]',
`HIS_mes_solicitante`='$_POST[HIS_mes_solicitante]',
`HIS_original_representante`='$_POST[HIS_original_representante]',
`HIS_puntual_representante`='$_POST[HIS_puntual_representante]',
`HIS_vigente_representante`='$_POST[HIS_vigente_representante]',
`HIS_29_representante`='$_POST[HIS_29_representante]',
`HIS_89_representante`='$_POST[HIS_89_representante]',
`HIS_90_representante`='$_POST[HIS_90_representante]',
`HIS_calificacion_representante`='$_POST[HIS_calificacion_representante]',
`HIS_maximo_representante`='$_POST[HIS_maximo_representante]',
`HIS_mes_representante`='$_POST[HIS_mes_representante]',
`HIS_original_accionista`='$_POST[HIS_original_accionista]',
`HIS_puntual_accionista`='$_POST[HIS_puntual_accionista]',
`HIS_vigente_accionista`='$_POST[HIS_vigente_accionista]',
`HIS_29_accionista`='$_POST[HIS_29_accionista]',
`HIS_89_accionista`='$_POST[HIS_89_accionista]',
`HIS_90_accionista`='$_POST[HIS_90_accionista]',
`HIS_calificacion_accionista`='$_POST[HIS_calificacion_accionista]',
`HIS_maximo_accionista`='$_POST[HIS_maximo_accionista]',
`HIS_mes_accionista`='$_POST[HIS_mes_accionista]',
`HIS_incidencias_solicitante`='$_POST[HIS_incidencias_solicitante]',
`HIS_incidencias_solicitante_detalle`='$_POST[HIS_incidencias_solicitante_detalle]',
`HIS_incidencias_representante`='$_POST[HIS_incidencias_representante]',
`HIS_incidencias_representante_detalle`='$_POST[HIS_incidencias_representante_detalle]',
`HIS_incidencias_accionista`='$_POST[HIS_incidencias_accionista]',
`HIS_incidencias_accionista_detalle`='$_POST[HIS_incidencias_accionista_detalle]',
`FLU_cuenta`='$_POST[FLU_cuenta]',
`FLU_banco`='$_POST[FLU_banco]',
`FLU_mes1`='$_POST[FLU_mes1]',
`FLU_inicial_mes1`='$_POST[FLU_inicial_mes1]',
`FLU_depositos_mes1`='$_POST[FLU_depositos_mes1]',
`FLU_retiros_mes1`='$_POST[FLU_retiros_mes1]',
`FLU_promedio_mes1`='$_POST[FLU_promedio_mes1]',
`FLU_mes2`='$_POST[FLU_mes2]',
`FLU_inicial_mes2`='$_POST[FLU_inicial_mes2]',
`FLU_depositos_mes2`='$_POST[FLU_depositos_mes2]',
`FLU_retiros_mes2`='$_POST[FLU_retiros_mes2]',
`FLU_promedio_mes2`='$_POST[FLU_promedio_mes2]',
`FLU_mes3`='$_POST[FLU_mes3]',
`FLU_inicial_mes3`='$_POST[FLU_inicial_mes3]',
`FLU_depositos_mes3`='$_POST[FLU_depositos_mes3]',
`FLU_retiros_mes3`='$_POST[FLU_retiros_mes3]',
`FLU_promedio_mes3`='$_POST[FLU_promedio_mes3]',
`FLU_mes4`='$_POST[FLU_mes4]',
`FLU_inicial_mes4`='$_POST[FLU_inicial_mes4]',
`FLU_depositos_mes4`='$_POST[FLU_depositos_mes4]',
`FLU_retiros_mes4`='$_POST[FLU_retiros_mes4]',
`FLU_promedio_mes4`='$_POST[FLU_promedio_mes4]',
`FLU_mes5`='$_POST[FLU_mes5]',
`FLU_inicial_mes5`='$_POST[FLU_inicial_mes5]',
`FLU_depositos_mes5`='$_POST[FLU_depositos_mes5]',
`FLU_retiros_mes5`='$_POST[FLU_retiros_mes5]',
`FLU_promedio_mes5`='$_POST[FLU_promedio_mes5]',
`FLU_mes6`='$_POST[FLU_mes6]',
`FLU_inicial_mes6`='$_POST[FLU_inicial_mes6]',
`FLU_depositos_mes6`='$_POST[FLU_depositos_mes6]',
`FLU_retiros_mes6`='$_POST[FLU_retiros_mes6]',
`FLU_promedio_mes6`='$_POST[FLU_promedio_mes6]',
`FLU_promedio_inicial`='$_POST[FLU_promedio_inicial]',
`FLU_promedio_depositos`='$_POST[FLU_promedio_depositos]',
`FLU_promedio_retiros`='$_POST[FLU_promedio_retiros]',
`FLU_promedio_promedio`='$_POST[FLU_promedio_promedio]',
`GAR_propietario`='$_POST[GAR_propietario]',
`GAR_relacion`='$_POST[GAR_relacion]',
`GAR_domicilio`='$_POST[GAR_domicilio]',
`GAR_ciudad`='$_POST[GAR_ciudad]',
`GAR_estado`='$_POST[GAR_estado]',
`GAR_cp`='$_POST[GAR_cp]',
`GAR_construccion`='$_POST[GAR_construccion]',
`GAR_terreno`='$_POST[GAR_terreno]',
`GAR_valor`='$_POST[GAR_valor]',
`LIS_listas_empresa`='$_POST[LIS_listas_empresa]',
`LIS_listas_empresa_detalle`='$_POST[LIS_listas_empresa_detalle]',
`LIS_listas_representante`='$_POST[LIS_listas_representante]',
`LIS_listas_representante_detalle`='$_POST[LIS_listas_representante_detalle]',
`LIS_listas_accionista`='$_POST[LIS_listas_accionista]',
`LIS_listas_accionista_detalle`='$_POST[LIS_listas_accionista_detalle]',
`LIS_listas_garante`='$_POST[LIS_listas_garante]',
`LIS_listas_garante_detalle`='$_POST[LIS_listas_garante_detalle]',
`LIS_google_empresa`='$_POST[LIS_google_empresa]',
`LIS_google_empresa_detalle`='$_POST[LIS_google_empresa_detalle]',
`LIS_google_representante`='$_POST[LIS_google_representante]',
`LIS_google_representante_detalle`='$_POST[LIS_google_representante_detalle]',
`LIS_google_accionista`='$_POST[LIS_google_accionista]',
`LIS_google_accionista_detalle`='$_POST[LIS_google_accionista_detalle]',
`LIS_google_garante`='$_POST[LIS_google_garante]',
`LIS_google_garante_detalle`='$_POST[LIS_google_garante_detalle]',`usuario`='$claveagente',`fecha`=NOW(), `OTR_comentarios`='$_POST[OTR_comentarios]' WHERE `id_analisis`='".$_POST[an]."'";
	mysql_query($sqlanalisis,$db);
	//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
	header($i_header."?organizacion=".urlencode($claveorganizacion));
break;
}
?>
