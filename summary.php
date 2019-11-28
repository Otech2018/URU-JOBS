<?php
  define("_VALID_PHP", true);
  require_once("init.php");
	  
  $cartrow = $content->getCartContent();
  $cart = $content->getCart();
  $gaterow = $content->getGateways(true);
?>
<?php require_once (THEMEDIR . "/summary.tpl.php");?>