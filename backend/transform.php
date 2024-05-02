<?php

require_once 'extract.php';

$SongList = [];

// Check if 'songList' is available in data and is an array
if (isset($songData['songList']) && is_array($songData['songList'])) {
    // Iterate over each song in the songList
    foreach ($songData['songList'] as $song) {
        $SongList[] = [
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