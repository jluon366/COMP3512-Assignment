<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:../data/f1.db');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$ref = isset($_GET['ref']) ? $_GET['ref'] : null;
$race = isset($_GET['race']) ? $_GET['race'] : null;

if ($requestMethod === 'GET') {
    if ($ref) {
        $stmt = $db->prepare("SELECT * FROM drivers WHERE driverRef = ?");
        $stmt->execute([$ref]);
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($driver) {
            echo json_encode($driver);
        } else {
            echo json_encode(['message' => 'Driver not found']);
        }
    } elseif ($race) {
        $stmt = $db->prepare("
        SELECT drivers.driverRef, drivers.number, drivers.code, drivers.forename, drivers.surname, drivers.nationality, results.position
        FROM drivers
        JOIN results ON drivers.driverId = results.driverId
        WHERE results.raceId = ?
        ORDER BY results.position ASC");
        $stmt->execute([$race]);
        $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($drivers) {
            echo json_encode($drivers);
        } else {
            echo json_encode(['message' => 'No drivers found for this race']);
        }
    } else {
        $stmt = $db->prepare("SELECT * FROM drivers");
        $stmt->execute();
        $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($drivers);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
}

