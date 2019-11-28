<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->LMG_INFO;?></div>
<div id="msgholder"></div>
<div class="wojo basic segment">
  <div class="header"><span><?php echo Lang::$word->LMG_SUB;?></span> </div>
  <div class="wojo form segment">
    <div class="wojo fluid icon input">
      <input id="filter" type="text" placeholder="<?php echo Lang::$word->FIND;?>">
      <i class="search icon"></i> </div>
    <div class="wojo divider"></div>
    <div id="langphrases" class="two columns small-gutters">
      <?php $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/lang.xml"); $data = new stdClass();?>
      <?php $i = 1;?>
      <?php  foreach ($xmlel as $pkey) :?>
      <div class="row">
        <div contenteditable="true" data-path="lang" data-edit-type="language" data-id="<?php echo $i++;?>" data-key="<?php echo $pkey['data'];?>" class="wojo phrase"><?php echo $pkey;?></div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $("#filter").on("keyup", function () {
        var filter = $(this).val(),
            count = 0;
        $("div[contenteditable=true]").each(function () {
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).parent().fadeOut();
            } else {
                $(this).parent().show();
                count++;
            }
        });
    });
});
// ]]>
</script>