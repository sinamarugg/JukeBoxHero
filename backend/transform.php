<?php

include 'spotifyconfig.php';
include 'extract.php';

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

$newList = [];

// Check if 'songList' is available in data and is an array
if (isset($songData['songList']) && is_array($songData['songList'])) {
    // Iterate over each song in the songList
    foreach ($songData['songList'] as $song) {
        $newList[] = [
            'title' => $song['title'],
            'interpret' => $song['artist']['name'],
            'duration' => $song['duration'],
            'playedAt' => $song['date']
        ];
    }
} else {
    echo "Error: 'songList' key not found or is not an array.";
}

?>