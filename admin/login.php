<?php
  
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php
  if ($user->is_Admin())
      redirect_to("index.php");
	  
  if (isset($_POST['submit']))
      : $result = $user->login($_POST['username'], $_POST['password']);
  //Login successful 
  if ($result)
      : redirect_to("index.php");
  endif;
  endif;

?>
<!DOCTYPE html>

<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,100,300,700,900" rel="stylesheet" type="text/css">
<link href="assets/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../assets/jquery.js"></script>
</head>
<body>
<div class="container">
  <form id="admin_form" name="admin_form" method="post" class="xform">
    <header>
      <?php if ($core->logo):?>
      <div> <img src="../uploads/<?php echo $core->logo;?>" alt="" class="logo" width='150px' height='150px' /></div>
      <?php endif;?>
      Admin Panel - Login </header>
    <div class="row">
      <input type="text" placeholder="<?php echo Lang::$word->USERNAME;?>" name="username">
      <input type="password" placeholder="<?php echo Lang::$word->PASSWORD;?>" name="password">
    </div>
    <input type="submit" name="submit" value="Sign in">
    <footer> Copyright &copy;<?php echo date('Y') . ' ' . $core->site_name . ' v' . $core->version;?> </footer>
  </form>
  <div id="message-box"><?php print Filter::$showMsg;?></div>
</div>
</body>
</html>