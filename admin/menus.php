<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Content::muTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->MNU_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label"><?php echo Lang::$word->MNU_SUB1;?> / <?php echo $row->name;?></div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label><?php echo Lang::$word->MNU_NAME;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="name" value="<?php echo $row->name;?>">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->MNU_TYPE;?></label>
          <select name="content_type" id="contenttype">
            <option value="">--- <?php echo Lang::$word->MNU_TYPE_S;?> ---</option>
            <?php echo Content::getContentType($row->content_type);?>
          </select>
        </div>
        <div id="webid" style="display:<?php echo ($row->content_type == "web") ? 'block' : 'none';?>">
          <div class="field">
            <label><?php echo Lang::$word->MNU_LINK;?></label>
            <label class="input">
              <input type="text" name="web" value="<?php echo $row->link;?>">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MNU_TARGET;?></label>
            <select name="target" id="target_id">
              <option value="">--- <?php echo Lang::$word->MNU_TARGET;?> ---</option>
              <option value="_blank"<?php if ($row->target == "_blank") echo ' selected="selected"';?>>_Blank</option>
              <option value="_self"<?php if ($row->target == "_self") echo ' selected="selected"';?>>_Self</option>
            </select>
          </div>
        </div>
        <div class="field" id="contentid" style="display:<?php echo ($row->content_type == "page") ? 'block' : 'none';?>">
          <label><?php echo Lang::$word->MNU_LINK;?></label>
          <select name="page_id" id="content_id">
            <?php $clist = $content->getPages();?>
            <?php if($clist):?>
            <?php foreach($clist as $crow):?>
            <?php $sel = ($crow->id == $row->page_id) ? " selected=\"selected\"" : "" ?>
            <option value="<?php echo $crow->id;?>"<?php echo $sel;?>><?php echo $crow->title;?></option>
            <?php endforeach;?>
            <?php unset($crow);?>
            <?php endif;?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->MNU_PUB;?></label>
          <div class="inline-group">
            <label class="radio">
              <input name="active" type="radio" value="1" <?php getChecked($row->active, 1); ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
              <i></i><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>
        <button type="button" name="doMenu" class="wojo button"><?php echo Lang::$word->MNU_UPDATE;?></button>
        <a href="index.php?do=menus" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
        <input name="processMenu" type="hidden" value="1">
        <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div id="menusort"> <?php echo $content->getMenuList();?></div>
    <div class="sholder push-right"><a id="serialize" class="wojo positive right labeled icon button"><i class="icon ok sign"></i><?php echo Lang::$word->MNU_SAVEB;?></a></div>
  </div>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->MNU_INFO;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label"><?php echo Lang::$word->MNU_SUB;?></div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label><?php echo Lang::$word->MNU_NAME;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="name" placeholder="<?php echo Lang::$word->MNU_NAME;?>">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->MNU_TYPE;?></label>
          <select name="content_type" id="contenttype">
            <option value="">--- <?php echo Lang::$word->MNU_TYPE_S;?> ---</option>
            <?php echo Content::getContentType();?>
          </select>
        </div>
        <div id="webid" style="display:none">
          <div class="field">
            <label><?php echo Lang::$word->MNU_LINK;?></label>
            <label class="input">
              <input type="text" name="web" placeholder="<?php echo Lang::$word->MNU_LINK;?>">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MNU_TARGET;?></label>
            <select name="target" id="target_id">
              <option value="">--- <?php echo Lang::$word->MNU_TARGET;?> ---</option>
              <option value="_blank">_Blank</option>
              <option value="_self">_Self</option>
            </select>
          </div>
        </div>
        <div class="field" id="contentid">
          <label><?php echo Lang::$word->MNU_LINK;?></label>
          <select name="page_id" id="content_id">
            <option value="0">--- none ---</option>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->MNU_PUB;?></label>
          <div class="inline-group">
            <label class="radio">
              <input type="radio" name="active"  value="1" checked="checked">
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input type="radio" name="active" value="0">
              <i></i><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>
        <button type="button" name="doMenu" class="wojo button"><?php echo Lang::$word->MNU_ADD;?></button>
        <input name="processMenu" type="hidden" value="1">
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div id="menusort"> <?php echo $content->getMenuList();?></div>
    <div class="sholder push-right"><a id="serialize" class="wojo positive right labeled icon button"><i class="icon ok sign"></i><?php echo Lang::$word->MNU_SAVEB;?></a></div>
  </div>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php endswitch;?>
<script src="assets/js/jquery.tree.js"></script> 
<script type="text/javascript"> 
// <![CDATA[ 
function loadList() {
    $.ajax({
        type: 'post',
        url: "controller.php",
        data: 'getmenus=1',
        cache: false,
        success: function (html) {
            $("#menusort").html(html);
        }
    });
}
$(document).ready(function () {
    $('div#menusort').nestedSortable({
        forcePlaceholderSize: true,
        listType: 'ul',
        handle: 'div',
        helper: 'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div'
    });
	
    $('body').on('click', "#serialize", function () {
        serialized = $('#menusort').nestedSortable('serialize');
        serialized += '&doMenuSort=1';
        $.ajax({
            type: 'post',
            url: "controller.php",
            data: serialized,
            success: function (msg) {
                $("#msgalt").html(msg);
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
            }
        });
    })
	
    $("button[name='doMenu']").click(function () {
        $(".wojo.form").addClass("loading");
        var str = $('#wojo_form').serialize()
        $.ajax({
            type: "post",
            url: "controller.php",
            dataType: 'json',
            data: str,
            cache: false,
            success: function (json) {
                if (json.type == "success") {
                    $(".wojo.form").removeClass("loading");
                    $("#msgholder").html(json.message);
                    loadList();
                } else {
                    $(".wojo.form").removeClass("loading");
                    $("#msgholder").html(json.message);
                }
            }
        });
    });
   $('#contenttype').change(function () {
	   var option = $(this).val();
	   $.ajax({
		   type: 'post',
		   url: "controller.php",
		   dataType: 'json',
		   data: {
			   contenttype: option
		   },
		   success: function (json) {
			   if (json.type == "page") {
				   $("#contentid").show();
				   $("#webid").hide();
				   $('#content_id').html(json.message).trigger("chosen:updated");
			   } else {
				   $("#webid").show();
				   $("#contentid").hide();
				   $(json.message).appendTo('#admin_form');
			   }
		   }
	   });
   });
});
// ]]>
</script>