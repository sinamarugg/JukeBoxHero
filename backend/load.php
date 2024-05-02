<?php

// include transform.php
include 'transform.php';

require_once 'spotifyconfig.php';

// require once config.php
require_once 'config.php';


//SPOTIFY TOKEN BEANTRAGEN
// Endpunkt zum Anfordern eines Zugriffstokens
$url = 'https://accounts.spotify.com/api/token';

// Erstellen einer neuen cURL-Sitzung
$ch = curl_init($url);

// Vorbereiten der POST-Felder
$data = 'grant_type=client_credentials';

// Setzen des HTTP-Headers
$headers = array(
    'Authorization: Basic ' . $credentials,
    'Content-Type: application/x-www-form-urlencoded'
);

// Setzen der cURL-Optionen
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Ausführen der cURL-Sitzung
$response = curl_exec($ch);
curl_close($ch);

// Dekodieren der Antwort, um das Zugriffstoken zu erhalten
$responseData = json_decode($response, true);
$accessToken = $responseData['access_token'];


//ÜBERPRÜFEN OB SONG SCHON IN DER DATENBANK IST
//WENN JA, ÜBERSPRINGEN
//WENN NEIN, HOLE INFORMATIONEN VON SPOTIFY API UND FÜGE SONG IN DATENBANK EIN

foreach($SongList as $song) {
    $title = $song['title'];
    $interpret = $song['interpret'];
    $playedAt = $song['playedAt'];
    $duration = $song['duration'];

    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

    $stmt = $pdo->prepare("SELECT * FROM Songs WHERE LOWER(title) = LOWER(?) AND played_at = ?");
    $stmt->execute([$title, $playedAt]);

    // Check if any rows returned
    if ($stmt->rowCount() > 0) {
        echo "The song ". $title ." from ". $interpret . " already exists in the database.<br><br>";
        continue;
    }

    // Get information about the song from the Spotify API

    $encodedTrack = urlencode($title);
    $encodedArtist = urlencode($interpret);

    // Construct the search query without encoding the field labels
    $searchQuery = "track:$encodedTrack%20artist:$encodedArtist";

    // Spotify search API endpoint with the query
    $url = "https://api.spotify.com/v1/search?q=$searchQuery&type=track&limit=1";

    // Create a new cURL session for the API request
    $ch = curl_init($url);

    // Set the HTTP header with the access token
    $headers = array('Authorization: Bearer ' . $accessToken);

    // Set cURL options
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session and fetch the response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response to get the track information
    $trackData = json_decode($response, true);

    // Check if the track information is available
    if (isset($trackData['tracks']['items'][0])) {
        $track = $trackData['tracks']['items'][0];

        // Get the Spotify ID of the track
        $spotifyId = $track['id'];

        // Get the preview URL of the track
        $previewUrl = $track['preview_url'];

        $imageUrl = '';
        foreach ($trackData['tracks']['items'][0]['album']['images'] as $image) {
            if ($image['height'] == 640) {
                $imageUrl = $image['url'];
                break;
            }
        }

        //get the duration
        $trackDuration = $track['duration_ms'];

        $previewUrl = $track['preview_url'];

        $album_name = $track['album']['name'];

        $album_release = $track['album']['release_date'];

        $spotify_url = $track['external_urls']['spotify'];

        // Insert the song into the database
        $stmt = $pdo->prepare("INSERT INTO Songs (spotify_id, played_at, title, interpret, duration, image_url, preview_url, album_name, release_date, spotify_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$spotifyId, $playedAt, $title, $interpret, $trackDuration, $imageUrl, $previewUrl, $album_name, $album_release, $spotify_url]);

        echo "The song " . $title . " from " . $interpret  . " has been successfully added to the database.<br><br>";
    } else {

        $stmt = $pdo->prepare("INSERT INTO Songs (played_at, title, interpret, duration) VALUES (?, ?, ?, ?)");
        $stmt->execute([$playedAt, $title, $interpret, $duration]);

        echo "<bold>The song " . $title . " from " . $interpret . " could not be found on Spotify.</bold><br>But the Song has been added to the database.<br><br>";
    }

}

?>