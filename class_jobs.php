<?php
  /**
   * Jobs Class
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Jobs
  {
      const jTable         = "jobs";
	  const aTable         = "job_applications";
	  const cTable         = "job_categories";
	  const lTable         = "job_locations";
	  const tTable         = "job_types";
	  const sTable         = "job_skills";
	  const uTable         = "users";
	  const comTable       = "companies";
	  const bmTable        = "bookmarks";
	  const msgTable       = "messages";
	  const rTable         = "resumes";
	  const pTable         = "packages";
	  const sbTable        = "subscriptions";
	  const txnTable       = "transactions";

	  //Jobs
	  private $cattree = array();
	  private $locationtree = array();
	  private $typetree = array();
	  private $packagetree = array();
	  private $skilltree = array();

	  private static $db;
      /**
       * Products::__construct()
       *
       * @return
       */
      public function __construct()
      {
          self::$db = Registry::get("Database");
		  $this->cattree = $this->getJobCatTree();
		  $this->locationtree = $this->getJobLocTree();
		  $this->typetree = $this->getJobTypeTree();
		  $this->packagetree = $this->getJobPackageTree();
		  $this->skilltree = $this->getJobSkillTree();
      }

	  /**
	   * Jobs::addJob()
	   *
	   * @return
	   */

    /**
    * Jobs::jobPostCheck()
    *
    * @return
    */
    public function jobPostCheck()
    {
        $uid = Registry::get("Users")->uid;
        $sql = "SELECT * FROM " . self::sbTable . " WHERE `uid` = " . $uid . " AND `limit` > `usage` ORDER BY `end_date` ASC";
        $row = self::$db->first($sql);
        //print_r($row);
  		  return ($row) ? $row : 0;

    }


	  public function addJob()
	  {
        Filter::checkPost('title', 'Job title is required');
        Filter::checkPost('location', 'Job location is required');
        Filter::checkPost('type', 'Job type is required');
        Filter::checkPost('categories', 'Job category is required');
        Filter::checkPost('salary', 'Salary is required');
        Filter::checkPost('description', 'Job description is required');
        Filter::checkPost('publish_date', 'Job publication date is required');
        Filter::checkPost('expire_date', 'Application closing date is required');

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'company' 		=> Registry::get("Users")->uid,
				  'title' 			=> sanitize($_POST['title']),
				  'location' 		=> sanitize($_POST['location']),
				  'type' 			=> sanitize($_POST['type']),
				  'categories' 		=> implode(",", $_POST['categories']),
				  'skills' 			=> implode(",", $_POST['skills']),
				  'salary' 			=> sanitize($_POST['salary']),
				  'description' 	=> sanitize($_POST['description']),
				  'responsibility' 	=> sanitize($_POST['responsibility']),
				  'experience' 		=> sanitize($_POST['experience']),
				  'education' 		=> sanitize($_POST['education']),
				  'benefits' 		=> sanitize($_POST['benefits']),
				  'additional_info' => sanitize($_POST['additional_info']),
				  'apply_url' 		=> sanitize($_POST['apply_url']),
				  'publish_date' 	=> date ("Y-m-d H:i:s", strtotime($_POST['publish_date'])),
				  'expire_date' 	=> date ("Y-m-d H:i:s", strtotime($_POST['expire_date'])),
				  'created' 		=> "NOW()"
			  );

                $limit = $this->jobPostCheck();
                if ( $limit->limit > $limit->usage ) {
                    self::$db->insert(self::jTable, $data);
                    $update = "UPDATE " . self::sbTable . " SET `usage` = `usage` + 1 WHERE id = $limit->id";
                    self::$db->query($update);
                }

			  $lastid = self::$db->insertid();

			  // New Job Mail Notification
			  /* require_once(BASEPATH . "lib/class_mailer.php");

			  $actlink = SITEURL . "/activate.php?token=" . $token . "&email=" . $data['email'];
			  $row = Registry::get("Core")->getRowById(Content::eTable, 1);

			  $body = str_replace(array(
				  '[NAME]',
				  '[USERNAME]',
				  '[PASSWORD]',
				  '[TOKEN]',
				  '[EMAIL]',
				  '[URL]',
				  '[LINK]',
				  '[SITE_NAME]'), array(
				  $data['fname'] . ' ' . $data['lname'],
				  $data['username'],
				  $_POST['pass'],
				  $token,
				  $data['email'],
				  SITEURL,
				  $actlink,
				  Registry::get("Core")->site_name), $row->body);

			  $newbody = cleanOut($body);

			  $mailer = Mailer::sendMail();
			  $message = Swift_Message::newInstance()
						->setSubject($row->subject)
						->setTo(array($data['email'] => $data['username']))
						->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
						->setBody($newbody, 'text/html');

			  $mailer->send($message); */

			  if (self::$db->affected()) {
				  $pmsg = '<div class="pmsg psuccess"><i class="fa fa-check"></i> New job successfully posted.</div>';
			  } else {
				  $pmsg = '<div class="pmsg pwarning"><i class="fa fa-exclamation-triangle"></i> Nothing to process.</div>';
			  }
			 return $lastid;

		  } else {
			  $pmsg = Filter::msgStatus();
			  return $pmsg;
		  }
	  }


    public function adminUpdateJob()
    {
        Filter::checkPost('title', 'Job title is required');
        Filter::checkPost('location', 'Job location is required');
        Filter::checkPost('categories', 'Job category is required');
        Filter::checkPost('type', 'Job type is required');
        Filter::checkPost('salary', 'Salary is required');
        Filter::checkPost('description', 'Job description is required');

        if (empty(Filter::$msgs)) {
            $data = array(
                'title' 			=> sanitize($_POST['title']),
                'location'          => sanitize($_POST['location']),
                'type'              => sanitize($_POST['type']),
                'categories' 		=> implode(",", $_POST['categories']),
                'skills'            => implode(",", $_POST['skills']),
                'salary' 			=> sanitize($_POST['salary']),
                'description'       => sanitize($_POST['description']),
                'responsibility' 	=> sanitize($_POST['responsibility']),
                'experience' 		=> sanitize($_POST['experience']),
                'education' 		=> sanitize($_POST['education']),
                'benefits'          => sanitize($_POST['benefits']),
                'additional_info'   => sanitize($_POST['additional_info']),
                'apply_url' 		=> sanitize($_POST['apply_url'])
            );

            self::$db->update(self::jTable, $data, "id=" . Filter::$id);
            $message = 'Job details successfully updated.';

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
     * User::approveJob()
     */
    public function approveJob()
    {
        $data['status'] = $_POST['approveJob'];
        self::$db->update(self::jTable, $data, "id = '" . Filter::$id . "'");

        // require_once (BASEPATH . "lib/class_mailer.php");
        // $row = Registry::get("Core")->getRowById(Content::eTable, 12);
        // $usr = Registry::get("Core")->getRowById(self::uTable, $jobrow->company);
        //
        // $body = str_replace(array(
        //   '[NAME]',
        //   '[URL]',
        //   '[SITE_NAME]'), array(
        //   $usr->fname . ' ' .$usr->lname,
        //   SITEURL,
        //   Registry::get("Core")->site_name), $row->body);
        //
        // $newbody = cleanOut($body);
        //
        // $mailer = $mail->sendMail();
        // $message = Swift_Message::newInstance()
        //     ->setSubject('Your job post has been approved')
        //     ->setTo(array($usr->email => $usr->username))
        //     ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
        //     ->setBody($newbody, 'text/html');
        //
        // $mailer->send($message);

        //if(self::$db->affected() && $mailer->send($message)) {
        if(self::$db->affected()) {
            $json['type'] = 'success';
            $json['title'] = 'Job Status';
            $json['message'] =  "Job post " . $_POST['approveJob'];
        } else {
            $json['type'] = 'error';
            $json['title'] = 'Job Status';
            $json['message'] = "Job post status change error.";
        }
        echo json_encode($json);
    }

    public function toggleJobFeatured() {
        $data['featured'] = $_POST['toggleJobFeatured'];
        self::$db->update(self::jTable, $data, "id = '" . Filter::$id . "'");

        if(self::$db->affected()) {
            $json['type'] = 'success';
            $json['title'] = 'Job Featured';
            $json['message'] =  "Job post " . $_POST['toggleJobFeatured'];
        } else {
            $json['type'] = 'error';
            $json['title'] = 'Error';
            $json['message'] = "Job post status change error.";
        }
        echo json_encode($json);
    }

    public function toggleResumeFeatured() {
        $data['featured'] = $_POST['toggleResumeFeatured'];
        self::$db->update(self::rTable, $data, "uid = '" . Filter::$id . "'");

        if(self::$db->affected()) {
            $json['type'] = 'success';
            $json['title'] = 'Resume Featured/Removed';
            $json['message'] =  "Job post " . $_POST['toggleResumeFeatured'];
        } else {
            $json['type'] = 'error';
            $json['title'] = 'Error';
            $json['message'] = "Resume status change error.";
        }
        echo json_encode($json);
    }

    public function jobCount(){
        $sql = "SELECT COUNT(id) as count FROM " . self::jTable . "";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row[0]->count : 0;
    }

    public function applicationCount(){
        $sql = "SELECT COUNT(id) as count FROM " . self::aTable . "";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row[0]->count : 0;
    }

    public function resumesCount(){
        $sql = "SELECT COUNT(uid) as count FROM " . self::rTable . "";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row[0]->count : 0;
    }

    public function companyCount(){
        $sql = "SELECT COUNT(uid) as count FROM " . self::comTable . "";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row[0]->count : 0;
    }

	  /**
	   * Jobs::getAllJobs()
	   *
	   * @return
	   */
	  public function getAllJobs($from = false)
	  {

		  if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
			  $enddate = date("Y-m-d");
			  $letter = sanitize($_GET['letter'], 2);
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::jTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'"
			  . "\n AND title REGEXP '^" . self::$db->escape($letter) . "'";
			  $where = " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND title REGEXP '^" . self::$db->escape($letter) . "'";

		  } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
			  $enddate = date("Y-m-d");
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::jTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
			  $where = " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";

		  } elseif(isset($_GET['letter'])) {
			  $letter = sanitize($_GET['letter'], 2);
			  $where = "WHERE title REGEXP '^" . self::$db->escape($letter) . "'";
			  $q = "SELECT COUNT(*) FROM " . self::jTable . " WHERE title REGEXP '^" . self::$db->escape($letter) . "' LIMIT 1";
		  } else {
			  $q = "SELECT COUNT(*) FROM " . self::jTable . " LIMIT 1";
			  $where = null;
		  }


        $record = self::$db->query($q);
        $total = self::$db->fetchrow($record);
        $counter = $total[0];

        $pager = Paginator::instance();
        $pager->items_total = $counter;
        $pager->default_ipp = Registry::get("Core")->perpage;
        $pager->paginate();

        $sql = "SELECT *, title as name,"
          . "\n (SELECT COUNT(job_applications.jobid) FROM job_applications WHERE job_applications.jobid = jobs.id) as totalapplications"
          . "\n FROM " . self::jTable
          . "\n $where"
          . "\n ORDER BY id DESC" . $pager->limit;

        $row = self::$db->fetch_all($sql);

        return ($row) ? $row : 0;

	  }


      /**
	   * Users::getAllResumes()
	   *
	   * @param bool $from
	   * @return
	   */
	  public function getAllResumes($from = false)
	  {
          $where = '';
		  if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
			  $enddate = date("Y-m-d");
			  $letter = sanitize($_GET['letter'], 2);
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::rTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'"
			  . "\n AND fullname REGEXP '^" . self::$db->escape($letter);
			  $where = " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND fullname REGEXP '^" . self::$db->escape($letter);

		  } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
			  $enddate = date("Y-m-d");
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::rTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
			  $where = " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";

		  } elseif(isset($_GET['letter'])) {
			  $letter = sanitize($_GET['letter'], 2);
			  $where = "WHERE fullname REGEXP '^" . self::$db->escape($letter) . "'";
			  $q = "SELECT COUNT(*) FROM " . self::rTable . " WHERE fullname REGEXP '^" . self::$db->escape($letter) . "' LIMIT 1";
		  } else {
			  $q = "SELECT COUNT(*) FROM " . self::rTable . " LIMIT 1";
		  }

          $record = self::$db->query($q);
          $total = self::$db->fetchrow($record);
          $counter = $total[0];

		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();

          $sql = "SELECT * "
		  . "\n FROM " . self::rTable
		  . "\n $where"
		  . "\n ORDER BY uid DESC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }


	  /**
	   * Jobs::getCatInfo()
	   *
	   * @return
	   */
	  public function getCatInfo($id,$separator = ', ')
	  {
		  $array = explode(',',$id);
		  $html = "";
		  //print_r($array);
		  foreach($array as $key => $value){
  			if($value != '' || $value != 0 || $value > 0){
  			  $catinfo = self::$db->first("SELECT * FROM " . self::cTable . " WHERE id = $value");
  			  $html .= ($key > 0) ? $separator : '';
  			  $html .= $catinfo->name;
  			}
		  }

		  return  $html;
	  }

	  /**
	   * Jobs::getMyJobs()
	   *
	   * @return
	   */
	  public function getMyJobs()
	  {

	  }

    /**
     * getUserInvoices()
     *
     * @return
     */
    public function getUserInvoices()
    {
        $sql = "SELECT *"
        . "\n FROM " . Content::inTable
        . "\n WHERE user_id = " . Registry::get("Users")->uid
        . "\n ORDER BY created DESC";

        $row = self::$db->fetch_all($sql);

        return ($row) ? $row : 0;
    }

    /**
     * Products::getUserTransactions()
     *
     * @return
     */
    public function getUserTransactions()
    {
        $sql = "SELECT s.*,p.id as pid,p.name as pname FROM " . self::sbTable . " as s LEFT JOIN " . self::pTable . " as p ON s.pid=p.id WHERE s.usage < s.limit AND end_date >= CURDATE() AND s.uid = " . Registry::get("Users")->uid . " ORDER BY s.created DESC";

        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

	  /**
       * Content::getJobCatTree()
       *
       * @return
       */
    protected function getJobCatTree()
	  {
		  $query = self::$db->query("SELECT * FROM " . self::cTable . " ORDER BY parent_id, position");

		  while ($row = self::$db->fetch($query, true)) {
			  $this->cattree[$row['id']] = array(
			        'id' => $row['id'],
					'name' => $row['name'],
					'parent_id' => $row['parent_id']
			  );
		  }
		  return $this->cattree;
	  }

	  /**
       * Content::getJobCatDropList()
       *
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
       * @return
       */
	  public function getJobCatDropList($parent_id, $level = 0, $spacer, $selected = false)
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
					  $this->getJobCatDropList($key, $level, $spacer, $selected);
					  $level--;
				  }
			  }
			  unset($row);
		  }
	  }

	  /**
       * Content::getJobSortCatList()
       *
	   * @param integer $parent_id
       * @return
       */
      public function getJobSortCatList($parent_id = 0)
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
				  .'<div class="dd-handle"><a data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-title="' . Lang::$word->CAT_DELETE . '" data-option="deleteJobCategory" class="delete">'
				  . '<i class="icon red remove sign"></i></a><i class="icon reorder"></i>'
				  .'<a href="index.php?do=job-categories&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'] . '</a></div>';
				  $this->getJobSortCatList($key);
				  print "</li>\n";
			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }



      public function browseCategories($parent_id = 0)
	  {

		  $subcat = false;
		  $class = ($parent_id == 0) ? "parent " : "child";
		  $parentlist = ($parent_id == 0) ? "parentlist " : "";

		  foreach ($this->cattree as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($subcat === false) {
					  $subcat = true;
					  print "<ul class='sortMenu " . $parentlist . "'>\n";
				  }

				  print '<li>'
				  .'<a href="' . SITEURL . '/browse-jobs.php?category=' . $row['id'] . '" class="'.$class.'">' . $row['name'] . '</a>';
				  $this->browseCategories($key);
				  print "</li>\n";
			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }

	  /**
	   * Content::processJobCategory()
	   *
	   * @return
	   */
	  public function processJobCategory()
	  {

		  Filter::checkPost('name', Lang::$word->CAT_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'parent_id' => intval($_POST['parent_id']),
				  'icon' => sanitize($_POST['icon']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['name']) : doSeo($_POST['slug']),
				  'active' => intval($_POST['active'])
			  );

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
    * Content::getJobLocTree()
    *
    * @return
    */
	  protected function getJobLocTree()
	  {
		  $query = self::$db->query("SELECT * FROM " . self::lTable . " ORDER BY parent_id, position");

		  while ($row = self::$db->fetch($query, true)) {
			  $this->locationtree[$row['id']] = array(
			        'id' => $row['id'],
					'name' => $row['name'],
					'parent_id' => $row['parent_id']
			  );
		  }
		  return $this->locationtree;
	  }


  	  public function getLocInfo($id)
	  {
		$locinfo = self::$db->first("SELECT * FROM " . self::lTable . " WHERE id = $id");
		return  ($locinfo) ? $locinfo->name : '';
	  }

    /**
    * Content::mostPopCategories()
    *
    * @return
    */
    public function mostPopCategories()
    {
      $sql = "SELECT id as cid, name, slug, icon, (SELECT COUNT(jobs.id) FROM jobs WHERE job_categories.id IN (jobs.categories)) as totaljobs FROM job_categories ORDER BY totaljobs DESC LIMIT 0,8";

      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0;
    }

    /**
    * Products::getlLatestJobs()
    *
    * @return
    */
    public function getlLatestJobs()
    {
      $sql = "SELECT j.id, j.title, j.type, j.location, j.categories, j.salary, j.skills, c.uid as company_id, c.name as company_name, c.avatar as company_logo"
      . "\n FROM " . self::jTable . " as j"
      . "\n INNER JOIN " . self::comTable . " as c"
      . "\n ON j.company = c.uid"
      . "\n ORDER BY j.id DESC LIMIT 0," . Registry::get('Core')->latest_jobs;

      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0;
    }

    public function getSingleResume($id){
        $sql = "SELECT * FROM " . self::rTable . " WHERE uid=" . $id;
        $row = self::$db->first($sql);
        return ($row) ? $row : 0;
    }

    public function getSingleCompany($id){
        $sql = "SELECT * FROM " . self::comTable . " WHERE uid=" . $id;
        $row = self::$db->first($sql);
        return ($row) ? $row : 0;
    }

    public function featuredjobs()
    {
      $sql = "SELECT id, title, description, type, location, salary FROM " . self::jTable . " WHERE status='approved' AND featured='featured' ORDER BY id DESC LIMIT 0," . Registry::get('Core')->featured_jobs;
      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0;
    }

    public function companyJobs($id)
    {
      $sql = "SELECT id, company, title, type FROM " . self::jTable . " WHERE company=" . $id . " AND status='approved' ORDER BY id DESC";
      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0;
    }

    public function featuredResumes()
    {
        $sql = "SELECT uid,avatar,fullname,title,objective,hourly_rate,skills,city,state,country FROM " . self::rTable . " WHERE featured='featured' ORDER BY uid DESC LIMIT 0," . Registry::get('Core')->featured_resumes;

      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0;
    }

    /**
    * Content::getJobLocDropList()
    *
    * @param mixed $parent_id
    * @param integer $level
    * @param mixed $spacer
    * @param bool $selected
    * @return
    */
	  public function getJobLocDropList($parent_id, $level = 0, $spacer, $selected = false)
	  {
		  if($this->locationtree) {
			  foreach ($this->locationtree as $key => $row) {
				  $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "" ;
				  if ($parent_id == $row['parent_id']) {
					  print "<option value=\"" . $row['id'] . "\"".$sel.">";

					  for ($i = 0; $i < $level; $i++)
						  print $spacer;

					  print $row['name'] . "</option>\n";
					  $level++;
					  $this->getJobLocDropList($key, $level, $spacer, $selected);
					  $level--;
				  }
			  }
			  unset($row);
		  }
	  }

	  /**
       * Content::getJobSortJobList()
       *
	   * @param integer $parent_id
       * @return
       */
    public function getJobSortLocList($parent_id = 0)
	  {

		  $subcat = false;
		  $class = ($parent_id == 0) ? "parent" : "child";

		  foreach ($this->locationtree as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($subcat === false) {
					  $subcat = true;
					  print "<ul class=\"sortMenu\">\n";
				  }

				  print '<li class="dd-item" id="list_' . $row['id'] . '">'
				  .'<div class="dd-handle"><a data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-title="' . Lang::$word->CAT_DELETE . '" data-option="deleteJobLocation" class="delete">'
				  . '<i class="icon red remove sign"></i></a><i class="icon reorder"></i>'
				  .'<a href="index.php?do=job-locations&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'] . '</a></div>';
				  $this->getJobSortLocList($key);
				  print "</li>\n";
			  }
		  }
		  unset($row);

		  if ($subcat === true)
			  print "</ul>\n";
	  }

	  /**
	   * Content::processJobLocation()
	   *
	   * @return
	   */
	  public function processJobLocation()
	  {

		  Filter::checkPost('name', Lang::$word->CAT_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'parent_id' => intval($_POST['parent_id']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['name']) : doSeo($_POST['slug']),
				  'active' => intval($_POST['active'])
			  );

			  (Filter::$id) ? self::$db->update(self::lTable, $data, "id=" . Filter::$id) : self::$db->insert(self::lTable, $data);
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
    * Content::getJobTypeTree()
    *
    * @return
    */
	  protected function getJobTypeTree()
	  {
		  $query = self::$db->query("SELECT * FROM " . self::tTable . " ORDER BY position");

		  while ($row = self::$db->fetch($query, true)) {
			  $this->typetree[$row['id']] = array(
			        'id' => $row['id'],
					'name' => $row['name'],
					'color' => $row['color'],
					'slug' => $row['slug']
			  );
		  }
		  return $this->typetree;
	  }

    /**
    * Content::getJobSortTypeList()
    *
    * @param integer $parent_id
    * @return
    */
    public function getJobSortTypeList($parent_id = 0)
	  {
  		print "<table class='wojo basic sortable table'>\n<tbody>\n";
  		foreach ($this->typetree as $key => $row) {
  			print '<tr id="node-' . $row['id'] . '">
  					<td class="id-handle"><i class="icon reorder"></i></td>
  					<td><span class="full-time" style="background-color: ' . $row['color'] . ';">' . $row['name'] . '</span></td>
  					<td>' . $row['slug'] . '</td>
  					<td><a href="index.php?do=job-types&amp;action=edit&amp;id=' . $row['id'] . '"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="Delete Job Type" data-option="deleteJobType" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"><i class="circular danger inverted remove icon link"></i></a></td>
  					</tr>';
  		}
  		print "</tbody>\n</table>\n";
	  }

    /**
    * Content::getJobPackageTree()
    *
    * @return
    */
    protected function getJobPackageTree()
    {
      $query = self::$db->query("SELECT * FROM " . self::pTable . " ORDER BY position");

      while ($row = self::$db->fetch($query, true)) {
        $this->packagetree[$row['id']] = array(
          'id' => $row['id'],
          'name' => $row['name'],
          'price' => $row['price'],
          'duration' => $row['duration'],
          'limit' => $row['limit'],
          'billing' => $row['billing']
        );
      }
      return $this->packagetree;
    }

    /**
    * Content::getPackageSortList()
    */
    public function getPackageSortList()
	  {
  		print "<table class='wojo basic sortable table'>\n<tbody>\n";
      print '<tr><td></td><td>Name</td><td>Limit</td><td>Duration</td><td>Price</td><td>Billing</td><td></td></tr>';
  		foreach ($this->packagetree as $key => $row) {
  			print '<tr id="node-' . $row['id'] . '">
  					<td class="id-handle"><i class="icon reorder"></i></td>
  					<td>' . $row['name'] . '</td>
  					<td>' . $row['limit'] . '</td>
  					<td>' . $row['duration'] . '</td>
  					<td>' . $row['price'] . '</td>
  					<td>' . $row['billing'] . '</td>
  					<td><a href="index.php?do=packages&amp;action=edit&amp;id=' . $row['id'] . '"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="Delete Package" data-option="deletePackage" data-id="' . $row['id'] . '" data-title="' . $row['name'] . '"><i class="circular danger inverted remove icon link"></i></a></td>
  					</tr>';
  		}
  		print "</tbody>\n</table>\n";
	  }

    /**
     * Content::getPackages()
     *
     * @return
     */
    public function getPackages()
    {
        $sql = "SELECT * FROM " . self::pTable . " WHERE active=1 ORDER BY position";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

	  /**
	   * Content::processJobType()
	   *
	   * @return
	   */
	  public function processJobType()
	  {

		  Filter::checkPost('name', Lang::$word->CAT_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['name']) : doSeo($_POST['slug']),
				  'color' => sanitize($_POST['color']),
				  'active' => intval($_POST['active'])
			  );

			  (Filter::$id) ? self::$db->update(self::tTable, $data, "id=" . Filter::$id) : self::$db->insert(self::tTable, $data);
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
	   * Content::processPackage()
	   *
	   * @return
	   */
	  public function processPackage()
	  {

		  Filter::checkPost('name', 'Package name is required');

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['name']) : doSeo($_POST['slug']),
          'features' => $_POST['features'],
				  'price' => sanitize($_POST['price']),
				  'featured' => sanitize($_POST['featured']),
				  'active' => sanitize($_POST['active']),
				  'limit' => intval($_POST['limit']),
				  'duration' => intval($_POST['duration']),
				  'billing' => sanitize($_POST['billing'])
			  );

			  (Filter::$id) ? self::$db->update(self::pTable, $data, "id=" . Filter::$id) : self::$db->insert(self::pTable, $data);
			  $message = (Filter::$id) ? 'Package successfully updated.' : 'Package successfully created.';

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
    * Content::getJobTypeDropList()
    *
    * @param integer $parent_id
    * @return
    */
    public function getJobTypeDropList($selected = false)
	  {
          foreach ($this->typetree as $key => $row) {
              $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "" ;
              print '<option value="' . $row['id'] . '" ' . $sel . '>' . $row['name'] . '</option>';
          }
	  }

    /**
    * Content::getJobSkillTree()
    *
    * @return
    */
	  protected function getJobSkillTree()
	  {
		  $query = self::$db->query("SELECT * FROM " . self::sTable . " ORDER BY position");

		  while ($row = self::$db->fetch($query, true)) {
			  $this->skilltree[$row['id']] = array(
			        'id' => $row['id'],
					'name' => $row['name'],
					'slug' => $row['slug']
			  );
		  }
		  return $this->skilltree;
	  }

    /**
    * Content::getJobSortSkillList()
    *
    * @param integer $parent_id
    * @return
    */
    public function getJobSortSkillList($parent_id = 0)
	  {
    		print "<table class='wojo basic sortable table'>\n<tbody>\n";
    		foreach ($this->skilltree as $key => $row) {
    			print '<tr id="node-' . $row['id'] . '">
    					<td class="id-handle"><i class="icon reorder"></i></td>
    					<td>' . $row['name'] . '</td>
    					<td>' . $row['slug'] . '</td>
    					<td><a href="index.php?do=job-skills&amp;action=edit&amp;id=' . $row['id'] . '"><i class="circular inverted success icon pencil link"></i></a> <a class="delete" data-title="Delete Job Skill" data-option="deleteJobSkill" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"><i class="circular danger inverted remove icon link"></i></a></td>
    					</tr>';
    		}
    		print "</tbody>\n</table>\n";
	  }


      public function applyToJob($jobid,$message,$expected)
      {
          $data = array(
              'userid'         => Registry::get("Users")->uid,
              'jobid'           => $jobid,
              'message'         => $message,
              'expected'        => $expected,
              'created'         => "NOW()"
          );
          $row = self::$db->insert(self::aTable, $data);
          return ($row) ? true : false;
      }

      public function freeCheckout($pid)
      {
          $row = Core::getRowById(self::pTable, $pid);
          if ($row->price <= 0) {
              $enddate = date('Y-m-d', strtotime($date. ' + ' . $row->duration . ' days'));
              $data = array(
                  'uid'             => Registry::get("Users")->uid,
                  'pid'             => $row->id,
                  'txn_id'          => 'FREE',
                  'limit'           => $row->limit,
                  'usage'           => 0,
                  'start_date'      => "NOW()",
                  'end_date'        => $enddate,
                  'created'         => "NOW()"
              );
              $row = self::$db->insert(self::sbTable, $data);
          }
          return ($row) ? true : false;
      }


    /**
    * Content::getJobApplicationList()
    *
    * @param integer $parent_id
    * @return
    */
    public function getJobApplicationList($jobid){
		$sql = "SELECT a.id,a.userid,r.fullname,a.expected,a.message,a.created FROM " . self::aTable . " as a LEFT JOIN " . self::rTable . " as r ON a.userid=r.uid WHERE a.jobid = $jobid ORDER by a.created ASC";
		$applications = self::$db->fetch_all($sql);

		print "<table class='wojo basic sortable table'>\n<tbody>\n";

        print '<tr>
        <th>Name</th>
        <th width="50%">Message</th>
        <th>Expectation</th>
        <th>Date</th>
        </tr>';

		foreach ($applications as $row) {
			print '<tr id="node-' . $row->id . '">
					<td><a target="_blank" href="' . SITEURL . '/resume.php?resumeid=' . $row->userid . '">' . $row->fullname . '</td>
					<td width="50%">' . substr($row->message, 0 , 100) . '</td>
					<td>' . $row->expected . '</td>
					<td>' . dodate($row->created) . '</td>
					</tr>';
		}

		print "</tbody>\n</table>\n";
	  }

      public function jobAppliedCheck($jobid)
      {
          $uid = Registry::get("Users")->uid;
          $sql = "SELECT * FROM " . self::aTable . " WHERE userid=" . $uid . " AND jobid=" . $jobid . "";
          $row = self::$db->first($sql);
          return ($row) ? 1 : 0;
      }

    /**
    * Content::getJobSkillDropList()
    *
    * @param integer $parent_id
    * @return
    */
    public function getJobSkillDropList($skills = 0)
	  {
      $skillr =  explode(',',$skills);
      foreach ($this->skilltree as $key => $row) {
        $selected = ( in_array($row['id'], $skillr) ) ? 'selected="selected"' : '';
        print '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>';

  		}
	  }
    /**
    * Content::getJobSkillTags()
    *
    * @param integer $parent_id
    * @return
    */
    public function getJobSkillTags($skills = 0){
        $skillr =  explode(',',$skills);
        foreach ($this->skilltree as $key => $row) {
            print ( in_array($row['id'], $skillr) ) ? '<span>' . $row['name'] . '</span>' : '';
        }
    }

    public function getJobSkills($skills = 0){
        $skillr =  explode(',',$skills);
        foreach ($this->skilltree as $key => $row) {
            print ( in_array($row['id'], $skillr) ) ? $row['name'] . ' ' : '';
        }
    }

      /**
       * Content::getJobCatCheckList()
       *
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
       * @return
       */
	  public function getJobCatCheckList($parent_id, $level = 0, $spacer, $selected = false)
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
					  print "<div class=\"" . $class . $hsel . "\"> <label class=\"checkbox\"><input type=\"checkbox\" name=\"categories[]\" class=\"checkbox\" value=\"" . $row['id'] . "\"".$sel." />";
					  for ($i = 0; $i < $level; $i++)
						  print $spacer;

					  print "<i></i>".$row['name'] . "</label></div>\n";
					  $level++;
					  $this->getJobCatCheckList($key, $level, $spacer, $selected);
					  $level--;
				  }
			  }
			  unset($row);
		  }
	  }


	  /**
	   * Content::processJobSkill()
	   *
	   * @return
	   */
	  public function processJobSkill()
	  {

		  Filter::checkPost('name', Lang::$word->CAT_NAME);

		  if (empty(Filter::$msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'slug' => (empty($_POST['slug'])) ? doSeo($_POST['name']) : doSeo($_POST['slug']),
				  'active' => intval($_POST['active'])
			  );

			  (Filter::$id) ? self::$db->update(self::sTable, $data, "id=" . Filter::$id) : self::$db->insert(self::sTable, $data);
			  $message = (Filter::$id) ? Lang::$word->JOBSKILL_UPDATED : Lang::$word->JOBSKILL_ADDED;

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

	  public function jobType($type)
    {

		  $html = '';
		  $sql = "SELECT * FROM " . self::tTable . " WHERE id = $type";
          $row = self::$db->first($sql);

		  $html = '<span class="full-time" style="background-color: ' . $row->color . ';">' . $row->name . '</span>';

		  return $html;
	  }

	  public function jobLocation($locationid)
    {
		  $sql = "SELECT * FROM " . self::lTable . " WHERE id = $locationid";
		  $row = self::$db->first($sql);
		  return $row->name;
	  }

	  public function getEmployerJobs()
    {

		  $q = "SELECT * FROM " . self::jTable . " WHERE company=" . Registry::get("Users")->uid . " AND ( status='pending' OR status='approved' )";

		  $record = self::$db->query($q);
      $total = self::$db->fetchrow($record);
      $counter = $total[0];

		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();

		  $sql = "SELECT *,"
		  . "\n (SELECT COUNT(job_applications.jobid) FROM job_applications WHERE job_applications.jobid = jobs.id AND job_applications.active = 1) as totalapplications"
		  . "\n FROM " . self::jTable
		  . "\n WHERE company=" . Registry::get("Users")->uid . " AND ( status='pending' OR status='approved' )"
		  . "\n ORDER BY id DESC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  public function getJob($id)
    {

		  $sql = "SELECT " . self::jTable . ".*, " . self::comTable . ".* FROM " . self::jTable . " INNER JOIN " . self::comTable . " ON " . self::jTable . ".company=" . self::comTable . ".uid WHERE " . self::jTable . ".id = $id";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
	  }

    public function bookmarkCheck($type,$id)
    {
        $uid = Registry::get("Users")->uid;
        $sql = "SELECT * FROM " . self::bmTable . " WHERE user_id=" . $uid . " AND type='" . $type . "' AND source_id=" . $id . "";
        $row = self::$db->first($sql);
        return ($row) ? 1 : 0;
    }

    public function addBookmark($type,$id)
    {
        $data = array(
            'user_id' 		=> Registry::get("Users")->uid,
            'type' 			=> $type,
            'source_id' 	=> $id,
            'created' 		=> "NOW()"
        );
        $row = self::$db->insert(self::bmTable, $data);
        return ($row) ? true : false;
    }

    public function deleteBookmark($type,$id)
    {
        $uid = Registry::get("Users")->uid;
        $sql = "DELETE FROM " . self::bmTable . " WHERE user_id=" . $uid . " AND type='" . $type . "' AND source_id=" . $id . "";
        $delete = self::$db->query($sql);
        return ($delete) ? true : false;
    }

    public function getJobApplications($jobid, $status, $order = 'id')
    {
      $jstatus = ($status != '' && $status != 'all') ? ' AND a.status="' . $status . '"' : '';
      $jorder = ($order != '') ? ' ORDER BY a.' . $order . ' ASC' : ' ORDER BY a.id DESC';
      $jorder = ($order == 'name') ? ' ORDER BY r.fullname ASC' : $jorder;
      $jorder = ($order == 'rating') ? ' ORDER BY a.rating DESC' : $jorder;

      $sql = "SELECT a.*,r.avatar,r.fullname,r.email FROM " . self::aTable . " as a INNER JOIN " . self::rTable . " as r ON a.userid=r.uid WHERE a.jobid=" . $jobid . " AND a.active=1" . $jstatus . $jorder;

      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0;
    }

    public function getJobTitle($jobid=0)
    {
      $sql = "SELECT title FROM " . self::jTable . " WHERE id=" . $jobid;
      $row = self::$db->first($sql);
      return ($row) ? $row->title : false;

    }

    public function applicationStatusUpdate($appid,$status,$rating)
    {
      $data = array(
        'status' 		=> $status,
        'rating' 		=> $rating
      );
      self::$db->update(self::aTable, $data, "id=" . $appid);
    }

    public function applicationAddNote($appid,$note)
    {
      $data = array(
        'note' 		=> $note
      );
      self::$db->update(self::aTable, $data, "id=" . $appid);
    }

    public function applicationSendMessage($to,$message)
    {
      $data = array(
        'from'          => Registry::get("Users")->uid,
        'to'            => sanitize($_POST['to']),
        'content'       => sanitize($_POST['content']),
        'created'       => "NOW()"
      );

      self::$db->insert(self::msgTable, $data);
    }

    public function getResumes($search, $short, $skills, $city, $state)
    {
      $searchq = ( $search != '' ) ? " AND title LIKE '%" . $search ."%'" : '';
      $skillsq = '';
      $cityq = "";
      $stateq = "";
      $rateq = "";
      $orderby = ( $short != '' ) ? " ORDER BY hourly_rate " . $short . "" : " ORDER BY uid DESC";

      $q = "SELECT * FROM " . self::rTable . " WHERE banned=0 " . $searchq  . $cityq . $stateq . $rateq . $orderby . "";
      $qr = self::$db->query($q);
      $counter = self::$db->numrows($qr);

		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();

      $sql = "SELECT uid,avatar,fullname,title,objective,hourly_rate,skills,city,state,country FROM " . self::rTable . " WHERE banned=0" . $searchq . $cityq . $stateq . $rateq . $orderby . "" . $pager->limit;

      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0 ;
    }

    /**
    * Jobs::getJobs()
    */
    public function getJobs($search, $short, $type, $city, $state)
    {
      $searchq = ( $search != '' ) ? " AND j.title LIKE '%" . $search ."%'" : '';

      $typeq = "";
      $cityq = "";
      $stateq = "";

      if($short != '' && $short == 'recent'){
          $orderby = " ORDER BY j.id DESC";
      } elseif ($short != '' && $short == 'oldest') {
          $orderby = " ORDER BY j.id ASC";
      } elseif ($short != '' && $short == 'expiry') {
          $orderby = " ORDER BY j.expire_date ASC";
      } else {
          $orderby = " ORDER BY j.id DESC";
      }

      $q = "SELECT * FROM " . self::jTable . " as j WHERE j.expire_date >= CURDATE() AND j.status='approved' AND j.active=1" . $searchq . $typeq . $cityq . $stateq . "";
      $qr = self::$db->query($q);
      $counter = self::$db->numrows($qr);

	  $pager = Paginator::instance();
	  $pager->items_total = $counter;
	  $pager->default_ipp = Registry::get("Core")->perpage;
	  $pager->paginate();

      $sql = "SELECT j.id,j.title,j.location,j.type,j.skills,j.description,j.salary,c.name,c.avatar FROM " . self::jTable . " as j INNER JOIN " . self::comTable . " as c ON j.company=c.uid WHERE j.expire_date >= CURDATE() AND status='approved' AND active=1" . $searchq . $typeq . $cityq . $stateq . $orderby . "" . $pager->limit;

      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0 ;
    }

    /**
    * Jobs::getJobBookmarks()
    */
    public function getJobBookmarks()
    {
      $sql = "SELECT j.title,j.type as jobtype,b.* FROM " . self::bmTable . " as b INNER JOIN " . self::jTable . " as j ON j.id=b.source_id WHERE b.type='job' AND b.user_id=" . Registry::get("Users")->uid;
      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0 ;
    }

    /**
    * Jobs::getResumeBookmarks()
    */
    public function getResumeBookmarks()
    {
      $sql = "SELECT r.fullname,r.title,b.* FROM " . self::bmTable . " as b INNER JOIN " . self::rTable . " as r ON r.uid=b.source_id WHERE b.type='resume' AND b.user_id=" . Registry::get("Users")->uid;
      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0 ;
    }

    /**
    * Jobs::getSubscriptions()
    */
    public function getSubscriptions()
    {
        if (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
            $enddate = date("Y-m-d");
            $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
            if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                $enddate = $_POST['enddate_submit'];
            }
            $q = "SELECT COUNT(*) FROM " . self::sbTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
            $where = " WHERE s.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
        } else {
            $q = "SELECT COUNT(*) FROM " . self::sbTable . " LIMIT 1";
            $where = null;
        }

        $record = self::$db->query($q);
        $total = self::$db->fetchrow($record);
        $counter = $total[0];

        $pager = Paginator::instance();
        $pager->items_total = $counter;
        $pager->default_ipp = Registry::get("Core")->perpage;
        $pager->paginate();


        $sql = "SELECT t.*, t.id as id, u.id as uid, u.username, p.id as pid, p.title"
        . "\n FROM " . self::tTable . " as t"
        . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = t.uid"
        . "\n LEFT JOIN " . self::pTable . " as p ON p.id = t.pid" . $where . " ORDER BY t.created DESC" . $pager->limit;


        $sql = "SELECT s.txn_id, s.uid, s.pid, c.name as ename, p.name as pname, s.usage, s.limit, s.start_date, s.end_date, s.created FROM " . self::sbTable . " as s INNER JOIN " . self::comTable . " as c ON s.uid = c.uid INNER JOIN " . self::pTable . " as p ON s.pid = p.id " . $where . " ORDER BY s.id DESC" . $pager->limit;

        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0 ;
    }

    /**
    * Jobs::getEmployers()
    */
    public function getEmployers()
    {
      $sql = "SELECT u.id, CONCAT(u.fname,u.lname) as aname, u.username, c.name as cname FROM " . self::uTable . " as u INNER JOIN " . self::comTable . " as c ON u.id = c.uid WHERE u.active='y' AND u.userlevel=2";
      $row = self::$db->fetch_all($sql);
      return ($row) ? $row : 0 ;
    }

    /**
    * Jobs::processAdminTransaction()
    */
    public function processAdminTransaction()
    {
        if(!Filter::$id) {
            Filter::checkPost('pid', Lang::$word->TXN_SELP);
            Filter::checkPost('uid', Lang::$word->TXN_SELUSER);
            Filter::checkPost('created', Lang::$word->TXN_DATE);
        }
        if (empty(Filter::$msgs)) {
            if(!Filter::$id) {
                  $row = Registry::get("Core")->getRowById(self::pTable, intval($_POST['pid']));
                  $email = getValueById("email", Users::uTable, intval($_POST['uid']));
                  $data = array(
                        'txn_id' => "MAN_" . time(),
                        'pid' => intval($row->id),
                        'uid' => intval($_POST['uid']),
                        'file_date' => time(),
                        'created' => sanitize($_POST['created_submit']) . ' ' . date('H:i:s'),
                        'payer_email' => sanitize($email),
                        'payer_status' => "verified",
                        'item_qty' => intval($_POST['item_qty']),
                        'price' => floatval($row->price),
                        'currency' => Registry::get("Core")->currency,
                        'pp' => sanitize($_POST['pp']),
                        'memo' => sanitize($_POST['memo']),
                        'status' => 1,
                        'active' => 1
                  );

                  $sdata = array(
                        'txn_id' => "MAN_" . time(),
                        'pid'       => intval($row->id),
                        'uid'       => intval($_POST['uid']),
                        'usage'     => 0,
                        'limit'     => $row->limit,
                        'start_date'   => sanitize($_POST['created_submit']),
                        'end_date'   => date('Y-m-d', strtotime(sanitize($_POST['created_submit']). ' + ' .  $row->duration .' days')),
                        'created'   => "NOW()"
                  );
            }
            if(Filter::$id) {
                $edata = array(
                      'status' => intval($_POST['status']),
                      'active' => intval($_POST['active']),
                      'downloads' => intval($_POST['downloads']),
                      'memo' => sanitize($_POST['memo'])
                );
            }

            (Filter::$id) ? self::$db->update(self::txnTable, $edata, "id=" . Filter::$id) : self::$db->insert(self::txnTable, $data);

            self::$db->insert(self::sbTable, $sdata);

            $message = (Filter::$id) ? Lang::$word->TXN_UPDATED : Lang::$word->TXN_ADDED;

            if (self::$db->affected()) {
                $json['type'] = 'success';
                $json['message'] = Filter::msgOk($message, false);
            } else {
                $json['type'] = 'info';
                $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
            }
            print json_encode($json);

            if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
                $username = getValueById("username", Users::uTable, $data['uid']);
                require_once(BASEPATH . "lib/class_mailer.php");
                $mailer = Mailer::sendMail();

                $row2 = Registry::get("Core")->getRowById("email_templates", 9);
                $body = str_replace(array('[USERNAME]', '[ITEMNAME]', '[PRICE]', '[QTY]', '[SITE_NAME]', '[URL]'),
                array($username, $row->title, $row->price, $data['item_qty'], Registry::get("Core")->site_name, SITEURL), $row2->body);

                $message = Swift_Message::newInstance()
                          ->setSubject($row2->subject)
                          ->setTo(array($email => $username))
                          ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
                          ->setBody(cleanOut($body), 'text/html');

                 $mailer->send($message);
            }

        } else {
            $json['message'] = Filter::msgStatus();
            print json_encode($json);
        }
    }

    /**
     * Products::getPayments()
     *
     * @param bool $where
     * @param bool $from
     * @return
     */
    public function getTransactions($from = false)
    {
        if (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
            $enddate = date("Y-m-d");
            $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
            if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                $enddate = $_POST['enddate_submit'];
            }
            $q = "SELECT COUNT(*) FROM " . self::txnTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
            $where = " WHERE t.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
        } else {
            $q = "SELECT COUNT(*) FROM " . self::txnTable . " LIMIT 1";
            $where = null;
        }

        $record = self::$db->query($q);
        $total = self::$db->fetchrow($record);
        $counter = $total[0];

        $pager = Paginator::instance();
        $pager->items_total = $counter;
        $pager->default_ipp = Registry::get("Core")->perpage;
        $pager->paginate();


        $sql = "SELECT t.*, t.id as id, u.id as uid, u.username, p.id as pid, p.name"
        . "\n FROM " . self::txnTable . " as t"
        . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = t.uid"
        . "\n LEFT JOIN " . self::pTable . " as p ON p.id = t.pid"
        . "\n " . $where . " ORDER BY t.created DESC" . $pager->limit;
        $row = self::$db->fetch_all($sql);

         return ($row) ? $row : 0;
    }


    /**
     * Products::exportTransactionsXLS()
     *
     * @return
     */
    public function exportTransactionsXLS()
    {
        $sql = "SELECT t.*, t.id as id, u.id as uid, u.username, p.id as pid, p.name as title,"
        . "\n t.created as cdate"
        . "\n FROM " . self::txnTable ." as t"
        . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = t.uid"
        . "\n LEFT JOIN " . self::pTable . "  as p ON p.id = t.pid"
        . "\n ORDER BY t.created";
        $result = self::$db->fetch_all($sql);

        $type = "vnd.ms-excel";
        $date = date('m-d-Y H:i');
        $title = "Exported from the " . Registry::get("Core")->site_name . " on $date";

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Type: application/$type");
        header("Content-Disposition: attachment;filename=temp_" . time() . ".xls");
        header("Content-Transfer-Encoding: binary ");

        print '
        <table width="100%" cellpadding="1" cellspacing="2" border="1">
        <caption>' . $title . '</caption>
          <tr>
            <td># TXN ID</th>
            <td>' . Lang::$word->PRD_NAME . '</td>
            <td>' . Lang::$word->USERNAME . '</td>
            <td>' . Lang::$word->TXN_AMT . '</td>
            <td>' . Lang::$word->CREATED . '</td>
            <td>' . Lang::$word->TXN_PP . '</td>
            <td>' . Lang::$word->TXN_FEES . '</td>
            <td>IP</td>
            <td>' . Lang::$word->STATUS . '</td>
          </tr>';
          foreach ($result as $row) {
              $status = ($row->status) ? 'Completed':'Pending' ;
              print '<tr>
                <td>'.$row->txn_id.'</td>
                <td>'.$row->title.'</td>
                <td>'.$row->username.'</td>
                <td>'.$row->price.'</td>
                <td>'.$row->cdate.'</td>
                <td>'.$row->pp.'</td>
                <td>'.$row->mc_fee.'</td>
                <td>'.$row->ip.'</td>
                <td>'.$status.'</td>
              </tr>';
          }

        print '</table>';
        unset($row);
        exit();
    }


    /**
     * Products::exportTransactionsPDF()
     *
     * @return
     */
    public function exportTransactionsPDF()
    {
        $sql = "SELECT t.*, t.id as id, u.id as uid, u.username, p.id as pid, p.name as title,"
        . "\n t.created as cdate"
        . "\n FROM " . self::txnTable ." as t"
        . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = t.uid"
        . "\n LEFT JOIN " . self::pTable . "  as p ON p.id = t.pid"
        . "\n ORDER BY t.created";
        $result = self::$db->fetch_all($sql);

        $date = date('m-d-Y H:i');
        $title = "Exported from the " . Registry::get("Core")->site_name . " on $date";

        $html = '
        <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
        <table style="background:#F4F4F4;border:2px solid #bbb;width:100%" border="0" cellpadding="10" cellspacing="0">
        <caption>' . $title . '</caption>
        <tr>
         <th colspan="7" style="background-color:#c1c1c1;font-size:16px;padding:5px;border-bottom-width:2px;border-bottom-color:#bbb;border-bottom-style:solid">' . Lang::$word->TXN_SREP . '</th>
         </tr>
          <tr style="background-color:#dddddd;">
            <td>' . Lang::$word->PRD_NAME . '</td>
            <td>' . Lang::$word->USERNAME . '</td>
            <td>' . Lang::$word->TXN_AMT . '</td>
            <td>' . Lang::$word->CREATED . '</td>
            <td>' . Lang::$word->TXN_PP . '</td>
            <td>' . Lang::$word->STATUS . '</td>
          </tr>';
          foreach ($result as $row) {
              $status = ($row->status) ? 'Completed':'Pending';
              $html .= '<tr>
                <td style="border-top-width:1px; border-top-color:#ddd; border-top-style:solid">'.$row->title.'</td>
                <td style="border-top-width:1px; border-top-color:#ddd; border-top-style:solid">'.$row->username.'</td>
                <td style="border-top-width:1px; border-top-color:#ddd; border-top-style:solid">'.$row->price.'</td>
                <td style="border-top-width:1px; border-top-color:#ddd; border-top-style:solid">'.$row->cdate.'</td>
                <td style="border-top-width:1px; border-top-color:#ddd; border-top-style:solid">'.$row->pp.'</td>
                <td style="border-top-width:1px; border-top-color:#ddd; border-top-style:solid">'.$status.'</td>
              </tr>';
          }

        $html .= '</table></div>';

        require_once(BASEPATH . 'lib/mPdf/mpdf.php');
        $mpdf=new mPDF('utf-8');
        $mpdf->SetTitle($title);
        $mpdf->SetAutoFont();
        $mpdf->WriteHTML($html);
        $mpdf->Output($title . ".pdf", "D");

        unset($row);
        exit();
    }





  }
?>
