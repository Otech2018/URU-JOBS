<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Content::gTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->GTW_INFO;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->GTW_SUB;?> / <?php echo $row->displayname;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->GTW_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="displayname" value="<?php echo $row->displayname;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo $row->extra_txt;?></label>
        <label class="input">
          <input type="text" name="extra" value="<?php echo $row->extra;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo $row->extra_txt2;?></label>
        <label class="input">
          <input type="text" name="extra2" value="<?php echo $row->extra2;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo $row->extra_txt3;?></label>
        <label class="input">
          <input type="text" name="extra3" value="<?php echo $row->extra3;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->GTW_LIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="demo" value="1" <?php getChecked($row->demo, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="demo" value="0" <?php getChecked($row->demo, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->GTW_ACTIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->GTW_IPN;?></label>
        <label class="input state-disabled">
          <input type="text" disabled="disabled" value="<?php echo SITEURL.'/gateways/'.$row->dir.'/ipn.php';?>" readonly>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->GTW_HLP;?></label>
        <a class="viewtip"><i class="large circular inverted info icon question link"></i></a></div>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->GTW_UPDATE;?></button>
    <a href="index.php?do=gateways" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processGateway" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<div id="showhelp" style="display:none"><?php echo cleanOut($row->info);?></div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$('a.viewtip').on('click', function () {
		var text = $("#showhelp").html();
		new Messi(text, {
			title: "<?php echo $row->displayname;?>"
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php default: ?>
<?php $gaterow = $content->getGateways();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->GTW_INFO2;?></div>
<div class="wojo basic segment">
  <div class="header"><span><?php echo Lang::$word->GTW_SUB2;?></span> </div>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->GTW_NAME;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$gaterow):?>
      <tr>
        <td colspan="2"><?php echo Filter::msgSingleError(Lang::$word->GTW_NOGATE);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($gaterow as $row):?>
      <tr>
        <td><?php echo $row->displayname;?></td>
        <td><a href="index.php?do=gateways&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>
