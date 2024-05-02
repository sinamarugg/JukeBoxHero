<?php

// Set default timezone
date_default_timezone_set('Europe/Berlin'); // This timezone is UTC+2 during daylight saving time

// Current timestamp
$currentDateTime = new DateTime('today midnight');
$currentFormatted = $currentDateTime->format('c');  // 'c' format corresponds to the ISO 8601 date
$currentFormatted = str_replace(":", "%3A", $currentFormatted);
$currentFormatted = str_replace("+", "%2B", $currentFormatted);
//echo "Current Time: " . $currentFormatted . "\n";

// Timestamp one hour in the future
$futureDateTime = new DateTime('today 23:59:59');
$futureFormatted = $futureDateTime->format('c');
$futureFormatted = str_replace(":", "%3A", $futureFormatted);
$futureFormatted = str_replace("+", "%2B", $futureFormatted);

$url = "https://il.srgssr.ch/integrationlayer/2.0/srf/songList/radio/byChannel/69e8ac16-4327-4af4-b873-fd5cd6e895a7?from=".$currentFormatted."&to=".$futureFormatted."&pageSize=500";


$ch = curl_init($url);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($ch);

curl_close($ch);


//speichere hier alle Daten in variabeln

$songData = json_decode($output, true);


?>