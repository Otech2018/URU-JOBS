<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Jobs::sTable, Filter::$id);?>

<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label">Editing Skill / <?php echo $row->name;?></div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label>Name</label>
          <label class="input"> <i class="icon-append icon-asterisk"></i>
            <input type="text" name="name" value="<?php echo $row->name;?>">
          </label>
        </div>
        <div class="field">
          <label>Slug</label>
          <label class="input">
            <input type="text" name="slug" value="<?php echo $row->slug;?>">
          </label>
        </div>
        <div class="field">
          <label>Skill Published</label>
          <div class="inline-group">
            <label class="radio">
              <input name="active" type="radio" value="1" <?php getChecked($row->active, 1); ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="active" type="radio" value="0" <?php getChecked($row->active, 0); ?>>
              <i></i><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>
        <button type="button" name="doJobSkill" class="wojo button">Update Skill</button>
        <a href="index.php?do=job-skills" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
        <input name="processJobSkill" type="hidden" value="1">
        <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div id="menusort"> <?php echo $jobs->getJobSortSkillList();?></div>
  </div>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>

<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label">Adding Skill</div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label>Name</label>
          <label class="input"> <i class="icon-append icon-asterisk"></i>
            <input type="text" name="name" placeholder="Skill Name">
          </label>
        </div>
        <div class="field">
          <label>Slug</label>
          <label class="input">
            <input type="text" name="slug" placeholder="Skill Slug">
          </label>
        </div>
        <div class="field">
          <label>Skill Published</label>
          <div class="inline-group">
            <label class="radio">
              <input name="active" type="radio" value="1" checked="checked">
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="active" type="radio" value="0" >
              <i></i><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>
        <button type="button" name="doJobSkill" class="wojo button">Add Skill</button>
        <input name="processJobSkill" type="hidden" value="1">
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
	<div id="menusort"> <?php echo $jobs->getJobSortSkillList();?></div>
  </div>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php endswitch;?>
<script type="text/javascript"> 
// <![CDATA[
function loadList() {
	$.ajax({
		type: 'post',
		url: "controller.php",
		data: 'getjobskills=1',
		cache: false,
		success: function (html) {
			$("div#menusort").html(html);
		}
	});
}
$(document).ready(function () {
    $("button[name='doJobSkill']").click(function () {
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
                url: "controller.php?sortjobskill",
                data: serialized,
                success: function (msg) {}
            });
        }
    });
	
});
// ]]>
</script>