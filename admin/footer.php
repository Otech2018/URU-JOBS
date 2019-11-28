<?php
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Footer -->
<footer id="footer" class="clearfix">
  <div class="copyright">Copyright &copy; <?php echo date('Y').' '.$core->company;?> &bull; Powered by: Job Board v <?php echo $core->version;?></div>
</footer>
<script src="../assets/fullscreen.js"></script>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    $.Master({
        lang: {
            button_text: "<?php echo Lang::$word->BROWSE;?>",
            empty_text: "<?php echo Lang::$word->NOFILE;?>",
			clear : "<?php echo Lang::$word->CLEAR;?>",
			delBtn : "<?php echo Lang::$word->DELETE_REC;?>",
            delMsg1: "<?php echo Lang::$word->DELCONFIRM;?>",
            delMsg2: "<?php echo Lang::$word->DELCONFIRM1;?>",
            working: "<?php echo Lang::$word->WORKING;?>"
        }
    });
});
// ]]>
</script>
<!-- Footer /-->
</body></html>