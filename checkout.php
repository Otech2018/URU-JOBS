<?php
  define("_VALID_PHP", true);
  require_once("init.php");
	  
  $cartrow = $content->getCartContent(false);
  $cart = $content->getCart();
?>
<?php require_once (THEMEDIR . "/checkout.tpl.php");?>