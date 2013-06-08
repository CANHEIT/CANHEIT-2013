<?php

  //ini_set('error_reporting', E_ALL);
  //ini_set('display_errors', 1);

# set defaults

  require_once '../config.php';

# load requirements
  require_once '../lib/.vendor/autoload.php';
  require_once 'db.php';

  $db = load_db();

  if(isset($_GET['now']))
  {
    $stmt = $db->prepare('SELECT `guidebook_event`.*, `guidebook_location`.name as "location" FROM `guidebook_event`, `guidebook_location`
                            WHERE `guidebook_location`.id == `guidebook_event`.locations AND `guidebook_event`.startTime > date("now", "-1 hour")
                            ORDER BY startTime
                            LIMIT 3;');

    if ($result = $stmt->execute())
    {
        while($data = $result->fetchArray())
        {
          echo
            "<div class='time'>
            <h4>".date('g:iA', strtotime($data['startTime']))."</h4>
                <span class='eventname'><a href='/program/{$data['id']}'>{$data['name']}</a></span> <span class='endtime'>until ".date('g:iA', strtotime($data['endTime']))."</span> - <span class='location'>{$data['location']}</span>
            </div>";
        }
    }
  }
  $db->close();
?>