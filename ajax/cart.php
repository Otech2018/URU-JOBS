<?php
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  if (isset($_POST['addtocart']) or isset($_POST['delcart'])):
	  if (isset($_POST['addtocart'])):
		  if ($row = $db->first("SELECT id, price FROM " . Jobs::pTable . " WHERE id = " . Filter::$id)):
			  $data = array(
				  'user_id' => sanitize($user->sesid),
				  'pid' => $row->id,
				  'price' => floatval($row->price)
			  );
			  $db->insert(Content::crTable, $data);
			  unset($row);
			  $json['type'] = ($db->affected()) ? 'success' : 'error';
		  else:
			  $json['type'] = 'error';
		  endif;
	  endif;

	  if (isset($_POST['delcart'])):
		  $db->delete(Content::crTable, "pid=" . Filter::$id . " AND user_id = '" . $db->escape($user->sesid) . "'");
		  $db->delete(Content::exTable, "user_id = '" . $user->sesid . "'");
	  endif;

	  if ($row = $db->first("SELECT sum(price) as ptotal, COUNT(*) as itotal FROM " . Content::crTable . " WHERE user_id = '" . $db->escape($user->sesid) . "'")):
		  $itotal = ($row->itotal == 0) ? '0' : $row->itotal;
		  $ptotal = ($row->ptotal == 0) ? $core->cur_symbol . '0.00' : $core->formatMoney($row->ptotal);

		  $db->delete(Content::exTable, "user_id = '" . $user->sesid . "'");
		  $tax = Content::calculateTax();

		  $xdata = array(
			  'user_id' => $user->sesid,
			  'coupon' => 0.00,
			  'originalprice' => $row->ptotal,
			  'tax' => $tax,
			  'totaltax' => $row->ptotal * $tax,
			  'total' => $row->ptotal,
			  'totalprice' => $tax * $row->ptotal + $row->ptotal,
			  'created' => "NOW()",
			  );
		  $db->insert(Content::exTable, $xdata);


		  $json['message'] = $itotal . ' ' . Lang::$word->ITEMS . ' / ' . $ptotal;
		  $json['total'] = $core->formatMoney($row->ptotal);
	  else:
		  $json['message'] = '0 ' . Lang::$word->ITEMS . ' / ' . $core->cur_symbol . '0.00';
		  $json['total'] = '0.00';
	  endif;

	  $cartdata = $content->renderCart();
	  $html = '';
	  if ($cartdata):
		  foreach ($cartdata as $crow):
			  $thumb = SITEURL . '/thumbmaker.php?src=' . UPLOADURL . '/prod_images/' . $crow->thumb . '&amp;h=35&amp;w=35';
			  $url   = (Registry::get("Core")->seo) ? SITEURL . '/product/' . $crow->slug . '/' : SITEURL . '/item.php?itemname=' . $crow->slug;
			  $html .= '
			<div class="item"><img src="' . $thumb . '" alt="" class="tooltip" title="' . $crow->title . '"/> <a data-id="' . $crow->pid . '" class="delcart right tiny circular floated negative wojo icon button"><i class="icon trash"></i></a>
			  <div class="content"> <a class="header" href="' . $url . '">' . truncate($crow->title, 30) . '</a>
				<p>' . Registry::get("Core")->formatMoney($crow->price) . ' x ' . $crow->total . '</p>
			  </div>
			</div>';
		  endforeach;
	  else:
		  $html .= '<div class="item"> <i class="icon ban circle"></i> ' . Lang::$word->CKO_SUB . ' </div>';
	  endif;
	  $json['cart'] = $html;

	  print json_encode($json);
  endif;
?>
<?php
  if (isset($_POST['delfulcart'])):
	  $db->delete(Content::crTable, "pid=" . Filter::$id . " AND user_id = '" . $db->escape($user->sesid) . "'");
	  $db->delete(Content::exTable, "user_id = '" . $user->sesid . "'");
	  $json = array();
	  if ($row = $db->first("SELECT sum(price) as ptotal, COUNT(*) as itotal FROM " . Content::crTable . " WHERE user_id = '" . $db->escape($user->sesid) . "'")):
		  $itotal = ($row->itotal == 0) ? '0' : $row->itotal;
		  $ptotal = ($row->ptotal == 0) ? $core->cur_symbol . '0.00' : $core->formatMoney($row->ptotal);

		  $tax = Content::calculateTax();

		  $xdata = array(
			  'user_id' => $user->sesid,
			  'coupon' => 0.00,
			  'originalprice' => $row->ptotal,
			  'tax' => $tax,
			  'totaltax' => $row->ptotal * $tax,
			  'total' => $row->ptotal,
			  'totalprice' => $tax * $row->ptotal + $row->ptotal,
			  'created' => "NOW()",
			  );
		  $db->insert(Content::exTable, $xdata);

		  $json['message'] = $itotal . ' ' . Lang::$word->ITEMS . ' / ' . $ptotal;
		  $json['total'] = $core->formatMoney($xdata['totalprice'], false);
		  $json['tax'] = $core->formatMoney($xdata['totaltax'], false);
		  $json['subt'] = $core->formatMoney($xdata['total'], false);
	  else:
		  $json['message'] = '0 ' . Lang::$word->ITEMS . ' / ' . $core->cur_symbol . '0.00';
		  $json['total'] = '0.00';
	  endif;
     print json_encode($json);

  endif;
?>
