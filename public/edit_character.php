<?php
require_once '../src/includes/auth_helper.php';
require_once '../src/Controller/CharacterController.php';
require_once '../src/includes/header.php';

checkAuthentication();

// Fetch character details
$characterController = new CharacterController();
$character = null;

// Fetch all characters for the dropdown
$characters = $characterController->getCharactersByUser($_SESSION['user_id']);

// Fetch campaigns for dropdown
$campaigns = $characterController->getCampaignsForCurrentUser();

// Handle character selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['character_id'])) {
    try {
        $characterId = (int)$_POST['character_id'];
        $characterData = $characterController->getCharacter($characterId);
        $character = $characterData['data'] ?? null;
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error loading character: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Character</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
    <script src="./js/edit_character_script.js"></script>
</head>

<body>
    <main>
        <h1>Edit Character</h1>

        <!-- Flash message -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Select character form -->
        <div class="form-container">
            <form method="POST" action="edit_character.php">
                <label for="character_id">Select Character:</label>
                <select name="character_id" id="character_id" required>
                    <option value="">-- Select a Character --</option>
                    <?php foreach ($characters as $char): ?>
                        <option value="<?= htmlspecialchars($char['id']); ?>"
                            <?= isset($character) && $character['id'] == $char['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($char['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Load Character</button>
            </form>
        </div>

        <!-- Edit character form -->
        <?php if ($character): ?>
            <div class="form-container">
                <form id="updateCharacterForm" method="POST" action="../src/Handler/CharacterHandler.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="updateCharacter">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($character['id']); ?>">
                    <input type="hidden" name="campaign_id" value="<?= htmlspecialchars($character['campaign_id']); ?>">

                    <label for="name">Character Name:</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($character['name']); ?>" required>

                    <label for="race">Race:</label>
                    <select id="race" name="race" required>
                        <option value="">-- Select Race --</option>
                        <option value="Human" <?= $character['race'] === 'Human' ? 'selected' : ''; ?>>Human</option>
                        <option value="Elf" <?= $character['race'] === 'Elf' ? 'selected' : ''; ?>>Elf</option>
                        <option value="Dwarf" <?= $character['race'] === 'Dwarf' ? 'selected' : ''; ?>>Dwarf</option>
                        <option value="Halfling" <?= $character['race'] === 'Halfling' ? 'selected' : ''; ?>>Halfling</option>
                        <option value="Gnome" <?= $character['race'] === 'Gnome' ? 'selected' : ''; ?>>Gnome</option>
                        <option value="Tiefling" <?= $character['race'] === 'Tiefling' ? 'selected' : ''; ?>>Tiefling</option>
                        <option value="Half-Orc" <?= $character['race'] === 'Half-Orc' ? 'selected' : ''; ?>>Half-Orc</option>
                        <option value="Dragonborn" <?= $character['race'] === 'Dragonborn' ? 'selected' : ''; ?>>Dragonborn</option>
                        <option value="Other" <?= !in_array($character['race'], ['Human', 'Elf', 'Dwarf', 'Halfling', 'Gnome', 'Tiefling', 'Half-Orc', 'Dragonborn']) ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <input type="text" id="custom_race" name="custom_race" placeholder="Enter custom race" style="display: <?= $character['race'] === 'Other' ? 'block' : 'none'; ?>;" value="<?= htmlspecialchars(!in_array($character['race'], ['Human', 'Elf', 'Dwarf', 'Halfling', 'Gnome', 'Tiefling', 'Half-Orc', 'Dragonborn']) ? $character['race'] : ''); ?>">

                    <label for="class">Class:</label>
                    <select id="class" name="class" required>
                        <option value="">-- Select Class --</option>
                        <option value="Fighter" <?= $character['class'] === 'Fighter' ? 'selected' : ''; ?>>Fighter</option>
                        <option value="Wizard" <?= $character['class'] === 'Wizard' ? 'selected' : ''; ?>>Wizard</option>
                        <option value="Rogue" <?= $character['class'] === 'Rogue' ? 'selected' : ''; ?>>Rogue</option>
                        <option value="Cleric" <?= $character['class'] === 'Cleric' ? 'selected' : ''; ?>>Cleric</option>
                        <option value="Barbarian" <?= $character['class'] === 'Barbarian' ? 'selected' : ''; ?>>Barbarian</option>
                        <option value="Paladin" <?= $character['class'] === 'Paladin' ? 'selected' : ''; ?>>Paladin</option>
                        <option value="Ranger" <?= $character['class'] === 'Ranger' ? 'selected' : ''; ?>>Ranger</option>
                        <option value="Sorcerer" <?= $character['class'] === 'Sorcerer' ? 'selected' : ''; ?>>Sorcerer</option>
                        <option value="Warlock" <?= $character['class'] === 'Warlock' ? 'selected' : ''; ?>>Warlock</option>
                        <option value="Bard" <?= $character['class'] === 'Bard' ? 'selected' : ''; ?>>Bard</option>
                        <option value="Monk" <?= $character['class'] === 'Monk' ? 'selected' : ''; ?>>Monk</option>
                        <option value="Druid" <?= $character['class'] === 'Druid' ? 'selected' : ''; ?>>Druid</option>
                        <option value="Other" <?= !in_array($character['class'], ['Fighter', 'Wizard', 'Rogue', 'Cleric', 'Barbarian', 'Paladin', 'Ranger', 'Sorcerer', 'Warlock', 'Bard', 'Monk', 'Druid']) ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <input type="text" id="custom_class" name="custom_class" placeholder="Enter custom class" style="display: <?= $character['class'] === 'Other' ? 'block' : 'none'; ?>;" value="<?= htmlspecialchars(!in_array($character['class'], ['Fighter', 'Wizard', 'Rogue', 'Cleric', 'Barbarian', 'Paladin', 'Ranger', 'Sorcerer', 'Warlock', 'Bard', 'Monk', 'Druid']) ? $character['class'] : ''); ?>">

                    <label for="level">Level:</label>
                    <input type="number" name="level" id="level" min="1" value="<?= htmlspecialchars($character['level']); ?>" required>

                    <label for="experience">Experience:</label>
                    <input type="number" name="experience" id="experience" min="0" value="<?= htmlspecialchars($character['experience']); ?>" required>

                    <label for="health">Health:</label>
                    <input type="number" name="health" id="health" min="1" value="<?= htmlspecialchars($character['health']); ?>" required>

                    <label for="image">Character Image (Optional):</label>
                    <label for="image" class="file-label">Select Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <span class="file-name" id="file-name">No file selected</span>

                    <button type="submit">Update Character</button>
                </form>
            </div>
        <?php endif; ?>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>