<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $row = (isset(Filter::$get['emailid'])) ? Core::getRowById(Content::eTable, 12) : Core::getRowById(Content::eTable, 4);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->NWL_INFO;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->NWL_SUB;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->NWL_FROM;?></label>
        <label class="input state-disabled">
          <input name="title" type="text" disabled="disabled" value="<?php echo $core->site_email;?>" placeholder="<?php echo Lang::$word->NWL_FROM;?>" readonly>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->NWL_REC;?></label>
        <?php if(isset(Filter::$get['emailid'])):?>
        <label class="input">
          <input name="recipient" type="text" value="<?php echo sanitize(Filter::$get['emailid']);?>" placeholder="<?php echo Lang::$word->NWL_REC;?>" >
        </label>
        <?php else:?>
        <select name="recipient" id="multiusers">
          <option value="all"><?php echo Lang::$word->NWL_REC_A;?></option>
          <option value="newsletter"><?php echo Lang::$word->NWL_REC_N;?></option>
        </select>
        <?php endif;?>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->NWL_SUBJECT;?></label>
      <label class="input"> <i class="icon-append icon-asterisk"></i>
        <input name="subject" type="text" value="<?php echo $row->subject;?>">
      </label>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <textarea class="bodypost" name="body"><?php echo $row->body;?></textarea>
      <p class="wojo error"><?php echo Lang::$word->NWL_MSGE;?></p>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->NWL_SEND;?></button>
    <input name="processNewsletter" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>