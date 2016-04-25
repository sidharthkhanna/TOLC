<?php
$content = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
<style>
  body { font-family:dejavusans;}
</style>
</head>
<body>';
require_once("dompdf/dompdf_config.inc.php");
$pdfname = "registrationmagic_".$_POST['id'].".pdf";
$formid = $_POST['formid'];
$id = $_POST['id'];
$form_fields = new crf_basic_fields;
$content .= $form_fields->crf_get_entry_details($formid,$id);
$content .='</body></html>';
//$content = ' <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
  
  //$string = mb_convert_encoding('', 'HTML-ENTITIES', 'UTF-8');
  $dompdf = new DOMPDF();
  $dompdf->load_html($content);
  $dompdf->set_paper('a4', 'portrait');
  $dompdf->render();
  $dompdf->stream($pdfname);
  exit(0);
?>