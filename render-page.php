<?php

  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);

# set defaults

  $cache_dir = '.json-cache/';
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
      $json_uri = '/api/v1/event/?guide__id=5396&';
      $template_file = $matches[1].'/index.twig';
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
  
    # restaurants
  
    case (
      preg_match(
        "/^\/(your-stay\/restaurants)\/([0-9]{1,6})$/"
        , $p, $matches) ? true : false
      ) :
      $json_uri = '/api/v1/poi/' . $matches[2] . '/?category=14833&';
      $template_file = $matches[1].'/restaurant.twig';
      array_push($parse_functions, 'fetch_links');
      break;
    case (
      preg_match(
        "/^\/(your-stay\/restaurants)\/$/"
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
    
    foreach ($data['links'] as $link) {
      $json = get_api_object($link);
      if (true === $json) {
        $json = utf8_encode($json);
        $data = json_decode($json, true);
        if (false == is_array($data)) {
          $link = $data;
        }
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
    
  }
  
  function get_all_results_pages(&$data) {
    
  }
  
  function group_sessions_by_start_time(&$data) {
    
  }

?>