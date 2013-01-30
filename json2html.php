<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once 'lib/vendor/autoload.php';

//$json_url = "http://plaliberte@uOttawa.ca:".urlencode("JYsdp89yV?CpiH=LVRer")."@gears.guidebook.com/api/v1/poi/571968/?category=14833&format=json&username=jarsenea@uottawa.ca&api_key=DYJmr8vDWBrfZeBUr8wfgrhxMQUemRvnvSGYnfdKDQQxsvY";

$json_url = ".json-cache/532628.json";

//echo $json_url;

//$options = array(
//  'http'=>array(
//    'method'=>"GET",
//    'header'=>"Accept-language: en\r\n" .
//              "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
//              "User-Agent: Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25\r\n"
//  )
//);
//
//$context = stream_context_create($options);

$loader = new Twig_Loader_Filesystem('templates/');
$twig = new Twig_Environment($loader);

//$json = file_get_contents($json_url, false, $context);
$json = file_get_contents($json_url);
//var_dump($json);
$json = utf8_encode($json); 

$data = json_decode($json, true);

//echo "<pre>".print_r($data,true)."</pre>";

echo $twig->render('accommodation.twig', $data);

?>