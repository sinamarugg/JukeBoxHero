<?php

// include transform.php
include 'transform.php';

require_once 'spotifyconfig.php';

// require once config.php
require_once 'config.php';

echo $weather_data;

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    // SQL-Query mit Platzhaltern für das Einfügen von Daten
    $sql = "INSERT INTO Songs (location, temperature, precipitation, cloud_cover, weather_condition) VALUES (?, ?, ?, ?, ?)";

    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Fügt jedes Element im Array in die Datenbank ein
    foreach ($weather_data as $item) {
        $stmt->execute([
            $item['location'],
            $item['temperature_2m'],
            $item['precipitation'],
            $item['cloud_cover'],
            $item['condition']
        ]);
    }

    echo "Daten erfolgreich eingefügt.";
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}