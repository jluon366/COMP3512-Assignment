<?php
$db = new PDO('sqlite:./data/f1.db');

$constructorRef = isset($_GET['ref']) ? $_GET['ref'] : null;

if ($constructorRef) {
    $constructorStmt = $db->prepare("SELECT constructorId, constructorRef, name, nationality, url FROM constructors WHERE constructorRef = ?");
    $constructorStmt->execute([$constructorRef]);
    $constructor = $constructorStmt->fetch(PDO::FETCH_ASSOC);

    if ($constructor) {
        $resultsStmt = $db->prepare("
            SELECT races.name AS race_name, races.round, races.year, races.date, races.time,
                   drivers.driverRef, drivers.forename, drivers.surname, drivers.number, drivers.code,
                   results.grid, results.position, results.points, results.laps, results.time AS race_time, results.fastestLap, results.fastestLapTime
            FROM results
            JOIN races ON results.raceId = races.raceId
            JOIN drivers ON results.driverId = drivers.driverId
            WHERE results.constructorId = ? AND races.year = 2022
            ORDER BY races.date ASC");
        $resultsStmt->execute([$constructor['constructorId']]);
        $results = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Constructor not found!";
        exit;
    }
} else {
    echo "No constructor specified!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $constructor['name']; ?> - Constructor Page</title>
    <link rel="stylesheet" href="./css/global.css" />
    <link rel="stylesheet" href="./css/constructor.css" />
</head>
<body>
  <?php include './shared/header.php'; ?>

  <div class="container">
    <div class="constructor-details">
        <h1><?php echo $constructor['name']; ?></h1>
        <p><strong>Nationality:</strong> <?php echo $constructor['nationality']; ?></p>
        <p><strong>More Info:</strong> <a href="<?php echo $constructor['url']; ?>">Constructor Profile</a></p>
    </div>

    <div class="race-results">
        <h2>Race Results for the Season</h2>
        <table>
            <thead>
                <tr>
                    <th>Race Name</th>
                    <th>Round</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Driver</th>
                    <th>Driver Number</th>
                    <th>Grid Position</th>
                    <th>Finish Position</th>
                    <th>Points</th>
                    <th>Laps</th>
                    <th>Race Time</th>
                    <th>Fastest Lap</th>
                    <th>Fastest Lap Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo $result['race_name']; ?></td>
                        <td><?php echo $result['round']; ?></td>
                        <td><?php echo $result['date']; ?></td>
                        <td><?php echo $result['time']; ?></td>
                        <td><?php echo $result['forename'] . ' ' . $result['surname']; ?></td>
                        <td><?php echo $result['number']; ?></td>
                        <td><?php echo $result['grid']; ?></td>
                        <td><?php echo $result['position']; ?></td>
                        <td><?php echo $result['points']; ?></td>
                        <td><?php echo $result['laps']; ?></td>
                        <td><?php echo $result['race_time']; ?></td>
                        <td><?php echo $result['fastestLap']; ?></td>
                        <td><?php echo $result['fastestLapTime']; ?></td>
      </tr>
  </div>
<?php endforeach; ?>

