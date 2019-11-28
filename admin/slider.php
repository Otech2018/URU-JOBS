<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "config": ?>
<?php $row = $content->sliderConfiguration();?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->SLM_INFO1;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->SLM_SUB1;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_HEIGHT;?></label>
        <label class="input">
          <input type="text" class="slrange" value="<?php echo $row->sliderHeight;?>" name="sliderHeight">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_TRSPEED;?></label>
        <label class="input">
          <input type="text" class="slrange" value="<?php echo $row->slideTransitionSpeed;?>" name="slideTransitionSpeed">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_TRDELAY;?></label>
        <label class="input">
          <input type="text" class="slrange" value="<?php echo $row->slideTransitionDelay;?>" name="slideTransitionDelay">
        </label>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_TRANS;?></label>
        <select name="slideTransition">
          <option value="slide"<?php if($row->slideTransition == 'slide') echo ' selected="selected"';?>>Slide Effect</option>
          <option value="fade"<?php if($row->slideTransition == 'fade') echo ' selected="selected"';?>>Fade Effect</option>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_TRANS_EAS;?></label>
        <select name="slideTransitionEasing">
          <option value="swing"<?php if($row->slideTransitionDirection == 'swing') echo ' selected="selected"';?>>swing</option>
          <option value="easeInQuad"<?php if($row->slideTransitionDirection == 'easeInQuad') echo ' selected="selected"';?>>easeInQuad</option>
          <option value="easeOutQuad"<?php if($row->slideTransitionDirection == 'easeOutQuad') echo ' selected="selected"';?>>easeOutQuad</option>
          <option value="easeInOutQuad"<?php if($row->slideTransitionDirection == 'easeInOutQuad') echo ' selected="selected"';?>>easeInOutQuad</option>
          <option value="easeOutExpo"<?php if($row->slideTransitionDirection == 'easeOutExpo') echo ' selected="selected"';?>>easeOutExpo</option>
          <option value="easeInOutExpo"<?php if($row->slideTransitionDirection == 'easeInOutExpo') echo ' selected="selected"';?>>easeInOutExpo</option>
          <option value="easeInBack"<?php if($row->slideTransitionDirection == 'easeInBack') echo ' selected="selected"';?>>easeInBack</option>
          <option value="easeOutBack"<?php if($row->slideTransitionDirection == 'easeOutBack') echo ' selected="selected"';?>>easeOutBack</option>
          <option value="easeOutBounce"<?php if($row->slideTransitionDirection == 'easeOutBounce') echo ' selected="selected"';?>>easeOutBounce</option>
          <option value="easeInOutBounce"<?php if($row->slideTransitionDirection == 'easeInOutBounce') echo ' selected="selected"';?>>easeInOutBounce</option>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_TRANS_DIR;?></label>
        <select name="slideTransitionDirection">
          <option value="up"<?php if($row->slideTransitionDirection == 'up') echo ' selected="selected"';?>>Up</option>
          <option value="right"<?php if($row->slideTransitionDirection == 'right') echo ' selected="selected"';?>>Right</option>
          <option value="down"<?php if($row->slideTransitionDirection == 'down') echo ' selected="selected"';?>>Down</option>
          <option value="left"<?php if($row->slideTransitionDirection == 'left') echo ' selected="selected"';?>>Left</option>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_SCALE;?></label>
        <select name="slideImageScaleMode">
          <option value="fit"<?php if($row->slideImageScaleMode == 'fit') echo ' selected="selected"';?>>Fit</option>
          <option value="fill"<?php if($row->slideImageScaleMode == 'fill') echo ' selected="selected"';?>>Fill</option>
          <option value="stretch"<?php if($row->slideImageScaleMode == 'stretch') echo ' selected="selected"';?>>Stretch</option>
          <option value="center"<?php if($row->slideImageScaleMode == 'center') echo ' selected="selected"';?>>Center</option>
          <option value="none"<?php if($row->slideImageScaleMode == 'none') echo ' selected="selected"';?>>None</option>
        </select>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_ADPHEIGHT;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="sliderHeightAdaptable" type="radio" value="1" <?php getChecked($row->sliderHeightAdaptable, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="sliderHeightAdaptable" type="radio" value="0" <?php getChecked($row->sliderHeightAdaptable, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_APLAY;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="sliderAutoPlay" type="radio" value="1" <?php getChecked($row->sliderAutoPlay, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="sliderAutoPlay" type="radio" value="0" <?php getChecked($row->sliderAutoPlay, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_SHUFLLE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="slideShuffle" type="radio" value="1" <?php getChecked($row->slideShuffle, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="slideShuffle" type="radio" value="0" <?php getChecked($row->slideShuffle, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_WLOAD;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="waitForLoad" type="radio" value="1" <?php getChecked($row->sliderAutoPlay, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="waitForLoad" type="radio" value="0" <?php getChecked($row->sliderAutoPlay, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_REVERSE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="slideReverse" type="radio" value="1" <?php getChecked($row->slideReverse, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="slideReverse" type="radio" value="0" <?php getChecked($row->slideReverse, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_STRIP;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="showFilmstrip" type="radio" value="1" <?php getChecked($row->showFilmstrip, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="showFilmstrip" type="radio" value="0" <?php getChecked($row->showFilmstrip, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_CAPTIONS;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="showCaptions" type="radio" value="1" <?php getChecked($row->showCaptions, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
          <input name="showCaptions" type="radio" value="0" <?php getChecked($row->showCaptions, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?>
          </label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_CAPTIONS_S;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="simultaneousCaptions" type="radio" value="1" <?php getChecked($row->simultaneousCaptions, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="simultaneousCaptions" type="radio" value="0" <?php getChecked($row->simultaneousCaptions, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_TIMER;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="showTimer" type="radio" value="1" <?php getChecked($row->showTimer, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="showTimer" type="radio" value="0" <?php getChecked($row->showTimer, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_PAUSE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="showPause" type="radio" value="1" <?php getChecked($row->showPause, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="showPause" type="radio" value="0" <?php getChecked($row->showPause, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_ARROWS;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="showArrows" type="radio" value="1" <?php getChecked($row->showArrows, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="showArrows" type="radio" value="0" <?php getChecked($row->showArrows, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_DOTS;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="showDots" type="radio" value="1" <?php getChecked($row->showDots, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="showDots" type="radio" value="0" <?php getChecked($row->showDots, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->SLM_UPDATEC;?></button>
    <a href="index.php?do=slider" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSliderConfiguration" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $("input[name=sliderHeight]").ionRangeSlider({
		min: 200,
		max: 1000,
        step: 50,
		postfix: " px",
        type: 'single',
        hasGrid: true
    });

    $("input[name=slideTransitionSpeed]").ionRangeSlider({
		min: 500,
		max: 5000,
        step: 500,
		postfix: " ms",
        type: 'single',
        hasGrid: true
    });

    $("input[name=slideTransitionDelay]").ionRangeSlider({
		min: 2500,
		max: 10000,
        step: 500,
		postfix: " ms",
        type: 'single',
        hasGrid: true
    });
});
// ]]>
</script>
<?php break;?>
<?php case"edit": ?>
<?php $row = Core::getRowById(Content::slTable, Filter::$id);?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->SLM_INFO2;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->SLM_SUB2;?> / <?php echo $row->caption;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->caption;?>" name="caption">
        </label>

        <div class="small-top-space">
          <label><?php echo Lang::$word->SLM_IMG_SEL;?></label>
          <label class="input">
            <input type="file" name="thumb" class="filefield">
          </label>
        </div>
        <div class="small-top-space">
          <label>Text Alignment</label>
          <select name="alignment">
            <option value="left" <?php echo ($row->alignment == 'left') ? 'selected="selected"' : ''?>>Left</option>
            <option value="right" <?php echo ($row->alignment == 'right') ? 'selected="selected"' : ''?>>Right</option>
            <option value="center" <?php echo ($row->alignment == 'center') ? 'selected="selected"' : ''?>>Center</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_DESC;?></label>
        <textarea name="body"><?php echo $row->body;?></textarea>
      </div>
    </div>
    <div class="two fields">

      <div class="field">
        <label><?php echo Lang::$word->SLM_URL_T;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="urltype" type="radio" onclick="$('#button_text').show();$('#urlchange').show();$('#urlchange2').hide()" value="ext" <?php getChecked($row->urltype, "ext"); ?>>
            <i></i><?php echo Lang::$word->SLM_EXTLINK;?></label>
          <label class="radio">
            <input name="urltype" type="radio" onclick="$('#button_text').show();$('#urlchange2').show();$('#urlchange').hide()" value="int" <?php getChecked($row->urltype, "int"); ?>>
            <i></i><?php echo Lang::$word->SLM_INTLINK;?></label>
          <label class="radio">
            <input name="urltype" type="radio" value="nourl" onclick="$('#button_text').hide();$('#urlchange2').hide();$('#urlchange').hide();" <?php getChecked($row->urltype, "nourl"); ?>>
            <i></i><?php echo Lang::$word->SLM_NOLINK;?></label>
        </div>
      </div>

      <div class="field">
          <div class="two fields">
            <div class="field">
              <div id="button_text" class="field" <?php echo ($row->urltype == "nourl") ? " style=\"display:none\"" : ''; ?>>
                <label>Button Text</label>
                <label class="input"><i class="icon-append icon asterisk"></i>
                  <input type="text" value="<?php echo $row->button_text;?>" name="button_text">
                </label>
              </div>
            </div>
            <div class="field">
              <div class="field">
                <div id="urlchange"<?php echo ($row->urltype == "ext") ? "" : " style=\"display:none\""; ?>>
                  <label> <?php echo Lang::$word->SLM_EXTLINK;?></label>
                  <label class="input"><i class="icon-append icon asterisk"></i>
                    <input type="text" value="<?php echo $row->url;?>" name="url">
                  </label>
                </div>
                <div id="urlchange2"<?php echo ($row->urltype == "int") ? "" : " style=\"display:none\""; ?>>
                  <label> <?php echo Lang::$word->SLM_INTPAGE;?></label>
                  <?php echo Content::getPagesList("id", $row->page_id);?> </div>
              </div>
            </div>
          </div>
      </div>

    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->SLM_UPDATE;?></button>
    <a href="index.php?do=slider" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSlide" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->SLM_INFO3;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->SLM_SUB4;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->SLM_NAME;?>" name="caption">
        </label>
        <div class="small-top-space">
          <label><?php echo Lang::$word->SLM_IMG_SEL;?></label>
          <label class="input">
            <input type="file" name="thumb" class="filefield">
          </label>
        </div>
        <div class="small-top-space">
          <label>Text Alignment</label>
          <select name="alignment">
            <option value="left">Left</option>
            <option value="right">Right</option>
            <option value="center">Center</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLM_DESC;?></label>
        <textarea placeholder="<?php echo Lang::$word->SLM_DESC;?>" name="body"></textarea>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SLM_URL_T;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="urltype" type="radio" onclick="$('#button_text').show();$('#urlchange').show();$('#urlchange2').hide()" value="ext">
            <i></i><?php echo Lang::$word->SLM_EXTLINK;?></label>
          <label class="radio">
            <input name="urltype" type="radio" onclick="$('#button_text').show();$('#urlchange2').show();$('#urlchange').hide()" value="int">
            <i></i><?php echo Lang::$word->SLM_INTLINK;?></label>
          <label class="radio">
            <input name="urltype" type="radio" value="nourl" onclick="$('#button_text').hide();$('#urlchange2').hide();$('#urlchange').hide();" checked="checked">
            <i></i><?php echo Lang::$word->SLM_NOLINK;?></label>
        </div>
      </div>


      <div class="field">
          <div class="two fields">
            <div class="field">
              <div id="button_text" class="field" style="display:none">
                <label>Button Text</label>
                <label class="input"><i class="icon-append icon asterisk"></i>
                  <input type="text" value="" name="button_text">
                </label>
              </div>
            </div>
            <div class="field">
              <div class="field">
                <div id="urlchange" style="display:none">
                  <label> <?php echo Lang::$word->SLM_EXTLINK;?></label>
                  <label class="input"><i class="icon-append icon asterisk"></i>
                    <input placeholder="<?php echo Lang::$word->SLM_EXTLINK;?>" type="text" name="url">
                  </label>
                </div>
                <div id="urlchange2" style="display:none">
                  <label> <?php echo Lang::$word->SLM_INTPAGE;?></label>
                  <?php echo Content::getPagesList("id");?> </div>
              </div>
            </div>
          </div>
      </div>

    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->SLM_ADDSLIDE;?></button>
    <a href="index.php?do=slider" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSlide" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $sliderdata = $content->getSlides();?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->SLM_INFO;?></div>
<div class="wojo basic segment">
  <div class="header">
    <div class="wojo buttons push-right"><a class="wojo positive button" href="index.php?do=slider&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->SLM_ADD;?></a><a class="wojo warning button" href="index.php?do=slider&amp;action=config"><i class="icon setting"></i> <?php echo Lang::$word->SLM_CONFIG;?></a></div>
    <span><?php echo Lang::$word->SLM_SUB;?></span> </div>
  <table class="wojo basic sortable table">
    <thead>
      <tr>
        <th class="disabled"></th>
        <th data-sort="string"><?php echo Lang::$word->SLM_NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->SLM_SORT;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$sliderdata):?>
      <tr>
        <td colspan="4"><?php echo Filter::msgSingleAlert(Lang::$word->FAQ_NOFAQ);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($sliderdata as $row):?>
      <tr id="node-<?php echo $row->id;?>">
        <td class="id-handle"><i class="icon reorder"></i></td>
        <td><?php echo $row->caption;?></td>
        <td><span class="wojo black label"><?php echo $row->sorting;?></span></td>
        <td><a href="index.php?do=slider&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->SLM_DELETE;?>" data-option="deleteSlide" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->caption;?>"><i class="circular danger inverted remove icon link"></i></a></td>
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
                url: "controller.php?sortslides",
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
