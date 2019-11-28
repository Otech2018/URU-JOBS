<?php
  define("_VALID_PHP", true);
  require_once("../init.php");

  if (!$user->logged_in)
      exit;
?>
<?php
  /* Free Checkout */
    if ( isset($_REQUEST['freeCheckout']) ):
        $jobs->freeCheckout($_REQUEST['pid']);
        $db->delete(Content::crTable, "user_id='" . $_REQUEST['user_id'] . "'");
        $db->delete(Content::exTable, "user_id='" . $_REQUEST['user_id'] . "'");
    endif;

  /* Apply To Job */
  if ( isset($_REQUEST['applytojob']) ):
    $jobs->applyToJob($_REQUEST['jobid'],$_REQUEST['message'],$_REQUEST['expected']);
  endif;

  /* Add Bookmark */
  if ( isset($_REQUEST['bookmark']) ):
    $jobs->addBookmark($_REQUEST['type'],$_REQUEST['id']);
  endif;

  /* Delete Bookmark */
  if ( isset($_POST['unbookmark']) ):
    $jobs->deleteBookmark($_REQUEST['type'],$_REQUEST['id']);
  endif;

  /* Application Status Update */
  if ( isset($_POST['applicationStatusUpdate']) ):
    $jobs->applicationStatusUpdate($_POST['appid'],$_POST['status'],$_POST['rating']);
  endif;

  /* Application Add Note */
  if ( isset($_POST['applicationAddNote']) ):
    $jobs->applicationAddNote($_POST['appid'],$_POST['note']);
  endif;

  /* Application Send Message */
  if ( isset($_POST['processMessage']) ):
    $content->processMessage();
  endif;

  /* Application Delete */
  if ( isset($_POST['applicationDelete']) ):
    $db->delete(Jobs::aTable, "id = " . $_POST['appid']);
  endif;

    /* Get Invoice */
  if (isset($_GET['doresume'])):
      $row = $jobs->getSingleResume(intval($_GET['doresume']));
      if ($row):
          $usr = Registry::get("Core")->getRowById(Users::uTable, Registry::get("Users")->uid);

          $title = "Resume of " . $row->fullname;
          $footer = "Powered by " . $core->site_name . "|" . SITEURL . "|{PAGENO}";
          ob_start();
          require_once (THEMEDIR . '/print_resume.tpl.php');
          $pdf_html = ob_get_contents();
          ob_end_clean();

          require_once (BASEPATH . 'lib/mPdf/mpdf.php');
          $mpdf = new mPDF('utf-8', $core->psize);
          $mpdf->SetTitle($title);
          $mpdf->SetFooter($footer);
          $mpdf->SetAutoFont();
          $mpdf->WriteHTML($pdf_html);
          $mpdf->Output($title . ".pdf", "D");
          exit;
      else:
          exit;
      endif;
  endif;

?>
