<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if(Filter::$action == "edit"): ?>
    <?php $row = Core::getRowById(Jobs::lTable, Filter::$id);?>
<?php endif; ?>

<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label"><?php echo (!isset($row)) ? 'Adding Job Location' : 'Editing Job Location / ' . $row->name;?></div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label>Location Name</label>
          <label class="input"> <i class="icon-append icon-asterisk"></i>
            <input type="text" name="name" value="<?php echo (isset($row)) ? $row->name : '';?>">
          </label>
        </div>
        <div class="field">
          <label>Location Parent</label>
          <select name="parent_id">
            <option value="0">Location Parent</option>
            <?php (isset($row)) ? $jobs->getJobLocDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row->parent_id) : $jobs->getJobLocDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;",'');?>
          </select>
        </div>
        <div class="field">
          <label>Location Slug</label>
          <label class="input">
            <input type="text" name="slug" value="<?php echo (isset($row)) ? $row->slug : '';?>">
          </label>
        </div>
        <div class="field">
          <label>Location Published</label>
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
          <button type="button" name="doJobLocation" class="wojo button">Update Location</button>
          <a href="index.php?do=job-locations" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
          <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
        <?php else: ?>
          <button type="button" name="doJobLocation" class="wojo button">Add Location</button>
        <?php endif; ?>
        <input name="processJobLocation" type="hidden" value="1">
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div id="menusort"> <?php echo $jobs->getJobSortLocList();?></div>
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
		data: 'getjoblocations=1',
		cache: false,
		success: function (html) {
			$("div#menusort").html(html);
		}
	});
}
$(document).ready(function () {
    $("button[name='doJobLocation']").click(function () {
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
		serialized += '&doJobLocSort=1';
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
