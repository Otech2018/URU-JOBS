<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = $core->getRowById(Content::fqTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->FAQ_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->FAQ_SUB1;?> / <?php echo $row->question;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->FAQ_QUEST;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" name="question" value="<?php echo $row->question;?>">
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FAQ_ANSW;?></label>
      <textarea class="bodypost" name="answer"><?php echo $row->answer;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->FAQ_UPDATE;?></button>
    <a href="index.php?do=faq" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processFaq" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case "add":?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->FAQ_INFO2;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->FAQ_SUB2;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->FAQ_QUEST;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" name="question" placeholder="<?php echo Lang::$word->FAQ_QUEST;?>">
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FAQ_ANSW;?></label>
      <textarea class="bodypost" name="answer"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->FAQ_ADD;?></button>
    <a href="index.php?do=faq" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processFaq" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $faqrow = $content->getFaq();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->FAQ_INFO;?></div>
<div class="wojo basic segment">
  <div class="header"><a class="wojo button push-right" href="index.php?do=faq&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->FAQ_ADD;?></a><span><?php echo Lang::$word->FAQ_SUB;?></span> </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th class="disabled"></th>
        <th data-sort="string"><?php echo Lang::$word->FAQ_QUEST;?></th>
        <th data-sort="string"><?php echo Lang::$word->FAQ_ANSW;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$faqrow):?>
      <tr>
        <td colspan="4"><?php echo Filter::msgSingleAlert(Lang::$word->FAQ_NOFAQ);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($faqrow as $row):?>
      <tr id="node-<?php echo $row->id;?>">
        <td class="id-handle"><i class="icon reorder"></i></td>
        <td><?php echo $row->question;?></td>
        <td><?php echo cleanSanitize(truncate($row->answer,80));?></td>
        <td><a href="index.php?do=faq&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->FAQ_DELETE;?>" data-option="deleteFaq" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->question;?>"><i class="circular danger inverted remove icon link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".wojo.table tbody").sortable({
        helper: 'clone',
        handle: '.id-handle',
        placeholder: 'placeholder',
        opacity: .6,
        update: function (event, ui) {
            serialized = $(".wojo.table tbody").sortable('serialize');
            $.ajax({
                type: "POST",
                url: "controller.php?sortfaq",
                data: serialized,
                success: function (msg) {}
            });
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>