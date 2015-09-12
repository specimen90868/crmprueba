<?php

// load library
require 'php-excel.class.php';
include ("config/config.php");

$DB_TBLName = "precalifica"; //MySQL Table Name   
$sql = "SELECT * FROM $DB_TBLName"; 
$result = mysql_query($sql,$db) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
$campos = mysql_num_fields($result);

//start of printing column names as names of MySQL fields
for ($i = 0; $i < $campos; $i++)
{
	$z[0][$i]=mysql_field_name($result,$i); 
}

/*while($row=mysql_fetch_array($result)){ 
echo "<tr>"; 
for($j=0; $j<$campos; $j++) { 
echo "<td>".$row[$j]."</td>"; 
} 
echo "</tr>"; 
} */
$k=1;
while($row = mysql_fetch_array($result))
{
	for($j=0; $j<$campos; $j++)
	{
		$z[$k][$j]=$row[$j];
	}
	$k++;
}   


// create a simple 2-dimensional array
/*$z = array(
        1 => array ('Name', 'Surname'),
        array('María', '2014-03-05'),
        array('Test', 'Peter')
        );*/

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, $DB_TBLName);
$xls->addArray($z);
$xls->generateXML($DB_TBLName);

?>