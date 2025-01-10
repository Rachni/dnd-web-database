<?php
require_once '../src/includes/auth_helper.php';
checkAuthentication();  // Asegurarse de que el usuario está autenticado
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Campaign</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Create Campaign</h1>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>
        <!-- Contenedor del formulario -->
        <div class="form-container">
            <form action="../src/Handler/CampaignHandler.php" method="POST">
                <input type="hidden" name="action" value="createCampaign">
                
                <!-- Campo hidden para el user_id -->
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>"> <!-- Añadimos user_id aquí -->
                
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date">

                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="paused">Paused</option>
                </select>

                <button type="submit">Create Campaign</button>
            </form>
        </div>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
