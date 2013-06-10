<?php

  //ini_set('error_reporting', E_ALL);
  //ini_set('display_errors', 1);

  date_default_timezone_set('Canada/Eastern');

# set defaults

  require_once '../config.php';

# load requirements
  require_once '../lib/.vendor/autoload.php';
  require_once 'db.php';

  $db = load_db();

  if(isset($_GET['now']))
  {
    $nowCount = 0;
    $content = "";

    $stmt = $db->prepare('SELECT `guidebook_event`.*, `guidebook_location`.name as "location" FROM `guidebook_event`, `guidebook_location`
                            WHERE `guidebook_location`.id == `guidebook_event`.locations AND (:now BETWEEN `guidebook_event`.startTime AND
                            `guidebook_event`.endTime)
                            ORDER BY startTime;');
    $stmt->bindValue(':now', date('Y-m-d H:i:s', strtotime("+5 minutes")), SQLITE3_TEXT);
    if ($result = $stmt->execute())
    {
        while($data = $result->fetchArray())
        {
          $content .=
            "<div class='now-event'>
              <span class='endtime'>until ".date('g:iA', strtotime($data['endTime'])).":</span>
                <span class='eventname'><a href='/program/{$data['id']}'>{$data['name']}</a></span> - <span class='location'>{$data['location']}</span>
            </div>
            <br/>";
            $nowCount++;
        }
    }

    if(!$nowCount)
    {
      $stmt = $db->prepare('SELECT `guidebook_event`.*,  `guidebook_location`.name as "location" FROM `guidebook_event`, `guidebook_location`
                              WHERE `guidebook_location`.id == `guidebook_event`.locations AND `guidebook_event`.startTime > :startTime
                              ORDER BY startTime
                              LIMIT 2;');
      $stmt->bindValue(':startTime', date('Y-m-d H:i:s'), SQLITE3_TEXT);
      if ($result = $stmt->execute())
      {
          while($data = $result->fetchArray())
          {
            $content .=
              "<div class='upcoming-event'>
              <span class='starttime'>".date('g:iA', strtotime($data['startTime'])).":</span>
                  <span class='eventname'><a href='/program/{$data['id']}'>{$data['name']}</a></span> <span class='endtime'>until ".date('g:iA', strtotime($data['endTime']))."</span> - <span class='location'>{$data['location']}</span>
              </div>
              <br/>";
          }
      }
    }

    echo "<h2>".($nowCount ? "Happening now" : "Coming up&hellip;")."</h2><div id='events'>".$content."</div>";
  }
  $db->close();
?>