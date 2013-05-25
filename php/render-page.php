<?php

//  ini_set('error_reporting', E_ALL);
//  ini_set('display_errors', 1);

# set defaults

  require_once '../config.php';
  
# load requirements

  require_once '../lib/.vendor/autoload.php';
  require_once 'db.php';

  $db = load_db();

  $twig_loader = new Twig_Loader_Filesystem(TEMPLATE_DIR);
  $twig = new Twig_Environment($twig_loader);

# parse the URI

  $p = $_SERVER['REQUEST_URI'];
	if (preg_match('/^\/[0-9]{2}\//',$p)) {
	  $p = substr($p, 3);
	}
  $template_file = "";
  $parse_functions = Array();
  
  # defaults for fetched data
  $poi_category_id = NULL;
  $poi_query = 'SELECT guidebook_poi.* 
  FROM guidebook_poi
  INNER JOIN guidebook_poi_category
  ON guidebook_poi_category.poi_id = guidebook_poi.id
  WHERE guidebook_poi_category.poicategory_id = :id
  ORDER BY guidebook_poi.rank';
  $is_single_object_expected = false;
  
  switch ($p) {
  
    # program
    
    case (
      preg_match(
        "/^\/(program)\/([0-9]{1,10})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_event` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/session.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
      
    case (
      preg_match(
        "/^\/(program)\/(saturday|sunday|monday|tuesday|wednesday|thursday|friday)$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_event` WHERE `startTime` LIKE :date ORDER BY startTime;');
      $stmt->bindValue(':date', get_program_date_from_day($matches[2]).'%', SQLITE3_TEXT); // starts with
      $template_file = $matches[1].'/day.twig';
      array_push($parse_functions, 'prepare_program_day');
      break;
    
    case (
      preg_match(
        "/^\/(program)\/$/"
        , $p, $matches) ? true : false
      ) :
      # if now is prior to the first day, redirect to first day
      if (time() < strtotime("2013-06-08")) {
        header("Location: " . $_SERVER['REQUEST_URI'] . strtolower(get_program_day_from_date("2013-06-08"))); exit; // to fix
      }
      # if now is after to the last day, redirect to the last day
      elseif (time() >= strtotime("2013-06-15")) {
        header("Location: " . $_SERVER['REQUEST_URI'] . strtolower(get_program_day_from_date("2013-06-14"))); exit; // to fix
      }
      # otherwise, redirect to the current day
      else {
        header("Location: " . $_SERVER['REQUEST_URI'] . strtolower(get_program_day_from_date("today"))); exit;
      }
      break;
    
    # accommodations
  
    case (
      preg_match(
        "/^\/(your-stay\/accommodations)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/accommodation.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/accommodations)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 14833;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
  
    # local-eats
  
    case (
      preg_match(
        "/^\/(your-stay\/local-eats)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/local-eat.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/local-eats)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 13617;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
      
    # attractions
  
    case (
      preg_match(
        "/^\/(your-stay\/attractions)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/attraction.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/attractions)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 13618;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;

    # nightlife
  
    case (
      preg_match(
        "/^\/(your-stay\/nightlife)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/nightlife.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/nightlife)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 18927;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
          
    # getting-here
  
    case (
      preg_match(
        "/^\/(your-stay\/getting-here)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/getting-here.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/getting-here)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 14836;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
              
    # uottawa-campus
  
    case (
      preg_match(
        "/^\/(your-stay\/uottawa-campus)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/uottawa-campus.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/uottawa-campus)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 17939;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
                  
    # sponsors
  
    case (
      preg_match(
        "/^\/(sponsors)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_poi` WHERE id = :id');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/sponsor.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;
    case (
      preg_match(
        "/^\/(sponsors)\/$/"
        , $p, $matches) ? true : false
      ) :
      $poi_category_id = 13615;
      $stmt = $db->prepare($poi_query);
      $stmt->bindParam(':id', $poi_category_id, SQLITE3_INTEGER);
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
  
      
    # otherwise, 404
    
    default:
      return_404();
  }

# load the data

  if ($stmt) {
    $data = array();
    $data['objects'] = array();
    $result = $stmt->execute();
    
    if ($is_single_object_expected) {
      $data = $result->fetchArray();
    } else {
      while($row = $result->fetchArray()) {
        array_push($data['objects'], $row);
      }
    }
    
    $db->close();
  }
  
# test and parse the data 

  # test the data, throw a 404 on error
  if (false == is_array($data)) {
    return_404();
  }
  
  # run parse functions to modify the output
  foreach ($parse_functions as $parse_function) {
    if (function_exists($parse_function)) {
      $parse_function($data);
    }
  }

# load the template

  if (!is_file(TEMPLATE_DIR . '/' . $template_file)) {
    return_404();
  }

  echo $twig->render($template_file, $data);

# helper functions

  function return_404() {
    header("HTTP/1.0 404 Not Found");
    require '../404.html';
    exit;
  }
  
  function get_correct_image_urls(&$data) {
    if (isset($data['objects'])) {
      foreach($data['objects'] as $object) {
        if (isset($object['image'])) {
          $object['image'] = get_correct_image_url($object['image']);
        }
      }
    } else {
       $data['image'] = get_correct_image_url($data['image']);
    }
  }
  
  function get_correct_image_url($image_filename) {
    if (preg_match('/img-(.*\.(png|jpg|gif))\.jpg/', $image_filename, $matches)) {
      return IMAGE_AWS_ROOT_URL.$matches[1];
    }
  }
  
  function parse_links(&$data) {
    if(!isset($data['links'])) {
      return;
    }
    
    $new_links = json_decode($data['links'],1); // convert to array

    unset($data['links']);
    
    if(is_array($new_links) && !empty($new_links) && is_array($new_links[0]) && is_array($new_links[0]['links'])) {
      $data['links'] = $new_links[0]['links'];
    }
  }
  
# program helpers

  function get_program_date_from_day($day_name) {
    switch(strtolower($day_name)) {
      case ('saturday'):  return '2013-06-08';
      case ('sunday'):    return '2013-06-09';
      case ('monday'):    return '2013-06-10';
      case ('tuesday'):   return '2013-06-11';
      case ('wednesday'): return '2013-06-12';
      case ('thursday'):  return '2013-06-13';
      case ('friday'):    return '2013-06-14';
    }
    return null;
  }
  
  function get_program_day_from_date($date) {
    return strftime("%A", strtotime($date)); //weekday
  }

  function prepare_program_data(&$data) {
    group_sessions_by_day_and_start_time($data);
  }
  
  function prepare_program_day(&$data) {
    add_program_day_metadata($data);
    group_sessions_by_start_time($data);
  }
  
  function group_sessions_by_start_time(&$data) {
    
    $data['starttimes'] = array();
    $starttime = $new_starttime = null;
    
    foreach ($data['objects'] as $session) {
    
      # determine the start time of the first event
      $new_starttime = $session['startTime'];
      
      if ($starttime != $new_starttime) {
        
        # if different starttime, then setup a new object
        
        $starttimes_count = array_push(
          $data['starttimes'],
          array(
            'starttime' => $new_starttime,
            'events' => array(),
          )
        );
        $starttime = $new_starttime;
      }
      
      # store the object in its new location in the new starttime listing
      
      array_push($data['starttimes'][$starttimes_count - 1]['events'], $session);
    }
    
    unset($data['objects']);
  }
  
  function add_program_day_metadata(&$data) {
    # determine the date of the first item in the result set
    preg_match('/^([1-9][0-9]{3}-[0-9]{2}-[0-9]{2})/', $data['objects'][0]['startTime'], $matches);
    $data['date'] = $matches[1];
    if ($data['date'] != '2013-06-08') { // to fix
      $data['prevday'] = date('Y-m-d', strtotime('-1 day', strtotime($data['date'])));
    }
    if ($data['date'] != '2013-06-14') { // to fix
      $data['nextday'] = date('Y-m-d', strtotime('+1 day', strtotime($data['date'])));
    }
  }
?>