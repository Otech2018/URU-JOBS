<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Jobs::jTable, Filter::$id);?>
<div class="wojo form segment">
  <div class="wojo top right attached label">Editing Job Post / <?php echo $row->title;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">

      <div class="field">
          <div class="field">
            <label>Job Title</label>
            <label class="input state-disabled"> <i class="icon-append icon asterisk"></i>
              <input type="text" name="title" value="<?php echo $row->title;?>">
            </label>
          </div>

          <div class="field">
            <label>Location</label>
            <select name="location">
              <option value="0">Location</option>
              <?php $jobs->getJobLocDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row->location);?>
            </select>
          </div>

          <div class="field">
            <label>Job Type</label>
            <select class="select" name="type">
              <option value="">Choose Type</option>
              <?php $jobs->getJobTypeDropList($row->type);?>
            </select>
          </div>

          <div class="field">
            <label>Skills</label>
            <select class="select" name="skills[]" multiple>
                <option value="">Choose Skills</option>
                <?php $jobs->getJobSkillDropList($row->skills);?>
            </select>
          </div>

          <div class="field">
            <label>Salary</label>
            <label class="input state-disabled">
              <input type="text" name="salary" value="<?php echo $row->salary;?>">
            </label>
          </div>
      </div>

      <div class="field">
        <label><?php echo Lang::$word->PRD_CAT;?></label>
        <div class="scrollbox padded">
          <?php $jobs->getJobCatCheckList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;",$row->categories);?>
        </div>
      </div>






    </div>

    <div class="two fields">
        <div class="field">
          <label>Job Description</label>
          <textarea class="bodypost" name="description"><?php echo $row->description;?></textarea>
        </div>

        <div class="field">
          <label>Job Responsibility</label>
          <textarea class="bodypost" name="responsibility"><?php echo $row->responsibility;?></textarea>
        </div>
    </div>

    <div class="two fields">
        <div class="field">
          <label>Work Experience</label>
          <textarea class="bodypost" name="experience"><?php echo $row->experience;?></textarea>
        </div>

        <div class="field">
          <label>Academic Qualifications</label>
          <textarea class="bodypost" name="education"><?php echo $row->education;?></textarea>
        </div>
    </div>

    <div class="two fields">
        <div class="field">
          <label>Other Benefits</label>
          <textarea class="bodypost" name="benefits"><?php echo $row->benefits;?></textarea>
        </div>

        <div class="field">
          <label>Additional Information</label>
          <textarea class="bodypost" name="additional_info"><?php echo $row->additional_info;?></textarea>
        </div>
    </div>

    <div class="two fields">
        <div class="field">
          <label>Apply URL/Email</label>
          <label class="input state-disabled">
            <input type="text" name="apply_url" value="<?php echo $row->apply_url;?>">
          </label>
        </div>

        <div class="field">
          <label>Job Published</label>
          <div class="inline-group">
            <label class="radio">
              <input type="radio" name="status" value="approved" <?php getChecked($row->status, "approved"); ?>>
              <i></i>Approved</label>
            <label class="radio">
              <input type="radio" name="status" value="pending" <?php getChecked($row->status, "pending"); ?>>
              <i></i>Pending</label>
            <label class="radio">
              <input type="radio" name="status" value="declined" <?php getChecked($row->status, "declined"); ?>>
              <i></i>Declined</label>
          </div>
        </div>
    </div>

    <div class="wojo fitted divider"></div>
    <button type="button" name="doUpdateJob" class="wojo button">Update Job Details</button>
    <a href="index.php?do=jobs" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="adminUpdateJob" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $("button[name='doUpdateJob']").click(function () {
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



});
// ]]>
</script>

<?php break;?>
<?php case"details": ?>
<?php $row = Core::getRowById(Jobs::jTable, Filter::$id);?>

<div class="screen-50 tablet-50 phone-100">
<div class="wojo form segment">
  <div class="wojo top left attached label">Showing Job Details</div>

  <h2><?php echo $row->title;?> &nbsp; <?php echo jobStatus($row->status, $row->id);?> &nbsp; <?php echo $jobs->jobType($row->type);?></h2>
  <p>Category: <?php echo $jobs->getCatInfo($row->categories);?></p><br><br>

  <h4 class="margin-bottom-10">Salary : <?php echo $row->salary; ?></h4><br>

  <h4 class="margin-bottom-10">Job Location : <?php echo $jobs->jobLocation($row->location);?></h4><br>

	<h4 class="margin-bottom-10">Description</h4>
	<p class="margin-reset">
		<?php echo cleanOut($row->description); ?>
	</p>
	<br>

	<h4 class="margin-bottom-10">Job Responsibility</h4>
	<p class="margin-reset">
		<?php echo cleanOut($row->responsibility); ?>
	</p>
	<br>

	<h4 class="margin-bottom-10">Work Experience</h4>
	<p class="margin-reset">
		<?php echo cleanOut($row->experience); ?>
	</p>
	<br>

	<h4 class="margin-bottom-10">Academic Qualifications</h4>
	<p class="margin-reset">
		<?php echo cleanOut($row->education); ?>
	</p>
	<br>

	<h4 class="">Other Benefits</h4>
	<p class="margin-reset">
		<?php echo cleanOut($row->benefits); ?>
	</p>
	<br>

	<h4 class="">Additional Information</h4>
	<p class="margin-reset">
		<?php echo cleanOut($row->additional_info); ?>
	</p>
	<br>

	<h4 class="">Apply URL/Email</h4>
	<p class="margin-reset">
		<?php echo $row->apply_url; ?>
	</p>

</div>
</div>
<div class="screen-50 tablet-50 phone-100" style="padding-left: 30px;">
  <h2>Applications</h2>
  <br>
	<div id="menusort">
	   <?php $jobs->getJobApplicationList($row->id);?>
	</div>
</div>
<div id="msgholder"></div>

<script type="text/javascript">
$(document).ready(function () {
    $('a.activate').on('click', function () {
        var jid = $(this).data('id')
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i><p><?php echo 'Are you sure you want to approve this job post?';?><br /><strong><?php echo 'Email notification will be sent as well';?></strong></p></div>";
        new Messi(text, {
            title: "Approve This Job Post",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: "Approve",
                val: 'Y',
				class: 'positive'
            }],
			  callback: function (val) {
				  $.ajax({
					  type: 'post',
					  ataType: 'json',
					  url: "controller.php",
					  data: {
						  approveJob: 1,
						  id: jid,
					  },
					  cache: false,
					  success: function (json) {
						  $.sticky(decodeURIComponent(json.message), {
							  type: json.type,
							  title: json.title
						  });
					  }
				  });
			  }
        });
    });
});
</script>
<?php break;?>
<?php default:?>
<?php  $jobsrow = $jobs->getAllJobs();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?>Here you can manage all job posts. You can activate each job post  by clicking on <i class="icon adjust"></i> icon.</div>
<div class="wojo basic segment">
  <div class="header"><span>Viewing All jobs</span> </div>
  <div class="wojo small segment form">
    <form method="post" id="wojo_form" name="wojo_form">
      <div class="four fields">
        <div class="field">
          <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
            <input name="fromdate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->FROM;?>" id="fromdate" />
          </div>
        </div>
        <div class="field">
          <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
            <input name="enddate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->TO;?>" id="enddate" />
            <a id="doDates" class="wojo icon button"><?php echo Lang::$word->FIND;?></a> </div>
        </div>
        <div class="field">
          <div class="wojo icon input">
            <input type="text" name="usersearchfield" placeholder="Search Job" id="searchfield"  />
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
    </form>
    <div class="content-center"> <?php echo alphaBits('index.php?do=jobs', "letter");?> </div>
  </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string">#</th>
        <th data-sort="string">Title</th>
        <th data-sort="string">Type</th>
        <th data-sort="string">Categories</th>
        <th data-sort="string">Publish Date</th>
        <th data-sort="string">Expire Date</th>
        <th data-sort="int"># Apps</th>
        <th data-sort="int">Featured</th>
        <th data-sort="int">Status</th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$jobsrow):?>
      <tr>
        <td colspan="5"><?php echo Filter::msgSingleAlert(Lang::$word->USR_NOUSER);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($jobsrow as $row):?>
      <tr id="jobid_<?php echo $row->id;?>">
        <td><?php echo $row->id;?></td>
        <td><a href="index.php?do=jobs&amp;action=details&amp;id=<?php echo $row->id;?>"><?php echo $row->name;?></a></td>
        <td><?php echo $jobs->jobType($row->type);?></td>
        <td><?php echo $jobs->getCatInfo($row->categories);?></td>
        <td><?php echo dodate($row->publish_date);?></td>
        <td><?php echo dodate($row->expire_date);?></td>
        <td><span class="wojo count label"><a href="index.php?do=jobs&amp;action=details&amp;id=<?php echo $row->id;?>"><?php echo $row->totalapplications;?></a></span></td>
        <td><?php echo jobFeatured($row->featured, $row->id);?></td>
        <td><?php echo jobStatus($row->status, $row->id);?></td>
        <td>
			<a href="index.php?do=jobs&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a>
			<a class="delete" data-title="Delete Job" data-option="deleteJob" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->name;?>"><i class="circular danger inverted remove icon link"></i></a>
		</td>
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
    /* == User Search == */
    $("#searchfield").on('keyup', function () {
        var srch_string = $(this).val();
        var data_string = 'jobSearch=' + srch_string;
        if (srch_string.length > 2) {
            $.ajax({
                type: "post",
                url: "controller.php",
                data: data_string,
                beforeSend: function () {

                },
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
    $('a.activate').on('click', function () {
        var jid = $(this).data('id')
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i><p><?php echo 'Are you sure you want to approve this job post?';?><br /><strong><?php echo 'Email notification will be sent as well';?></strong></p></div>";
        new Messi(text, {
            title: "Approve This Job Post",
            modal: true,
            closeButton: true,
            buttons: [
                {id: 0, label: 'Approved', val: 'approved', class: 'positive'},
                {id: 1, label: 'Declined', val: 'declined', class: 'negative'}
            ],
			  callback: function (val) {
				  $.ajax({
					  type: 'post',
					  ataType: 'json',
					  url: "controller.php",
					  data: {
						  approveJob: val,
						  id: jid,
					  },
					  cache: false,
					  success: function (json) {
                           var json = JSON.parse(json);
						  $.sticky(decodeURIComponent(json.message), {
							  type: json.type,
							  title: json.title
						  });
                          document.getElementById("jobStatus_" + jid).innerHTML = val;
                          document.getElementById("jobStatus_" + jid).removeAttribute("class");
                          document.getElementById("jobStatus_" + jid).setAttribute("class", 'wojo ' + val + ' label');
					  }
				  });
			  }
        });
    });

    $('a.toggleJobFeatured').on('click', function () {
        var jid = $(this).data('id')
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i><p><?php echo 'Are you sure you want to make/remove featured this job post?';?><br /></p></div>";
        new Messi(text, {
            title: "Make/Remove This Job Post Featured",
            modal: true,
            closeButton: true,
            buttons: [
                {id: 0, label: 'Not Featured', val: 'notfeatured', class: 'negative'},
                {id: 1, label: 'Make Featured', val: 'featured', class: 'positive'}
            ],
			  callback: function (val) {
				  $.ajax({
					  type: 'post',
					  ataType: 'json',
					  url: "controller.php",
					  data: {
						  toggleJobFeatured: val,
						  id: jid,
					  },
					  cache: false,
					  success: function (json) {
                           var json = JSON.parse(json);
						  $.sticky(decodeURIComponent(json.message), {
							  type: json.type,
							  title: json.title
						  });
                          document.getElementById("jobFeatured_" + jid).innerHTML = val;
                          document.getElementById("jobFeatured_" + jid).removeAttribute("class");
                          document.getElementById("jobFeatured_" + jid).setAttribute("class", 'wojo ' + val + ' label');
					  }
				  });
			  }
        });
    });

});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
