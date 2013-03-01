<?php

//  ini_set('error_reporting', E_ALL);
//  ini_set('display_errors', 1);

# set defaults

  $cache_dir = '/.json-cache/';
  $cache_time = 3600; //seconds
  $template_dir = 'templates';
  $api_url_start = 'http://gears.guidebook.com';
  $api_url_end = 'format=json&username=jarsenea@uottawa.ca&api_key=DYJmr8vDWBrfZeBUr8wfgrhxMQUemRvnvSGYnfdKDQQxsvY';

# load requirements

  require_once 'lib/.vendor/autoload.php';
  require_once 'lib/.vendor/simplecache/simpleCache.php';
  $loader = new Twig_Loader_Filesystem($template_dir);
  $twig = new Twig_Environment($loader);
  $cache = new SimpleCache();
  $cache->cache_path = $cache_dir;
  $cache->cache_time = $cache_time;

# parse the URI

  $p = $_SERVER['REQUEST_URI'];
  $json_uri = "";
  $template_file = "";
  $parse_functions = Array();
  
  switch ($p) {
  
    # program
    
    case (
      preg_match(
        "/^\/(program)\/$/"
        , $p, $matches) ? true : false
      ) :
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
      break;
      
    # otherwise, 404
    
    default:
      return_404();
  }

# load the json, throw a 404 on error

  $json = get_api_object($json_uri);
  
  if (false === $json) {
    return_404();
  }
  
  $json = utf8_encode($json);

# parse the json, throw a 404 on error

  $data = json_decode($json, true);
  
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