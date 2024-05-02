<?php

require_once 'config.php';

$pdo = new PDO($dsn, $db_user, $db_pass, $options);

// SQL-Query mit Platzhaltern für das Einfügen von Daten

$sql = "SELECT *, COUNT(*) AS times_played FROM Songs WHERE played_at >= CURDATE() - INTERVAL 7 DAY GROUP BY title, interpret ORDER BY times_played DESC";

// Bereitet die SQL-Anweisung vor
$stmt = $pdo->prepare($sql);

// Führt die SQL-Anweisung aus

$stmt->execute();

$data = $stmt->fetchAll();

$json = json_encode($data);

echo $json;

?>