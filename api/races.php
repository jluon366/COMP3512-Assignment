<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:../data/f1.db');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$ref = isset($_GET['ref']) ? $_GET['ref'] : null;

if ($requestMethod === 'GET') {
    if ($ref) {
        $stmt = $db->prepare("
            SELECT races.raceId, races.name, races.date, circuits.name AS circuit_name, circuits.location, circuits.country 
            FROM races
            JOIN circuits ON races.circuitId = circuits.circuitId
            WHERE races.raceId = ?");
        $stmt->execute([$ref]);
        $race = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($race) {
            echo json_encode($race);
        } else {
            echo json_encode(['message' => 'Race not found']);
        }
    } else {
        $stmt = $db->prepare("SELECT * FROM races WHERE races.year = 2022");
        $stmt->execute();
        $races = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($races);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
}

