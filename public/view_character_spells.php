<?php
require_once '../src/includes/auth_helper.php';
require_once '../src/Controller/CharacterSpellController.php';

checkAuthentication();
$characterSpellController = new CharacterSpellController();

// Validar si se proporciona el ID del personaje
if (!isset($_GET['character_id']) || !is_numeric($_GET['character_id'])) {
    die("Invalid character ID.");
}

$characterId = (int)$_GET['character_id'];
$characterName = htmlspecialchars($_GET['character_name'] ?? 'Unknown');

// Procesar desvinculación del hechizo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['spell_id'])) {
    $spellId = (int)$_POST['spell_id'];
    try {
        $characterSpellController->removeSpellFromCharacter([
            'character_id' => $characterId,
            'spell_id' => $spellId,
        ]);
        $_SESSION['flash_message'] = "Spell successfully unlinked from the character.";
        header("Location: view_character_spells.php?character_id=$characterId&character_name=" . urlencode($characterName));
        exit;
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
    }
}

// Obtener los hechizos del personaje
try {
    $spellsData = $characterSpellController->getSpellsByCharacter(['character_id' => $characterId]);
    $spells = $spellsData['data'] ?? [];
} catch (Exception $e) {
    die("Error fetching spells for character: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Character Spells</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/table_styles.css"> <!-- Link table styles -->
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Spells for <?= $characterName ?></h1>

        <!-- Botón para agregar hechizos -->
        <div class="add-spells-button">
            <a href="add_spells_to_character.php?character_id=<?= htmlspecialchars($characterId) ?>&character_name=<?= urlencode($characterName) ?>">Add Spells to <?= htmlspecialchars($characterName) ?></a>
        </div>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($spells)): ?>
            <p>This character has no spells assigned.</p>
        <?php else: ?>
            <div class="table-container"> <!-- Added table container -->
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Level</th>
                            <th>Type</th>
                            <th>Casting Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($spells as $spell): ?>
                            <tr>
                                <td><?= htmlspecialchars($spell['spell_name'] ?? 'Unknown'); ?></td>
                                <td><?= htmlspecialchars($spell['description'] ?? 'No description'); ?></td>
                                <td><?= htmlspecialchars($spell['spell_level'] ?? 'Unknown'); ?></td>
                                <td><?= htmlspecialchars($spell['type'] ?? 'Unknown'); ?></td>
                                <td><?= htmlspecialchars($spell['casting_level'] ?? 'Default'); ?></td>
                                <td>
                                    <form method="POST" action="view_character_spells.php?character_id=<?= htmlspecialchars($characterId) ?>&character_name=<?= urlencode($characterName) ?>" style="display:inline;">
                                        <input type="hidden" name="spell_id" value="<?= htmlspecialchars($spell['spell_id']); ?>">
                                        <button type="submit" class="unlink-button">Unlink</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
