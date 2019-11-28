<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if(Filter::$action == "edit" &&  Filter::$id > 0) { ?>
    <?php $row = Core::getRowById(Jobs::pTable, Filter::$id);?>
<?php } ?>

<div id="msgalt"></div>
<div class="columns small-gutters">
  <div class="screen-50 tablet-50 phone-100">
    <div class="wojo form segment">
      <div class="wojo top left attached label">Adding Package</div>
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="field">
          <label>Package Name</label>
          <label class="input"> <i class="icon-append icon-asterisk"></i>
            <input type="text" name="name" placeholder="Package Name" value="<?php echo (Filter::$action == "edit" &&  Filter::$id > 0) ? $row->name : ''; ?>">
          </label>
        </div>
        <div class="field">
          <label>Package Slug</label>
          <label class="input">
            <input type="text" name="slug" placeholder="Package Slug" value="<?php echo (Filter::$action == "edit" &&  Filter::$id > 0) ? $row->slug : ''; ?>">
          </label>
        </div>
        <div class="field">
          <label>Package Features</label>
          <textarea class="bodypost" name="features"><?php echo (Filter::$action == "edit" &&  Filter::$id > 0) ? $row->features : ''; ?></textarea>
        </div>
        <div class="field">
          <label>Featured Package</label>
          <div class="inline-group">
            <label class="radio">
              <input name="featured" type="radio" value="1" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->featured == 1) ? 'checked="checked"' : ''; ?>>
              <i></i>Yes</label>
            <label class="radio">
              <input name="featured" type="radio" value="0" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->featured == 0) ? 'checked="checked"' : ''; ?>>
              <i></i>No</label>
          </div>
        </div>
        <div class="field">
          <label>Package Price</label>
          <label class="input">
            <input type="text" name="price" placeholder="Package Price" value="<?php echo (Filter::$action == "edit" &&  Filter::$id > 0) ? $row->price : ''; ?>">
          </label>
        </div>
        <div class="field">
          <label>Package Billing</label>
          <div class="inline-group">
            <label class="radio">
              <input name="billing" type="radio" value="onetime" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->billing == 'onetime') ? 'checked="checked"' : ''; ?>>
              <i></i>One Time</label>
            <label class="radio">
              <input name="billing" type="radio" value="weekly" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->billing == 'weekly') ? 'checked="checked"' : ''; ?>>
              <i></i>Weekly</label>
            <label class="radio">
              <input name="billing" type="radio" value="monthly" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->billing == 'monthly') ? 'checked="checked"' : ''; ?>>
              <i></i>Monthly</label>
            <label class="radio">
              <input name="billing" type="radio" value="yearly" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->billing == 'yearly') ? 'checked="checked"' : ''; ?>>
              <i></i>Yearly</label>
          </div>
        </div>
        <div class="field">
          <label>Post Limit</label>
          <label class="input">
            <input type="text" name="limit" placeholder="Post Limit" value="<?php echo (Filter::$action == "edit" &&  Filter::$id > 0) ? $row->limit : ''; ?>">
          </label>
        </div>
        <div class="field">
          <label>Duration</label>
          <label class="input">
            <input type="text" name="duration" placeholder="Duration" value="<?php echo (Filter::$action == "edit" &&  Filter::$id > 0) ? $row->duration : ''; ?>">
          </label>
        </div>
        <div class="field">
          <label>Active Package</label>
          <div class="inline-group">
            <label class="radio">
              <input name="active" type="radio" value="1" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->active == 1) ? 'checked="checked"' : ''; ?>>
              <i></i>Yes</label>
            <label class="radio">
              <input name="active" type="radio" value="0" <?php echo (Filter::$action == "edit" &&  Filter::$id > 0 && $row->active == 0) ? 'checked="checked"' : ''; ?>>
              <i></i>No</label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>

        <input name="processPackage" type="hidden" value="1">

        <?php if(Filter::$action == "edit" &&  Filter::$id > 0) { ?>
            <button type="button" name="doPackage" class="wojo button">Update Package</button>
            <a href="index.php?do=packages" class="wojo basic button">Cancel</a>
            <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
        <?php } else { ?>
            <button type="button" name="doPackage" class="wojo button">Add Package</button>
        <?php } ?>

      </form>
    </div>
  </div>

  <div class="screen-50 tablet-50 phone-100">
	   <div id="menusort"> <?php echo $jobs->getPackageSortList();?></div>
  </div>

</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
function loadList() {
	$.ajax({
		type: 'post',
		url: "controller.php",
		data: 'getpackages=1',
		cache: false,
		success: function (html) {
			$("div#menusort").html(html);
		}
	});
}
$(document).ready(function () {
    $("button[name='doPackage']").click(function () {
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
                url: "controller.php?sortpackage",
                data: serialized,
                success: function (msg) {}
            });
        }
    });

});
// ]]>
</script>
