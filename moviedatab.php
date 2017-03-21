#!/usr/bin/php
<?php

$url = "https://api.themoviedb.org/3/search/movie?query=logan&language=en-US&api_key=db277983b36c1274dbe40cdeef5356e9";

$result = file_get_contents($url);
$json_message = json_decode($result, true);
echo $json_message["results"]["0"]["release_date"]."\n";
/*$count = 0;
while ($count < count($json_message["results"])){
	echo $json_message["results"]["$count"]["title"];
	echo $json_message["results"]["$count"]["id"] ."\n";
	$count ++ ;
}*/
?>
