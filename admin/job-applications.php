<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = $core->getRowById(Products::pTable, Filter::$id);?>
<?php $filerow = $content->getFileTree();?>
<?php $cidrow = $content->fetchProductCategories(Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->PRD_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->PRD_SUB1;?> / <?php echo $row->title;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="title" value="<?php echo $row->title;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_SLUG;?></label>
        <label class="input">
          <input type="text" name="slug" value="<?php echo $row->slug;?>" >
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_CAT;?></label>
        <div class="scrollbox padded">
          <?php $content->getCatCheckList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $cidrow);?>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_PRICE;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>
          <input type="text" name="price" value="<?php echo $row->price;?>">
        </label>
        <div class="small-top-space">
          <label><?php echo Lang::$word->PRD_EXP;?></label>
          <label class="input"><i class="icon-append icon-asterisk"></i>
            <?php if(substr_count($row->expiry,'D')==1):?>
            <input type="text" name="expiry" value="<?php echo substr($row->expiry,1);?>">
            <?php else:?>
            <input type="text" name="expiry" class="inputbox" value="<?php echo $row->expiry;?>">
            <?php endif;?>
          </label>
        </div>
        <div class="small-top-space">
          <label><?php echo Lang::$word->PRD_EXP;?></label>
          <div class="inline-group">
            <?php if(substr_count($row->expiry,'D')==1):?>
            <label class="radio">
              <input type="radio" name="expiry_type" value="days" checked="checked"/>
              <i></i><?php echo Lang::$word->PRD_DAYS;?></label>
            <label class="radio">
              <input type="radio" name="expiry_type" value="downloads" />
              <i></i><?php echo Lang::$word->PRD_DOWNS;?></label>
            <?php else:?>
            <label class="radio">
              <input type="radio" name="expiry_type" value="days"/>
              <i></i><?php echo Lang::$word->PRD_DAYS;?></label>
            <label class="radio">
              <input type="radio" name="expiry_type" value="downloads" checked="checked" />
              <i></i><?php echo Lang::$word->PRD_DOWNS;?></label>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PRD_SHORT;?></label>
      <label class="textarea">
        <textarea name="description"><?php echo $row->description;?></textarea>
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PRD_FILE;?></label>
      <?php if($filerow):?>
      <input id="filter" type="text" placeholder="Filter" class="custom">
      <div id="fsearch" class="scrollbox padded custom">
        <div class="two columns">
          <?php $class = 'odd'; ?>
          <?php foreach ($filerow as $frow) : ?>
          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          <div class="row <?php echo $class; ?><?php if ($frow->id == $row->file_id) echo " active";?>">
            <label class="radio">
              <?php if ($frow->id == $row->file_id): ?>
              <input type="radio" name="file_id" value="<?php echo $frow->id; ?>" checked="checked" />
              <i></i><?php echo $frow->alias.' <small>('.getSize($frow->filesize).' - '.Filter::dodate("long_date", $frow->cdate).')</small>';?>
              <?php  else: ?>
              <input type="radio" name="file_id" value="<?php echo $frow->id; ?>" />
              <i></i><?php echo $frow->alias.' <small>('.getSize($frow->filesize).' - '.Filter::dodate("long_date", $frow->cdate).')</small>';?>
              <?php endif; ?>
            </label>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_IMG;?></label>
        <label class="input">
          <input type="file" name="thumb" id="thumbid" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_IMG;?></label>
        <div class="wojo avatar image">
          <?php if($row->thumb):?>
          <img src="<?php echo UPLOADURL;?>prod_images/<?php echo $row->thumb;?>" alt="<?php echo $row->thumb;?>">
          <?php else:?>
          <img src="<?php echo UPLOADURL;?>prod_images/blank.png" alt="<?php echo $row->thumb;?>">
          <?php endif;?>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_AUD;?></label>
        <label class="input">
          <input type="file" name="audio" id="audioid" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_AUD;?></label>
        <?php if($row->audio):?>
        <audio controls>
          <source src="<?php echo UPLOADURL;?>prod_audio/<?php echo $row->audio;?>">
        </audio>
        <?php endif;?>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_YOUTUBE;?></label>
        <label class="input">
          <input type="text" name="youtube" value="<?php echo $row->youtube;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_LIVE;?></label>
        <label class="input">
          <input type="text" name="preview" value="<?php echo $row->preview;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_AFF;?></label>
        <label class="input"><i class="icon-append icon-asterisk"></i>
          <input type="text" name="affiliate" value="<?php echo $row->affiliate;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_TAGS;?></label>
        <label class="input"><i class="icon-append icon-asterisk"></i>
          <input type="text" name="tags" value="<?php echo $item->getTags();?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CAT_METAK;?></label>
        <label class="textarea">
          <textarea name="metakeys"><?php echo $row->metakeys;?></textarea>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CAT_METAD;?></label>
        <label class="textarea">
          <textarea name="metadesc"><?php echo $row->metadesc;?></textarea>
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <textarea class="bodypost" name="body"><?php echo $row->body;?></textarea>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PRD_PUB;?></label>
      <div class="inline-group">
        <label class="radio">
          <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->PRD_UPDATE;?></button>
    <a href="index.php?do=products" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProduct" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$("#filter").on("keyup", function() {
		var filter = $(this).val(),
			count = 0;
		$("#fsearch .row").each(function() {
			if ($(this).text().search(new RegExp(filter, "i")) < 0) {
				$(this).fadeOut();
			} else {
				$(this).show();
				count++;
			}
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<?php $filerow = $content->getFileTree();?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->PRD_INFO2;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->PRD_SUB2;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="title" placeholder="<?php echo Lang::$word->PRD_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_SLUG;?></label>
        <label class="input">
          <input type="text" name="slug" placeholder="<?php echo Lang::$word->PRD_SLUG;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_CAT;?></label>
        <div class="scrollbox padded">
          <?php $content->getCatCheckList(0, 0,"|&nbsp;&nbsp;&nbsp;&nbsp;");?>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_PRICE;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>
          <input type="text" name="price" placeholder="<?php echo Lang::$word->PRD_PRICE;?>">
        </label>
        <div class="small-top-space">
          <label><?php echo Lang::$word->PRD_EXP;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input type="text" name="expiry"  placeholder="<?php echo Lang::$word->PRD_EXP;?>">
          </label>
        </div>
        <div class="small-top-space">
          <label><?php echo Lang::$word->PRD_EXP;?></label>
          <div class="inline-group">
            <label class="radio">
              <input type="radio" name="expiry_type" value="days" checked="checked"/>
              <i></i><?php echo Lang::$word->PRD_DAYS;?></label>
            <label class="radio">
              <input type="radio" name="expiry_type" value="downloads" />
              <i></i><?php echo Lang::$word->PRD_DOWNS;?></label>
          </div>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PRD_SHORT;?></label>
      <textarea name="description" placeholder="<?php echo Lang::$word->PRD_SHORT;?>"></textarea>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PRD_FILE;?></label>
      <?php if($filerow):?>
      <input id="filter" type="text" placeholder="Filter" class="custom">
      <div id="fsearch" class="scrollbox padded custom">
        <div class="two columns">
          <?php $class = 'odd'; ?>
          <?php foreach ($filerow as $frow) : ?>
          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          <div class="row <?php echo $class; ?>">
            <label class="radio">
              <input type="radio" name="file_id" value="<?php echo $frow->id; ?>" />
              <i></i><?php echo $frow->alias.' <small>(' . getSize($frow->filesize) . ' - ' . Filter::dodate("long_date", $frow->cdate) . ')</small>';?> </label>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_IMG;?></label>
        <label class="input">
          <input name="thumb" type="file" id="thumbid" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_AUD;?></label>
        <label class="input">
          <input name="audio" type="file" id="audioid" class="filefield">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_YOUTUBE;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="youtube" placeholder="<?php echo Lang::$word->PRD_YOUTUBE;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_LIVE;?></label>
        <label class="input">
          <input type="text" name="preview" placeholder="<?php echo Lang::$word->PRD_LIVE;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PRD_AFF;?></label>
        <label class="input">
          <input type="text" name="affiliate" placeholder="<?php echo Lang::$word->PRD_AFF;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PRD_TAGS;?></label>
        <label class="input">
          <input type="text" name="tags" placeholder="<?php echo Lang::$word->PRD_TAGS;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CAT_METAK;?></label>
        <label class="textarea">
          <textarea name="metakeys" placeholder="<?php echo Lang::$word->CAT_METAK;?>" cols="60" rows="4"></textarea>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CAT_METAD;?></label>
        <label class="textarea">
          <textarea name="metadesc" placeholder="<?php echo Lang::$word->CAT_METAD;?>" cols="60" rows="4"></textarea>
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PRD_PUB;?></label>
      <div class="inline-group">
        <label class="radio">
          <input type="radio" name="active" value="1" checked="checked">
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="active" value="0" >
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->PRD_ADD;?></button>
    <a href="index.php?do=products" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProduct" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$("#filter").on("keyup", function() {
		var filter = $(this).val(),
			count = 0;
		$("#fsearch .row").each(function() {
			if ($(this).text().search(new RegExp(filter, "i")) < 0) {
				$(this).fadeOut();
			} else {
				$(this).show();
				count++;
			}
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php case"gallery": ?>
<?php include("gallery.php");?>
<?php break;?>
<?php default: ?>
<?php $itemsrow = $item->getProducts();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->PRD_INFO;?></div>
<div class="wojo basic segment">
  <div class="header"><a class="wojo button push-right" href="index.php?do=products&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->PRD_ADD;?></a><span><?php echo Lang::$word->PRD_SUB;?></span> </div>
  <div class="wojo small segment form">
    <div class="two fields">
      <div class="field">
        <div class="wojo icon input">
          <input type="text" name="serachprod" placeholder="<?php echo Lang::$word->PRD_SEARCH;?>" id="searchfield"  />
          <i class="search icon"></i>
          <div id="suggestions"> </div>
        </div>
      </div>
      <div class="field">
        <div class="two fields">
          <div class="field"> <?php echo $pager->items_per_page();?> </div>
          <div class="field"> <?php echo $pager->jump_menu();?> </div>
        </div>
      </div>
    </div>
    <div class="content-center"> <?php echo alphaBits('index.php?do=products', "letter");?> </div>
  </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->PRD_NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->PRD_CAT;?></th>
        <th data-sort="int"><?php echo Lang::$word->PRD_PRICE;?></th>
        <th data-sort="int"><?php echo Lang::$word->PRD_SALES;?></th>
        <th data-sort="int"><?php echo Lang::$word->PRD_EXP;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$itemsrow):?>
      <tr>
        <td colspan="6"><?php echo Filter::msgSingleAlert(Lang::$word->PRD_NOPROD);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($itemsrow as $row):?>
      <tr>
        <td><?php if($row->thumb):?>
          <img src="<?php echo UPLOADURL;?>prod_images/<?php echo $row->thumb;?>" alt="<?php echo $row->title;?>" class="wojo avatar image"/>
          <?php else:?>
          <img src="<?php echo UPLOADURL;?>prod_images/blank.png" alt="<?php echo $row->title;?>" class="wojo avatar image"/>
          <?php endif;?>
          <?php echo $row->title;?></td>
        <td><a href="index.php?do=categories&amp;action=edit&amp;id=<?php echo $row->cid;?>"><?php echo $row->name;?></a></td>
        <td><?php echo $core->formatMoney($row->price);?></td>
        <td><span class="wojo black label"><?php echo $row->sales;?></span></td>
        <td><?php echo (substr_count($row->expiry,'D') == 1) ? '<i class="icon calendar" data-content="' . Lang::$word->PRD_DAYS. '"></i> ' . substr($row->expiry,1) : '<i class="icon cloud download" data-content="' . Lang::$word->PRD_DOWNS. '"></i> ' . $row->expiry;?></td>
        <td><a href="index.php?do=comments&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->CMT_TITLE;?>"><i class="circular inverted warning icon comment link"></i></a> <a href="index.php?do=products&amp;action=gallery&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->GAL_TITLE;?>"><i class="circular inverted info icon photo link"></i></a> <a href="index.php?do=products&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->PRD_DELETE;?>" data-option="deleteProduct" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="circular danger inverted remove icon link"></i></a></td>
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
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $("#searchfield").on('keyup', function () {
        var srch_string = $(this).val();
        var data_string = 'productSearch=' + srch_string;
        if (srch_string.length > 4) {
            $.ajax({
                type: "post",
                url: "controller.php",
                data: data_string,
                success: function (res) {
                    $('#suggestions').html(res).show();
                    $("input").blur(function () {
                        $('#suggestions').fadeOut();
                    });
                }
            });
        }
        return false;
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>