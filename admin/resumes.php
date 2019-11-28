<?php
if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');
?>
<?php  $resumesrow = $jobs->getAllResumes();?>
<div class="wojo wojo black message"><i class="icon pin"></i> Here you can manage all of the resumes.</div>
<div class="wojo basic segment">
  <div class="header"><span>Viewing All Resumes</div>
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
            <input type="text" name="usersearchfield" placeholder="<?php echo Lang::$word->USR_SEARCHU;?>" id="searchfield"  />
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
    <div class="content-center"> <?php echo alphaBits('index.php?do=resumes', "letter");?> </div>
  </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string">Fullname</th>
        <th data-sort="string">Title</th>
        <th data-sort="string">City</th>
        <th data-sort="int">Skills</th>
        <th data-sort="int">Featured</th>
        <th data-sort="int">Status</th>
        <th data-sort="int">Created</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$resumesrow):?>
      <tr>
        <td colspan="5"><?php echo Filter::msgSingleAlert(Lang::$word->USR_NOUSER);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($resumesrow as $row):?>
      <tr>
        <td><?php if($row->avatar):?>
          <img src="<?php echo UPLOADURL;?>avatars/<?php echo $row->avatar;?>" alt="<?php echo $row->fullname;?>" class="wojo image avatar"/>
          <?php else:?>
          <img src="<?php echo UPLOADURL;?>avatars/blank.png" alt="<?php echo $row->fullname;?>" class="wojo image avatar"/>
          <?php endif;?>
          <a href="<?php echo SITEURL . '/resume.php?resumeid=' . $row->uid; ?>" target="_blank"><?php echo $row->fullname;?></a></td>
        <td><?php echo $row->title;?></td>
        <td><?php echo $row->city;?></td>
        <td><?php $jobs->getJobSkills($row->skills) ?></td>
        <td><?php echo resumeFeatured($row->featured, $row->uid);?></td>
        <td><?php echo ($row->banned == 1) ? 'Banned' : 'Active';?></td>
        <td><?php echo dodate($row->created);?></td>
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
        var data_string = 'resumeSearch=' + srch_string;
        if (srch_string.length > 3) {
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

    $('a.toggleResumeFeatured').on('click', function () {
        var jid = $(this).data('id')
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i><p><?php echo 'Are you sure you want to make/remove featured this job post?';?><br /></p></div>";
        new Messi(text, {
            title: "Make/Remove This Resume Featured",
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
                          toggleResumeFeatured: val,
                          id: jid,
                      },
                      cache: false,
                      success: function (json) {
                           var json = JSON.parse(json);
                          $.sticky(decodeURIComponent(json.message), {
                              type: json.type,
                              title: json.title
                          });
                          document.getElementById("resumeFeatured_" + jid).innerHTML = val;
                          document.getElementById("resumeFeatured_" + jid).removeAttribute("class");
                          document.getElementById("resumeFeatured_" + jid).setAttribute("class", 'wojo ' + val + ' label');
                      }
                  });
              }
        });
    });





});
// ]]>
</script>
