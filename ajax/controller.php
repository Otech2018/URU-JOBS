<?php
  define("_VALID_PHP", true);
  require_once("../init.php");

  if (!$user->logged_in)
      exit;
?>
<?php
  /* Proccess Gateway */
  if (isset($_POST['loadgateway'])):
      $row = Core::getRowById(Content::gTable, Filter::$id);

      $form_url = BASEPATH . "gateways/" . $row->dir . "/form.tpl.php";
      (file_exists($form_url)) ? include ($form_url) : Filter::msgSingleError('You have selected an invalid payment method. Please try again.');
  endif;


  /* Get Invoice */
  if (isset($_GET['doInvoice'])):
      $row = Registry::get("Core")->getRowById(Content::inTable, Filter::$id);
      if ($row):
          $usr = Registry::get("Core")->getRowById(Users::uTable, Registry::get("Users")->uid);

          $title = cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $row->created));
          ob_start();
          require_once (THEMEDIR . '/print_pdf.tpl.php');
          $pdf_html = ob_get_contents();
          ob_end_clean();

          require_once (BASEPATH . 'lib/mPdf/mpdf.php');
          $mpdf = new mPDF('utf-8', $core->psize);
          $mpdf->SetTitle($title);
          $mpdf->SetAutoFont();
          $mpdf->WriteHTML($pdf_html);
          $mpdf->Output($title . ".pdf", "D");
          exit;
      else:
          exit;
      endif;
  endif;
?>
