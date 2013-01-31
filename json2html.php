<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once 'lib/vendor/autoload.php';

$cache_dir = 'json-cache/';

$json_url = isset($_GET['src'])? $cache_dir . $_GET['src'] . '.json' : "http://gears.guidebook.com/api/v1/poi/571968/?category=14833&format=json&username=jarsenea@uottawa.ca&api_key=DYJmr8vDWBrfZeBUr8wfgrhxMQUemRvnvSGYnfdKDQQxsvY";

$template = isset($_GET['template'])? $_GET['template'] : 'accommodation.twig';

//$json_url = ".json-cache/532628.json";

$loader = new Twig_Loader_Filesystem('templates/');
$twig = new Twig_Environment($loader);

$json = file_get_contents($json_url);
//var_dump($json);
$json = utf8_encode($json); 

$data = json_decode($json, true);

//echo "<pre>".print_r($data,true)."</pre>";

echo $twig->render($template, $data);

?>