<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  $color = array("5AB1EF","B6A2DE","2EC7C9","D87A80","F5994E");
  $number = array(90,80,70,60,50);
?>

<div class="wojo basic segment">
  <div class="header"><span><?php echo Lang::$word->ADM_TITLE;?></span> </div>
  <div class="wojo segment">
      <div class="four columns small-gutters">
        <div class="row">
          <div class="wojo info message content-center dashboard-title">Job Apllications
            <p class="wojo big font dashboard-count"> <?php echo countEntries(Jobs::aTable);?></p>
          </div>
        </div>
        <div class="row">
          <div class="wojo warning message content-center dashboard-title">Available Jobs
            <p class="wojo big font dashboard-count"> <?php echo countEntries(Jobs::jTable);?></p>
          </div>
        </div>
        <div class="row">
          <div class="wojo success message content-center dashboard-title">Resumes Submitted
            <p class="wojo big font dashboard-count"> <?php echo countEntries(Jobs::rTable, "banned", 0);?></p>
          </div>
        </div>
        <div class="row">
          <div class="wojo negative message content-center dashboard-title">Companies Hiring
            <p class="wojo big font dashboard-count"> <?php echo countEntries(Jobs::comTable);?></p>
          </div>
        </div>
      </div>
  </div>
  <div class="wojo segment">
    <div class="two columns small-gutters">
      <div class="row">
        <div class="wojo huge pointing below label">Job Post vs Job Application Statistics</div>
      </div>
      <div class="row">
        <select name="pid" onchange="getHitsChart(this.value)"  id="rangefilter">
            <option value="0">Choose Period</option>
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
        </select>
      </div>
    </div>
  </div>
  <div id="chart" style="height:300px"></div>
</div>
<script type="text/javascript" src="../assets/jquery.flot.js"></script>
<script type="text/javascript" src="../assets/flot.resize.js"></script>
<script type="text/javascript" src="../assets/excanvas.min.js"></script>
<script type="text/javascript">
// <![CDATA[
function getHitsChart(range) {
    getChart(range);
}
getHitsChart($('#rangefilter').val());

function getChart(range) {
    $.ajax({
        type: 'GET',
        url: 'controller.php',
		data : {
			'getSaleStats' :1,
			'timerange' : range
		},
        dataType: 'json',
        async: false,
        success: function (json) {
            var option = {
                shadowSize: 0,
                lines: {
                    show: true,
                    fill: false,
                    lineWidth: 2
                },
				points: {
                    show: true
                },
                grid: {
                    backgroundColor: '#FFFFFF',
					hoverable: true,
                    clickable: true
                },
                xaxis: {
                    ticks: json.xaxis
                },
                colors: [ "#4cb158", "#5AB1EF"]
            }

            $.plot($('#chart'), [json.jobposts, json.jobapps], option);
        }
    });
}
getChart(0);


function showTooltip(x, y, contents) {
    $('<div id="tooltip" class="popover">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y + -50,
        left: x + -50,
        padding: '5px',
        opacity: 0.90
    }).appendTo("body").fadeIn(200);
}
var previousPoint = null;
$("#chart").on("plothover", function (event, pos, item) {
    if (item) {
        if (previousPoint != item.dataIndex) {
            previousPoint = item.dataIndex;
            $("#tooltip").remove();
            var x = item.datapoint[0],
                y = item.datapoint[1].toFixed(0);
            showTooltip(item.pageX, item.pageY,
            item.series.label + ": " + y);
        }
    } else {
        $("#tooltip").remove();
        previousPoint = null;
    }
});


$(document).ready(function () {
    $('#settingslist2 a').on('click', function () {
        var type = $(this).attr('data-type')
		  getChart(type);
	});
 });

// ]]>
</script>
