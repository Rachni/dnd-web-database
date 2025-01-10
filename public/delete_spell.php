<?php
require_once '../src/includes/auth_helper.php';
checkAuthentication();
require_once '../src/includes/header.php';
require_once '../src/Controller/SpellController.php';
require_once '../src/Controller/CampaignController.php';

checkAuthentication(); // Ensure the user is authenticated

$spellController = new SpellController();

// Fetch all spells for the dropdown
$spellsData = $spellController->getAllSpellNames(100);
$spells = $spellsData['data'] ?? [];

// Handle spell deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_spell'])) {
    try {
        $spellId = (int)$_POST['spell_id'];
        $response = $spellController->deleteSpell($spellId);

        $_SESSION['flash_message'] = $response['message'] ?? "Spell deleted successfully.";
        header("Location: delete_spell.php");
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
    <title>Delete Spell</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
</head>

<body>
    <main>
        <h1>Delete Spell</h1>

        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Form to select and delete a spell -->
        <div class="form-container">
            <form method="POST" action="delete_spell.php">
                <label for="spell_id">Select Spell to Delete:</label>
                <select name="spell_id" id="spell_id" required>
                    <option value="">-- Select a Spell --</option>
                    <?php foreach ($spells as $spell): ?>
                        <option value="<?= htmlspecialchars($spell['id']); ?>"><?= htmlspecialchars($spell['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="delete_spell" class="delete-button">Delete Spell</button>
            </form>
        </div>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>