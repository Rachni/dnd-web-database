<?php
require_once '../src/includes/auth_helper.php';
checkAuthentication();
require_once '../src/includes/header.php';
require_once '../src/Controller/CharacterController.php';

$characterController = new CharacterController();
$characters = $characterController->getCharactersByUser($_SESSION['user_id']);
error_log("Characters from controller: " . print_r($characters, true));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Characters</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/table_styles.css">
</head>

<body>
    <main>
        <h1>View Characters</h1>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($characters)): ?>
            <p>You have no characters.</p>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Race</th>
                            <th>Class</th>
                            <th>Level</th>
                            <th>Health</th>
                            <th>Campaign</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($characters as $character): ?>
                            <tr>
                                <td>
                                    <img src="../uploads/characters/<?= htmlspecialchars($character['image_path'] ?? 'default.png'); ?>"
                                        alt="<?= htmlspecialchars($character['name']); ?>"
                                        class="personaje-imagen"
                                        loading="lazy">
                                </td>
                                <td><?= htmlspecialchars($character['name']); ?></td>
                                <td><?= htmlspecialchars($character['race']); ?></td>
                                <td><?= htmlspecialchars($character['class']); ?></td>
                                <td><?= htmlspecialchars($character['level']); ?></td>
                                <td><?= htmlspecialchars($character['health']); ?></td>
                                <td><?= htmlspecialchars($character['campaign_name'] ?? 'No Campaign'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>