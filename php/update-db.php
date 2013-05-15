<?php

//  ini_set('error_reporting', E_ALL);
//  ini_set('display_errors', 1);

# set defaults

  require_once '../config.php';

# load requirements
  
  require_once 'db.php';
  
# load the db, downloads it if if doesn't exist

  $db = load_db();

# get version numbers from local db and the source manifest
  
  # get version number in the db
  
  $stmt = $db->prepare('SELECT guideVersion FROM guidebook_guide;');
  $data = $stmt->execute()->fetchArray();

  $current_db_version = $data['guideVersion'];

  $db->close();
  unset($data);
  
  # get version number in the manifest
  
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, DB_MANIFEST_URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	$json_content = curl_exec($ch);
  $manifest = json_decode($json_content,1);
	
	$manifest_db_version = $manifest['guideVersion'];
	
	curl_close($ch);
	unset($manifest);
  

# if a new database file exists, download it

  # compare current version number with source manifest

  if ((int)$manifest_db_version > (int)$current_db_version) {
    download_db();
    echo 'Data updated.<br ><a href="../">CANHEIT Home page &rsaquo;</a>';
  } else {
    echo 'No data updated. Using the latest version.<br ><a href="../">CANHEIT Home page &rsaquo;</a>';
  }