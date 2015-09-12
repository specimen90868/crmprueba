<?php
include("../../config/config.php");

$q=$_POST['q'];

//$sqlopt="SELECT DISTINCT `clave_organizacion` AS claveorganizacion FROM oportunidades WHERE (id_etapa!=6 AND id_etapa!=7) AND  `clave_organizacion` ='".$q."' ORDER BY `fecha_cierre_esperado` DESC";

$res=mysql_query("SELECT DISTINCT `clave_organizacion` AS claveorganizacion FROM oportunidades WHERE (id_etapa!=6 AND id_etapa!=7) AND  `clave_organizacion` ='".$q."' ORDER BY `fecha_cierre_esperado` DESC",$db);
$numopt = mysql_num_rows($res);
?>
<li><label>Oportunidad: </label>
<?php
if($numopt)
{
	?>
	<select  id="oportunidad" name="oportunidad" class="">
	<option value="" selected="selected">[Selecciona]</option>
	<?php
	while($myrowopt=mysql_fetch_array($res))
	{
		$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$myrowopt[claveorganizacion]."'";
		$resultorg= mysql_query ($sqlorg,$db);
		while($myroworg=mysql_fetch_array($resultorg))
		{
			$empresa=$myroworg[organizacion];
			$clave=$myroworg[clave_unica];
		}
		?>
		  <optgroup label="<?php echo $empresa; ?>">
		<?php
		$sqloptorg="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$myrowopt[claveorganizacion]."' AND (id_etapa!=6 AND id_etapa!=7) ORDER BY fecha_cierre_esperado ASC";
		
		$resultoptorg= mysql_query($sqloptorg,$db);
		while($myrowoptorg=mysql_fetch_array($resultoptorg))
		{
			?>
			<option value="<?php echo $myrowoptorg[id_oportunidad]; ?>"><?php echo $myrowoptorg[nombre_oportunidad]; ?></option>
			<?php
		}//cierre while de oportunidades
		?>
		</optgroup>
		<?php
	}
	?>
	</select>
	<?php
}
else
{
	echo "No hay oportunidades abiertas";
}
	
	/*while($fila=mysql_fetch_array($res))
	{ 
		?>
		<option value=".$fila[id_oportunidad]."><?php echo $fila[nombre_oportunidad]; ?></option>
		<?php 
	} */
?>
</li>

