<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Content::pTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->PAG_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->PAG_SUB1;?> / <?php echo $row->title;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="title" value="<?php echo $row->title;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_SLUG;?></label>
        <label class="input">
          <input type="text" name="slug" value="<?php echo $row->slug;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label>Page Template</label>
        <select name="template">
          <option value="">Default</option>
          <?php $clist = $content->getPageTemplates();?>
          <?php if($clist):?>
          <?php foreach($clist as $crow):?>
          <?php $sel = ($crow->slug == $row->template) ? " selected=\"selected\"" : "" ?>
          <option value="<?php echo $crow->slug;?>"<?php echo $sel;?>><?php echo $crow->name;?></option>
          <?php endforeach;?>
          <?php unset($crow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" name="created" data-datepicker="true" data-value="<?php echo $row->created;?>" value="<?php echo $row->created;?>">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_HOME;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="home_page" value="1" <?php getChecked($row->home_page, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="home_page" value="0" <?php getChecked($row->home_page, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label>Show Breadcrumb</label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="breadcrumb" value="1" <?php echo ($row->breadcrumb == 1) ? 'checked="checked"' : ''; ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="breadcrumb" value="0" <?php echo ($row->breadcrumb == 0) ? 'checked="checked"' : ''; ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>


      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_PUB;?></label>
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
    <div class="field">
      <textarea class="bodypost" name="body"><?php echo $row->body;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->PAG_UPDATE;?></button>
    <a href="index.php?do=pages" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processPage" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case "add":?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->PAG_INFO2;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->PAG_SUB2;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="title" placeholder="<?php echo Lang::$word->PAG_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_PUB;?></label>
        <label class="input">
          <input type="text" name="slug" placeholder="<?php echo Lang::$word->PAG_SLUG;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label>Page Template</label>
        <select name="template">
          <option value="">Default</option>
          <?php $clist = $content->getPageTemplates();?>
          <?php if($clist):?>
          <?php foreach($clist as $crow):?>
          <option value="<?php echo $crow->slug;?>"><?php echo $crow->name;?></option>
          <?php endforeach;?>
          <?php unset($crow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input"><i class="icon-append icon-calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="created">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_HOME;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="home_page" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="home_page" type="radio" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label>Show Breadcrumb</label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="breadcrumb" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="breadcrumb" value="0">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_PUB;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->PAG_ADD;?></button>
    <a href="index.php?do=pages" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processPage" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $pagerow = $content->getPages();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->PAG_INFO;?></div>
<div class="wojo basic segment">
  <div class="header"><a class="wojo button push-right" href="index.php?do=pages&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->PAG_ADD;?></a><span><?php echo Lang::$word->PAG_SUB;?></span> </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->PAG_NAME;?></th>
        <th data-sort="string">Page Template</th>
        <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$pagerow):?>
      <tr>
        <td colspan="3"><?php echo Filter::msgSingleAlert(Lang::$word->PAG_NOPAGE);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($pagerow as $row):?>
      <tr>
        <td><?php echo $row->title;?></td>
        <td><?php echo $row->page_template;?></td>
        <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
        <td><a href="index.php?do=pages&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->PAG_DELETE;?>" data-option="deletePage" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="circular danger inverted remove icon link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<div class="wojo divider"></div>
<div class="two columns horizontal-gutters">
  <div class="row"> <span class="wojo label"><?php echo Lang::$word->TOTAL . ': ' . $pager->items_total;?> / <?php echo Lang::$word->CURPAGE . ': ' . $pager->current_page . ' ' . Lang::$word->OF . ' ' . $pager->num_pages;?></span> </div>
  <div class="row">
    <div class="push-right"><?php echo $pager->display_pages();?></div>
  </div>
</div>
<?php break;?>
<?php endswitch;?>
