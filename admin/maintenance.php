<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->MTN_INFO;?></div>
<div id="msgholder"></div>
<div class="wojo basic segment">
<div class="header"><span><?php echo Lang::$word->MTN_SUB;?></span> </div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MTN_DELINCT;?></label>
        <div class="note"><?php echo Lang::$word->MTN_DELINCT_T;?></div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MTN_DAYS;?></label>
        <select name="days">
          <option value="3">3</option>
          <option value="7">7</option>
          <option value="14">14</option>
          <option value="30">30</option>
          <option value="60">60</option>
          <option value="100">100</option>
          <option value="180">180</option>
          <option value="365">365</option>
        </select>
      </div>
    </div>
    <div class="field">
      <button type="button" data-type="inactive" name="inactive" class="wojo negative button"><?php echo Lang::$word->MTN_DELINCTB;?></button>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label> <?php echo Lang::$word->MTN_DELBND;?> </label>
        <div class="note"><?php echo str_replace("[BANNED]", "<span class=\"wojo negative label\">" . countEntries("users","active","b") . "</span>", Lang::$word->MTN_DELBND_T);?> </div>
      </div>
      <div class="field">
        <button type="button" data-type="banned" name="banned" class="wojo warning button"><?php echo Lang::$word->MTN_DELBNDB;?></button>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MTN_DELRCT;?></label>
        <div class="note"><?php echo Lang::$word->MTN_DELRCT_T;?></div>
      </div>
      <div class="field">
        <button type="button" data-type="recent" name="recent" class="wojo positive button"><?php echo Lang::$word->MTN_DELRCTB;?></button>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MTN_STM;?></label>
        <div class="note"><?php echo Lang::$word->MTN_STM_T;?></div>
      </div>
      <div class="field">
        <button type="button" data-type="sitemap" name="sitemap" class="wojo button"><?php echo Lang::$word->MTN_STMB;?></button>
      </div>
    </div>
    <input name="processMaintenance" type="hidden" value="1">
  </form>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    /* == Master Form == */
    $('body').on('click', 'button', function () {
        function showResponse(json) {
			$('html, body').animate({
				scrollTop: 0
			}, 600);
            $("#msgholder").html(json.message);
        }

        function showLoader() {}
        var options = {
            target: "#msgholder",
            beforeSubmit: showLoader,
            success: showResponse,
            type: "post",
            url: "controller.php",
            dataType: 'json',
			data :{'do':$(this).data('type')}
        };

        $('#wojo_form').ajaxForm(options).submit();
    });
});
// ]]>
</script>