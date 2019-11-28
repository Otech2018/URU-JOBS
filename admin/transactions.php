<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "salesyear": ?>
<?php $reports = $item->getYearlyStats();?>
<?php $row = $item->getYearlySummary();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->TXN_INFO1;?></div>
<div class="wojo basic segment">
  <div class="header">
    <div data-select-range="true" class="wojo selection dropdown push-right">
      <div class="text"><?php echo Lang::$word->ADM_RANGE;?></div>
      <i class="dropdown icon"></i>
      <div class="menu">
        <div class="item" data-value="day"><?php echo Lang::$word->TXN_TODAY;?></div>
        <div class="item" data-value="week"><?php echo Lang::$word->TXN_WEEK;?></div>
        <div class="item" data-value="month"><?php echo Lang::$word->TXN_MONTH;?></div>
        <div class="item" data-value="year"><?php echo Lang::$word->TXN_YEAR;?></div>
      </div>
      <input name="range" type="hidden" value="">
    </div>
    <span><?php echo Lang::$word->TXN_SUB1;?></span> </div>
  <div class="wojo segment">
    <div id="chart" style="height:300px"></div>
  </div>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->TXN_MYEAR;?></th>
        <th>#<?php echo Lang::$word->PRD_SALES;?></th>
        <th><?php echo Lang::$word->TXN_TOTALR;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$reports):?>
      <tr>
        <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->TXN_NOTRANS1);?></td>
      </tr>
      <?php else:?>
      <?php foreach($reports as $report):?>
      <tr>
        <td><?php echo strftime('%B', strtotime(date("M", mktime(0, 0, 0, $report->month, 10)), false)).' / '.$core->year;?></td>
        <td><?php echo $report->total;?></td>
        <td><?php echo $core->formatMoney($report->totalprice);?></td>
      </tr>
      <?php endforeach ?>
      <?php unset($report);?>
      <tr class="warning">
        <td><strong><?php echo Lang::$word->TXN_TOTALY;?></strong></td>
        <td><?php echo $row->total;?></td>
        <td><?php echo $core->formatMoney($row->totalprice);?></td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>
</div>
<script type="text/javascript" src="../assets/jquery.flot.js"></script>
<script type="text/javascript" src="../assets/flot.resize.js"></script>
<script type="text/javascript" src="../assets/excanvas.min.js"></script>
<script type="text/javascript">
// <![CDATA[
function getSalesChart(range) {
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
                    fill: true,
                    lineWidth: 1
                },
				points: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: true,
					borderColor: {
						top: "rgba(255,255,255,0.2)",
						left: "rgba(255,255,255,0.2)"
					}
				},
                xaxis: {
                    ticks: json.xaxis
                }
            }
            $.plot($('#chart'), [json.order], option);
        }
    });
}
getSalesChart('year');
$(document).ready(function () {
    $("[data-select-range]").on('click', '.item', function () {
        v = $("input[name=range]").val();
        getSalesChart(v)
    });
});
// ]]>
</script>
<?php break;?>
<?php case "edit":?>
<?php $row = (Filter::$id) ? $item->getPaymentRecord() : Filter::error("You have selected an Invalid Id", "Products::getPaymentRecord()");;?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->TXN_INFO3;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->TXN_SUB3;?> / <?php echo $row->title;?></div>
  <table class="wojo basic table">
    <tbody>
      <tr>
        <td><?php echo Lang::$word->TXN_TRANS_D;?>: </td>
        <td><?php echo Filter::dodate("long_date", $row->created);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->USERNAME;?>: </td>
        <td><a href="index.php?do=users&amp;action=edit&amp;id=<?php echo $row->uid;?>"><?php echo $row->username;?></a></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->PRD_NAME;?>:</td>
        <td><a href="index.php?do=products&amp;action=edit&amp;id=<?php echo $row->pid;?>"><?php echo $row->title;?></a></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->TXN_BEMAIL;?>: </td>
        <td><a href="index.php?do=newsletter&amp;emailid=<?php echo urlencode($row->payer_email);?>"><?php echo $row->payer_email;?></a></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->TXN_AMT;?>: </td>
        <td><?php echo $core->formatMoney($row->price);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->CONF_CURRENCY;?>: </td>
        <td><?php echo $row->currency;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->TXN_PPM;?>: </td>
        <td><?php echo $row->pp;?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->TXN_PAY_S;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="status" value="1" <?php getChecked($row->status, 1); ?>>
            <i></i><?php echo Lang::$word->COMPLETED;?></label>
          <label class="radio">
            <input type="radio" name="status" value="0" <?php getChecked($row->status, 0); ?>>
            <i></i><?php echo Lang::$word->PENDING;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TXN_TRANS_S;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
            <i></i><?php echo Lang::$word->COMPLETED;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
            <i></i><?php echo Lang::$word->PENDING;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TXN_NODOWN;?></label>
        <label class="input">
          <input name="downloads" type="text" value="<?php echo $row->downloads;?>">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <label><?php echo Lang::$word->TXN_MEMO;?></label>
      <textarea name="memo"><?php echo $row->memo;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->TXN_UPDATE;?></button>
    <a href="index.php?do=transactions" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processTransaction" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case "add":?>
<?php $packagerow = $jobs->getPackages();?>
<?php $gaterow = $content->getGateways(true);?>
<?php $employersrow = $jobs->getEmployers();?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->TXN_INFO2;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->TXN_SUB2;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label>Select Employer</label>
        <select name="uid">
          <option value="">--- Select Employer ---</option>
          <?php if($employersrow):?>
              <?php foreach ($employersrow as $erow) : ?>
              <option value="<?php echo $erow->id; ?>"><?php echo $erow->username .' ('.$erow->cname.'/'.$erow->aname.')';?> </option>
          <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div class="field">
        <label>Select Package</label>
        <select name="pid">
          <option value="">--- Select Package ---</option>
          <?php if($packagerow):?>
              <?php foreach ($packagerow as $prow) : ?>
              <option value="<?php echo $prow->id; ?>"><?php echo $prow->name;?></option>
          <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TXN_PPM;?></label>
        <select name="pp">
          <option value="Offline">Offline</option>
          <?php if($gaterow):?>
          <?php foreach ($gaterow as $grow) : ?>
          <option value="<?php echo $grow->displayname;?>"><?php echo $grow->displayname;?></option>
          <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div class="field">
        <label>Subscription Start Date</label>
        <label class="input"><i class="icon-append icon asterisk"></i> <i class="icon-prepend icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="created">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TXN_QTY;?></label>
        <label class="input">
          <input type="text" name="item_qty" value="1" placeholder="<?php echo Lang::$word->TXN_QTY;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TXN_NOTIFY;?></label>
        <div class="inline-group">
          <label class="checkbox">
            <input name="notify" type="checkbox" value="1" class="checkbox"/>
            <i></i><?php echo Lang::$word->YES;?></label>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->TXN_MEMO;?></label>
      <textarea name="memo" placeholder="<?php echo Lang::$word->TXN_MEMO;?>"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->TXN_ADDPAY;?></button>
    <a href="index.php?do=transactions" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processAdminTransaction" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $transrow = $jobs->getTransactions();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->TXN_INFO;?></div>
<div class="wojo basic segment">
  <div class="header">
    <div class="push-right">
      <div class="wojo right pointing dropdown icon button"> <i class="setting icon"></i>
        <div class="menu"> <a class="item" href="controller.php?exportTransactionsXLS"><i class="icon excel outline"></i><?php echo Lang::$word->TXN_EXPORTE;?></a> <a class="item" href="controller.php?exportTransactionsPDF"><i class="icon pdf outline"></i><?php echo Lang::$word->TXN_EXPORTP;?></a> <a class="item" href="index.php?do=transactions&amp;action=salesyear"><i class="icon bar chart"></i><?php echo Lang::$word->TXN_VIEWSALE;?></a> <a class="item" href="index.php?do=transactions&amp;action=add"><i class="icon add"></i><?php echo Lang::$word->TXN_ADDPAY;?></a> </div>
      </div>
    </div>
    <span><?php echo Lang::$word->TXN_SUB;?></span> </div>
  <div class="wojo small segment form">
    <form method="post" id="wojo_form" name="wojo_form">
      <div class="three fields">
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
            <input type="text" name="serachtrans" placeholder="<?php echo Lang::$word->TXN_SEARCH;?>" id="searchfield"  />
            <i class="search icon"></i>
            <div id="suggestions"> </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string">TXN ID</th>
        <th data-sort="string"><?php echo Lang::$word->PRD_NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->USERNAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->TXN_AMT;?></th>
        <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
        <th data-sort="string"><?php echo Lang::$word->TXN_PP;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$transrow):?>
      <tr>
        <td colspan="8"><?php echo Filter::msgSingleAlert(Lang::$word->TXN_NOTRANS);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($transrow as $row):?>
      <tr>
        <td><?php echo $row->txn_id;?></td>
        <td><a href="index.php?do=products&amp;action=edit&amp;id=<?php echo $row->pid;?>"><?php echo truncate($row->name,35);?></a></td>
        <td><a href="index.php?do=users&amp;action=edit&amp;id=<?php echo $row->uid;?>"><?php echo $row->username;?></a></td>
        <td><?php echo $core->formatMoney($row->price);?></td>
        <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
        <td><?php echo $row->pp;?></td>
        <td><?php echo isActive($row->status);?> <a href="index.php?do=transactions&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->TXN_DELETE;?>" data-option="deleteTransaction" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->txn_id;?>"><i class="circular danger inverted remove icon link"></i></a></td>
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
    $("#searchfield").on('keyup', function () {
        var srch_string = $(this).val();
        var data_string = 'transactionSearch=' + srch_string;
        if (srch_string.length > 4) {
            $.ajax({
                type: "post",
                url: "controller.php",
                data: data_string,
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
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
