<?php
require_once '../src/Controller/CharacterController.php';
require_once '../src/includes/auth_helper.php';
checkAuthentication(); // Protege la página
require_once '../src/includes/header.php'; // Muestra el header con el estado de sesión

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$characterController = new CharacterController();
$characters = $characterController->getCharactersByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Character</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
</head>

<body>
    <main>
        <h1>Delete Character</h1>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para eliminar personaje -->
        <div class="form-container">
            <form action="../src/Handler/CharacterHandler.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this character?');">
                <input type="hidden" name="action" value="deleteCharacter">
                <label for="character">Select Character:</label>
                <select id="character" name="id" required>
                    <option value="">Select Character</option>
                    <?php foreach ($characters as $char): ?>
                        <option value="<?= htmlspecialchars($char['id']); ?>"><?= htmlspecialchars($char['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="delete-button">Delete Character</button>
            </form>

        </div>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>