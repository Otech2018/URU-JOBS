<?php
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php
  header("Content-Type: text/xml");
  header('Pragma: no-cache');
  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
  echo "<channel>\n";
  echo "<title><![CDATA[".$core->site_name."]]></title>\n";
  echo "<link><![CDATA[".SITEURL."]]></link>\n";
  echo "<description><![CDATA[Latest 20 Rss Feeds - ".$core->company."]]></description>\n";
  echo "<generator>".$core->company."</generator>\n";
  

  $sql = "SELECT p.id as pid, p.title, p.body, p.slug, p.thumb," 
  . "\n c.id as cid, c.name,"
  . "\n DATE_FORMAT(p.created, '%a, %d %b %Y %T GMT') as created" 
  . "\n FROM products as p" 
  . "\n LEFT JOIN categories as c ON c.id = p.cid" 
  . "\n WHERE c.id = '".Filter::$id."'"
  . "\n AND c.active = '1'"
  . "\n AND p.active = '1'"
  . "\n ORDER BY p.created DESC LIMIT 0,20";
  
  $data = $db->fetch_all($sql);
  
  foreach ($data as $row) {
      $title = cleanOut($row->title);
	  $text = cleanOut($row->body);
      $body = sanitize($text,550);
      $date = $row->created;
      $slug = $row->slug;
      
      $url = ($core->seo) ? SITEURL . '/product/' . $row->slug . '/' : SITEURL . '/item.php?itemname=' . $row->slug;
	  $thumb = ($row->thumb) ? PRODIMGURL . $row->thumb : UPLOADURL . 'blank.png';
	  $img = '<img src="'.$thumb.'" alt="" align="left" hspace="15" border="2" height="80" />';

      
      echo "<item>\n";
      echo "<title><![CDATA[$title]]></title>\n";
      echo "<link><![CDATA[$url]]></link>\n";
      echo "<guid isPermaLink=\"true\"><![CDATA[$url]]></guid>\n";
      echo "<description><![CDATA[$img$body]]></description>\n";
      echo "<pubDate><![CDATA[$date]]></pubDate>\n";
      echo "</item>\n";
  }
  unset($row);
  echo "</channel>\n";
  echo "</rss>";
?>