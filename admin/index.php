<?php

  define("_VALID_PHP", true);
  require_once("init.php");

  if (is_dir("../setup"))
      : die("<div style='text-align:center'>"
		  . "<span style='padding: 5px; border: 1px solid #999; background-color:#EFEFEF;"
		  . "font-family: Verdana; font-size: 11px; margin-left:auto; margin-right:auto'>"
		  . "<b>Warning:</b> Please delete setup directory!</div>");
  endif;

  if (!$user->is_Admin())
      redirect_to("login.php");
?>
<?php include("header.php");?>
<!-- Start Content-->
<div class="wojo-grid clearfix">
  <div class="tablebox">
    <div id="leftpanel">
      <div class="content-center admin-logo">
		<div class="screen-100 tablet-30 hide-phone admin-logo-div">
			<a href="index.php"><?php echo ($core->logo) ? '<img src="../uploads/admin-logo.png" alt="'.$core->site_name.'" class="logo"/>': '<span class="logo">' . $core->site_name . '</span>';?></a>
		</div>
      </div>
      <nav>
        <ul>
          <li class="parent<?php if (!Filter::$do) echo ' active';?>"><a href="index.php"><i class="icon dashboard"></i> <span><?php echo Lang::$word->ADM_DASH;?></span></a></li>
          <li class="parent"><a class="<?php echo (Filter::$do == 'pages' or Filter::$do == 'news' or Filter::$do == 'testimonials' or Filter::$do == 'faq' or Filter::$do == 'newsletter' or Filter::$do == 'templates' or Filter::$do == 'slider' or Filter::$do == 'menus' or Filter::$do == 'footer-menus') ? "expanded" : "collapsed";?>"><i class="icon file text"></i><span><?php echo Lang::$word->ADM_M_CONTENT;?><i class="icon triangle down"></i></span></a>
            <ul class="sublist<?php if (in_array(Filter::$do, array('pages','news','testimonials','faq','newsletter','templates','slider','menus','footer-menus'))) echo ' active';?>">
              <li class="<?php echo (Filter::$do == 'menus') ? "active" : "normal";?>"><a href="index.php?do=menus"><i class="icon reorder"></i> <span><?php echo Lang::$word->ADM_M_MENUS;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'footer-menus') ? "active" : "normal";?>"><a href="index.php?do=footer-menus"><i class="icon reorder"></i> <span>Footer Menu</span></a></li>
              <li class="<?php echo (Filter::$do == 'pages') ? "active" : "normal";?>"><a href="index.php?do=pages"><i class="icon file text"></i> <span><?php echo Lang::$word->ADM_M_PAGES;?></span></a></li>
			  <li class="<?php echo (Filter::$do == 'testimonials') ? "active" : "normal";?>"><a href="index.php?do=testimonials"><i class="icon quote left"></i> <span>Testimonials</span></a></li>
			  <li class="<?php echo (Filter::$do == 'faq') ? "active" : "normal";?>"><a href="index.php?do=faq"><i class="icon question"></i> <span><?php echo Lang::$word->ADM_M_FAQ;?></span></a></li>
			  <li class="<?php echo (Filter::$do == 'slider') ? "active" : "normal";?>"><a href="index.php?do=slider"><i class="icon photo"></i> <span><?php echo Lang::$word->ADM_M_SLIDER;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'news') ? "active" : "normal";?>"><a href="index.php?do=news"><i class="icon bullhorn"></i> <span><?php echo Lang::$word->ADM_M_NEWS;?></span></a></li>

              <li class="<?php echo (Filter::$do == 'newsletter') ? "active" : "normal";?>"><a href="index.php?do=newsletter"><i class="icon send"></i> <span><?php echo Lang::$word->ADM_M_NLETTER;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'templates') ? "active" : "normal";?>"><a href="index.php?do=templates"><i class="icon mail"></i> <span><?php echo Lang::$word->ADM_M_EMAIL;?></span></a></li>
            </ul>
          </li>
          <li class="parent"><a class="<?php echo (Filter::$do == 'jobs' or Filter::$do == 'resumes' or Filter::$do == 'job-categories' or Filter::$do == 'job-types' or Filter::$do == 'job-skills' or Filter::$do == 'job-locations') ? "expanded" : "collapsed";?>"><i class="icon briefcase"></i><span>Jobs</span><i class="icon triangle down"></i></a>
            <ul class="sublist<?php if (in_array(Filter::$do, array('jobs','resumes','job-categories','applications','job-types','job-skills','job-locations'))) echo ' active';?>">
                <li class="<?php echo (Filter::$do == 'jobs') ? "active" : "normal";?>"><a href="index.php?do=jobs"><i class="icon briefcase"></i> <span>Jobs</span></a></li>
                <li class="<?php echo (Filter::$do == 'resumes') ? "active" : "normal";?>"><a href="index.php?do=resumes"><i class="icon users"></i> <span>Resumes</span></a></li>
                <li class="<?php echo (Filter::$do == 'job-categories') ? "active" : "normal";?>"><a href="index.php?do=job-categories"><i class="icon folder"></i> <span>Categories</span></a></li>
                <li class="<?php echo (Filter::$do == 'job-locations') ? "active" : "normal";?>"><a href="index.php?do=job-locations"><i class="icon exchange"></i> <span>Locations</span></a></li>
			    <li class="<?php echo (Filter::$do == 'job-skills') ? "active" : "normal";?>"><a href="index.php?do=job-skills"><i class="icon exchange"></i> <span>Skills</span></a></li>
			    <li class="<?php echo (Filter::$do == 'job-types') ? "active" : "normal";?>"><a href="index.php?do=job-types"><i class="icon exchange"></i> <span>Types</span></a></li>
            </ul>
          </li>
          <li class="parent"><a class="<?php echo (Filter::$do == 'packages' or Filter::$do == 'subscriptions' or Filter::$do == 'transactions' or Filter::$do == 'gateways') ? "expanded" : "collapsed";?>"><i class="icon send"></i><span><?php echo 'Payment';?></span><i class="icon triangle down"></i></a>
            <ul class="sublist<?php if (in_array(Filter::$do, array('packages','subscriptions','gateways','transactions'))) echo ' active';?>">
                <li class="<?php echo (Filter::$do == 'packages') ? "active" : "normal";?>"><a href="index.php?do=packages"><i class="icon briefcase"></i> <span><?php echo 'Packages';?></span></a></li>
                <li class="<?php echo (Filter::$do == 'subscriptions') ? "active" : "normal";?>"><a href="index.php?do=subscriptions"><i class="icon reorder"></i> <span><?php echo 'Subscriptions';?></span></a></li>
    			      <li class="<?php echo (Filter::$do == 'gateways') ? "active" : "normal";?>"><a href="index.php?do=gateways"><i class="icon payment"></i><span><?php echo Lang::$word->ADM_M_GATE;?></span></a></li>
                <li class="<?php echo (Filter::$do == 'transactions') ? "active" : "normal";?>"><a href="index.php?do=transactions"><i class="icon exchange"></i> <span><?php echo Lang::$word->ADM_M_TRANS;?></span></a></li>
            </ul>
          </li>
          <li class="parent"><a class="<?php echo ( Filter::$do == 'users' ) ? "expanded" : "collapsed";?>"><i class="icon user"></i><span><?php echo Lang::$word->ADM_M_USER;?><i class="icon triangle down"></i></span></a>

            <ul class="sublist<?php if (Filter::$do == 'users') echo ' active';?>">
              <li class="<?php echo ( Filter::$do == 'users' && ($_GET['usertype'] == 'alluser' || $_GET['usertype'] == '')) ? "active" : "normal";?>"><a href="index.php?do=users"><i class="icon user"></i> <span>All Users</span></a></li>
              <li class="<?php echo ( Filter::$do == 'users' && $_GET['usertype'] == 'admin') ? "active" : "normal";?>"><a href="index.php?do=users&usertype=admin"><i class="icon user"></i> <span>Admins</span></a></li>
              <li class="<?php echo ( Filter::$do == 'users' && $_GET['usertype'] == 'employer') ? "active" : "normal";?>"><a href="index.php?do=users&usertype=employer"><i class="icon user"></i> <span>Employers</span></a></li>
              <li class="<?php echo ( Filter::$do == 'users' && $_GET['usertype'] == 'seeker') ? "active" : "normal";?>"><a href="index.php?do=users&usertype=seeker"><i class="icon user"></i> <span>Seeker</span></a></li>
            </ul>
		  </li>

          <li class="parent"><a class="<?php echo (Filter::$do == 'config' or Filter::$do == 'maintenance' or Filter::$do == 'backup' or Filter::$do == 'system' or Filter::$do == 'language' or Filter::$do == 'countries') ? "expanded" : "collapsed";?>"><i class="icon sliders"></i> <span><?php echo Lang::$word->ADM_M_CONF;?></span><i class="icon triangle down"></i></a>
            <ul class="sublist<?php if (in_array(Filter::$do, array('config','maintenance','backup','system','language','countries'))) echo ' active';?>">
              <li class="<?php echo (Filter::$do == 'config') ? "active" : "normal";?>"><a href="index.php?do=config"><i class="icon setting"></i><span><?php echo Lang::$word->ADM_M_SCONF;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'countries') ? "active" : "normal";?>"><a href="index.php?do=countries"><i class="icon globe"></i><span><?php echo Lang::$word->ADM_M_COUNTRY;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'maintenance') ? "active" : "normal";?>"><a href="index.php?do=maintenance"><i class="icon wrench"></i><span><?php echo Lang::$word->ADM_M_SM;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'system') ? "active" : "normal";?>"><a href="index.php?do=system"><i class="icon laptop"></i><span><?php echo Lang::$word->ADM_M_SY;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'language') ? "active" : "normal";?>"><a href="index.php?do=language"><i class="icon language"></i><span><?php echo Lang::$word->ADM_M_LM;?></span></a></li>
              <li class="<?php echo (Filter::$do == 'backup') ? "active" : "normal";?>"><a href="index.php?do=backup"><i class="icon database"></i><span><?php echo Lang::$word->ADM_M_DB;?></span></a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
    <div id="rightpanel">
      <header>
        <div class="columns">
          <div class="screen-40 tablet-30 hide-phone">&nbsp;</div>
          <div class="screen-60 tablet-70 phone-100">
            <div class="wojo secondary menu sec-menu"> <a class="item icon-menu" target="_blank" title="Home Front" href="../"><i class="home icon"></i></a> <a title="Edit Profile" class="item icon-menu" href="index.php?do=users&amp;action=edit&amp;id=<?php echo $user->uid;?>"><i class="user icon"></i></a>
              <div title="Change Language" class="wojo dropdown item icon-menu1"><i class="language icon"></i><i class="dropdown icon"></i>
                <div class="inverted menu">
                  <?php foreach(Lang::fetchLanguage() as $lang):?>
                  <?php if(Core::$language == $lang):?>
                  <a class="item active">
                  <div class="wojo label"><?php echo strtoupper($lang);?></div>
                  <?php echo Lang::$word->ADM_M_LANG;?></a>
                  <?php else:?>
                  <a class="item langchange" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>" data-lang="<?php echo $lang;?>">
                  <div class="wojo label"><?php echo strtoupper($lang);?></div>
                  <?php echo Lang::$word->ADM_M_LANG_C;?></a>
                  <?php endif?>
                  <?php endforeach;?>
                </div>
              </div>
              <div class="right menu">
				  <a class="wojo item admin-user-icon" href="index.php?do=users&amp;action=edit&amp;id=<?php echo $user->uid;?>">
					<?php if($user->avatar):?>
						<img src="<?php echo UPLOADURL;?>avatars/<?php echo $user->avatar;?>" alt="<?php echo $user->username;?>">
					<?php else:?>
						<img src="<?php echo UPLOADURL;?>avatars/blank.png" alt="<?php echo $user->username;?>">
					<?php endif;?>
					<span class="hide-phone"> <?php echo $user->username;?>!</span>
				  </a>
				  <a class="wojo item" href="logout.php"> <i class="sign out icon"></i> <?php echo Lang::$word->LOGOUT;?> </a>
			  </div>
            </div>
          </div>
        </div>
      </header>
      <div class="wojo breadcrumb relative">
        <?php include_once("helper.php");?>
        <?php include_once("crumbs.php");?>
      </div>
      <div class="wojo-content">
        <?php (Filter::$do && file_exists(Filter::$do.".php")) ? include(Filter::$do.".php") : include("main.php");?>
      </div>
    </div>
  </div>
</div>
<!-- End Content/-->
<?php include("footer.php");?>
