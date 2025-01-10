<?php
require_once '../src/includes/auth_helper.php';
require_once '../src/Controller/SpellController.php';

checkAuthentication(); // Ensure the user is logged in

$spellController = new SpellController();

// Fetch all spells for the dropdown
$spellsData = $spellController->getAllSpellNames(100); // Fetch up to 100 spells for selection
$spells = $spellsData['data'] ?? [];

// Initialize spell details
$spellDetails = null;

// Handle spell selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['spell_id'])) {
    $spellId = (int)$_POST['spell_id'];
    $spellResponse = $spellController->getSpell($spellId);
    $spellDetails = $spellResponse['data'] ?? null;
}

// Handle spell update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_spell'])) {
    try {
        $updateData = [
            'id' => (int)$_POST['spell_id'],
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'level' => (int)$_POST['level'],
            'type' => trim($_POST['type']),
        ];
        $response = $spellController->updateSpell($updateData['id'], $updateData);

        $_SESSION['flash_message'] = $response['message'] ?? "Spell updated successfully.";
        header("Location: edit_spell.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Spell</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css"> 
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Edit Spell</h1>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Spell Selection Form -->
        <div class="form-container">
            <form method="POST" action="edit_spell.php">
                <label for="spell_id">Select Spell:</label>
                <select name="spell_id" id="spell_id" required>
                    <option value="">-- Select a Spell --</option>
                    <?php foreach ($spells as $spell): ?>
                        <option value="<?= htmlspecialchars($spell['id']); ?>"
                            <?= isset($spellDetails) && $spellDetails['id'] == $spell['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($spell['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Load Spell</button>
            </form>
        </div>

        <!-- Spell Editing Form -->
        <?php if ($spellDetails): ?>
            <div class="form-container">
                <form method="POST" action="edit_spell.php">
                    <input type="hidden" name="update_spell" value="1">
                    <input type="hidden" name="spell_id" value="<?= htmlspecialchars($spellDetails['id']); ?>">

                    <label for="name">Spell Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($spellDetails['name']); ?>" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($spellDetails['description']); ?></textarea>

                    <label for="level">Level:</label>
                    <input type="number" id="level" name="level" min="1" max="20" value="<?= htmlspecialchars($spellDetails['level']); ?>" required>

                    <label for="type">Type:</label>
                    <select id="type" name="type" required>
                        <option value="Abjuration" <?= $spellDetails['type'] === 'Abjuration' ? 'selected' : ''; ?>>Abjuration</option>
                        <option value="Conjuration" <?= $spellDetails['type'] === 'Conjuration' ? 'selected' : ''; ?>>Conjuration</option>
                        <option value="Divination" <?= $spellDetails['type'] === 'Divination' ? 'selected' : ''; ?>>Divination</option>
                        <option value="Enchantment" <?= $spellDetails['type'] === 'Enchantment' ? 'selected' : ''; ?>>Enchantment</option>
                        <option value="Evocation" <?= $spellDetails['type'] === 'Evocation' ? 'selected' : ''; ?>>Evocation</option>
                        <option value="Illusion" <?= $spellDetails['type'] === 'Illusion' ? 'selected' : ''; ?>>Illusion</option>
                        <option value="Necromancy" <?= $spellDetails['type'] === 'Necromancy' ? 'selected' : ''; ?>>Necromancy</option>
                        <option value="Transmutation" <?= $spellDetails['type'] === 'Transmutation' ? 'selected' : ''; ?>>Transmutation</option>
                    </select>

                    <button type="submit">Update Spell</button>
                </form>
            </div>
        <?php endif; ?>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
