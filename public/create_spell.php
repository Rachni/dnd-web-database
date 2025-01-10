<?php
require_once '../src/includes/auth_helper.php';
checkAuthentication();
require_once '../src/includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Spell</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
</head>

<body>
    <main>
        <h1>Create a New Spell</h1>

        <!-- Mostrar mensajes de error o éxito -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <form action="../src/Handler/SpellHandler.php" method="POST">
                <!-- Acción para el handler -->
                <input type="hidden" name="action" value="createSpell">

                <!-- Nombre del hechizo -->
                <label for="name">Spell Name:</label>
                <input type="text" id="name" name="name" required>

                <!-- Descripción -->
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" required></textarea>

                <!-- Nivel del hechizo -->
                <label for="level">Level:</label>
                <input type="number" id="level" name="level" min="1" max="20" required>

                <!-- Tipo de hechizo -->
                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="Abjuration">Abjuration</option>
                    <option value="Conjuration">Conjuration</option>
                    <option value="Divination">Divination</option>
                    <option value="Enchantment">Enchantment</option>
                    <option value="Evocation">Evocation</option>
                    <option value="Illusion">Illusion</option>
                    <option value="Necromancy">Necromancy</option>
                    <option value="Transmutation">Transmutation</option>
                </select>

                <!-- Botón para enviar -->
                <button type="submit">Create Spell</button>
            </form>
        </div>
    </main>

    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
