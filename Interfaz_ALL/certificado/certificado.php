<?php 
$numero = count($_POST);

if($numero == 0){
    print("<script>window.history.go(-1)</script>"); 
}

$tags = array_keys($_POST);
$valores = array_values($_POST);

$nombre = $_POST['nombre'];
$fecha = $_POST['fecha'];
$n_notas = $_POST['n_notas'];

for($w = 0; $w < $n_notas; $w++){
	${"mundo_".$w} = $_POST['mundo_'.$w];
    ${"nota_".$w} = $_POST['nota_'.$w]/20;
}




    // get the HTML
    ob_start();
    include('formato_pdf.php');
    $content = ob_get_clean();

    // convert to PDF
    require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('Certificado.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
