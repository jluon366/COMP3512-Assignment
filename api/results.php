<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:../data/f1.db');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$raceRef = isset($_GET['ref']) ? $_GET['ref'] : null;
$driverRef = isset($_GET['driver']) ? $_GET['driver'] : null;

if ($requestMethod === 'GET') {
    if ($raceRef) {
        $stmt = $db->prepare("
            SELECT drivers.driverRef, drivers.code, drivers.forename, drivers.surname, 
                   races.name as race_name, races.round, races.year, races.date,
                   constructors.name as constructor_name, constructors.constructorRef, constructors.nationality, results.grid
            FROM results
            JOIN drivers ON results.driverId = drivers.driverId
            JOIN races ON results.raceId = races.raceId
            JOIN constructors ON results.constructorId = constructors.constructorId
            WHERE results.raceId = ?
            ORDER BY results.grid ASC");
        $stmt->execute([$raceRef]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo json_encode($results);
        } else {
            echo json_encode(['message' => 'No results found for this race']);
        }
    } elseif ($driverRef) {
        $stmt = $db->prepare("
            SELECT drivers.driverRef, drivers.code, drivers.forename, drivers.surname, 
                   races.name as race_name, races.round, races.year, races.date,
                   constructors.name as constructor_name, constructors.constructorRef, constructors.nationality, results.grid
            FROM results
            JOIN drivers ON results.driverId = drivers.driverId
            JOIN races ON results.raceId = races.raceId
            JOIN constructors ON results.constructorId = constructors.constructorId
            WHERE drivers.driverRef = ?
            ORDER BY races.date ASC");
        $stmt->execute([$driverRef]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo json_encode($results);
        } else {
            echo json_encode(['message' => 'No results found for this driver']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid request']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
}

