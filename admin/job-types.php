<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if(Filter::$action == "edit"): ?>
    <?php $row = Core::getRowById(Jobs::tTable, Filter::$id);?>
<?php endif; ?>

<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label"><?php echo (!isset($row)) ? 'Adding Job Type' : 'Editing Job Type / ' . $row->name;?></div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label>Job Type Name</label>
          <label class="input"> <i class="icon-append icon-asterisk"></i>
            <input type="text" name="name" value="<?php echo (isset($row)) ? $row->name : '';?>">
          </label>
        </div>
        <div class="field">
          <label>Job Type Slug</label>
          <label class="input">
            <input type="text" name="slug" value="<?php echo (isset($row)) ? $row->slug : '';?>">
          </label>
        </div>
		<div class="field">
          <label>Type Color</label>
          <label class="input">
            <input type="text" name="color" value="<?php echo (isset($row)) ? $row->color : '';?>">
          </label>
        </div>
        <div class="field">
          <label>Type Published</label>
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
          <button type="button" name="doJobType" class="wojo button">Update Job Type</button>
          <a href="index.php?do=job-types" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
          <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
        <?php else: ?>
          <button type="button" name="doJobType" class="wojo button">Add Job Type</button>
        <?php endif; ?>
        <input name="processJobType" type="hidden" value="1">
        
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div id="menusort"> <?php echo $jobs->getJobSortTypeList();?></div>
  </div>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
function loadList() {
	$.ajax({
		type: 'post',
		url: "controller.php",
		data: 'getjobtypes=1',
		cache: false,
		success: function (html) {
			$("div#menusort").html(html);
		}
	});
}
$(document).ready(function () {
    $("button[name='doJobType']").click(function () {
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

	$(".wojo.table tbody").sortable({
        helper: 'clone',
        handle: '.id-handle',
        placeholder: 'placeholder',
        opacity: .6,
        update: function (event, ui) {
            serialized = $(".wojo.table tbody").sortable('serialize');
            $.ajax({
                type: "POST",
                url: "controller.php?sortjobtype",
                data: serialized,
                success: function (msg) {}
            });
        }
    });

});
// ]]>
</script>
