<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Characters</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Manage Characters</h1>
        <ul class="management-list">
            <li class="management-item"><a href="./create_character.php">Create Character</a></li>
            <li class="management-item"><a href="./view_characters.php">View Characters</a></li>
            <li class="management-item"><a href="./edit_character.php">Update Character</a></li>
            <li class="management-item"><a href="./delete_character.php">Delete Character</a></li>
        </ul>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
