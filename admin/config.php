<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->CONF_INFO;?> <?php echo Lang::$word->REQFIELD1;?> <i class="icon asterisk"></i> <?php echo Lang::$word->REQFIELD2;?></div>
<div class="wojo form segment">
  <div class="wojo top right attached label"><?php echo Lang::$word->CONF_SUB;?></div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_SITE;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="site_name" value="<?php echo $core->site_name;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_COMPANY;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="company" value="<?php echo $core->company;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_URL;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="site_url" value="<?php echo $core->site_url;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_DIR;?></label>
        <label class="input">
          <input type="text" name="site_dir" value="<?php echo $core->site_dir;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_EMAIL;?></label>
        <label class="input">
          <input type="text" name="site_email" value="<?php echo $core->site_email;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_OFFLINE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="offline" onclick="$('.offline-data').slideDown();" value="1" <?php getChecked($core->offline, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="offline" onclick="$('.offline-data').slideUp();" value="0" <?php getChecked($core->offline, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="two fields offline-data"<?php echo ($core->offline) ? "" : " style=\"display:none\""; ?>>
      <div class="field">
        <label><?php echo Lang::$word->CONF_OFFLINE_DATE;?></label>
        <label class="input"><i class="icon-prepend icon calendar"></i> <i class="icon-append icon asterisk"></i>
          <input name="offline_d" data-datepicker="true" type="text" value="<?php echo $core->offline_d;?>">
        </label>
        <div class="small-top-space"></div>
        <label><?php echo Lang::$word->CONF_OFFLINE_TIME;?></label>
        <label class="input"><i class="icon-prepend icon time"></i> <i class="icon-append icon asterisk"></i>
          <input name="offline_t" data-timepicker="true" type="text" value="<?php echo $core->offline_t;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_OFFLINE_INFO;?></label>
        <textarea id="altpost" class="altpost" name="offline_msg"><?php echo $core->offline_msg;?></textarea>
      </div>
      <div class="wojo divider"></div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_LOGO;?></label>
        <label class="input">
          <input type="file" id="logo" name="logo" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_DELLOGO;?></label>
        <div class="inline-group">
          <label class="checkbox">
            <input name="dellogo" type="checkbox" value="1" class="checkbox"/>
            <i></i><?php echo Lang::$word->YES;?></label>
            <div class="wojo normal image"> <a class="lightbox" href="<?php echo UPLOADURL;?><?php echo $core->logo;?>"><img src="<?php echo UPLOADURL;?><?php echo $core->logo;?>" alt=""></a> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_PLOGO;?></label>
        <label class="input">
          <input type="file" id="plogo" name="plogo" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_PLOGO;?></label>
        <div class="wojo normal image"> <a class="lightbox" href="<?php echo UPLOADURL;?>print_logo.png"><img src="<?php echo UPLOADURL;?>print_logo.png" alt=""></a> </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label>Favourite Icon</label>
        <label class="input">
          <input type="file" id="favicon" name="favicon" class="filefield">
        </label>
      </div>
      <div class="field">
        <label>Favicon</label>
        <div class="wojo normal image"> <a class="lightbox" href="<?php echo UPLOADURL;?>favicon.png"><img src="<?php echo UPLOADURL;?>favicon.png" alt=""></a> </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_SDATE;?></label>
        <select name="short_date">
          <?php echo $core->getShortDate();?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_LDATE;?></label>
        <select name="long_date">
          <?php echo $core->getLongDate();?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_LANG;?></label>
        <select name="lang">
          <?php foreach(Lang::fetchLanguage() as $langlist):?>
          <option value="<?php echo $langlist;?>"<?php if($core->lang == $langlist) echo ' selected="selected"';?>><?php echo strtoupper($langlist);?></option>
          <?php endforeach;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_TDATE;?></label>
        <select name="time_format">
          <?php echo Core::getTimeFormat($core->time_format);?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_TZ;?></label>
        <?php echo $core->getTimezones();?> </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_LOCALES;?></label>
        <select name="locale">
          <?php echo $core->getlocaleList();?>
        </select>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_SEO;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="seo" value="1" <?php getChecked($core->seo, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="seo" value="0" <?php getChecked($core->seo, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_IPP;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="perpage" value="<?php echo $core->perpage;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_THEME;?></label>
        <select name="theme">
          <?php getTemplates(BASEPATH."/themes/", $core->theme)?>
        </select>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="four fields">
      <div class="field">
        <label>Facebook URL</label>
        <label class="input">
          <input type="text" name="facebook_url" value="<?php echo $core->facebook_url;?>">
        </label>
      </div>
      <div class="field">
        <label>Twitter URL</label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="twitter_url" value="<?php echo $core->twitter_url;?>">
        </label>
      </div>
      <div class="field">
        <label>Google Plus URL</label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="google_plus_url" value="<?php echo $core->google_plus_url;?>">
        </label>
      </div>
      <div class="field">
        <label>LinkedIn URL</label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="linkedin_url" value="<?php echo $core->linkedin_url;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label>Footer Copyright Text</label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="copyright" value="<?php echo $core->copyright;?>">
        </label>
      </div>
      <div class="field">
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_REGVER;?></label>
        <label class="radio">
          <input type="radio" name="reg_verify" value="1" <?php getChecked($core->reg_verify, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="reg_verify" value="0" <?php getChecked($core->reg_verify, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_AUTOREG;?></label>
        <label class="radio">
          <input type="radio" name="auto_verify" value="1" <?php getChecked($core->auto_verify, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="auto_verify" value="0" <?php getChecked($core->auto_verify, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_REGYES;?></label>
        <label class="radio">
          <input type="radio" name="reg_allowed" value="1" <?php getChecked($core->reg_allowed, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="reg_allowed" value="0" <?php getChecked($core->reg_allowed, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_NOTE;?></label>
        <label class="radio">
          <input type="radio" name="notify_admin" value="1" <?php getChecked($core->notify_admin, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="notify_admin" value="0" <?php getChecked($core->notify_admin, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_ULIMIT;?></label>
        <label class="input">
          <input type="text" name="user_limit" value="<?php echo $core->user_limit;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURRENCY;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="currency" value="<?php echo $core->currency;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURSYMBOL;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="cur_symbol" value="<?php echo $core->cur_symbol;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_FPATH;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="file_dir" value="<?php echo $core->file_dir;?>">
        </label>
      </div>
      <div class="field">
        <label>Home Page Latest Jobs</label>
        <label class="input">
          <input type="text" class="slrange" name="latest_jobs" value="<?php echo $core->latest_jobs;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <div class="two fields">
          <div class="field">
            <label><?php echo Lang::$word->CONF_FREE;?></label>
            <div class="inline-group">
              <label class="radio">
                <input type="radio" name="free_allowed" value="1" <?php getChecked($core->free_allowed, 1); ?>>
                <i></i><?php echo Lang::$word->YES;?></label>
              <label class="radio">
                <input type="radio" name="free_allowed" value="0" <?php getChecked($core->free_allowed, 0); ?>>
                <i></i><?php echo Lang::$word->NO;?></label>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->CONF_PSIZE;?></label>
            <select name="psize">
              <option value="A4" <?php if ($core->psize == "A4") echo "selected=\"selected\"";?>>A4</option>
              <option value="LETTER" <?php if ($core->psize == "LETTER") echo "selected=\"selected\"";?>>LETTER</option>
            </select>
          </div>
        </div>
      </div>
      <div class="field">
        <label>Featured Jobs</label>
        <label class="input">
          <input type="text" class="slrange" name="featured_jobs" value="<?php echo $core->featured_jobs;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <div class="two fields">
          <div class="field">
            <label><?php echo Lang::$word->CONF_LAYOUT;?></label>
            <div class="inline-group">
              <label class="radio">
                <input type="radio" name="hlayout" value="1" <?php getChecked($core->hlayout, 1); ?>>
                <i></i><?php echo Lang::$word->GRID;?></label>
              <label class="radio">
                <input type="radio" name="hlayout" value="0" <?php getChecked($core->hlayout, 0); ?>>
                <i></i><?php echo Lang::$word->LIST;?></label>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->CONF_TAX;?></label>
            <div class="inline-group">
              <label class="radio">
                <input type="radio" name="tax" value="1" <?php getChecked($core->tax, 1); ?>>
                <i></i><?php echo Lang::$word->YES;?></label>
              <label class="radio">
                <input type="radio" name="tax" value="0" <?php getChecked($core->tax, 0); ?>>
                <i></i><?php echo Lang::$word->NO;?></label>
            </div>
          </div>
        </div>
      </div>
      <div class="field">
        <label>Featured Resumes</label>
        <label class="input">
          <input type="text" class="slrange" name="featured_resumes" value="<?php echo $core->featured_resumes;?>">
        </label>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_THUMBW;?></label>
        <label class="input"><i class="icon-append icon-asterisk"></i>
          <input type="text" name="thumb_w" value="<?php echo $core->thumb_w;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_THUMBH;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="thumb_h" value="<?php echo $core->thumb_h;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_SHOWHOME;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="show_home" value="1" <?php getChecked($core->show_home, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="show_home" value="0" <?php getChecked($core->show_home, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_SHOWSLIDE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="show_slider" value="1" <?php getChecked($core->show_slider, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="show_slider" value="0" <?php getChecked($core->show_slider, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo divider"></div>
      <div class="two fields">
        <div class="field">
          <label>Company Address</label>
          <textarea class="altpost" name="inv_info"><?php echo $core->inv_info;?></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_INVNOTE;?></label>
          <textarea class="altpost" name="inv_note"><?php echo $core->inv_note;?></textarea>
        </div>
      </div>
      <div class="wojo divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_MAILER;?></label>
        <select name="mailer" id="mailerchange">
          <option value="PHP" <?php if ($core->mailer == "PHP") echo "selected=\"selected\"";?>>PHP Mailer</option>
          <option value="SMAIL" <?php if ($core->mailer == "SMAIL") echo "selected=\"selected\"";?>>Sendmail</option>
          <option value="SMTP" <?php if ($core->mailer == "SMTP") echo "selected=\"selected\"";?>>SMTP Mailer</option>
        </select>
      </div>
      <div class="field showsmail">
        <label><?php echo Lang::$word->CONF_SMAILPATH;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input name="sendmail" value="<?php echo $core->sendmail;?>" type="text">
        </label>
      </div>
    </div>
    <div class="showsmtp">
      <div class="wojo divider"></div>
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_HOST;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_host" value="<?php echo $core->smtp_host;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_HOST;?>" type="text">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_USER;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_user" value="<?php echo $core->smtp_user;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_USER;?>" type="text">
          </label>
        </div>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_PASS;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_pass" value="<?php echo $core->smtp_pass;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_PASS;?>" type="text">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_PORT;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_port" value="<?php echo $core->smtp_port;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_PORT;?>" type="text">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_SSL;?></label>
          <div class="inline-group">
            <label class="radio">
              <input name="is_ssl" type="radio" value="1" <?php getChecked($core->is_ssl, 1); ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="is_ssl" type="radio" value="0" <?php getChecked($core->is_ssl, 0); ?>>
              <i></i> <?php echo Lang::$word->NO;?> </label>
          </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_METAKEY;?></label>
        <textarea name="metakeys"><?php echo $core->metakeys;?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_METADESC;?></label>
        <textarea name="metadesc"><?php echo $core->metadesc;?></textarea>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->CONF_GA;?></label>
      <textarea name="analytics"><?php echo $core->analytics;?></textarea>
      <input type="hidden" name="popular" value="<?php echo $core->popular;?>">
      <input type="hidden" name="featured" value="<?php echo $core->featured;?>">
      <input type="hidden" name="homelist" value="<?php echo $core->homelist;?>">
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo button"><?php echo Lang::$word->CONF_UPDATE;?></button>
    <input name="processConfig" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
 $(document).ready(function () {
     var res2 = '<?php echo $core->mailer;?>';
     (res2 == "SMTP") ? $('.showsmtp').show() : $('.showsmtp').hide();
     $('#mailerchange').change(function () {
         var res = $("#mailerchange option:selected").val();
         (res == "SMTP") ? $('.showsmtp').show() : $('.showsmtp').hide();
     });

     (res2 == "SMAIL") ? $('.showsmail').show() : $('.showsmail').hide();
     $('#mailerchange').change(function () {
         var res = $("#mailerchange option:selected").val();
         (res == "SMAIL") ? $('.showsmail').show() : $('.showsmail').hide();
     });

    $("input[name=latest_jobs]").ionRangeSlider({
		min: 2,
		max: 10,
        step: 1,
		postfix: " itm",
        type: 'single',
        hasGrid: true
    });

    $("input[name=featured_jobs]").ionRangeSlider({
		min: 2,
		max: 10,
        step: 1,
		postfix: " itm",
        type: 'single',
        hasGrid: true
    });

    $("input[name=featured_resumes]").ionRangeSlider({
		min: 2,
		max: 10,
        step: 1,
		postfix: " itm",
        type: 'single',
        hasGrid: true
    });
 });
// ]]>
</script>
