<?PHP

/*header("Content-type: application/vnd.ms-excel" ) ; 
header("Content-Disposition: attachment; filename=archivo.xlsx" ) ; 

include ("config/config.php");
$tabla="contactanos";  
$qry=mysql_query("select * from $tabla",$db ) ; 
$campos = mysql_num_fields($qry) ; 
$i=0; 
echo "<table><tr>"; 
while($i<$campos){ 
echo "<td>". mysql_field_name ($qry, $i) ; 
echo "</td>"; 
$i++; 
} 
echo "</tr>"; 
while($row=mysql_fetch_array($qry)){ 
echo "<tr>"; 
for($j=0; $j<$campos; $j++) { 
echo "<td>".$row[$j]."</td>"; 
} 
echo "</tr>"; 
} 
echo "</table>"; 
*/


include ("config/config.php");
$tabla="contactanos";  
$qry=mysql_query("select * from $tabla",$db ) ; 


/*******EDIT LINES 3-8*******/
$DB_TBLName = "contactanos"; //MySQL Table Name   
$filename = "contacto";         //File Name
/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
//create MySQL connection   
$sql = "Select * from $DB_TBLName"; 
//execute query 
$result = mysql_query($sql,$db) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls; charset=utf-8");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
echo mysql_field_name($result,$i) . "\t";
}
print("\n");    
//end of printing column names  
//start while loop to get data
while($row = mysql_fetch_row($result))
{
	$schema_insert = "";
	for($j=0; $j<mysql_num_fields($result);$j++)
	{
		if(!isset($row[$j]))
			$schema_insert .= "NULL".$sep;
		elseif ($row[$j] != "")
			$schema_insert .= "$row[$j]".$sep;
		else
			$schema_insert .= "".$sep;
	}
	$schema_insert = str_replace($sep."$", "", $schema_insert);
	$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	$schema_insert .= "\t";
	print(trim($schema_insert));
	print "\n";
}   


//$r = mysql_query( $sql ) or trigger_error( mysql_error($conn), E_USER_ERROR );
/*$return = '';
if( mysql_num_rows($qry)>0){
    $return .= '<table border=1>';
    $cols = 0;
    while($rs = mysql_fetch_row($qry)){
        $return .= '<tr>';
        if($cols==0){
            $cols = sizeof($rs);
            $cols_names = array();
            for($i=0; $i<$cols; $i++){
                $col_name = mysql_field_name($r,$i);
                $return .= '<th>'.htmlspecialchars($col_name).'</th>';
                $cols_names[$i] = $col_name;
            }
            $return .= '</tr><tr>';
        }
        for($i=0; $i<$cols; $i++){
            #En esta iteración podes manejar de manera personalizada datos, por ejemplo:
            if($cols_names[$i] == 'fechaAlta'){ #Fromateo el registro en formato Timestamp
                $return .= '<td>'.htmlspecialchars(date('d/m/Y H:i:s',$rs[$i])).'</td>';
            }else if($cols_names[$i] == 'activo'){ #Estado lógico del registro, en vez de 1 o 0 le muestro Si o No.
                $return .= '<td>'.htmlspecialchars( $rs[$i]==1? 'SI':'NO' ).'</td>';
            }else{
                $return .= '<td>'.htmlspecialchars($rs[$i]).'</td>';
            }
        }
        $return .= '</tr>';
    }
    $return .= '</table>';
    mysql_free_result($r);
}
#Cambiando el content-type más las <table> se pueden exportar formatos como csv
header("Content-type: application/vnd-ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=contacto".date('d-m-Y').".xls");
echo $return;*/


?> 
