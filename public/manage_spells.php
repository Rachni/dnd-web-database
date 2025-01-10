<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Spells</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Manage Spells</h1>
        <ul class="management-list">
            <li class="management-item"><a href="./create_spell.php">Create Spell</a></li>
            <li class="management-item"><a href="./view_spells.php">View Spells</a></li>
            <li class="management-item"><a href="./edit_spell.php">Update Spell</a></li>
            <li class="management-item"><a href="./delete_spell.php">Delete Spell</a></li>
        </ul>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
