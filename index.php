<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Dashboard Project</title>
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

<?php include './shared/header.php'; ?>

<main class="homepage">
    <section class="intro">
        <h1>Welcome to the F1 Dashboard Project</h1>
        <p>This website provides comprehensive data about Formula 1 races, drivers, constructors and more. You can explore races, view race results and learn about the drivers and constructors who shape the F1 world. This site is created for assignment #1 for COMP3512 at Mount Royal
University</p>
        <br>
        <p>By Jason Luong</p>
        <br>
        <a href="https://github.com/jluon366/COMP3512-Assignment">Github Repository</a>
    </section>
    <section>
        <img src="./assets/hero.jpg" class="hero-img"/>
    </section>

    <section class="technologies">
        <h2>Technologies Used</h2>
        <ul>
            <li>PHP for server-side logic</li>
            <li>SQLite as the database</li>
            <li>HTML, CSS for the front-end</li>
        </ul>
    </section>

    <section class="browse-link">
        <a href="browse.php" class="button">Browse 2022 Season</a>
    </section>
</main>

</body>
</html>

