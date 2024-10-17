<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:../data/f1.db');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$ref = isset($_GET['ref']) ? $_GET['ref'] : null;

if ($requestMethod === 'GET' && $ref) {
    $stmt = $db->prepare("
        SELECT drivers.driverRef, drivers.forename, drivers.surname, constructors.name as constructor, qualifying.position
        FROM qualifying
        JOIN drivers ON qualifying.driverId = drivers.driverId
        JOIN constructors ON qualifying.constructorId = constructors.constructorId
        WHERE qualifying.raceId = ?
        ORDER BY qualifying.position ASC");
    $stmt->execute([$ref]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($results) {
        echo json_encode($results);
    } else {
        echo json_encode(['message' => 'No qualifying results found for this race']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request']);
}

