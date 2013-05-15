<?php

  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);

# set defaults

  $current_dir = dirname(__FILE__);
  $template_dir = 'templates';

  $cache_dir = $current_dir . '/.cache/';

  $db_file = $cache_dir . '/guide.db';
  $db_source_url = 'http://s3.amazonaws.com/media.guidebook.com/service/vXSEB4weN3Px5jc7gCRKnAqask9yup6t/guide.db';
  
# load requirements

  require_once 'lib/.vendor/autoload.php';

  $db = load_db();

  $twig_loader = new Twig_Loader_Filesystem($template_dir);
  $twig = new Twig_Environment($twig_loader);

# parse the URI

  $p = $_SERVER['REQUEST_URI'];
  $template_file = "";
  $parse_functions = Array();
  
  switch ($p) {
  
    # program
    
    case (
      preg_match(
        "/^\/(program)\/([0-9]{1,10})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/event/' . $matches[2] . '/?guide__id=5396&';
      $template_file = $matches[1].'/session.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    
    case (
      preg_match(
        "/^\/(program)\/$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT * FROM `guidebook_event`;');
      $json_uri = '/api/v1/event/?guide__id=5396&limit=20&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'prepare_program_data');
      break;
    
    # accommodations
  
    case (
      preg_match(
        "/^\/(your-stay\/accommodations)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=14833&';
      $template_file = $matches[1].'/accommodation.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/accommodations)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=14833&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
  
    # local-eats
  
    case (
      preg_match(
        "/^\/(your-stay\/local-eats)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=13617&';
      $template_file = $matches[1].'/local-eat.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/local-eats)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=13617&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
      
    # attractions
  
    case (
      preg_match(
        "/^\/(your-stay\/attractions)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=13618&';
      $template_file = $matches[1].'/attraction.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/attractions)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=13618&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;

    # attractions
  
    case (
      preg_match(
        "/^\/(your-stay\/nightlife)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=18927&';
      $template_file = $matches[1].'/nightlife.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/nightlife)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=18927&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
          
    # getting-here
  
    case (
      preg_match(
        "/^\/(your-stay\/getting-here)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=14836&';
      $template_file = $matches[1].'/getting-here.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/getting-here)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=14836&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
              
    # uottawa-campus
  
    case (
      preg_match(
        "/^\/(your-stay\/uottawa-campus)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=14836&';
      $template_file = $matches[1].'/uottawa-campus.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/uottawa-campus)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=17939&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
                  
    # sponsors
  
    case (
      preg_match(
        "/^\/(sponsors)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=13615&';
      $template_file = $matches[1].'/sponsor.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(sponsors)\/$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/?category=13615&';
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'get_all_results_pages');
      break;
  
      
    # otherwise, 404
    
    default:
      return_404();
  }

# load the data

  if ($stmt) {
    $result = $stmt->execute();
    $data = $result->fetchArray();
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

  if (!is_file($template_dir . '/' . $template_file)) {
    return_404();
  }

  echo $twig->render($template_file, $data);

# helper functions

  function load_db() {
    global $db_file, $db_source_url;

    # if db_file doesn't exist, download it
    if (!file_exists($db_file)) {

      $ch = curl_init($db_source_url);
      $fp = fopen($db_file, "w");
      
      if ($fp === FALSE) {
        return_404();
      }
      
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_HEADER, 0);

      # if error downloading, return a 404
      if (curl_exec($ch) === FALSE) {
        return_404();
      }
      curl_close($ch);
      fclose($fp);
    }
    
    return new SQLite3($db_file);
  }
  
  function return_404() {
    header("HTTP/1.0 404 Not Found");
    require '404.html';
    exit;
  }
  
  function fetch_links(&$data) {
    if(!is_array($data['links'])) {
      return;
    }
    
    foreach ($data['links'] as $i => $link) {
      $json = get_api_object($link . "?guide__id=5396&");
      $json = utf8_encode($json);
      $link_data = json_decode($json, true);
      if (is_array($link_data)) {
        $data['links'][$i] = $link_data;
      }
    }
  }
  
  function get_api_object($object_uri) {
    global $cache, $api_url_start, $api_url_end;
    
    return $cache->get_data(
      get_api_object_hash($object_uri),
      $api_url_start . $object_uri . $api_url_end
    );
  }
  
  function get_api_object_hash($object_uri) {
    return hash('sha1',$object_uri);
  }
  
# program helpers

  function prepare_program_data(&$data) {
    get_all_results_pages($data);
    group_sessions_by_day_and_start_time($data);
  }
  
  function get_all_results_pages(&$data) {
    # build a new object to store all the pages
    
      $pages = array();
      $count = array_push($pages, $data);
      $is_next_page = false;
      
      # keep fetching until you get all the pages
      
      do {
        $i = $count - 1;
        $is_next_page = (isset($pages[$i]['meta']['next']) && $pages[$i]['meta']['next'] != "null")? true : false;
        
        $json = get_api_object($pages[$i]['meta']['next'] . "&");
        $json = utf8_encode($json);
        $count = array_push($pages, json_decode($json, true));
      } while ($is_next_page);
      
      # assemble back into a single object for usage
      unset($data['objects']);
      
      $data['objects'] = array();
      
      foreach ($pages as $page) {
        foreach ($page['objects'] as $object) {
          array_push($data['objects'], $object);
        }
      }
      
      # invalidate the "next" attribute in the data field
      $data['meta']['next'] = "null";
  }
  
  function group_sessions_by_day_and_start_time(&$data) {
    
    $list_of_days = array();
    $day = $new_day = null;
    $starttime = $new_starttime = null;
    
    foreach ($data['objects'] as $session) {   
      # group sessions by day
      
        # determine the day of the first event
        $new_day = substr($session['startTime'],0,10);
        if ($day != $new_day) {
        
          # if different day, then setup a new object
          $days_count = array_push(
            $list_of_days,
            array(
              'day' => $new_day,
              'starttimes' => array(),
            )
          );
          $day = $new_day;
        }
      
      # until the next day, group by start time

        # determine the start time of the first event
        $new_starttime = $session['startTime'];
        
        if ($starttime != $new_starttime) {
          
          # if different starttime, the setup a new object
          
          $starttimes_count = array_push(
            $list_of_days[$days_count - 1]['starttimes'],
            array(
              'starttime' => $new_starttime,
              'events' => array(),
            )
          );
          $starttime = $new_starttime;
        }
        
      # store the object in its new location in the new day => starttime listing
      
      array_push($list_of_days[$days_count - 1]['starttimes'][$starttimes_count - 1]['events'], $session);
      
    }
    
    # replace the original $data objects list with the new dat => starttime listing
    
    unset($data['objects']);
    
    $data['days'] = $list_of_days;
    
  }

?>