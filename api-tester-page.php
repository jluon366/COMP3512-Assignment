<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tester</title>
    <link rel="stylesheet" href="css/global.css" />
    <link rel="stylesheet" href="css/api-tester-page.css" />
    <style>
    </style>
</head>
<body>
<?php include './shared/header.php'; ?>

<h1>API Tester Page</h1>

<h2>Sample API Routes</h2>
<ul class="api-tester">

    <!-- Constructors API routes -->
    <li><a href="/project/api/constructors.php">/api/constructors.php (All Constructors)</a></li>
    <li><a href="/project/api/constructors.php?ref=mclaren">/api/constructors.php?ref=mclaren (Constructor: McLaren)</a></li>

    <!-- Drivers API routes -->
    <li><a href="/project/api/drivers.php">/api/drivers.php (All Drivers)</a></li>
    <li><a href="/project/api/drivers.php?ref=hamilton">/api/drivers.php?ref=hamilton (Driver: Hamilton)</a></li>
    <li><a href="/project/api/drivers.php?race=1106">/api/drivers.php?race=1106 (Drivers in Race 1106)</a></li>
    
</ul>


<?php
// Fetch API data if an API route is selected
if (isset($_GET['api'])) {
    $apiRoute = $_GET['api'];
    $queryString = http_build_query($_GET);
    
    // Construct the API URL
    $apiUrl = "http://yourdomain.com/api/{$apiRoute}.php?" . $queryString;

    // Fetch the API response
    $response = file_get_contents($apiUrl);

    // Display the JSON response
    echo "<h2>API Response</h2>";
    echo "<pre>" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "</pre>";
}
?>

</body>
</html>

