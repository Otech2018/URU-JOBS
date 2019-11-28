<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Content::cnTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->CNT_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->CNT_SUB1;?> / <?php echo $row->name;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CNT_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="name" value="<?php echo $row->name;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CNT_ABBR;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->abbr;?>" name="abbr">
        </label>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->VAT;?></label>
        <label class="input"><span class="icon-append"><b>%</b></span>
          <input type="text" value="<?php echo $row->vat;?>" name="vat">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_SORT;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->sorting;?>" name="sorting">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PUBLISHED;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" <?php getChecked($row->active, 1);?>>
            <i></i><?php echo Lang::$word->ACTIVE;?></label>
          <label class="radio">
            <input name="active" type="radio" value="0" <?php getChecked($row->active, 0);?>>
            <i></i><?php echo Lang::$word->INACTIVE;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DEFAULT;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="home" type="radio" value="1" <?php getChecked($row->home, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="home" type="radio" value="0" <?php getChecked($row->home, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->CNT_UPDATE;?></button>
    <a href="index.php?do=countries" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processCountry" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $countryrow = $content->getCountryList();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->CNT_INFO;?></div>
<div class="wojo basic segment">
  <div class="header"><span><?php echo Lang::$word->CNT_SUB;?></span> </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->CNT_NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->CNT_ABBR;?></th>
        <th data-sort="int"><?php echo Lang::$word->DEFAULT;?></th>
        <th data-sort="int"><?php echo Lang::$word->PUBLISHED;?></th>
        <th data-sort="int"><?php echo Lang::$word->SLM_SORT;?></th>
        <th data-sort="int"><?php echo Lang::$word->VAT;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$countryrow):?>
      <tr>
        <td colspan="4"><?php echo Filter::msgSingleAlert(Lang::$word->CNT_NOCNT);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($countryrow as $row):?>
      <tr>
        <td><?php echo $row->name;?></td>
        <td><small class="wojo label"><?php echo $row->abbr;;?></small></td>
        <td data-sort-value="<?php echo $row->home;?>"><?php echo isActive($row->home);?></td>
        <td data-sort-value="<?php echo $row->active;?>"><?php echo isActive($row->active);?></td>
        <td><?php echo $row->sorting;?></td>
        <td data-editable="true" data-set='{"type": "cntvat", "id": <?php echo $row->id;?>,"key":"vat", "path":""}'><?php echo $row->vat;?></td>
        <td><a href="index.php?do=countries&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->CNT_DELETE;?>" data-option="deleteCountry" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->name;?>"><i class="circular danger inverted remove icon link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>