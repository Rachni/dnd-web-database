<?php
require_once realpath(__DIR__ . '/../src/includes/auth_helper.php');
require_once realpath(__DIR__ . '/../src/Controller/SpellController.php');

checkAuthentication();

$spellController = new SpellController();

// Fetch all spells with pagination
$limit = 10;
$offset = isset($_GET['page']) ? ($_GET['page'] - 1) * $limit : 0;

try {
    $spellsData = $spellController->getAllSpellNames($limit, $offset);
    $spells = $spellsData['data'] ?? [];
    $totalSpells = $spellController->countSpells();
    $totalPages = ceil($totalSpells / $limit);
} catch (Exception $e) {
    $spells = [];
    $totalPages = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Spells</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/table_styles.css">
</head>

<body>
    <?php include realpath(__DIR__ . '/../src/includes/header.php'); ?>
    <main>
        <h1>View Spells</h1>

        <?php if (empty($spells)): ?>
            <p>No spells found.</p>
        <?php else: ?>
            <div class="table-container"> <!-- Wrap table in container -->
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Level</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($spells as $spell): ?>
                            <tr>
                                <td><?= htmlspecialchars($spell['name']); ?></td>
                                <td><?= htmlspecialchars($spell['description']); ?></td>
                                <td><?= htmlspecialchars($spell['level']); ?></td>
                                <td><?= htmlspecialchars($spell['type']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i; ?>" class="<?= isset($_GET['page']) && $_GET['page'] == $i ? 'active' : ''; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>
    <?php include realpath(__DIR__ . '/../src/includes/footer.php'); ?>
</body>

</html>
