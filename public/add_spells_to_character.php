<?php
require_once realpath(__DIR__ . '/../src/includes/auth_helper.php');
require_once realpath(__DIR__ . '/../src/includes/header.php');
require_once realpath(__DIR__ . '/../src/Controller/SpellController.php');
require_once realpath(__DIR__ . '/../src/Controller/CharacterSpellController.php');

checkAuthentication(); // Ensure the user is logged in

// Validate the character_id and character_name from the GET request
if (!isset($_GET['character_id']) || !isset($_GET['character_name'])) {
    $_SESSION['flash_message'] = "Character not specified. Please select a character.";
    header("Location: view_characters.php");
    exit;
}

$characterId = (int)$_GET['character_id'];
$characterName = htmlspecialchars($_GET['character_name']);

// Instantiate necessary controllers
$spellController = new SpellController();
$characterSpellController = new CharacterSpellController();

// Fetch all spells
$spellsData = $spellController->getAllSpellNames(100); // Fetch up to 100 spells
$spells = $spellsData['data'] ?? [];

// Handle form submission to add a spell to a character
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['spell_id'])) {
    $spellId = (int)$_POST['spell_id'];
    $castingLevel = (int)$_POST['casting_level'];

    try {
        if ($spellId > 0 && $castingLevel > 0) {
            $characterSpellController->addSpellToCharacter([
                'character_id' => $characterId,
                'spell_id' => $spellId,
                'casting_level' => $castingLevel,
            ]);

            $_SESSION['flash_message'] = "Spell successfully added to $characterName.";
            header("Location: view_character_spells.php?character_id=$characterId&character_name=" . urlencode($characterName));
            exit;
        } else {
            throw new Exception("Invalid spell or casting level.");
        }
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
    <title>Add Spells to <?= $characterName ?></title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css">
</head>

<body>
    <main>
        <h1>Add Spells to <?= htmlspecialchars($characterName) ?></h1>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="form-container">
            <form method="POST" action="add_spells_to_character.php?character_id=<?= $characterId ?>&character_name=<?= urlencode($characterName) ?>">
                <label for="spell_id">Select Spell:</label>
                <select name="spell_id" id="spell_id" required>
                    <option value="">-- Select a Spell --</option>
                    <?php foreach ($spells as $spell): ?>
                        <option value="<?= htmlspecialchars($spell['id']); ?>"><?= htmlspecialchars($spell['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="casting_level">Casting Level:</label>
                <input type="number" name="casting_level" id="casting_level" min="1" max="20" value="1" required>

                <button type="submit">Add Spell</button>
            </form>
        </div>
    </main>
    <?php include realpath(__DIR__ . '/../src/includes/footer.php'); ?>
</body>

</html>
