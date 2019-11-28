<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Content
  {
	  const muTable = "menus";
	  const mfTable = "menus_footer";
	  const gTable = "gateways";
	  const pTable = "pages";
	  const ptTable = "page_templates";
	  const fqTable = "faq";
	  const nTable = "news";
	  const eTable = "email_templates";
	  const crTable = "cart";
	  const exTable = "extras";
	  const slTable = "slider";
	  const slcTable = "slider_config";
	  const cnTable = "countries";
	  const inTable = "invoices";
	  const uTable = "users";
	  const tesTable = "testimonials";

	  private $cattree = array();
	  private $menutree = array();
	  private $menufootertree = array();
	  private $catlist = array();
	  public static $gfileext = array("jpg","jpeg","png");

	  public $catslug = null;
	  public $pageslug = null;
	  public $tag = null;

	  private static $db;

      /**
       * Content::__construct()
       *
       * @return
       */
      function __construct()
      {
		  self::$db = Registry::get("Database");
		  $this->menutree = $this->getMenuTree();
		  $this->menufootertree = $this->getFooterMenuTree();
		  $this->getContentSlug();
		  $this->getCategorySlug();
		  $this->getTag();

      }

	  /**
	   * Content::getCategorySlug()
	   *
	   * @return
	   */
	  private function getCategorySlug()
	  {
		  if (isset($_GET['catname'])) {
			  $this->catslug = sanitize($_GET['catname'],100);
			  //$this->catslug = rtrim($this->catslug,"/");
			  return self::$db->escape($this->catslug);
		  }
	  }

	  /**
	   * Content::getContentSlug()
	   *
	   * @return
	   */
	  private function getContentSlug()
	  {

		  if (isset($_GET['pagename'])) {
			  $this->pageslug = sanitize($_GET['pagename'],100);
			  return self::$db->escape($this->pageslug);
		  }
	  }

	  /**
	   * Content::getTag()
	   *
	   * @return
	   */
	  private function getTag()
	  {

		  if (isset($_GET['tagname'])) {
			  $this->tag = sanitize($_GET['tagname'],60,false);
			  return self::$db->escape($this->tag);
		  }
	  }

      /**
       * Content::getCountryList()
       *
       * @return
       */
      public function getCountryList()
      {
          $sql = "SELECT * FROM " . self::cnTable . " ORDER BY sorting DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;

      }

      /**
       * Content:::processCountry()
       *
       * @return
       */
      public function processCountry()
      {

		  Filter::checkPost('name', Lang::$word->CNT_NAME);
		  Filter::checkPost('abbr', Lang::$word->CNT_ABBR);

          if (empty(Filter::$msgs)) {
              $data = array(
                  'name' => sanitize($_POST['name']),
                  'abbr' => sanitize($_POST['abbr']),
                  'active' => intval($_POST['active']),
                  'home' => intval($_POST['home']),
				  'vat' => floatval($_POST['vat']),
				  'sorting' => intval($_POST['sorting']),
				  );

			  if ($data['home'] == 1) {
				  self::$db->query("UPDATE `" . self::cnTable . "` SET `home`= DEFAULT(home);");
			  }

              Registry::get("Database")->update(self::cnTable, $data, "id=" . Filter::$id);

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->CNT_UPDATED, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

      /**
       * Content::getMenuList()
       *
       * @return
       */
	  public function getMenuList($parent_id = 0)
	  {
		  $subcat = false;
		  $class = ($parent_id == 0) ? "parent" : "child";

		  foreach ($this->menutree as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($subcat === false) {
					  $subcat = true;
					  print "<ul class=\"sortMenu\">\n";
				  }
				  print '<li class="dd-item" id="list_' . $row['id'] . '">'
				  . '<div class="dd-handle"><a data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-title="' . Lang::$word->MNU_DELETE . '" data-option="deleteMenu" class="delete">'
				  . '<i class="icon red remove sign"></i></a><i class="icon reorder"></i>'
				  . '<a href="index.php?do=menus&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'] . '</a></div>';
				  $this->getMenuList($key);
				  print "</li>\n";

			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }

	  protected function getMenuTree()
	  {
		  $query = self::$db->query("SELECT * FROM " . self::muTable ." ORDER BY parent_id, position");

		  while ($row = self::$db->fetch($query, true)) {
			  $this->menutree[$row['id']] = array(
			        'id' => $row['id'],
					'name' => $row['name'],
					'parent_id' => $row['parent_id']
			  );
		  }
		  return $this->menutree;
	  }
      /**
       * Content::getMenu()
       *
       * @return
       */
	  public function getMenu()
	  {
		  $sql = "SELECT m.*, p.id as pid, p.home_page,p.slug"
		  . "\n FROM menus as m"
		  . "\n LEFT JOIN pages AS p ON p.id = m.page_id"
		  . "\n WHERE m.active = '1'"
		  . "\n ORDER BY m.position";
		  $row = self::$db->fetch_all($sql);

		  foreach($row as $mrow):
				$url = (Registry::get("Core")->seo == 1) ? SITEURL . '/content/' . $mrow->slug . '/' : SITEURL . '/content.php?pagename=' . $mrow->slug;
				$mainurl = ($mrow->home_page == 1) ?  SITEURL : $url;
				$dourl = ($mrow->content_type == 'web') ? $mrow->link : $mainurl;
				$target = ($mrow->content_type == 'web') ? ' target="'.$mrow->target.'"': '' ;
				$menuarray[$mrow->id] = array(
					'id' => $mrow->id,
					'name' => $mrow->name,
					'parent_id' => $mrow->parent_id,
					'slug' => $mrow->slug,
					'url' => $dourl,
					'active' => 1,
					'target' => $target
				);

		  endforeach;
		  return ($menuarray) ? $menuarray : 0;
	  }

	  /**
	   * Content::processMenu()
	   *
	   * @return
	   */
	  public function processMenu()
	  {

		  Filter::checkPost('name', Lang::$word->MNU_NAME);
		  Filter::checkPost('content_type', Lang::$word->MNU_TYPE_S);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'page_id' => intval($_POST['page_id']),
				  'content_type' => sanitize($_POST['content_type']),
				  'link' => (isset($_POST['web'])) ? sanitize($_POST['web']) : "NULL",
				  'target' => (isset($_POST['target'])) ? sanitize($_POST['target']) : "DEFAULT(target)",
				  'active' => intval($_POST['active'])
			  );

			  (Filter::$id) ? self::$db->update(self::muTable, $data, "id=" . Filter::$id) : self::$db->insert(self::muTable, $data);
			  $message = (Filter::$id) ? Lang::$word->MNU_UPDATED : Lang::$word->MNU_ADDED;

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

      protected function getFooterMenuTree()
      {
          $query = self::$db->query("SELECT * FROM " . self::mfTable ." ORDER BY parent_id, position");

          while ($row = self::$db->fetch($query, true)) {
              $this->menufootertree[$row['id']] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'parent_id' => $row['parent_id']
              );
          }
          return $this->menufootertree;
      }

      public function getFooterMenuList($parent_id = 0)
      {
          $subcat = false;
          $class = ($parent_id == 0) ? "parent" : "child";

          foreach ($this->menufootertree as $key => $row) {
              if ($row['parent_id'] == $parent_id) {
                  if ($subcat === false) {
                      $subcat = true;
                      print "<ul class=\"sortMenu\">\n";
                  }
                  print '<li class="dd-item" id="list_' . $row['id'] . '">'
                  . '<div class="dd-handle"><a data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-title="' . Lang::$word->MNU_DELETE . '" data-option="deleteFooterMenu" class="delete">'
                  . '<i class="icon red remove sign"></i></a><i class="icon reorder"></i>'
                  . '<a href="index.php?do=footer-menus&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'] . '</a></div>';
                  $this->getFooterMenuList($key);
                  print "</li>\n";

              }
          }
          unset($row);

          if ($subcat === true)
              print "</ul>\n";
      }

      public function processFooterMenu()
      {

          Filter::checkPost('name', Lang::$word->MNU_NAME);
          Filter::checkPost('content_type', Lang::$word->MNU_TYPE_S);

          if (empty(Filter::$msgs)) {
              $data = array(
                  'name' => sanitize($_POST['name']),
                  'page_id' => intval($_POST['page_id']),
                  'content_type' => sanitize($_POST['content_type']),
                  'link' => (isset($_POST['web'])) ? sanitize($_POST['web']) : "NULL",
                  'target' => (isset($_POST['target'])) ? sanitize($_POST['target']) : "DEFAULT(target)",
                  'active' => intval($_POST['active'])
              );

              (Filter::$id) ? self::$db->update(self::mfTable, $data, "id=" . Filter::$id) : self::$db->insert(self::mfTable, $data);
              $message = (Filter::$id) ? Lang::$word->MNU_UPDATED : Lang::$word->MNU_ADDED;

              if (self::$db->affected()) {
                  $json['type'] = 'success';
                  $json['message'] = Filter::msgOk($message, false);
              } else {
                  $json['type'] = 'info';
                  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
              }
              print json_encode($json);

          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      public function getFooterMenu()
      {
          $sql = "SELECT m.*, p.id as pid, p.home_page,p.slug"
          . "\n FROM menus_footer as m"
          . "\n LEFT JOIN pages AS p ON p.id = m.page_id"
          . "\n WHERE m.active = '1'"
          . "\n ORDER BY m.position";
          $row = self::$db->fetch_all($sql);

          foreach($row as $mrow):
                $url = (Registry::get("Core")->seo == 1) ? SITEURL . '/content/' . $mrow->slug . '/' : SITEURL . '/content.php?pagename=' . $mrow->slug;
                $mainurl = ($mrow->home_page) ?  SITEURL : $url;
                $dourl = ($mrow->content_type == 'web') ? $mrow->link : $mainurl;
                $target = ($mrow->content_type == 'web') ? ' target="'.$mrow->target.'"': '' ;
                $menuarray[$mrow->id] = array(
                    'id' => $mrow->id,
                    'name' => $mrow->name,
                    'parent_id' => $mrow->parent_id,
                    'slug' => $mrow->slug,
                    'url' => $dourl,
                    'active' => 1,
                    'target' => $target
                );

          endforeach;
          return ($menuarray) ? $menuarray : 0;
      }

      /**
       * Content::getSingleCategory()
       *
       * @return
       */
      public function getSingleCategory()
      {

          $sql = "SELECT *"
		  . "\n FROM " . self::cTable
		  . "\n WHERE slug = '" . sanitize($this->catslug) . "'";

          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getCatList()
       *
       * @return
       */
      public function getCatList()
	  {

		  $query = self::$db->query("SELECT * FROM " . self::cTable
		  . "\n WHERE active = 1"
		  . "\n ORDER BY parent_id, position");

		  while ($row = self::$db->fetch($query, true)) {
			  $catlist[$row['id']] = array(
			        'id' => $row['id'],
					'name' => $row['name'],
					'parent_id' => $row['parent_id'],
					'active' => $row['active'],
					'slug' => $row['slug']
			  );
		  }
		  return $catlist;
	  }

      /**
       * Content::getSortCatList()
       *
	   * @param integer $parent_id
       * @return
       */
      public function getSortCatList($parent_id = 0)
	  {

		  $subcat = false;
		  $class = ($parent_id == 0) ? "parent" : "child";

		  foreach ($this->cattree as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($subcat === false) {
					  $subcat = true;
					  print "<ul class=\"sortMenu\">\n";
				  }

				  print '<li class="dd-item" id="list_' . $row['id'] . '">'
				  .'<div class="dd-handle"><a data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-title="' . Lang::$word->CAT_DELETE . '" data-option="deleteCategory" class="delete">'
				  . '<i class="icon red remove sign"></i></a><i class="icon reorder"></i>'
				  .'<a href="index.php?do=categories&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'] . '</a></div>';
				  $this->getSortCatList($key);
				  print "</li>\n";
			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }

      /**
       * Content::getCatCheckList()
       *
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
       * @return
       */
	  public function getCatCheckList($parent_id, $level = 0, $spacer, $selected = false)
	  {

		  if($this->cattree) {
			  $class = 'odd';

			  if($selected) {
				$arr = explode(",",$selected);
				reset($arr);
			  }

			  foreach ($this->cattree as $key => $row) {
				  if($selected) {
					$sel =  (in_array($row['id'], $arr))  ? " checked=\"checked\"" : "";
					$hsel = (in_array($row['id'], $arr)) ? " active" : "";
				  } else {
					  $sel = '';
					  $hsel = '';
				  }
				  $class = ($class == 'even' ? 'odd' : 'even');

				  if ($parent_id == $row['parent_id']) {
					  print "<div class=\"" . $class . $hsel . "\"> <label class=\"checkbox\"><input type=\"checkbox\" name=\"cid[]\" class=\"checkbox\" value=\"" . $row['id'] . "\"".$sel." />";
					  for ($i = 0; $i < $level; $i++)
						  print $spacer;

					  print "<i></i>".$row['name'] . "</label></div>\n";
					  $level++;
					  $this->getCatCheckList($key, $level, $spacer, $selected);
					  $level--;
				  }
			  }
			  unset($row);
		  }
	  }

	  /**
	   * Content::fetchProductCategories()
	   *
	   * @return
	   */
	  public function fetchProductCategories()
	  {

		  if ($result = self::$db->fetch_all("SELECT cid FROM " . self::rTable . " WHERE pid = ".Filter::$id)) {
			  $cids = array();
			  foreach ($result as $row) {
				  $cids[] = $row->cid;
			  }
			  unset($row);
			  $cids = implode(",", $cids);
		  } else {
			  $cids = "";
		  }
		  return $cids;

	  }

      /**
       * Content::getCategories()
       *
	   * @param mixed $array
	   * @param integer $parent_id
       * @return
       */
	  public function getCategories($array, $parent_id = 0, $menuid = 'main-menu', $class = 'top-menu')
	  {

		  $subcat = false;
		  $attr = (!$parent_id) ? ' class="' . $class . '" id="' . $menuid . '"' : ' class="menu-submenu"';
		  $attr2 = (!$parent_id) ? ' class="nav-item"' : ' class="nav-submenu-item"';

		  foreach ($array as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($subcat === false) {
					  $subcat = true;

					  print "<ul" . $attr . ">\n";
				  }
                  $active = ($row['slug'] == $this->catslug) ? " class=\"active\"" : "";
				  $url = (Registry::get('Core')->seo == 1) ? SITEURL . '/category/' . sanitize($row['slug']) . '/' :  SITEURL . '/category.php?catname=' . sanitize($row['slug']);

				  $link = '<a href="'.$url.'"' . $active . '>' . $row['name'] . '</a>';
				  print '<li' . $attr2 . '>';
				  print $link;
				  $this->getCategories($array, $key);
				  print "</li>\n";
			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }

	  public function getMainMenus($array, $parent_id = 0, $menuid = 'main-menu', $class = 'top-menu')
	  {
		  $subcat = false;
		  $attr = (!$parent_id) ? ' class="' . $class . '" id="' . $menuid . '"' : ' class="menu-submenu"';
		  $attr2 = (!$parent_id) ? ' class="nav-item"' : ' class="nav-submenu-item"';

		  foreach ($array as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($subcat === false) {
					  $subcat = true;

					  print "<ul" . $attr . ">\n";
				  }
                  $active = ($row['slug'] == $this->pageslug) ? " class=\"active\"" : "";

				  $link = '<a href="'. $row['url'] .'"' . $active . '>' . $row['name'] . '</a>';
				  print '<li' . $attr2 . '>';
				  print $link;
				  $this->getMainMenus($array, $key);
				  print "</li>\n";
			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }

      /**
       * Content::getCatDropList()
       *
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
       * @return
       */
	  public function getCatDropList($parent_id, $level = 0, $spacer, $selected = false)
	  {
		  if($this->cattree) {
			  foreach ($this->cattree as $key => $row) {
				  $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "" ;
				  if ($parent_id == $row['parent_id']) {
					  print "<option value=\"" . $row['id'] . "\"".$sel.">";

					  for ($i = 0; $i < $level; $i++)
						  print $spacer;

					  print $row['name'] . "</option>\n";
					  $level++;
					  $this->getCatDropList($key, $level, $spacer, $selected);
					  $level--;
				  }
			  }
			  unset($row);
		  }
	  }

	  /**
	   * Content::processCategory()
	   *
	   * @return
	   */
	  public function processCategory()
	  {

		  Filter::checkPost('name', Lang::$word->CAT_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'parent_id' => intval($_POST['parent_id']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['name']) : doSeo($_POST['slug']),
				  'description' => sanitize($_POST['description']),
				  'metakeys' => sanitize($_POST['metakeys']),
				  'metadesc' => sanitize($_POST['metadesc']),
				  'active' => intval($_POST['active'])
			  );

              if (empty($_POST['metakeys']) or empty($_POST['metadesc'])) {
                  include (BASEPATH . 'lib/class_meta.php');
                  parseMeta::instance($_POST['description']);
                  if (empty($_POST['metakeys'])) {
                      $data['metakeys'] = parseMeta::get_keywords();
                  }
                  if (empty($_POST['metadesc'])) {
                      $data['metadesc'] = parseMeta::metaText($_POST['description']);
                  }
              }

			  (Filter::$id) ? self::$db->update(self::cTable, $data, "id=" . Filter::$id) : self::$db->insert(self::cTable, $data);
			  $message = (Filter::$id) ? Lang::$word->CAT_UPDATED : Lang::$word->CAT_ADDED;

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }
      /**
       * Content::rendertCategories()
       *
       * @return
       */
      public function rendertCategories($catname, $cid)
      {

		  $pager = Paginator::instance();

		  $counter = countEntries(Products::pTable, "cid" ,$cid);
		  $pager->path = (Registry::get("Core")->seo) ? SITEURL . '/category/' . $catname . '/' : false;
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();

		  if (isset($_GET['sort'])) {
			  $val = explode("-", $_GET['sort']);
			  if (count($val) == 2) {
				  $sort = sanitize($val[0]);
				  $order = sanitize($val[1]);
				  if (in_array($sort, array("title", "price", "rating", "created"))) {
					  $ord = ($order == 'DESC') ? " DESC" : " ASC";
					  $sorting = "p." . $sort . $ord;
				  } else {
					  $sorting = " p.created DESC";
				  }
			  } else {
				  $sorting = " p.created DESC";
			  }
		  } else {
			  $sorting = " p.created DESC";
		  }

		  $sql = "SELECT p.*, p.id as pid,"
		  . "\n (SELECT COUNT(pid) FROM " . self::cmTable . " WHERE pid = p.id) as comments,"
		  . "\n (SELECT SUM(hits) FROM " . Products::sTable . " WHERE pid = p.id) as hits"
		  . "\n FROM " . Products::pTable . " as p"
		  . "\n INNER JOIN " . self::rTable . " rc ON p.id = rc.pid"
		  . "\n WHERE rc.cid = " . (int)$cid
		  . "\n AND p.active = '1'"
		  . "\n ORDER BY $sorting " . $pager->limit;

		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
      }

      /**
       * Content::getFileTree()
       *
       * @return
       */
      public function getFileTree()
	  {
		  global $db, $core;

		  $sql = "SELECT *, created as cdate FROM files ORDER BY name";
          $row = $db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getGateways()
       *
       * @return
       */
      public function getGateways($active = false)
      {
          global $db;

		  $where = ($active) ? "WHERE active = '1'" : null ;
          $sql = "SELECT * FROM " . self::gTable
		  . "\n " . $where
		  . "\n ORDER BY name";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }


	  /**
	   * Content::processGateway()
	   *
	   * @return
	   */
	  public function processGateway()
	  {

		  Filter::checkPost('displayname', Lang::$word->GTW_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
					  'displayname' => sanitize($_POST['displayname']),
					  'extra' => sanitize($_POST['extra']),
					  'extra2' => sanitize($_POST['extra2']),
					  'extra3' => sanitize($_POST['extra3']),
					  'demo' => intval($_POST['demo']),
					  'active' => intval($_POST['active'])
			  );

			  self::$db->update(self::gTable, $data, "id=" . Filter::$id);

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->GTW_UPDATED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

	  /**
	   * Content::createSiteMap()
	   *
	   * @return
	   */
	  public function createSiteMap()
	  {

		  $sql1 = "SELECT id, slug, created FROM pages ORDER BY created DESC";
		  $pages = self::$db->query($sql1);



		  $smap = "";

		  $smap .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		  $smap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\r\n";
		  $smap .= "<url>\r\n";
		  $smap .= "<loc>" . SITEURL . "/index.php</loc>\r\n";
		  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
		  $smap .= "</url>\r\n";

		  while ($row = self::$db->fetch($pages)) {
			  if (Registry::get("Core")->seo == 1) {
				  $url = SITEURL . '/content/' . $row->slug . '/';
			  } else
				  $url = SITEURL . '/content.php?pagename=' . $row->slug;

			  $smap .= "<url>\r\n";
			  $smap .= "<loc>" . $url . "</loc>\r\n";
			  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
			  $smap .= "<changefreq>weekly</changefreq>\r\n";
			  $smap .= "</url>\r\n";
		  }

		  /* while ($row = self::$db->fetch($items)) {
			  if (Registry::get("Core")->seo == 1) {
				  $url = SITEURL . '/product/' . $row->slug . '/';
			  } else
				  $url = SITEURL . '/item.php?itemname=' . $row->slug;

			  $smap .= "<url>\r\n";
			  $smap .= "<loc>" . $url . "</loc>\r\n";
			  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
			  $smap .= "<changefreq>weekly</changefreq>\r\n";
			  $smap .= "</url>\r\n";
		  }

		  while ($row = self::$db->fetch($cats)) {
			  if (Registry::get("Core")->seo == 1) {
				  $url = SITEURL . '/category/' . $row->slug . '/';
			  } else
				  $url = SITEURL . '/category.php?catname=' . $row->slug;

			  $smap .= "<url>\r\n";
			  $smap .= "<loc>" . $url . "</loc>\r\n";
			  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
			  $smap .= "<changefreq>weekly</changefreq>\r\n";
			  $smap .= "</url>\r\n";
		  } */

		  $smap .= "</urlset>";

		  return $smap;
	  }

      /**
       * Content::writeSiteMap()
       *
       * @return
       */
	  public function writeSiteMap()
	  {

		  $filename = BASEPATH . 'sitemap.xml';
		  if (is_writable($filename)) {
			  file_put_contents($filename, $this->createSiteMap());
			  $json['type'] = 'success';
			  $json['message'] = Filter::msgOk(Lang::$word->MTN_STM_OK, false);
		  } else {
			  $json['type'] = 'error';
			  $json['message'] = Filter::msgAlert(str_replace("[FILENAME]", $filename, Lang::$word->MTN_STM_ERR), false);
		  }

		  print json_encode($json);
	  }

	  /**
	   * Content::processNewsletter()
	   *
	   * @return
	   */
	  public function processNewsletter()
	  {


		   Filter::checkPost('subject', Lang::$word->NWL_SUBJECT);
		   Filter::checkPost('body', Lang::$word->NWL_BODY);

		  if (empty(Filter::$msgs)) {
				  $to = sanitize($_POST['recipient']);
				  $subject = sanitize($_POST['subject']);
				  $body = cleanOut($_POST['body']);
				  $numSent = 0;
				  $failedRecipients = array();

			  switch ($to) {
				  case "all":
					  require_once(BASEPATH . "lib/class_mailer.php");
					  $mailer = Mailer::sendMail();
					  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));

					  $sql = "SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE id != 1";
					  $userrow = self::$db->fetch_all($sql);

					  $replacements = array();
					  if($userrow) {
                          foreach ($userrow as $cols) {
                              $replacements[$cols->email] = array(
                                  '[NAME]' => $cols->name,
                                  '[SITE_NAME]' => Registry::get("Core")->site_name,
                                  '[URL]' => Registry::get("Core")->site_url);
                          }

						$decorator = new Swift_Plugins_DecoratorPlugin($replacements);
						$mailer->registerPlugin($decorator);

						$message = Swift_Message::newInstance()
								  ->setSubject($subject)
								  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
								  ->setBody($body, 'text/html');

						foreach ($userrow as $row) {
							$message->setTo(array($row->email => $row->name));
							$numSent++;
							$mailer->send($message, $failedRecipients);
						}
						unset($row);

					  }
					  break;

				  case "newsletter":
					  require_once(BASEPATH . "lib/class_mailer.php");
					  $mailer = Mailer::sendMail();
					  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));

					  $sql = "SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE newsletter = '1' AND id != 1";
					  $userrow = self::$db->fetch_all($sql);

					  $replacements = array();
					  if($userrow) {
                          foreach ($userrow as $cols) {
                              $replacements[$cols->email] = array(
                                  '[NAME]' => $cols->name,
                                  '[SITE_NAME]' => Registry::get("Core")->site_name,
                                  '[URL]' => Registry::get("Core")->site_url);
                          }

						  $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
						  $mailer->registerPlugin($decorator);

						  $message = Swift_Message::newInstance()
									->setSubject($subject)
									->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
									->setBody($body, 'text/html');

						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
					  }
					  break;

				  default:
					  require_once(BASEPATH . "lib/class_mailer.php");
					  $mailer = Mailer::sendMail();

					  $row = Registry::get("Database")->first("SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE email LIKE '%" . sanitize($to) . "%'");
					  if ($row) {
						  $newbody = str_replace(array(
							  '[NAME]',
							  '[SITE_NAME]',
							  '[URL]'), array(
							  $row->name,
							  Registry::get("Core")->site_name,
							  Registry::get("Core")->site_url), $body);

						  $message = Swift_Message::newInstance()
									->setSubject($subject)
									->setTo(array($to => $row->name))
									->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
									->setBody($newbody, 'text/html');

						  $numSent++;
						  $mailer->send($message, $failedRecipients);

					  }
					  break;
			  }

			  if ($numSent) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($numSent . ' ' . Lang::$word->MAIL_SENT, false);
			  } else {
				  $json['type'] = 'error';
				  $res = '';
				  $res .= '<ul>';
				  foreach ($failedRecipients as $failed) {
					  $res .= '<li>' . $failed . '</li>';
				  }
				  $res .= '</ul>';
				  $json['message'] = Filter::msgAlert(Lang::$word->NWL_SEND_ERR . $res, false);

				  unset($failed);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }

	  }

      /**
       * Content::getHomePage()
       *
       * @return
       */
      public function getHomePage()
      {
          $sql = "SELECT * FROM pages WHERE home_page = '1'";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getPages()
       *
       * @return
       */
      public function getPages()
      {
          $pager = Paginator::instance();
          $pager->items_total = countEntries(self::pTable);
          $pager->default_ipp = Registry::get("Core")->perpage;
          $pager->paginate();

          $sql = "SELECT p.*,pt.name as page_template FROM " . self::pTable ." as p LEFT JOIN " . self::ptTable ." as pt ON p.template=pt.slug ORDER BY title ASC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
      /**
       * Content::getPageTemplates()
       *
       * @return
       */
      public function getPageTemplates()
      {
          $pager = Paginator::instance();
          $pager->items_total = countEntries(self::ptTable);
          $pager->default_ipp = Registry::get("Core")->perpage;
          $pager->paginate();

          $sql = "SELECT * FROM " . self::ptTable ." ORDER BY name ASC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Content:::processPage()
	   *
	   * @return
	   */
	  public function processPage()
	  {

		  Filter::checkPost('title', Lang::$word->PAG_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'title' => sanitize($_POST['title']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['title']) : doSeo($_POST['slug']),
          'template' => sanitize($_POST['template']),
				  'body' => $_POST['body'],
				  'created' => sanitize($_POST['created_submit']),
				  'home_page' => intval($_POST['home_page']),
				  'breadcrumb' => intval($_POST['breadcrumb']),
				  'active' => intval($_POST['active'])
			  );


			  if ($data['home_page'] == 1) {
				  $home['home_page'] = "DEFAULT(home_page)";
				  self::$db->update(self::pTable, $home);
			  }

			  if (!Filter::$id) {
				  $data['created'] = "NOW()";
			  }

			  (Filter::$id) ? self::$db->update(self::pTable, $data, "id=" . Filter::$id) : self::$db->insert(self::pTable, $data);
			  $message = (Filter::$id) ? Lang::$word->PAG_UPDATED : Lang::$word->PAG_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

      /**
       * Content::getEmailTemplates()
       *
       * @return
       */
      public function getEmailTemplates()
      {
          $sql = "SELECT * FROM email_templates ORDER BY name ASC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Content:::processEmailTemplate()
	   *
	   * @return
	   */
	  public function processEmailTemplate()
	  {

		  Filter::checkPost('name', Lang::$word->ETP_NAME);
		  Filter::checkPost('subject', Lang::$word->ETP_SUBJECT);
		  Filter::checkPost('body', Lang::$word->ETP_BODY);

		  if (empty(Filter::$msgs)) {
			  $data = array(
					  'name' => sanitize($_POST['name']),
					  'subject' => sanitize($_POST['subject']),
					  'body' => $_POST['body'],
					  'help' => sanitize($_POST['help'])
			  );

			  self::$db->update(self::eTable, $data, "id=" . Filter::$id);

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->ETP_UPDATED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

      /**
       * Content::getNews()
       *
       * @return
       */
      public function getNews()
      {

          $sql = "SELECT * FROM " . self::nTable . " ORDER BY title ASC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::renderNews()
       *
       * @return
       */
      public function renderNews()
      {

          $sql = "SELECT * FROM " . self::nTable . " WHERE active = 1";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Content::processNews()
	   *
	   * @return
	   */
	  public function processNews()
	  {

		  Filter::checkPost('title', Lang::$word->NWS_NAME);
		  Filter::checkPost('created_submit', Lang::$word->CREATED);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'title' => sanitize($_POST['title']),
				  'author' => sanitize($_POST['author']),
				  'body' => $_POST['body'],
				  'created' => sanitize($_POST['created_submit']),
				  'active' => intval($_POST['active'])
			  );

			  if ($data['active'] == 1) {
				  $news['active'] = "DEFAULT(active)";
				  self::$db->update(self::nTable, $news);
			  }

			  (Filter::$id) ? self::$db->update(self::nTable, $data, "id='" . Filter::$id . "'") : self::$db->insert(self::nTable, $data);
			  $message = (Filter::$id) ? Lang::$word->NWS_UPDATED : Lang::$word->NWS_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

      /**
       * Content::getFaq()
       *
       * @return
       */
      public function getFaq()
      {

          $sql = "SELECT * FROM " . self::fqTable . " ORDER BY position";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Content::processFaq()
	   *
	   * @return
	   */
	  public function processFaq()
	  {

		  Filter::checkPost('question', Lang::$word->FAQ_QUEST);
		  Filter::checkPost('answer', Lang::$word->FAQ_ANSW);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'question' => sanitize($_POST['question']),
				  'answer' => $_POST['answer']
			  );

			  (Filter::$id) ? self::$db->update(self::fqTable, $data, "id='" . Filter::$id . "'") : self::$db->insert(self::fqTable, $data);
			  $message = (Filter::$id) ? Lang::$word->FAQ_UPDATED : Lang::$word->FAQ_ADDED;

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }
      /**
       * Content::getTestimonials()
       *
       * @return
       */
      public function getTestimonials()
      {

          $sql = "SELECT * FROM " . self::tesTable . " ORDER BY position";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Content::processTestimonials()
	   *
	   * @return
	   */
	  public function processTestimonials()
	  {

		  Filter::checkPost('name', 'Testimonial author name is required.');
		  Filter::checkPost('content', 'Testimonial content is required.');

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'company' => sanitize($_POST['company']),
				  'content' => $_POST['content']
			  );

			  (Filter::$id) ? self::$db->update(self::tesTable, $data, "id='" . Filter::$id . "'") : self::$db->insert(self::tesTable, $data);
			  $message = (Filter::$id) ? "Testimonial successfully updated." : 'Testimonial successfully inserted.';

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

	  /**
	   * Content::getCommentsConfig()
	   *
	   * @return
	   */
	  public function getCommentsConfig()
	  {

		  $sql = "SELECT * FROM comments_config";
          return $row = self::$db->first($sql);
	  }

	  /**
	   * Comments::processCommentConfig()
	   *
	   * @return
	   */
	  public function processCommentConfig()
	  {

		  Filter::checkPost('dateformat', Lang::$word->CMT_DATEF);

		  if (empty(Filter::$msgs)) {
			  $data = array(
					'username_req' => intval($_POST['username_req']),
					'email_req' => intval($_POST['email_req']),
					'show_captcha' => intval($_POST['show_captcha']),
					'show_www' => intval($_POST['show_www']),
					'show_username' => intval($_POST['show_username']),
					'show_email' => intval($_POST['show_email']),
					'auto_approve' => intval($_POST['auto_approve']),
					'notify_new' => intval($_POST['notify_new']),
					'public_access' => intval($_POST['public_access']),
					'sorting' => sanitize($_POST['sorting'],4),
					'blacklist_words' => trim($_POST['blacklist_words']),
					'char_limit' => intval($_POST['char_limit']),
					'perpage' => intval($_POST['perpage']),
					'dateformat' => sanitize($_POST['dateformat'])
			  );

			  self::$db->update("comments_config", $data);

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->CMT_UPDATEDC, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }
	  /**
	   * Content::getComments()
	   *
	   * @param bool $sort
	   * @return
	   */
	  public function getComments($from = false)
	  {
		  $pager = Paginator::instance();
		  $comconfig = $this->getCommentsConfig();
		  $total = (Filter::$id) ? countEntries(self::cmTable, "id", Filter::$id) : countEntries(self::cmTable);
		  $counter = $total;
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();


          if (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = $_POST['enddate_submit'];
              }
			  $where = (Filter::$id) ? " WHERE c.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND p.id = '" . Filter::$id . "'" : " WHERE c.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
          }
		  if (Filter::$id) {
			  $where = " WHERE p.id = " . Filter::$id;
		  } else
			  $where = (isset($where)) ? $where : null;

		  $sql = "SELECT c.*, c.id as cid, p.id as id, p.title"
		  . "\n FROM " . self::cmTable . " as c"
		  . "\n LEFT JOIN " . Products::pTable . " AS p ON p.id = c.pid"
		  . "\n $where"
		  . "\n ORDER BY c.created DESC" . $pager->limit;
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::keepTags()
	   *
	   * @param mixed $str
	   * @param mixed $tags
	   * @return
	   */
	  public function keepTags($string, $allowtags = null, $allowattributes = null)
	  {
		  $string = strip_tags($string, $allowtags);
		  if (!is_null($allowattributes)) {
			  if (!is_array($allowattributes))
				  $allowattributes = explode(",", $allowattributes);
			  if (is_array($allowattributes))
				  $allowattributes = implode(")(?<!", $allowattributes);
			  if (strlen($allowattributes) > 0)
				  $allowattributes = "(?<!" . $allowattributes . ")";
			  $string = preg_replace_callback("/<[^>]*>/i", create_function('$matches', 'return preg_replace("/ [^ =]*' . $allowattributes . '=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'), $string);
		  }
		  return $string;
	  }

	  /**
	   * Content::censored()
	   *
	   * @param mixed $string
	   * @param mixed $words
	   * @return
	   */
	  public function censored($string, $words)
	  {
		  $array = explode("\r\n",$words);
		  reset($array);

		  foreach ($array as $row) {
			  $string = preg_replace("`$row`", "***", $string);
		  }
		  unset($row);
		  return $string;
	  }

	  /**
	   * Content::getDiscounts()
	   *
	   * @return
	   */
	  public function getDiscounts()
	  {

		  $sql = "SELECT * FROM " . self::cpTable;
          $row = self::$db->fetch_all($sql);

		   return ($row) ? $row : 0;
	  }

	  /**
	   * Content::processDiscount()
	   *
	   * @return
	   */
	  public function processDiscount()
	  {

		  Filter::checkPost('title', Lang::$word->CPN_NAME);
		  Filter::checkPost('code', Lang::$word->CPN_CODE);
		  Filter::checkPost('discount', Lang::$word->CPN_DISC);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'title' => sanitize($_POST['title']),
				  'code' => sanitize($_POST['code']),
				  'discount' => intval($_POST['discount']),
				  'type' => intval($_POST['type']),
				  'validuntil' => (empty($_POST['validuntil_submit'])) ? "0000-00-00" : sanitize($_POST['validuntil_submit']),
				  'minval' => (empty($_POST['minval'])) ? 0.00 : floatval($_POST['minval']),
				  'active' => intval($_POST['active'])
			  );

			  if(!Filter::$id) {
				  $data['created'] = "NOW()";
			  }

			  (Filter::$id) ? self::$db->update(self::cpTable, $data, "id=" . Filter::$id) : self::$db->insert(self::cpTable, $data);
			  $message = (Filter::$id) ? Lang::$word->CPN_UPDATED : Lang::$word->CPN_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

	  /**
	   * Content::sliderConfiguration()
	   *
	   * @return
	   */
	  public function sliderConfiguration()
	  {

		  $sql = "SELECT * FROM " . self::slcTable;
          $row = self::$db->first($sql);

		   return ($row) ? $row : 0;
	  }

	  /**
	   * Content::processSliderConfiguration()
	   *
	   * @return
	   */
	  public function processSliderConfiguration()
	  {

		  Filter::checkPost('slideTransition', Lang::$word->SLM_TRANS);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'sliderHeight' => intval($_POST['sliderHeight']),
				  'sliderHeightAdaptable' => intval($_POST['sliderHeightAdaptable']),
				  'sliderAutoPlay' => intval($_POST['sliderAutoPlay']),
				  'waitForLoad' => intval($_POST['waitForLoad']),
				  'slideTransition' => sanitize($_POST['slideTransition']),
				  'slideTransitionDirection' => sanitize($_POST['slideTransitionDirection']),
				  'slideTransitionSpeed' => intval($_POST['slideTransitionSpeed']),
				  'slideTransitionDelay' => intval($_POST['slideTransitionDelay']),
				  'slideTransitionEasing' => sanitize($_POST['slideTransitionEasing']),
				  'slideImageScaleMode' => sanitize($_POST['slideImageScaleMode']),
				  'slideShuffle' => intval($_POST['slideShuffle']),
				  'slideReverse' => intval($_POST['slideReverse']),
				  'showFilmstrip' => intval($_POST['showFilmstrip']),
				  'showCaptions' => intval($_POST['showCaptions']),
				  'simultaneousCaptions' => intval($_POST['simultaneousCaptions']),
				  'showTimer' => intval($_POST['showTimer']),
				  'showPause' => intval($_POST['showPause']),
				  'showArrows' => intval($_POST['showArrows']),
				  'showDots' => intval($_POST['showDots']),
			  );

			  self::$db->update(self::slcTable, $data);

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->SLM_CONF_UPDATED, false);
			  } else {
				  $json['type'] = 'info';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

      /**
       * Content::getSlides()
       *
       * @return
       */
	   public function getSlides()
	  {

          $sql = "SELECT * FROM " . self::slTable
		  . "\n ORDER BY sorting";
          $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getImageInfo()
       *
       * @return
       */
      public function getImageInfo()
      {
		  $row = Core::getRowById(self::slTable, Filter::$id);
          if(file_exists($file = UPLOADS . 'slider/' . $row->thumb)) {
			  $link = UPLOADURL . 'slider/' . $row->thumb;

			  print "
			  <div id=\"filedetails\">
				<form class=\"xform modal\" id=\"admin_form\" method=\"post\">
				  <div class=\"row\">
					<section class=\"col col-4\">
					  <figure>";
						list($w, $h) = @getimagesize(UPLOADS . 'slider/' . $row->thumb);
						$resolution = "<li>Resolution: " . $w . " x " . $h . "</li>";
						print "<a href=\"" . $link . "\"  class=\"fancybox\" title=\"" . $row->caption . "\"> <img src=\"" . $link . "\" alt=\"\" style=\"max-width:100%\"/></a>";
						print "
					  </figure>
					  <figcaption>
						<ul>
						  " . $resolution . "
						  <li>" . Lang::$word->GAL_SIZE . ": " . getSize(filesize(UPLOADS . 'slider/' . $row->thumb)) . "</li>
						  <li>" . Lang::$word->GAL_TYPE . ": " . getMIMEtype($row->thumb) . "</li>
						  <li>" . Lang::$word->GAL_FILELM . ": " . date('d-m-Y', filemtime(UPLOADS . 'slider/' . $row->thumb)) . "</li>
						</ul>
					  </figcaption>
					</section>
					<section class=\"col col-8\">
					  <div class=\"row\">
						<section class=\"col col-12\">
						  <label class=\"input\">
							<input type=\"text\" name=\"filename\" value=\"" . $row->caption . "\"> </label>
						  <div class=\"note\">" . Lang::$word->GAL_NAME . "</div>
						</section>
					  </div>
					  <div class=\"row\">
						<section class=\"col col-12\">
						  <label class=\"input state-disabled\">
							<input type=\"text\" name=\"filepath\" value=\"" . UPLOADS . 'slider/' . $row->thumb . "\" readonly=\"readonly\"> </label>
						  <div class=\"note\">" . Lang::$word->GAL_PATH . "</div>
						</section>
					  </div>
					  <div class=\"row\">
						<section class=\"col col-12\">
						  <label class=\"input state-disabled\">
							<input type=\"text\" name=\"fileurl\" value=\"" . $link . "\" readonly=\"readonly\">
						  </label>
						  <div class=\"note\">" . Lang::$word->GAL_URL . "</div>
						</section>
					  </div>
					  <div class=\"row\">
						<section class=\"col col-12\">
						  <label class=\"checkbox\">
							<input name=\"delfile_yes\" type=\"checkbox\" value=\"1\" class=\"checkbox\"/>
							<i></i>" . Lang::$word->GAL_DELIMG . "</label>
						  <div class=\"note note-error\">" . Lang::$word->GAL_DELIMG_T . "</div>
						</section>
					  </div>
					</section>
				  </div>
				  <input name=\"id\" type=\"hidden\" value=\"" . Filter::$id . "\" />
				  <input name=\"doSliderImage\" type=\"hidden\" value=\"1\" />
				</form>
			  </div>
			  ";
		  } else {
			  Filter::msgError(Lang::$word->GAL_IMGERROR);
		  }

      }

	  /**
	   * Slider::processSlide()
	   *
	   * @return
	   */
	  public function processSlide()
	  {

		  Filter::checkPost('caption', Lang::$word->SLM_NAME);

		  if (!Filter::$id) {
			  if (empty($_FILES['thumb']['name']))
				  Filter::$msgs['thumb'] = Lang::$word->SLM_IMG_SEL;
		  }

		  if (!empty($_FILES['thumb']['name'])) {
			  if (!preg_match("/(\.jpg|\.png)$/i", $_FILES['thumb']['name'])) {
				  Filter::$msgs['thumb'] = Lang::$word->CONF_LOGO_R;
			  }
			  $file_info = getimagesize($_FILES['thumb']['tmp_name']);
			  if (empty($file_info))
				  Filter::$msgs['thumb'] = Lang::$word->CONF_LOGO_R;
		  }

		  if (empty(Filter::$msgs)) {
			  $data['caption'] = sanitize($_POST['caption']);
			  $data['body'] = sanitize($_POST['body']);
			  $data['alignment'] = sanitize($_POST['alignment']);

			  if (isset($_POST['urltype']) && $_POST['urltype'] == "int" && isset($_POST['page_id'])) {
				  $slug = getValueByID("slug", Products::pTable, (int)$_POST['page_id']);
				  $data['button_text'] = ($_POST['button_text'] != '') ? $_POST['button_text'] : '';
          $data['url'] = $slug;
				  $data['urltype'] = "int";
				  $data['page_id'] = intval($_POST['page_id']);
			  } elseif (isset($_POST['urltype']) && $_POST['urltype'] == "ext" && isset($_POST['url'])) {
          $data['button_text'] = ($_POST['button_text'] != '') ? $_POST['button_text'] : '';
				  $data['url'] = sanitize($_POST['url']);
				  $data['urltype'] = "ext";
				  $data['page_id'] = "DEFAULT(page_id)";
			  } else {
          $data['button_text'] = 'GET STARTED';
				  $data['url'] = "#";
				  $data['urltype'] = "nourl";
				  $data['page_id'] = "DEFAULT(page_id)";
			  }

			  // Procces Image
			  if (!empty($_FILES['thumb']['name'])) {
				  $filedir = UPLOADS . "slider/";
				  $newName = "IMG_" . randName();
				  $ext = substr($_FILES['thumb']['name'], strrpos($_FILES['thumb']['name'], '.') + 1);
				  $fullname = $filedir . $newName . "." . strtolower($ext);

				  if (Filter::$id and $file = getValueById("thumb", self::slTable, Filter::$id)) {
					  @unlink($filedir . $file);
				  }

				  if (!move_uploaded_file($_FILES['thumb']['tmp_name'], $fullname)) {
					  die(Filter::msgError(Lang::$word->SLM_FILE_ERR, false));
				  }
				  $data['thumb'] = $newName . "." . strtolower($ext);
			  }

			  (Filter::$id) ? self::$db->update(self::slTable, $data, "id=" . Filter::$id) : $lastid = self::$db->insert(self::slTable, $data);
			  $message = (Filter::$id) ? Lang::$word->SLM_UPDATED : Lang::$word->SLM_ADDED;

			  if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

      /**
       * Content::getContentType()
       *
	   * @param bool $selected
       * @return
       */
      public static function getContentType($selected = false)
	  {
		  $arr = array(
				'page' => Lang::$word->MNU_TYPE_PG,
				'web' => Lang::$word->MNU_TYPE_EL
		  );

		  $html = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }


      /**
       * Content::getTagName()
       *
       * @return
       */
      public function getTagName()
      {
		  $sql = "SELECT tag FROM " . Products::tagTable . " WHERE tag = '" . self::$db->escape($this->tag) . "'";
		  $row = self::$db->first($sql);

		  return ($row) ? $row : 0;
      }

	  /**
	   * Content::getProductList()
	   *
	   * @return
	   */
	  public static function getProductList($id, $selected = false)
	  {

		  $sql = "SELECT id, slug, title FROM " . Products::pTable;
		  $result = self::$db->fetch_all($sql);

		  $display = '';
		  if ($result) {
			  $display .= "<select name=\"page_id\">";
			  foreach ($result as $row) {
				  $sel = ($row->$id == $selected) ? ' selected="selected"' : null;
				  $display .= "<option value=\"" . $row->$id . "\"" . $sel . ">" . $row->title . "</option>\n";
			  }

			  $display .= "</select>\n";
		  }
		  return $display;

	  }

	  /**
	   * Content::getPagesList()
	   *
	   * @return
	   */
	  public static function getPagesList($id, $selected = false)
	  {

		  $sql = "SELECT id, slug, title FROM " . self::pTable;
		  $result = self::$db->fetch_all($sql);

		  $display = '';
		  if ($result) {
			  $display .= "<select name=\"page_id\">";
			  foreach ($result as $row) {
				  $sel = ($row->$id == $selected) ? ' selected="selected"' : null;
				  $display .= "<option value=\"" . $row->$id . "\"" . $sel . ">" . $row->title . "</option>\n";
			  }

			  $display .= "</select>\n";
		  }
		  return $display;

	  }

	  /**
	   * Content::renderPages()
	   *
	   * @return
	   */
	  public function renderPages()
	  {

		  $sql = "SELECT p.*,t.directory as directory,t.template as template_file,t.filename FROM " . self::pTable
		  . "\n as p LEFT JOIN " . self::ptTable . " as t ON p.template=t.slug WHERE  p.slug = '" . $this->pageslug . "'"
		  . "\n AND active = '1'";
		  $row = self::$db->first($sql);

		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getCartCounter()
       *
       * @return
       */
	  public function getCartCounter()
	  {
		  if ($row = self::$db->first("SELECT sum(price) as ptotal, COUNT(*) as itotal FROM " . self::crTable . " WHERE user_id = '" . Registry::get("Users")->sesid . "' GROUP BY user_id")) {
			  $itotal = ($row->itotal == 0) ? '/' : $row->itotal;
			  $ptotal = ($row->ptotal == 0) ? '/' : Registry::get("Core")->formatMoney($row->ptotal);
			  print $itotal . ' item(s) / ' . $ptotal;
		  } else {
			  print '0 ' . Lang::$word->ITEMS . ' / ' . Registry::get("Core")->cur_symbol . '0.00';
		  }
	  }

      /**
       * Content::getCartContent()
       *
       * @param mixed $sesid
       * @return
       */
      public function getCartContent($sesid = false)
      {

		  $uid = ($sesid) ? $sesid : Registry::get("Users")->sesid;

		  $sql = "SELECT c.*, p.id as pid, p.name as title, p.price, COUNT(c.pid) as total"
		  . "\n FROM " . self::crTable . " as c"
		  . "\n LEFT JOIN " . Jobs::pTable . " as p ON p.id = c.pid"
		  . "\n WHERE c.user_id = '" . self::$db->escape($uid) . "' AND p.price = c.price"
		  . "\n GROUP BY c.pid ORDER BY c.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getCartTotal()
       *
       * @param mixed $sesid
       * @return
       */
      public function getCartTotal($sesid = false)
      {
          global $db, $user;

		  $uid = ($sesid) ? $sesid : $user->sesid;

		  $sql = "SELECT sum(c.price) as total, COUNT(c.pid) as titems, e.coupon, sum(p.price) as ptotal"
		  . "\n FROM cart as c"
		  . "\n LEFT JOIN extras as e ON e.user_id = c.user_id"
		  . "\n LEFT JOIN products as p ON p.id = c.pid"
		  . "\n WHERE c.user_id = '" . $db->escape($uid) . "' AND p.price = c.price"
		  . "\n GROUP BY c.user_id";

          $row = $db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getCart()
       *
	   * @param bool $uid
       * @return
       */
	  public static function getCart($uid = false)
	  {
		  $id = ($uid) ? sanitize($uid) : Registry::get("Users")->sesid;
		  $row = Registry::get("Database")->first("SELECT * FROM " . Content::exTable . " WHERE user_id = '" . $id . "'");

		  return ($row) ? $row : 0;
	  }

      /**
       * Content::renderCart()
       *
       * @return
       */
	  public function renderCart()
	  {
		  $sql = "SELECT p.id as pid, p.title, p.slug, p.price, p.thumb,"
		  . "\n COUNT(c.pid) as total"
		  . "\n FROM " . Products::pTable . " as p"
		  . "\n LEFT JOIN " . self::crTable . " as c ON p.id = c.pid"
		  . "\n WHERE c.user_id = '" . self::$db->escape(Registry::get("Users")->sesid) . "' AND p.price = c.price"
		  . "\n GROUP BY c.pid ORDER BY c.id DESC";

		  $row = self::$db->fetch_all($sql);

		  if($row) {
			  return $row;
		  } else {
			  return 0;
		  }
	  }

      /**
       * Content::calculateTax()
       *
	   * @param bool $uid
       * @return
       */
	  public static function calculateTax($uid = false)
	  {
		  if(Registry::get("Core")->tax and Registry::get("Users")->logged_in) {
			  if ($uid) {
				  $cnt = Registry::get("Database")->first("SELECT country FROM " . Users::uTable . " WHERE id = " . $uid);
				  $row = Registry::get("Database")->first("SELECT vat FROM " . Content::cnTable . " WHERE abbr = '" . $cnt->country . "'");
			  } else {
				  $row = Registry::get("Database")->first("SELECT vat FROM " . Content::cnTable . " WHERE abbr = '" . Registry::get("Users")->country . "'");
			  }

			  return ($row->vat / 100);
		  } else {
			  return 0.00;
		  }
	  }

      /**
       * Content::getSearchResults()
       *
       * @return
       */
      public function getSearchResults($keyword)
      {

          $row = self::$db->fetch_all("SELECT * ,MATCH(title, body) AGAINST('$keyword') AS score, id as pid,"
		  . "\n (SELECT COUNT(pid) FROM " . Content::cmTable . " WHERE pid = id) as comments,"
		  . "\n (SELECT SUM(hits) FROM " . Products::sTable . " WHERE pid = id) as hits"
		  . "\n FROM " . Products::pTable
		  . "\n WHERE MATCH(title, body) AGAINST('$keyword)' IN BOOLEAN MODE) AND active = 1"
		  . "\n ORDER BY score ASC"
		  . "\n LIMIT 20");

          return ($row) ? $row : 0;
      }

      /**
       * Content::renderMetaData()
       *
       * @return
       */
	  public function renderMetaData($row)
	  {
		  //$row = isset($row) ? $row : null;

		  $sep = " | ";
		  $meta = "<meta charset=\"utf-8\">\n";
		  $meta .= "<title>" . Registry::get("Core")->site_name;

		  if ($this->catslug and $row) {
			  $meta .= $sep . $row->name;
		  } elseif ($this->pageslug and $row) {
			  $meta .= $sep . $row->title;
		  } elseif ($this->tag and $row) {
			  $meta .= $sep . $row->tag;
		  }
		  $meta .= "</title>\n";
		  $meta .= "<meta name=\"keywords\" content=\"";
		  if ($this->catslug and $row) {
			  if ($row->metakeys) {
				  $meta .= $row->metakeys;
			  } else {
				  $meta .= Registry::get("Core")->metakeys;
			  }

		  } else{
			  $meta .= Registry::get("Core")->metakeys;
		  }
		  $meta .= "\" />\n";
		  $meta .= "<meta name=\"description\" content=\"";
		  if ($this->catslug and $row) {
			  if ($row->metadesc) {
				  $meta .= $row->metadesc;
			  } else {
				  $meta .= Registry::get("Core")->metakeys;
			  }

		  } else{
			  $meta .= Registry::get("Core")->metakeys;
		  }
		  $meta .= "\" />\n";
		  $meta .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . UPLOADURL ."favicon.png\" />\n";
		  $meta .= "<meta name=\"dcterms.rights\" content=\"" . Registry::get("Core")->company . " &copy; All Rights Reserved\" >\n";
		  $meta .= "<meta name=\"robots\" content=\"index, follow\" />\n";
		  $meta .= "<meta name=\"revisit-after\" content=\"1 day\" />\n";
		  $meta .= "<meta name=\"generator\" content=\"Powered by Job Board v" . Registry::get("Core")->version . "\" />\n";
		  $meta .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\" />\n";
		  return $meta;
	  }


      public function getMessages()
      {
          $q = "SELECT COUNT(messages.uid1) as total"
          . "\n FROM messages, users"
          . "\n WHERE ((messages.user1='" . Registry::get("Users")->uid . "'"
          . "\n AND users.id=messages.user2) OR (messages.user2='" . Registry::get("Users")->uid . "'"
          . "\n AND users.id=messages.user1))"
          . "\n AND messages.uid2='1'"
          . "\n LIMIT 1";

          $record = self::$db->query($q);
          $total = self::$db->fetchrow($record);
          $counter = $total[0];


          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = Registry::get("Core")->perpage;
          $pager->paginate();

          $sql = "SELECT m1.uid1,m1.id, m1.msgsubject, m1.created, m1.user1read, m1.user2read, m1.user1, m1.user2, m1.uid2,"
          . "\n CONCAT(users.fname,' ',users.lname) as name,"
          . "\n COUNT(m2.uid1) as replies, users.id as userid,"
          . "\n users.username FROM messages as m1, messages as m2,users"
          . "\n WHERE ((m1.user1='" . Registry::get("Users")->uid . "'"
          . "\n AND users.id=m1.user2) OR (m1.user2='" . Registry::get("Users")->uid . "'"
          . "\n AND users.id=m1.user1))"
          . "\n AND m1.uid2='1'"
          . "\n AND m2.uid1=m1.uid1"
          . "\n GROUP BY m1.uid1"
          . "\n ORDER BY m1.created DESC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      public function processMessage()
      {
          if (empty($_POST['recipient']))
              Filter::$msgs['recipient'] = lang('MSG_RECEPIENT_R');

          if (empty($_POST['msgsubject']))
              Filter::$msgs['msgsubject'] = lang('MSG_MSGERR1');

          if (empty($_POST['body']))
              Filter::$msgs['body'] = lang('MSG_MSGERR2');

          if (empty(Filter::$msgs)) {
              if (Filter::$id and isset($_POST['update'])) {
                  $data = array(
                      'uid1' => Filter::$id,
                      'uid2' => intval($_POST['uid2']),
                      'msgsubject' => "",
                      'user1' => Registry::get("Users")->uid,
                      'user2' => 0,
                      'body' => $_POST['body'],
                      'created' => "NOW()",
                      'user1read' => "yes",
                      'user2read' => "no",
                      );
                  self::$db->insert("messages", $data);

                  $data2 = array('user' . intval($_POST['userp']) . 'read' => "no");
                  self::$db->update("messages", $data2, "uid1='" . Filter::$id . "' AND uid2 = '1'");

                  $sql = "SELECT email, CONCAT(fname,' ',lname) as clientname FROM users WHERE id = " . (int)$_POST['recipient'];
                  $userdata = self::$db->first($sql);

                  // Message Notification
                  require_once(BASEPATH . "lib/class_mailer.php");
                  $row = Registry::get("Core")->getRowById(Content::eTable, 12);
                  $body = str_replace(
                        array(
                              '[NAME]',
                              '[URL]',
                              '[SITE_NAME]'),
                        array(
                              $userdata->clientname,
                              SITEURL,
                              Registry::get("Core")->site_name),
                        $row->body
                    );

                  $newbody = cleanOut($body);
                  $subject = 'New Message - ' . cleanOut($_POST['msgsubject']);

                  $mailer = Mailer::sendMail();
                  $message = Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setTo(array($userdata->email => $userdata->clientname))
                            ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
                            ->setBody($newbody, 'text/html');

                  $mailer->send($message);

              } else {
                  $single = self::$db->first("SELECT COUNT(id) as recip, id as recipid, (SELECT COUNT(*) FROM messages) as newmsg FROM users where id='" . intval($_POST['recipient']) . "'");
                  $data = array(
                      'uid1' => intval($single->newmsg + 1),
                      'uid2' => 1,
                      'msgsubject' => sanitize($_POST['msgsubject']),
                      'user1' => Registry::get("Users")->uid,
                      'user2' => intval($single->recipid),
                      'body' => $_POST['body'],
                      'created' => "NOW()",
                      'user1read' => "yes",
                      'user2read' => "no",
                      );

                  self::$db->insert("messages", $data);

                  $sql = "SELECT email, CONCAT(fname,' ',lname) as clientname FROM users WHERE id = " . (int)$_POST['recipient'];
                  $userdata = self::$db->first($sql);

                  // Message Notification
                  require_once(BASEPATH . "lib/class_mailer.php");
                  $row = Registry::get("Core")->getRowById(Content::eTable, 12);
                  $body = str_replace(
                        array(
                              '[NAME]',
                              '[URL]',
                              '[SITE_NAME]'),
                        array(
                              $userdata->clientname,
                              SITEURL,
                              Registry::get("Core")->site_name),
                        $row->body
                    );

                  $newbody = cleanOut($body);
                  $subject = 'New Message - ' . cleanOut($_POST['msgsubject']);

                  $mailer = Mailer::sendMail();
                  $message = Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setTo(array($userdata->email => $userdata->clientname))
                            ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
                            ->setBody($newbody, 'text/html');

                  $mailer->send($message);

              }

              (self::$db->affected()) ? Filter::msgOk('Message successfully sent.') : Filter::msgAlert('Nothing to process.');

          } else
              print Filter::msgStatus();
      }

      public function renderMessages()
      {
          $sql = "SELECT m.created, m.body, m.attachment, u.id as userid, u.username"
          . "\n FROM messages as m, users AS u"
          . "\n WHERE m.uid1='" . Filter::$id. "'"
          . "\n AND u.id=m.user1"
          . "\n ORDER BY m.uid2";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      public function getMessageById()
      {

          $sql = "SELECT msgsubject, user1, user2 FROM messages WHERE uid1='" . Filter::$id . "' AND uid2=1";
          $row = self::$db->first($sql);

          return ($row) ? $row : Filter::error("You have selected an Invalid Id","Content::getMessageById()");
      }

      public function updateMessageStatus($user_id)
      {
          if ($user_id == Registry::get("Users")->uid) {
              $data['user1read'] = "yes";
              self::$db->update("messages", $data, "uid1='" . Filter::$id . "' AND uid2 = '1'");
              return 2;
          } else {
              $data['user2read'] = "yes";
              self::$db->update("messages", $data, "uid1='" . Filter::$id . "' AND uid2 = '1'");
              return 1;
          }
      }












  }
?>
