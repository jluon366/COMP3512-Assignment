<?php
// Connect to the SQLite database
$db = new PDO('sqlite:./data/f1.db');

// Get the raceId from the URL, if available
$raceId = isset($_GET['raceId']) ? $_GET['raceId'] : null;

if ($raceId) {
    // Fetch race details
    $raceStmt = $db->prepare("SELECT raceId, name, year, round, date, time, circuitId FROM races WHERE raceId = ?");
    $raceStmt->execute([$raceId]);
    $race = $raceStmt->fetch(PDO::FETCH_ASSOC);

    // Fetch circuit details
    $circuitStmt = $db->prepare("SELECT name, location, country FROM circuits WHERE circuitId = ?");
    $circuitStmt->execute([$race['circuitId']]);
    $circuit = $circuitStmt->fetch(PDO::FETCH_ASSOC);

    // Fetch qualifying results
    $qualifyingStmt = $db->prepare("
        SELECT drivers.forename, drivers.surname, drivers.driverRef, constructors.name AS constructor_name, constructors.constructorRef, qualifying.position, qualifying.q1, qualifying.q2, qualifying.q3
        FROM qualifying
        JOIN drivers ON qualifying.driverId = drivers.driverId
        JOIN constructors ON qualifying.constructorId = constructors.constructorId
        WHERE qualifying.raceId = ?
        ORDER BY qualifying.position ASC
    ");
    $qualifyingStmt->execute([$raceId]);
    $qualifyingResults = $qualifyingStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch race results
    $resultsStmt = $db->prepare("
        SELECT drivers.forename, drivers.surname, drivers.driverRef, constructors.name AS constructor_name, constructors.constructorRef, results.grid, results.position, results.points, results.laps
        FROM results
        JOIN drivers ON results.driverId = drivers.driverId
        JOIN constructors ON results.constructorId = constructors.constructorId
        WHERE results.raceId = ?
        ORDER BY results.position ASC
    ");
    $resultsStmt->execute([$raceId]);
    $raceResults = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all races for the season
    $racesStmt = $db->prepare("SELECT raceId, name, round, year, date FROM races where year = 2022 ORDER BY date ASC");
    $racesStmt->execute();
    $races = $racesStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Races</title>
    <link rel="stylesheet" href="./css/global.css" />
    <link rel="stylesheet" href="./css/browse.css" />
</head>
<body>
<?php include './shared/header.php'; ?>

<?php if ($raceId && $race): ?>
    <main class="container-one">
      <h1><?php echo $race['name']; ?> (<?php echo $race['year']; ?>)</h1>
      <p><strong>Round:</strong> <?php echo $race['round']; ?></p>
      <p><strong>Date:</strong> <?php echo $race['date']; ?> <?php echo $race['time']; ?></p>
      <p><strong>Circuit:</strong> <?php echo $circuit['name']; ?> - <?php echo $circuit['location']; ?>, <?php echo $circuit['country']; ?></p>

      <div class="race-result">
        <div>
          <h2>Qualifying Results</h2>
          <table border="1">
              <thead>
                  <tr>
                      <th>Position</th>
                      <th>Driver</th>
                      <th>Constructor</th>
                      <th>Q1</th>
                      <th>Q2</th>
                      <th>Q3</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($qualifyingResults as $qualifying): ?>
                      <tr>
                          <td><?php echo $qualifying['position']; ?></td>
                          <td><a href="driver.php?ref=<?php echo $qualifying['driverRef']?>"><?php echo $qualifying['forename'] . ' ' . $qualifying['surname']; ?></a></td>
                          <td><a href="constructor.php?ref=<?php echo $qualifying['constructorRef']?>"><?php echo $qualifying['constructor_name']; ?></a></td>
                          <td><?php echo $qualifying['q1']; ?></td>
                          <td><?php echo $qualifying['q2']; ?></td>
                          <td><?php echo $qualifying['q3']; ?></td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
        </div>

        <div>
          <h2>Race Results</h2>
          <table border="1">
              <thead>
                  <tr>
                      <th>Position</th>
                      <th>Driver</th>
                      <th>Constructor</th>
                      <th>Grid</th>
                      <th>Laps</th>
                      <th>Points</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($raceResults as $result): ?>
                      <tr>
                          <td><?php echo $result['position']; ?></td>
                          <td><a href="driver.php?ref=<?php echo $result['driverRef']?>"><?php echo $result['forename'] . ' ' . $result['surname']; ?></a></td>
                          <td><a href="constructor.php?ref=<?php echo $result['constructorRef'] ?>"><?php echo $result['constructor_name']; ?></a></td>
                          <td><?php echo $result['grid']; ?></td>
                          <td><?php echo $result['laps']; ?></td>
                          <td><?php echo $result['points']; ?></td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
        </div>
      </div>

      <a href="browse.php" class="back-link">Back to all races</a>
    </main>
    <?php else: ?>
    <main class="container-all">
      <h1>2022 Races</h1>
      <p>Click on the race to view more info.</p>
      <ul>
          <?php foreach ($races as $race): ?>
              <li>
                  <a href="browse.php?raceId=<?php echo $race['raceId']; ?>">
                      <?php echo $race['name']; ?> (<?php echo $race['year']; ?>)
                  </a> - Round <?php echo $race['round']; ?>, <?php echo $race['date']; ?>
              </li>
          <?php endforeach; ?>
      </ul>
    </main>
<?php endif; ?>

</body>
</html>

