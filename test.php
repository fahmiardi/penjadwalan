<?php
require_once('html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P', 'A4', 'en');
$html2pdf->writeHTML("<b>Check!</b>");
$html2pdf->Output('random.pdf');
?>