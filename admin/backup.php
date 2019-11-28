<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  require_once(BASEPATH . "lib/class_dbtools.php");
  Registry::set('dbTools',new dbTools());
  
  if (isset($_GET['backupok']) && $_GET['backupok'] == "1")
      Filter::msgOk(Lang::$word->DBM_BKP_OK,1,0);
	    
  if (isset($_GET['create']) && $_GET['create'] == "1")
      Registry::get("dbTools")->doBackup('',false);
	  
  $dir = BASEPATH . 'admin/backups/';
?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->DBM_INFO;?></div>
<div class="wojo basic segment">
  <div class="header"><a class="wojo button push-right" href="index.php?do=backup&amp;create=1"><i class="icon add"></i> <?php echo Lang::$word->DBM_ADD;?></a><span><?php echo Lang::$word->DBM_SUB;?></span> </div>
  <?php if (is_dir($dir)):?>
  <?php $getDir = dir($dir);?>
  <div class="wojo divided list">
    <?php while (false !== ($file = $getDir->read())):?>
    <?php if ($file != "." && $file != ".." && $file != "index.php"):?>
    <?php $latest =  ($file == $core->backup) ? " active" : "";?>
    <div class="item<?php echo $latest;?>"><i class="big icon hdd"></i>
      <div class="header"><?php echo getSize(filesize(BASEPATH . 'admin/backups/' . $file));?></div>
      <div class="push-right"> <a class="dbdelete" data-content="<?php echo Lang::$word->DELETE;?>" data-option="deleteBackup" data-file="<?php echo $file;;?>"><i class="rounded danger inverted trash icon"></i></a> <a href="<?php echo ADMINURL . '/backups/' . $file;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded success inverted download alt icon"></i></a> <a class="restore" data-content="<?php echo Lang::$word->RESTORE;?>" data-file="<?php echo $file;?>"><i class="rounded warning inverted refresh icon"></i></a> </div>
      <div class="content"><?php echo str_replace(".sql", "", $file);?></div>
    </div>
    <?php endif;?>
    <?php endwhile;?>
    <?php $getDir->close();?>
  </div>
  <?php endif;?>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $('a.restore').on('click', function () {
        var parent = $(this).closest('div.item');
        var id = $(this).data('file')
        var title = id;
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p><?php echo Lang::$word->DBM_RES_T;?><br><strong><?php echo Lang::$word->DELCONFIRM1;?></strong></p></div>";
        new Messi(text, {
            title: "<?php echo Lang::$word->DBM_RES;?>",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: "<?php echo Lang::$word->DBM_RES;?>",
                val: 'Y',
				class: 'negative'
            }],
            callback: function (val) {
                if (val === "Y") {
					$.ajax({
						type: 'post',
						dataType: 'json',
						url: "controller.php",
						data: 'restoreBackup=' + id,
						success: function (json) {
							parent.effect('highlight', 1500);
							$.sticky(decodeURIComponent(json.message), {
								type: json.type,
								title: json.title
							});
						}
					});
                }
            }
        })
    });
	
    $('body').on('click', 'a.dbdelete', function () {
        var file = $(this).data('file');
        var name = $(this).data('file');
        var title = $(this).data('file');
        var option = $(this).data('option');
        var parent = $(this).parent().parent();

        new Messi("<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p><?php echo Lang::$word->DELCONFIRM;?><br><strong><?php echo Lang::$word->DELCONFIRM1;?></strong></p></div>", {
            title: title,
            titleClass: '',
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: '<?php echo Lang::$word->DELETE;?>',
                class: 'negative',
                val: 'Y'
            }],
            callback: function (val) {
                $.ajax({
                    type: 'post',
                    url: "controller.php",
                    dataType: 'json',
                    data: {
                        file: file,
                        delete: option,
                        title: encodeURIComponent(name)
                    },
                    beforeSend: function () {
                        parent.animate({
                            'backgroundColor': '#FFBFBF'
                        }, 400);
                    },
                    success: function (json) {
                        parent.fadeOut(400, function () {
                            parent.remove();
                        });
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
// ]]>
</script> 