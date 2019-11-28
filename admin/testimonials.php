<?php
  /**
   * Testimonials Manager
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = $core->getRowById(Content::tesTable, Filter::$id);?>

<div class="wojo form segment">
  <div class="wojo top right attached label">Editing Testimonial / <?php echo $row->name . ' - ' . $row->company;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
        <div class="field">
          <label>Name</label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input type="text" name="name" value="<?php echo $row->name;?>">
          </label>
        </div>
        <div class="field">
          <label>Company</label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input type="text" name="company" value="<?php echo $row->company;?>">
          </label>
        </div>
    </div>
    <div class="field">
      <label>Quote Content</label>
      <textarea class="" name="content"><?php echo $row->content;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button">Update Testimonial</button>
    <a href="index.php?do=testimonials" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processTestimonials" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case "add":?>

<div class="wojo form segment">
  <div class="wojo top right attached label">Adding New Testimonial</div>
  <form id="wojo_form" name="wojo_form" method="post">

    <div class="two fields">
        <div class="field">
          <label>Author Name</label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input type="text" name="name" placeholder="Author Name">
          </label>
        </div>
        <div class="field">
          <label>Company</label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input type="text" name="company" placeholder="Company">
          </label>
        </div>
    </div>

    <div class="field">
      <label>Quote Content</label>
      <textarea class="" name="content"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button">Add Testimonial</button>
    <a href="index.php?do=testimonials" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processTestimonials" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $testimonialrow = $content->getTestimonials();?>

<div class="wojo basic segment">
  <div class="header"><a class="wojo button push-right" href="index.php?do=testimonials&amp;action=add"><i class="icon add"></i> Add Testimonial</a><span>Viewing Testimonials</span> </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th class="disabled"></th>
        <th data-sort="string">Name</th>
        <th data-sort="string">Company</th>
        <th data-sort="string">Content</th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$testimonialrow):?>
      <tr>
        <td colspan="5"><?php echo Filter::msgSingleAlert(Lang::$word->FAQ_NOFAQ);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($testimonialrow as $row):?>
      <tr id="node-<?php echo $row->id;?>">
        <td class="id-handle"><i class="icon reorder"></i></td>
        <td><?php echo $row->name;?></td>
        <td><?php echo $row->company;?></td>
        <td><?php echo cleanSanitize(truncate($row->content,80));?></td>
        <td><a href="index.php?do=testimonials&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo 'Delete Testimonial';?>" data-option="deleteTestimonial" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->name . '/' . $row->company;?>"><i class="circular danger inverted remove icon link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $(".wojo.table tbody").sortable({
        helper: 'clone',
        handle: '.id-handle',
        placeholder: 'placeholder',
        opacity: .6,
        update: function (event, ui) {
            serialized = $(".wojo.table tbody").sortable('serialize');
            $.ajax({
                type: "POST",
                url: "controller.php?sorttestimonial",
                data: serialized,
                success: function (msg) {}
            });
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
