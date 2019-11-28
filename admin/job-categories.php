<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>

<?php if(Filter::$action == "edit"): ?>
    <?php $row = Core::getRowById(Jobs::cTable, Filter::$id);?>
<?php endif; ?>

<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->CAT_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label"><?php echo (!isset($row)) ? 'Adding Job Category' : 'Editing Job Category / ' . $row->name;?></div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label><?php echo Lang::$word->CAT_NAME;?></label>
          <label class="input"> <i class="icon-append icon-asterisk"></i>
            <input type="text" name="name" value="<?php echo (isset($row)) ? $row->name : '';?>">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CAT_PARENT;?></label>
          <select name="parent_id">
            <option value="0">Top Level</option>
            <?php (isset($row)) ? $jobs->getJobCatDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row->parent_id) : $jobs->getJobCatDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;",'');?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CAT_SLUG;?></label>
          <label class="input">
            <input type="text" name="slug" value="<?php echo (isset($row)) ? $row->slug : '';?>">
          </label>
        </div>

        <div class="field">
          <label>Category Icon</label>
          <?php $icons = $params['icons'];
              $iconlist = '<option value="">Choose Icon</option>';
              foreach ($icons as $key => $value) {
                $selected = ( isset($row) && $row->icon == $key ) ? ' selected="selected"' : '';
                $iconlist .= '<option value="' . $key . '" ' . $selected . '><i class="' . $key . ' icon"></i> ' . $value . '</option>';
              }
          ?>
          <select name="icon">
            <?php echo $iconlist; ?>
          </select>
        </div>

        <div class="field">
          <label><?php echo Lang::$word->CAT_PUB;?></label>
          <div class="inline-group">
            <label class="radio">
              <input name="active" type="radio" value="1" <?php echo (isset($row)) ? getChecked($row->active, 1) : 'checked="checked"'; ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="active" type="radio" value="0" <?php echo (isset($row)) ? getChecked($row->active, 0) : ''; ?>>
              <i></i><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>

        <?php if(Filter::$action == "edit"): ?>
          <button type="button" name="doJobCategory" class="wojo button">Update Category</button>
          <a href="index.php?do=job-categories" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
          <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
        <?php else: ?>
          <button type="button" name="doJobCategory" class="wojo button">Add Category</button>
        <?php endif; ?>
        <input name="processJobCategory" type="hidden" value="1">

      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div id="menusort"> <?php echo $jobs->getJobSortCatList();?></div>
    <div class="sholder push-right"><a id="serialize" class="wojo positive right labeled icon button"><i class="icon ok sign"></i><?php echo Lang::$word->MNU_SAVEB;?></a></div>
  </div>
</div>
<div id="msgholder"></div>

<script src="assets/js/jquery.tree.js"></script>
<script type="text/javascript">
// <![CDATA[
function loadList() {
	$.ajax({
		type: 'post',
		url: "controller.php",
		data: 'getjobcategories=1',
		cache: false,
		success: function (html) {
			$("div#menusort").html(html);
		}
	});
}
$(document).ready(function () {
    $("button[name='doJobCategory']").click(function () {
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
	// Short Categories
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

	//Save categoriy position
	$('body').on('click', "#serialize", function () {
		serialized = $('#menusort').nestedSortable('serialize');
		serialized += '&doJobCatSort=1';
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
});
// ]]>
</script>
