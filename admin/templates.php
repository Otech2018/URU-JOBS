<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = $core->getRowById(Content::eTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->ETP_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->ETP_SUB1;?> / <?php echo $row->name;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->ETP_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="name" value="<?php echo $row->name;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ETP_SUBJECT;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="subject" value="<?php echo $row->subject;?>">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <label><?php echo Lang::$word->ETP_HELP;?></label>
      <label class="textarea">
        <textarea name="help"><?php echo $row->help;?></textarea>
      </label>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <textarea class="bodypost" name="body"><?php echo $row->body;?></textarea>
    </div>
    <p class="wojo error"><?php echo Lang::$word->ETP_MSGE;?> </p>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->ETP_UPDATE;?></button>
    <a href="index.php?do=templates" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processEmailTemplate" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $temprow = $content->getEmailTemplates();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->ETP_INFO;?></div>
<div class="wojo basic segment">
  <div class="header"><span><?php echo Lang::$word->ETP_SUB;?></span> </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string">Template Name</th>
        <th data-sort="string">&nbsp;</th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$temprow):?>
      <tr>
        <td colspan="3"><?php echo Filter::msgSingleError("Your are missing all email templates. You need to reinstall them manually");?></td>
      </tr>
      <?php else:?>
      <?php foreach ($temprow as $row):?>
      <tr>
        <td><?php echo $row->name;?></td>
        <td><small><?php echo $row->help;?></small></td>
        <td><a href="index.php?do=templates&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>