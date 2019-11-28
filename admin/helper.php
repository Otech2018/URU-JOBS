<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php

  switch (Filter::$do) {
      case "users";
          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="users" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }

          break;

      case "config":
      default:
          print '<a data-help="config" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
          break;


      case "gateways":
          switch (Filter::$action) {
              case "edit":
                  print '<a data-help="gateway" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }
          break;


      case "language":
          switch (Filter::$action) {
              default:
                  print '<a data-help="language" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
          }

          break;

      case "categories":
          switch (Filter::$action) {
              default:
			      print '<a data-help="category" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
          }

          break;

      case "pages":
          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="pages" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
				  break;
              default:
                  break;
          }

          break;

      case "slider":
          switch (Filter::$action) {
              case "config":
                  print '<a data-help="slider" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }
          break;
		  
      case "coupons":
          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="coupons" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
				  break;
              default:
                  break;
          }

          break;

      case "comments":
          switch (Filter::$action) {
              case "config":
                  print '<a data-help="comments" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }
          break;

      case "products":
          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="products" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }
          break;
		  
      case "transactions":
          switch (Filter::$action) {
              case "add":
                  print '<a data-help="trans" class="helper wojo top right attached info help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }

          break;
		  
      default:
              break;
          }

?>