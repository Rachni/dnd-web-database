<?php
require_once '../src/includes/auth_helper.php';
require_once '../src/Controller/CharacterController.php';

checkAuthentication();

$characterController = new CharacterController();

// Obtener las campañas del usuario
$campaigns = $characterController->getCampaignsForCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Character</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <script src="./js/create_character_script.js" defer></script>
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Create Character</h1>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <!-- Formulario de creación de personaje -->
            <form action="../src/Handler/CharacterHandler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="createCharacter">

                <label for="campaign_id">Select Campaign:</label>
                <select name="campaign_id" id="campaign_id" required>
                    <option value="">-- Select Campaign --</option>
                    <?php if (!empty($campaigns)): ?>
                        <?php foreach ($campaigns as $campaign): ?>
                            <option value="<?= htmlspecialchars($campaign['id']); ?>">
                                <?= htmlspecialchars($campaign['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No campaigns available</option>
                    <?php endif; ?>
                </select>

                <label for="name">Character Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="race">Race:</label>
                <select id="race" name="race" required>
                    <option value="">-- Select Race --</option>
                    <option value="Human">Human</option>
                    <option value="Elf">Elf</option>
                    <option value="Dwarf">Dwarf</option>
                    <option value="Halfling">Halfling</option>
                    <option value="Gnome">Gnome</option>
                    <option value="Tiefling">Tiefling</option>
                    <option value="Half-Orc">Half-Orc</option>
                    <option value="Dragonborn">Dragonborn</option>
                    <option value="Other">Other</option>
                </select>

                <input type="text" id="custom_race" name="custom_race" placeholder="Enter custom race" style="display: none;">

                <label for="class">Class:</label>
                <select id="class" name="class" required>
                    <option value="">-- Select Class --</option>
                    <option value="Fighter">Fighter</option>
                    <option value="Wizard">Wizard</option>
                    <option value="Rogue">Rogue</option>
                    <option value="Cleric">Cleric</option>
                    <option value="Barbarian">Barbarian</option>
                    <option value="Paladin">Paladin</option>
                    <option value="Ranger">Ranger</option>
                    <option value="Sorcerer">Sorcerer</option>
                    <option value="Warlock">Warlock</option>
                    <option value="Bard">Bard</option>
                    <option value="Monk">Monk</option>
                    <option value="Druid">Druid</option>
                    <option value="Other">Other</option>
                </select>

                <input type="text" id="custom_class" name="custom_class" placeholder="Enter custom class" style="display: none;">

                <label for="level">Level:</label>
                <input type="number" id="level" name="level" min="1" value="1">

                <label for="experience">Experience:</label>
                <input type="number" id="experience" name="experience" min="0" value="0">

                <label for="health">Health:</label>
                <input type="number" id="health" name="health" min="1" value="100">

                <label for="image">Character Image (Optional):</label>
                <label for="image" class="file-label">Select Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <span class="file-name" id="file-name">No file selected</span>

                <button type="submit">Create Character</button>
            </form>
        </div>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>