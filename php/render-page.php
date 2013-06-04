<?php

  //ini_set('error_reporting', E_ALL);
  //ini_set('display_errors', 1);

# set defaults

  require_once '../config.php';

# load requirements

  require_once '../lib/.vendor/autoload.php';
  require_once 'db.php';

  $db = load_db();

  $twig_loader = new Twig_Loader_Filesystem(TEMPLATE_DIR);
  $twig_loader->addPath('../include');
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
      $stmt = $db->prepare('SELECT `guidebook_event`.*, `guidebook_location`.name as "location" FROM `guidebook_event`, `guidebook_location` WHERE `guidebook_location`.id == `guidebook_event`.locations AND `guidebook_event`.id = :id ORDER BY startTime;');
      $stmt->bindParam(':id', $matches[2], SQLITE3_INTEGER);
      $is_single_object_expected = true;
      $template_file = $matches[1].'/session.twig';
      array_push($parse_functions, 'get_correct_image_urls', 'parse_links');
      break;

    case (
      preg_match(
        "/^\/(program)\/$/"
        , $p, $matches) ? true : false
      ) :
      $stmt = $db->prepare('SELECT `guidebook_event`.*, `guidebook_location`.name as "location" FROM `guidebook_event`, `guidebook_location` WHERE `guidebook_location`.id == `guidebook_event`.locations ORDER BY startTime;');
      $template_file = $matches[1].'/index.twig';
      array_push($parse_functions, 'prepare_program_data');
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
      remove_prod_domain_and_app_toggle_param_from_link_url($data);
    }
  }
  
  function remove_prod_domain_and_app_toggle_param_from_link_url(&$data) {
    foreach($data['links'] as &$link) {
      # remove production domain if found
      $link['gb_url'] = preg_replace('/http:\/\/canheit\.uottawa\.ca/','', $link['gb_url']);
      # remove app=1 and &app=1 if found
      $link['gb_url'] = preg_replace('/&?app=1/','', $link['gb_url']);
      # remove trailing ? if found
      if ('?' == substr($link['gb_url'],-1)) {
        $link['gb_url'] = substr($link['gb_url'],0,-1);
      }
    }
  }
  
# program helpers

  function prepare_program_data(&$data) {
    group_sessions_by_day_and_start_time($data);
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

          # if different starttime, then setup a new object

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

    unset($data['objects']);

    $data['days'] = $list_of_days;

  }

?>