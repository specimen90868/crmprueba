<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    //Definir Location
$i_header="Location: http://www.anabiosiscrm.com.mx/premo/";
$i_header.="modulos/organizaciones/oportunidades.php";
		
	
	// get the HTML
    ob_start();
	include('pdf/exemple07a.php');
    $content = ob_get_clean();

    // convert to PDF
    require_once('../../html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'Letter', 'es');
        $html2pdf->pdf->SetDisplayMode('fullpage');
//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		//$html2pdf->Output('fac_'.$nrofac.'.pdf', 'I');
		$html2pdf->Output('terminos.pdf', 'F');
		//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
		header($i_header."?organizacion=".urlencode($claveorganizacion)); 
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
