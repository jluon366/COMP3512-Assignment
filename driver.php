<?php
// Connect to the SQLite database
$db = new PDO('sqlite:./data/f1.db');

// Get the driverRef from the URL
$driverRef = isset($_GET['ref']) ? $_GET['ref'] : null;

if ($driverRef) {
    // Fetch driver details using driverRef
    $driverStmt = $db->prepare("SELECT driverId, driverRef, number, code, forename, surname, dob, nationality, url FROM drivers WHERE driverRef = ?");
    $driverStmt->execute([$driverRef]);
    $driver = $driverStmt->fetch(PDO::FETCH_ASSOC);

    if ($driver) {
        // Fetch race results for the driver
        $resultsStmt = $db->prepare("
            SELECT races.name AS race_name, races.round, races.year, races.date, races.time,
                   constructors.name AS constructor_name, constructors.constructorRef, constructors.nationality,
                   results.grid, results.position, results.points, results.laps, results.time AS race_time, results.fastestLap, results.fastestLapTime
            FROM results
            JOIN races ON results.raceId = races.raceId
            JOIN constructors ON results.constructorId = constructors.constructorId
            WHERE results.driverId = ? AND races.year = 2022
            ORDER BY races.date ASC");
        $resultsStmt->execute([$driver['driverId']]);
        $results = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Driver not found!";
        exit;
    }
} else {
    echo "No driver specified!";
    exit;
}

function calculateAge($dateOfBirth) {
  $dob = new DateTime($dateOfBirth);
  $currentDate = new DateTime();

  $age = $currentDate->diff($dob);

  return $age->y;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $driver['forename'] . ' ' . $driver['surname']; ?> - Driver Page</title>
    <link rel="stylesheet" href="./css/global.css" />
    <link rel="stylesheet" href="./css/driver.css" />
</head>
<body>

    <?php include './shared/header.php'; ?>
    <div class="container">
      <div class="driver-details">
          <h1><?php echo $driver['forename'] . ' ' . $driver['surname']; ?></h1>
          <p><strong>Nationality:</strong> <?php echo $driver['nationality']; ?></p>
          <p><strong>Date of Birth:</strong> <?php echo $driver['dob']; ?></p>
          <p><strong>Age:</strong> <?php echo calculateAge($driver['dob']); ?></p>
          <p><strong>Driver Number:</strong> <?php echo $driver['number']; ?></p>
          <p><strong>Driver Code:</strong> <?php echo $driver['code']; ?></p>
          <p><strong>More Info:</strong> <a href="<?php echo $driver['url']; ?>">Driver Profile</a></p>
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
                      <th>Constructor</th>
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
                          <td><?php echo $result['constructor_name']; ?></td>
                          <td><?php echo $result['grid']; ?></td>
                          <td><?php echo $result['position']; ?></td>
                          <td><?php echo $result['points']; ?></td>
                          <td><?php echo $result['laps']; ?></td>
                          <td><?php echo $result['race_time']; ?></td>
                          <td><?php echo $result['fastestLap']; ?></td>
                          <td><?php echo $result['fastestLapTime']; ?></td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
    </div>
</body>
</html>

