<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "add": ?>
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
    <button type="button" name="dosubmit" class="wojo button">Add Record</button>
    <a href="index.php?do=subscriptions" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processAdminTransaction" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $subscriptions = $jobs->getSubscriptions();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?>Here you can view all your subscriptions. Note: Deleting valid subscription record will also limit access to job posting of that employer.</div>
<div class="wojo basic segment">
  <div class="header">
    <a class="wojo button push-right" href="index.php?do=subscriptions&amp;action=add"><i class="icon add"></i> Add Record</a>
    <span>Viewing Subscriptions</span> </div>
  <div class="wojo small segment form">
    <form method="post" id="wojo_form" name="wojo_form">
      <div class="two fields">
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
      </div>
    </form>
  </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th data-sort="string">TXN ID</th>
        <th data-sort="string">Employer</th>
        <th data-sort="string">Package</th>
        <th data-sort="int">Usage/Limit</th>
        <th data-sort="int">Start</th>
        <th data-sort="int">End</th>
        <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$subscriptions):?>
      <tr>
        <td colspan="8"><?php echo Filter::msgSingleAlert("You don't have any subscription records yet...");?></td>
      </tr>
      <?php else:?>
      <?php foreach ($subscriptions as $row):?>
      <tr>
        <td><?php echo $row->txn_id;?></td>
        <td><a href="index.php?do=users&amp;action=edit&amp;id=<?php echo $row->uid;?>"><?php echo $row->ename;?></a></td>
        <td><a href="index.php?do=packages&amp;action=edit&amp;id=<?php echo $row->pid;?>"><?php echo $row->pname;?></a></td>
        <td><?php echo $row->usage;?>/<?php echo $row->limit;?></td>
        <td data-sort-value="<?php echo strtotime($row->start_date);?>"><?php echo Filter::dodate("short_date", $row->start_date);?></td>
        <td data-sort-value="<?php echo strtotime($row->end_date);?>"><?php echo Filter::dodate("short_date", $row->end_date);?></td>
        <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
        <td><a href="index.php?do=transactions&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->TXN_DELETE;?>" data-option="deleteTransaction" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->txn_id;?>"><i class="circular danger inverted remove icon link"></i></a></td>
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
<?php break;?>
<?php endswitch;?>
