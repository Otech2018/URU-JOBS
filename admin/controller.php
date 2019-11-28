<?php
  define("_VALID_PHP", true);

  require_once ("init.php");
  if (!$user->is_Admin())
      redirect_to("login.php");

  $delete = (isset($_POST['delete']))  ? $_POST['delete'] : null;
?>
<?php
  switch ($delete):

	  /* == Delete Page == */
	  case "deletePage":
		  $res   = $db->delete(Content::pTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->PAG_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete News == */
	  case "deleteNews":
		  $res   = $db->delete(Content::nTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->NWS_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Coupon == */
	  case "deleteCoupon":
		  $res   = $db->delete(Content::cpTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->CPN_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete F.A.Q. == */
	  case "deleteFaq":
		  $res   = $db->delete(Content::fqTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->FAQ_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Testimonial == */
	  case "deleteTestimonial":
		  $res   = $db->delete(Content::tesTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, 'Testimonial Deleted');
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Job Type. == */
	  case "deleteJobType":
		  $res   = $db->delete(Jobs::tTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->JOBTYPE_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Job Skill == */
	  case "deleteJobSkill":
		  $res   = $db->delete(Jobs::sTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->JOBSKILL_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

      /* == Delete Job Type. == */
  	  case "deletePackage":
  		  $res   = $db->delete(Jobs::pTable, "id=" . Filter::$id);
  		  $title = sanitize($_POST['title']);
  		  if ($res):
  			  $json['type']    = 'success';
  			  $json['title']   = Lang::$word->SUCCESS;
  			  $json['message'] = str_replace("-ITEM-", $title, 'Package has been deleted');
  		  else:
  			  $json['type']    = 'warning';
  			  $json['title']   = Lang::$word->ALERT;
  			  $json['message'] = Lang::$word->NOPROCCESS;
  		  endif;

  		  print json_encode($json);
  		  break;

	  /* == Delete Slide == */
	  case "deleteSlide":
		  if ($thumb = getValueById("thumb", Content::slTable, Filter::$id)):
			  unlink(UPLOADS . "slider/" . $thumb);
		  endif;

		  $res   = $db->delete(Content::slTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->SLM_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete User == */
	  case "deleteUser":
		  if (Filter::$id == 1):
			  $json['type']    = 'error';
			  $json['title']   = Lang::$word->ERROR;
			  $json['message'] = Lang::$word->USR_DELUSER_ERR1;
		  else:
			  if ($avatar = getValueById("avatar", Users::uTable, Filter::$id)):
				  unlink(UPLOADS . 'avatars/' . $avatar);
			  endif;
			  $db->delete(Users::uTable, "id=" . Filter::$id);

			  $title = sanitize($_POST['title']);
			  if ($db->affected()):
				  $json['type']    = 'success';
				  $json['title']   = Lang::$word->SUCCESS;
				  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->USR_DELUSER_OK);
			  else:
				  $json['type']    = 'warning';
				  $json['title']   = Lang::$word->ALERT;
				  $json['message'] = Lang::$word->NOPROCCESS;
			  endif;
		  endif;
		  print json_encode($json);
		  break;

	  /* == Delete Product == */
	  case "deleteProduct":
		  if ($thumb = getValueById("thumb", Products::pTable, Filter::$id)):
			  unlink(UPLOADS . "prod_images/" . $thumb);
		  endif;

		  $res = $db->delete(Products::pTable, "id=" . Filter::$id);
		  $db->delete(Content::cmTable, "pid=" . Filter::$id);

		  $title = sanitize($_POST['title']);

		  if ($getphotos = $db->fetch_all("SELECT thumb FROM " . Products::phTable . " WHERE pid = " . Filter::$id)):
			  foreach ($getphotos as $prow):
				  @unlink(UPLOADS . '/prod_gallery/' . $prow->thumb);
			  endforeach;
		  endif;

		  $db->delete(Products::phTable, "pid=" . Filter::$id);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->PRD_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Gallery Image == */
	  case "deleteGalleryImage":
		  if ($thumb = getValueById("thumb", Products::phTable, Filter::$id)):
			  unlink(UPLOADS . "prod_gallery/" . $thumb);
		  endif;

		  $res   = $db->delete(Products::phTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->GAL_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Transaction == */
	  case "deleteTransaction":
		  $res   = $db->delete(Products::tTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->TXN_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete File == */
	  case "deleteFile":
		  $action = false;
		  $title  = sanitize($_POST['title']);
		  if ($_POST['extra'] == "temp"):
			  @unlink(Registry::get("Core")->file_dir . $title);
			  $action = true;
		  elseif ($_POST['extra'] == "live"):
			  $thumb = getValueByID("name", Products::fTable, Filter::$id);
			  $db->delete(Products::fTable, "id=" . Filter::$id);
			  @unlink(Registry::get("Core")->file_dir . $thumb);
			  $action = true;
		  endif;

		  if ($action):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->FLM_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

		/* == Delete Backup == */
		case "deleteBackup":
			$title = sanitize($_POST['title']);
			$action = false;

			if(file_exists(BASEPATH . 'admin/backups/'.sanitize($_POST['file']))) :
			  $action = unlink(BASEPATH . 'admin/backups/'.sanitize($_POST['file']));
			endif;

			if($action) :
				$json['type'] = 'success';
				$json['title'] = Lang::$word->SUCCESS;
				$json['message'] = str_replace("-ITEM-", $title, Lang::$word->DBM_DEL_OK);
			else :
				$json['type'] = 'warning';
				$json['title'] = Lang::$word->ALERT;
				$json['message'] = Lang::$word->NOPROCCESS;
			endif;
			print json_encode($json);
		 break;

	  /* == Delete Menu == */
	  case "deleteMenu":
		  $res = $db->delete(Content::muTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->MNU_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Fooer Menu == */
	  case "deleteFooterMenu":
		  $res = $db->delete(Content::mfTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->MNU_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Job Category == */
	  case "deleteJobCategory":
		  $res = $db->delete(Jobs::cTable, "id=" . Filter::$id);
		  $db->delete(Jobs::cTable, "parent_id=" . Filter::$id);
		  $title = sanitize($_POST['title']);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->CAT_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Job Location == */
	  case "deleteJobLocation":
		  $res = $db->delete(Jobs::lTable, "id=" . Filter::$id);
		  $db->delete(Jobs::lTable, "parent_id=" . Filter::$id);
		  $title = sanitize($_POST['title']);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->CAT_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Category == */
	  case "deleteCategory":
		  $res = $db->delete(Content::cTable, "id=" . Filter::$id);
		  $db->delete(Content::cTable, "parent_id=" . Filter::$id);
		  $title = sanitize($_POST['title']);

		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->CAT_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

	  /* == Delete Country == */
	  case "deleteCountry":
		  $res   = $db->delete(Content::cnTable, "id=" . Filter::$id);
		  $title = sanitize($_POST['title']);
		  if ($res):
			  $json['type']    = 'success';
			  $json['title']   = Lang::$word->SUCCESS;
			  $json['message'] = str_replace("-ITEM-", $title, Lang::$word->CNT_DELETED);
		  else:
			  $json['type']    = 'warning';
			  $json['title']   = Lang::$word->ALERT;
			  $json['message'] = Lang::$word->NOPROCCESS;
		  endif;

		  print json_encode($json);
		  break;

  endswitch;

// ======= Menu Start =====
  /* == Load Menus == */
  if (isset($_POST['getmenus'])):
      $content->getMenuList();
  endif;

  /* == Proccess Menus == */
  if (isset($_POST['processMenu'])):
      $content->processMenu();
  endif;

  /* == Sort Menus == */
  if (isset($_POST['doMenuSort'])):
      $i = 0;
      foreach ($_POST['list'] as $k => $v):
          $i++;
          $data['parent_id'] = intval($v);
		  $data['position'] = intval($i);
          $res = $db->update(Content::muTable, $data, "id=" . (int)$k);
      endforeach;
      print ($res) ? Filter::msgSingleOk(Lang::$word->MNU_SORTED) : Filter::msgSngleAlert(Lang::$word->NOPROCCESS);

  endif;
// ======= Menu End =====
// ======= Footer Menu Start =====
  /* == Load Footer Menus == */
  if (isset($_POST['getfootermenus'])):
      $content->getFooterMenuList();
  endif;

  /* == Proccess Footer Menus == */
  if (isset($_POST['processFooterMenu'])):
      $content->processFooterMenu();
  endif;

  /* == Admin Update Job == */
  if (isset($_POST['adminUpdateJob'])):
      $jobs->adminUpdateJob();
  endif;

  /* == Sort Footer Menus == */
  if (isset($_POST['doFooterMenuSort'])):
      $i = 0;
      foreach ($_POST['list'] as $k => $v):
          $i++;
          $data['parent_id'] = intval($v);
		  $data['position'] = intval($i);
          $res = $db->update(Content::mfTable, $data, "id=" . (int)$k);
      endforeach;
      print ($res) ? Filter::msgSingleOk(Lang::$word->MNU_SORTED) : Filter::msgSngleAlert(Lang::$word->NOPROCCESS);

  endif;
// ======= Footer Menu End =====
  /* Get Content Type */
  if (isset($_POST['contenttype'])):
      $type = sanitize($_POST['contenttype']);
      $html = "";
      switch ($type):
          case "page":
              $sql = "SELECT id, title FROM " . Content::pTable . " WHERE active = '1' ORDER BY title ASC";
              $result = $db->fetch_all($sql);

              if ($result):
                  foreach ($result as $row):
                      $html .= "<option value=\"" . $row->id . "\">" . $row->title . "</option>\n";
                  endforeach;
                  $json['type'] = 'page';
                  $json['message'] = $html;
              endif;
              break;

          default:
              $html .= "<input name=\"page_id\" type=\"hidden\" value=\"0\" />";
              $json['type'] = 'web';
              $json['message'] = $html;
      endswitch;

      print json_encode($json);
  endif;

  /* == Proccess Country == */
  if (isset($_POST['processCountry'])):
      $content->processCountry();
  endif;

  /* == Load Categories == */
  if (isset($_POST['getcategories'])):
      $content->getSortCatList();
  endif;

  /* == Proccess Category == */
  if (isset($_POST['processCategory'])):
      $content->processCategory();
  endif;

  /* == Sort Category == */
  if (isset($_POST['doCatSort'])):
      $i = 0;
      foreach ($_POST['list'] as $k => $v):
          $i++;
          $data['parent_id'] = intval($v);
          $data['position'] = intval($i);
          $res = $db->update(Content::cTable, $data, "id=" . (int)$k);
      endforeach;
      print ($res) ? Filter::msgSingleOk(Lang::$word->CAT_SORTED) : Filter::msgSingleAlert(Lang::$word->NOPROCCESS);
  endif;

  /* == Proccess Product == */
  if (isset($_POST['processProduct'])):
      $item->processProduct();
  endif;

  /* == Product Live Search == */
  if (isset($_POST['productSearch'])):
      $string = sanitize($_POST['productSearch'], 15);
      if (strlen($string) > 3):
          $sql = "SELECT id, title, thumb, body, created"
		  . "\n FROM " . Products::pTable
		  . "\n WHERE MATCH (title) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)"
		  . "\n ORDER BY title LIMIT 10";

          $html = '';
          if ($result = $db->fetch_all($sql)):
              $html .= '<div id="search-results" class="wojo segment celled list">';
              foreach ($result as $row):
                  $thumb = ($row->thumb) ? '<img src="' . UPLOADURL . 'prod_images/' . $row->thumb . '" alt="" class="wojo small image"/>' : '<img src="' . UPLOADURL . 'prod_images/blank.png" alt="" class="wojo small image"/>';
                  $link = 'index.php?do=products&amp;action=edit&amp;id=' . $row->id;
                  $html .= '<div class="item">' . $thumb;
                  $html .= '<div class="items">';
                  $html .= '<div class="header"><a href="' . $link . '">' . $row->title . '</a></div>';
                  $html .= '<p>' . Filter::dodate('short_date', $row->created) . '</p>';
                  $html .= '<p><small>' . cleanSanitize($row->body, 150). '</small></p>';
                  $html .= '</div>';
                  $html .= '</div>';
              endforeach;
              $html .= '</div>';
              print $html;
          endif;
      endif;
  endif;

  /* == Rename File Alias == */
  if (isset($_POST['quickedit']) and $_POST['type'] == "file"):
          if (empty($_POST['title'])):
              print '-/-';
              exit;
          endif;

		  $title = cleanOut($_POST['title']);
		  $title = strip_tags($title);

          if($_POST['key'] == "title"):
		    $data['alias'] = $title;
		    $db->update(Products::fTable, $data, "id = " . Filter::$id);
		  endif;

	  print $title;
  endif;

  /* == Add All Temp Files */
  if (isset($_POST['addAllTempFiles'])):
      $item->addTempFiles();
  endif;

  /* == Upload Files == */
  if (isset($_POST['uploadMainFiles'])):
      Registry::get('FM')->filesUpload('mainfile');
  endif;


  /* == Upload Gallery Image == */
  if (isset($_POST['uploadGalleryImages'])):
      $item->galleryUpload('mainfile');
  endif;

  /* == Edit Gallery == */
  if (isset($_POST['quickedit']) and $_POST['type'] == "gallery"):
          if (empty($_POST['title'])):
              print '-/-';
              exit;
          endif;

		  $title = cleanOut($_POST['title']);
		  $title = strip_tags($title);

          if($_POST['key'] == "title"):
		    $data['caption'] = $title;
		    $db->update(Products::phTable, $data, "id = " . Filter::$id);
		  endif;

	  print $title;
  endif;

  /* == Rename File Alias == */
  if (isset($_POST['quickedit']) and $_POST['type'] == "language"):
          if (empty($_POST['title'])):
              print '-/-';
              exit;
          endif;

		  $title = cleanOut($_POST['title']);
		  $title = strip_tags($title);

          if (file_exists(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['path'] . ".xml")):
		      $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['path'] . ".xml");
              $node = $xmlel->xpath("/language/phrase[@data = '" . $_POST['key'] . "']");
              $node[0][0] = $title;
              $xmlel->asXML(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['path'] . ".xml");
          endif;

	  print $title;
  endif;

  /* == Proccess Configuration == */
  if (isset($_POST['processConfig'])):
      $core->processConfig();
  endif;

  /* == Proccess Gateway == */
  if (isset($_POST['processGateway'])):
      $content->processGateway();
  endif;

  /* == Restore SQL Backup == */
  if (isset($_POST['restoreBackup'])):
	  require_once(BASEPATH . "lib/class_dbtools.php");
	  Registry::set('dbTools',new dbTools());
	  $tools = Registry::get("dbTools");

	  if($tools->doRestore($_POST['restoreBackup'])) :
		  $json['type'] = 'success';
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['message'] = str_replace("-ITEM-", $_POST['restoreBackup'], Lang::$word->DBM_RES_OK);
		  else :
		  $json['type'] = 'warning';
		  $json['title'] = Lang::$word->ALERT;
		  $json['message'] = Lang::$word->NOPROCCESS;
	  endif;
	  print json_encode($json);
  endif;

  /* == Proccess Page == */
  if (isset($_POST['processPage'])):
      $content->processPage();
  endif;

  /* == Proccess F.A.Q == */
  if (isset($_POST['processFaq'])):
      $content->processFaq();
  endif;

  /* == Update faq order == */
  if (isset($_GET['sortfaq'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['position'] = $p;
          $db->update(Content::fqTable, $data, "id=" . intval($v));
      endforeach;
  endif;

  /* == Proccess Testimonials == */
  if (isset($_POST['processTestimonials'])):
      $content->processTestimonials();
  endif;

  /* == Update testimonial order == */
  if (isset($_GET['sorttestimonial'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['position'] = $p;
          $db->update(Content::tesTable, $data, "id=" . intval($v));
      endforeach;
  endif;

  /* == Proccess Newsletter == */
  if (isset($_POST['processNewsletter'])):
      $content->processNewsletter();
  endif;

  /* == Proccess Email Template == */
  if (isset($_POST['processEmailTemplate'])):
      $content->processEmailTemplate();
  endif;

  /* == Proccess News == */
  if (isset($_POST['processNews'])):
      $content->processNews();
  endif;

  /* == Proccess Comment Configuration == */
  if (isset($_POST['processCommentConfig'])):
      $content->processCommentConfig();
  endif;

  /* == Comments Actions == */
  if (isset($_POST['comproccess']) && intval($_POST['comproccess']) == 1):
      $action = '';
      if (empty($_POST['comid'])):
          $json['type'] = 'warning';
          $json['message'] = Filter::msgAlert(Lang::$word->CMT_ACT_1, false);
      endif;

      if (!empty($_POST['comid'])):
          foreach ($_POST['comid'] as $val):
              $id = intval($val);
              if (isset($_POST['action']) && $_POST['action'] == "disapprove"):
                  $data['active'] = 0;
                  $action = Lang::$word->CMT_ACT_2;
              elseif (isset($_POST['action']) && $_POST['action'] == "approve"):
                  $data['active'] = 1;
                  $action = Lang::$word->CMT_ACT_3;
              endif;

              if (isset($_POST['action']) && $_POST['action'] == "delete"):
                  $db->delete(Content::cmTable, "id=" . $id);
                  $action = Lang::$word->CMT_ACT_4;
              else:
                  $db->update(Content::cmTable, $data, "id=" . $id);
              endif;
              endforeach;

		  if ($db->affected()):
			  $json['type'] = 'success';
			  $json['message'] = Filter::msgOk($action, false);
		  else:
			  $json['type'] = 'warning';
			  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
		  endif;

      endif;
	  print json_encode($json);
  endif;

  /* == Load Comment For Edit == */
  if (isset($_POST['loadComment'])):
      $row = Core::getRowById(Content::cmTable, Filter::$id);
      if ($row):
          $html =  '<div class="wojo small form" style="width:400px">';
          $html .= '<div class="field"><textarea name="body" class="altpost" id="bodyid">' . $row->body . '</textarea></div>';
          $html .= '<p class="wojo info">' . $row->www . '</p>';
          $html .= '<p class="wojo info">IP: ' . $row->ip . '</p>';
          $html .= '</div>';
          print $html;
      endif;
  endif;

  /* == Update Comment == */
  if (isset($_POST['processComment'])):
      $data['body'] = cleanOut($_POST['content']);
      $result = $db->update(Content::cmTable, $data, "id=" . Filter::$id);

      if ($result):
          $json['type'] = 'success';
          $json['title'] = Lang::$word->SUCCESS;
          $json['message'] = Lang::$word->CMT_UPDATED;
      else:
          $json['type'] = 'warning';
          $json['title'] = Lang::$word->ALERT;
          $json['message'] = Lang::$word->NOPROCCESS;
      endif;
      print json_encode($json);
  endif;

  /* == Proccess Coupons == */
  if (isset($_POST['processDiscount'])):
      $content->processDiscount();
  endif;

  /* == Proccess Slider Configuration == */
  if (isset($_POST['processSliderConfiguration'])):
      $content->processSliderConfiguration();
  endif;

  /* == Proccess Slider == */
  if (isset($_POST['processSlide'])):
      $content->processSlide();
  endif;

  /* == Update Slide order == */
  if (isset($_GET['sortslides'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['sorting'] = $p;
          $db->update(Content::slTable, $data, "id=" . intval($v));
      endforeach;
  endif;


  /* == Proccess User == */
  if (isset($_POST['processUser'])):
      $user->processUser();
  endif;

  /* == Acctivate User == */
  if (isset($_POST['activateAccount'])):
      $user->activateAccount();
  endif;

  /* == Acctivate Job == */
  if (isset($_POST['approveJob'])):
      $jobs->approveJob();
  endif;

  /* == Featured Job == */
  if (isset($_POST['toggleJobFeatured'])):
      $jobs->toggleJobFeatured();
  endif;

  /* == Featured Resume == */
  if (isset($_POST['toggleResumeFeatured'])):
      $jobs->toggleResumeFeatured();
  endif;


  /* == User Search == */
  if (isset($_POST['userSearch'])):
      $string = sanitize($_POST['userSearch'], 15);
      if (strlen($string) > 3):
          $sql = "SELECT id, username, email, created, avatar, CONCAT(fname,' ',lname) as name"
		  . "\n FROM " . Users::uTable
		  . "\n WHERE MATCH (username) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)"
		  . "\n ORDER BY username LIMIT 10";

          $html = '';
          if ($result = $db->fetch_all($sql)):
              $html .= '<div id="search-results" class="wojo segment celled list">';
              foreach ($result as $row):
                  $thumb = ($row->avatar) ? '<img src="' . UPLOADURL . 'avatars/' . $row->avatar . '" alt="" class="wojo image avatar"/>' : '<img src="' . UPLOADURL . 'avatars/blank.png" alt="" class="wojo image avatar"/>';
                  $link = 'index.php?do=users&amp;action=edit&amp;id=' . $row->id;
                  $html .= '<div class="item">' . $thumb;
                  $html .= '<div class="items">';
                  $html .= '<div class="header"><a href="' . $link . '">' . $row->name . '</a> <small>(' . $row->username . ')</small></div>';
                  $html .= '<p>' . Filter::dodate('short_date', $row->created) . '</p>';
                  $html .= '<p><a href="index.php?do=newsletter&amp;emailid=' . urlencode($row->email) . '">' . $row->email . '</a></p>';
                  $html .= '</div>';
                  $html .= '</div>';
              endforeach;
              $html .= '</div>';
              print $html;
          endif;
      endif;
  endif;

  /* == Resume Search == */
  if (isset($_POST['resumeSearch'])):
      $string = sanitize($_POST['resumeSearch'], 15);
      if (strlen($string) > 3):
          $sql = "SELECT uid, fullname, created, avatar, title"
        . "\n FROM " . Jobs::rTable
        . "\n WHERE fullname LIKE '%" . $db->escape($string) . "%' "
        . "\n ORDER BY fullname LIMIT 10";

          $html = '';
          if ($result = $db->fetch_all($sql)):
              $html .= '<div id="search-results" class="wojo segment celled list">';
              foreach ($result as $row):
                  $thumb = ($row->avatar) ? '<img src="' . UPLOADURL . 'avatars/' . $row->avatar . '" alt="" class="wojo image avatar"/>' : '<img src="' . UPLOADURL . 'avatars/blank.png" alt="" class="wojo image avatar"/>';
                  $link = SITEURL . '/resume.php?resumeid=' . $row->uid;
                  $html .= '<div class="item">' . $thumb;
                  $html .= '<div class="items">';
                  $html .= '<div class="header"><a target="_blank" href="' . $link . '">' . $row->fullname . '</a></div>';
                  $html .= '<p>' . $row->title . '</p>';
                  $html .= '</div>';
                  $html .= '</div>';
              endforeach;
              $html .= '</div>';
              print $html;
          endif;
      endif;
  endif;

  /* == Job Search == */
  if (isset($_POST['jobSearch'])):
      $string = sanitize($_POST['jobSearch'], 15);
      if (strlen($string) >= 3):
          $sql = "SELECT * "
		  . "\n FROM " . Jobs::jTable
		  . "\n WHERE title LIKE '%" . $db->escape($string) . "%'"
		  . "\n ORDER BY id LIMIT 10";
          $html = '';

		  if ($result = $db->fetch_all($sql)):
              $html .= '<div id="search-results" class="wojo segment celled list">';
              foreach ($result as $row):
                  $link = 'index.php?do=jobs&amp;action=details&amp;id=' . $row->id;
                  $html .= '<div class="item">';
                  $html .= '<div class="items">';
                  $html .= '<div class="header"><a href="' . $link . '">' . $row->title . '</a> <small>(' . Jobs::getCatInfo($row->categories) . ')</small></div>';
                  $html .= '<p>' . dodate($row->created) . '</p>';
                  $html .= '</div>';
                  $html .= '</div>';
              endforeach;
              $html .= '</div>';
              print $html;
          endif;
      endif;
  endif;

  /* == Site Maintenance == */
  if (isset($_POST['processMaintenance'])):
	switch ($_POST['do']):
		case "inactive":
				$now = date('Y-m-d H:i:s');
				$diff = intval($_POST['days']);
				$expire = date("Y-m-d H:i:s", strtotime($now . -$diff . " days"));
				$db->delete(Users::uTable, "lastlogin < '" . $expire . "' AND active = 'y' AND userlevel !=9");
				if ($db->affected()):
					$json['type'] = 'success';
					$json['message'] = Filter::msgOk(str_replace("[NUMBER]", $db->affected(), Lang::$word->MTN_DELINCT_OK), false);
				else:
					$json['type'] = 'success';
					$json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
				endif;
			print json_encode($json);
		 break;

	  case "banned":
		$db->delete(Users::uTable, "active = 'b'");
		if ($db->affected()):
			$json['type'] = 'success';
			$json['message'] = Filter::msgOk(str_replace("[NUMBER]", $db->affected(), Lang::$word->MTN_DELBND_OK), false);
		else:
			$json['type'] = 'success';
			$json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
		endif;
		print json_encode($json);
	   break;

	  case "recent":
		$db->query("TRUNCATE TABLE recent");
		if ($db->affected()):
			$json['type'] = 'success';
			$json['message'] = Filter::msgOk(str_replace("[NUMBER]", $db->affected(), Lang::$word->MTN_DELRCT_OK), false);
		else:
			$json['type'] = 'success';
			$json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
		endif;
		print json_encode($json);
	   break;

	  case "sitemap":
		$content->writeSiteMap();
	   break;

	endswitch;

  endif;

  /* == Proccess Transaction == */
  if (isset($_POST['processAdminTransaction'])):
      $jobs->processAdminTransaction();
  endif;

  /* == Proccess Transaction == */
  if (isset($_POST['processTransaction'])):
      $item->processTransaction();
  endif;

  /* == Transaction Live Search == */
  if (isset($_POST['transactionSearch'])):
      $string = sanitize($_POST['transactionSearch'], 15);
      if (strlen($string) > 3):
          $sql = "SELECT t.*, t.id as id, u.id as uid, u.username, p.id as pid, p.title, p.thumb"
		  . "\n FROM " . Products::tTable . " as t"
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = t.uid"
		  . "\n LEFT JOIN " . Products::pTable . " as p ON p.id = t.pid"
		  . "\n WHERE MATCH (p.title, t.txn_id, u.username) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)"
		  . "\n ORDER BY t.created DESC LIMIT 5";

          $html = '';
          if ($result = $db->fetch_all($sql)):
              $html .= '<div id="search-results" class="wojo segment celled list">';
              foreach ($result as $row):
                  $thumb = ($row->thumb) ? '<img src="' . UPLOADURL . 'prod_images/' . $row->thumb . '" alt="" class="wojo small image"/>' : '<img src="' . UPLOADURL . 'prod_images/blank.png" alt="" class="wojo small image"/>';
                  $link = 'index.php?do=products&amp;action=edit&amp;id=' . $row->id;
                  $html .= '<div class="item">' . $thumb;
                  $html .= '<div class="items">';
                  $html .= '<div class="header"><a href="' . $link . '">' . $row->title . '</a></div>';
                  $html .= '<p>' . Lang::$word->USERNAME . ': ' . $row->username . '</p>';
                  $html .= '<p>' . Lang::$word->TXN_AMT . ': ' . $core->formatMoney($row->price) . '</p>';
				  $html .= '<p><small>' . Lang::$word->CREATED . ': ' . $row->created . '</small></p>';
                  $html .= '</div>';
                  $html .= '</div>';
              endforeach;
              $html .= '</div>';
              print $html;
          endif;
      endif;
  endif;

  /* == Export Transactions XLS == */
  if (isset($_GET['exportTransactionsXLS'])):
       $jobs->exportTransactionsXLS();
  endif;

  /* == Export Transactions PDF == */
  if (isset($_GET['exportTransactionsPDF'])):
       $jobs->exportTransactionsPDF();
  endif;

  /* == Latest Sales Stats == */
    if (isset($_GET['getSaleStats'])):
        if (intval($_GET['getSaleStats']) == 0 || empty($_GET['getSaleStats'])):
            die();
        endif;

    $range = (isset($_GET['timerange'])) ? sanitize($_GET['timerange']) : 'month';
    $data = array();
    $data['jobposts'] = array();
    $data['xaxis'] = array();
    $data['jobposts']['label'] = ' Job Posts';
    $data['jobapps']['label'] = ' Job Applications';

    switch ($range) {
  	  case 'day':
  	  $date = date('Y-m-d');
  		  for ($i = 0; $i < 24; $i++) {
  			  $query = $db->first("SELECT COUNT(*) AS total FROM jobs"
  			  . "\n WHERE DATE(created) = '" . $db->escape($date) . "'"
  			  . "\n AND HOUR(created) = '" . (int)$i . "'"
  			  . "\n GROUP BY HOUR(created) ORDER BY created ASC");

  			  ($query) ? $data['jobposts']['data'][] = array($i, (int)$query->total) : $data['jobposts']['data'][] = array($i, 0);
  			  $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));

                $query = $db->first("SELECT COUNT(*) AS total FROM job_applications"
  			  . "\n WHERE DATE(created) = '" . $db->escape($date) . "'"
  			  . "\n AND HOUR(created) = '" . (int)$i . "'"
  			  . "\n GROUP BY HOUR(created) ORDER BY created ASC");
  			  ($query) ? $data['jobapps']['data'][] = array($i, (int)$query->total) : $data['jobapps']['data'][] = array($i, 0);

  		  }
  		  break;
  	  case 'week':
  		  $date_start = strtotime('-' . date('w') . ' days');

  		  for ($i = 0; $i < 7; $i++) {
  			  $date = date('Y-m-d', $date_start + ($i * 86400));
  			  $query = $db->first("SELECT COUNT(*) AS total FROM jobs"
  			  . "\n WHERE DATE(created) = '" . $db->escape($date) . "'"
  			  . "\n GROUP BY DATE(created)");

  			  ($query) ? $data['jobposts']['data'][] = array($i, (int)$query->total) : $data['jobposts']['data'][] = array($i, 0);
  			  $data['xaxis'][] = array($i, date('D', strtotime($date)));

                $query = $db->first("SELECT COUNT(*) AS total FROM job_applications"
  			  . "\n WHERE DATE(created) = '" . $db->escape($date) . "'"
  			  . "\n GROUP BY DATE(created)");
  			  ($query) ? $data['jobapps']['data'][] = array($i, (int)$query->total) : $data['jobapps']['data'][] = array($i, 0);

  		  }

  		  break;
  	  default:
  	  case 'month':
  		  for ($i = 1; $i <= date('t'); $i++) {
  			  $date = date('Y') . '-' . date('m') . '-' . $i;

  			  $query = $db->first("SELECT COUNT(*) AS total FROM jobs"
  			  . "\n WHERE (DATE(created) = '" . $db->escape($date) . "')"
  			  . "\n GROUP BY DAY(created)");

  			  ($query) ? $data['jobposts']['data'][] = array($i, (int)$query->total) : $data['jobposts']['data'][] = array($i, 0);
  			  $data['xaxis'][] = array($i, date('j', strtotime($date)));

                $query = $db->first("SELECT COUNT(*) AS total FROM job_applications"
  			  . "\n WHERE (DATE(created) = '" . $db->escape($date) . "')"
  			  . "\n GROUP BY DAY(created)");
  			  ($query) ? $data['jobapps']['data'][] = array($i, (int)$query->total) : $data['jobapps']['data'][] = array($i, 0);

  		  }
  		  break;
  	  case 'year':
  		  for ($i = 1; $i <= 12; $i++) {
  			  $query = $db->first("SELECT COUNT(*) AS total FROM jobs"
  			  . "\n WHERE YEAR(created) = '" . date('Y') . "'"
  			  . "\n AND MONTH(created) = '" . $i . "'"
  			  . "\n GROUP BY MONTH(created)");

  			  ($query) ? $data['jobposts']['data'][] = array($i, (int)$query->total) : $data['jobposts']['data'][] = array($i, 0);
  			  $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));

                $query = $db->first("SELECT COUNT(*) AS total FROM job_applications"
  			  . "\n WHERE YEAR(created) = '" . date('Y') . "'"
  			  . "\n AND MONTH(created) = '" . $i . "'"
  			  . "\n GROUP BY MONTH(created)");
  			  ($query) ? $data['jobapps']['data'][] = array($i, (int)$query->total) : $data['jobapps']['data'][] = array($i, 0);

  		  }
  		  break;
    }

    print json_encode($data);
    endif;

  /* == Latest Product Stats == */
  if (isset($_GET['getProductStats'])):

      $data = array();
      $data['hits'] = array();
      $data['xaxis'] = array();
      $data['hits']['label'] = Lang::$word->ADM_PVIEWS;
      $data['uhits']['label'] = Lang::$word->ADM_UVIEWS;

      $and = (Filter::$id) ? "AND pid = " . Filter::$id : null;

      for ($i = 1; $i <= 12; $i++):
          $row = $db->first("SELECT SUM(hits) AS hits,"
		  . "\n SUM(uhits) as uhits"
		  . "\n FROM stats"
		  . "\n WHERE YEAR(day) = '" . date('Y') . "'"
		  . "\n AND MONTH(day) = '" . $i . "'"
		  . "\n $and"
		  . "\n GROUP BY MONTH(day)");

          $data['hits']['data'][] = ($row) ? array($i, (int)$row->hits) : array($i, 0);
          $data['uhits']['data'][] = ($row) ? array($i, (int)$row->uhits) : array($i, 0);
          $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
      endfor;

      print json_encode($data);
  endif;

  // ADMIN JOB CATEGORY PROCESSES //
  /* == Load Job Categories == */
  if (isset($_POST['getjobcategories'])):
      $jobs->getJobSortCatList();
  endif;

  /* == Proccess Job Category == */
  if (isset($_POST['processJobCategory'])):
      $jobs->processJobCategory();
  endif;

  /* == Sort Job Category == */
  if (isset($_POST['doJobCatSort'])):
      $i = 0;
      foreach ($_POST['list'] as $k => $v):
          $i++;
          $data['parent_id'] = intval($v);
          $data['position'] = intval($i);
          $res = $db->update(Jobs::cTable, $data, "id=" . (int)$k);
      endforeach;
      print ($res) ? Filter::msgSingleOk(Lang::$word->CAT_SORTED) : Filter::msgSingleAlert(Lang::$word->NOPROCCESS);
  endif;

  // ADMIN JOB LOCATION PROCESSES //
  /* == Load Job Location == */
  if (isset($_POST['getjoblocations'])):
      $jobs->getJobSortLocList();
  endif;

  /* == Proccess Job Location == */
  if (isset($_POST['processJobLocation'])):
      $jobs->processJobLocation();
  endif;

  /* == Sort Job Location == */
  if (isset($_POST['doJobLocSort'])):
      $i = 0;
      foreach ($_POST['list'] as $k => $v):
          $i++;
          $data['parent_id'] = intval($v);
          $data['position'] = intval($i);
          $res = $db->update(Jobs::lTable, $data, "id=" . (int)$k);
      endforeach;
      print ($res) ? Filter::msgSingleOk(Lang::$word->CAT_SORTED) : Filter::msgSingleAlert(Lang::$word->NOPROCCESS);
  endif;


  // ADMIN JOB Type PROCESSES //
  /* == Load Job Type == */
  if (isset($_POST['getjobtypes'])):
      $jobs->getJobSortTypeList();
  endif;

  /* == Proccess Job Type == */
  if (isset($_POST['processJobType'])):
      $jobs->processJobType();
  endif;

  /* == Update job type order == */
  if (isset($_GET['sortjobtype'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['position'] = $p;
          $db->update(Jobs::tTable, $data, "id=" . intval($v));
      endforeach;
  endif;

  // ADMIN Package PROCESSES //
  /* == Load Packages == */
  if (isset($_POST['getpackages'])):
      $jobs->getPackageSortList();
  endif;

  /* == Proccess Package == */
  if (isset($_POST['processPackage'])):
      $jobs->processPackage();
  endif;

  /* == Update package order == */
  if (isset($_GET['sortpackage'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['position'] = $p;
          $db->update(Jobs::pTable, $data, "id=" . intval($v));
      endforeach;
  endif;


  // ADMIN JOB Skill PROCESSES //
  /* == Load Job Skill == */
  if (isset($_POST['getjobskills'])):
      $jobs->getJobSortSkillList();
  endif;

  /* == Proccess Job Skill == */
  if (isset($_POST['processJobSkill'])):
      $jobs->processJobSkill();
  endif;

  /* == Update job skill order == */
  if (isset($_GET['sortjobskill'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['position'] = $p;
          $db->update(Jobs::sTable, $data, "id=" . intval($v));
      endforeach;
  endif;


?>
