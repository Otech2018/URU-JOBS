<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  switch (Filter::$do) {
      case "users";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=users" class="section">' . Lang::$word->USR_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->USR_SUB . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=users" class="section">' . Lang::$word->USR_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->USR_SUB1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->USR_TITLE . '</div>';
                  break;
          }

          break;

      case "config":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CONF_TITLE . '</div>';
          break;

      case "files":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FLM_TITLE . '</div>';
          break;
		  
      case "backup":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->DBM_TITLE . '</div>';
          break;

      case "maintenance":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MTN_TITLE . '</div>';
          break;

      case "system":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SYS_TITLE . '</div>';
          break;

      case "language":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LMG_TITLE . '</div>';
          break;
		    
      case "gateways":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=gateways" class="section">' . Lang::$word->GTW_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GTW_SUB . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GTW_TITLE . '</div>';
                  break;
          }

          break;

      case "templates":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=templates" class="section">' . Lang::$word->ETP_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->ETP_SUB1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->ETP_TITLE . '</div>';
                  break;
          }

          break;

      case "menus":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=menus" class="section">' . Lang::$word->MNU_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MNU_SUB1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MNU_TITLE . '</div>';
                  break;
          }

          break;

      case "categories":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=categories" class="section">' . Lang::$word->CAT_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CAT_SUB1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CAT_TITLE . '</div>';
                  break;
          }
		  
          break;
		  		  
      case "newsletter":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NWL_TITLE . '</div>';
          break;

      case "pages";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=pages" class="section">' . Lang::$word->PAG_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PAG_SUB1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <a href="index.php?do=pages" class="section">' . Lang::$word->PAG_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PAG_SUB2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PAG_TITLE . '</div>';
                  break;
          }

          break;

      case "coupons";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=coupons" class="section">' . Lang::$word->CPN_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CPN_SUB1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <a href="index.php?do=coupons" class="section">' . Lang::$word->CPN_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CPN_SUB2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CPN_TITLE . '</div>';
                  break;
          }

          break;

      case "slider":

          switch (Filter::$action) {
              case "config":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=slider" class="section">' . Lang::$word->SLM_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SLM_SUB1 . '</div>';
                  break;
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=slider" class="section">' . Lang::$word->SLM_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SLM_SUB2 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=slider" class="section">' . Lang::$word->SLM_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SLM_SUB4 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SLM_TITLE . '</div>';
                  break;
          }

          break;
		  
      case "faq";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=faq" class="section">' . Lang::$word->FAQ_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FAQ_SUB1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <a href="index.php?do=faq" class="section">' . Lang::$word->FAQ_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FAQ_SUB2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FAQ_TITLE . '</div>';
                  break;
          }

          break;

      case "news";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=news" class="section">' . Lang::$word->NWS_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NWS_SUB1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <a href="index.php?do=news" class="section">' . Lang::$word->NWS_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NWS_SUB2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NWS_TITLE . '</div>';
                  break;
          }

          break;

      case "countries";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=countries" class="section">' . Lang::$word->CNT_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CNT_SUB1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CNT_TITLE . '</div>';
                  break;
          }

          break;
		  
      case "products";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=products" class="section">' . Lang::$word->PRD_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PRD_SUB1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=products" class="section">' . Lang::$word->PRD_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PRD_SUB2 . '</div>';
                  break;
              case "gallery":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=products" class="section">' . Lang::$word->PRD_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GAL_TITLE . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PRD_TITLE . '</div>';
                  break;
          }

          break;
		        
      case "transactions":

          switch (Filter::$action) {
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=transactions" class="section">' . Lang::$word->TXN_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TXN_SUB2 . '</div>';
                  break;
              case "salesyear":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=transactions" class="section">' . Lang::$word->TXN_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TXN_SUB1 . '</div>';
                  break;
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=transactions" class="section">' . Lang::$word->TXN_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TXN_SUB3 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TXN_TITLE . '</div>';
                  break;
          }

          break;
		  
      case "comments":

          switch (Filter::$action) {
              case "config":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div><a href="index.php?do=comments" class="section">' . Lang::$word->CMT_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CMT_SUB1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->ADM_DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CMT_TITLE . '</div>';
                  break;
          }

          break;
		  

      default:
          echo '<i class="icon home"></i> <div class="divider"></div> <div class="active section">' . Lang::$word->WELCOME . '</div>';

      break;
  }
?>