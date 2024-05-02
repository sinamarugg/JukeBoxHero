<?php

include 'extract.php';

//make map with lat / lon to location



function weather_condition($precipitation, $cloud_cover) {
    if ($cloud_cover <= 80 && $precipitation == 0) {
        return 'sunny';
    } elseif ($cloud_cover > 80 && $precipitation < 5) {
        return 'cloudy';
    } elseif ($precipitation >= 5) {
        return 'rainy';
   }
}

//transform data
foreach ($weather_data as $index => $item) { 

    //round temperature to integer
   $weather_data[$index]['temperature_2m'] = round($item['temperature_2m']);

   //convert lat / lon to location
    $coordinates = $item['latitude'] . ',' . $item['longitude'];

    // use map to get location
    $weather_data[$index]['location'] = $locations[$coordinates];

    unset($weather_data[$index]['latitude']);
    unset($weather_data[$index]['longitude']);

    $weather_data[$index]['condition'] = weather_condition($item['precipitation'], $item['cloud_cover']);


}
$weather_data = json_encode($weather_data);

echo $weather_data;
?>