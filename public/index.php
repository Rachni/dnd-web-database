<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
    <h1>Welcome to our Dungeons & Dragons campaign database </h1>
    <p>What would you like to manage?</p>
    <ul class="management-list">
        <li class="management-item"><a href="./manage_campaigns.php">Campaigns</a></li>
        <li class="management-item"><a href="./manage_characters.php">Characters</a></li>
        <li class="management-item"><a href="./manage_spells.php">Spells</a></li>
    </ul>
</main>


    <?php include '../src/includes/footer.php'; ?>
</body>

</html>