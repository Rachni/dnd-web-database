<?php
session_start();
require_once realpath(__DIR__ . '/../src/Controller/UserCampaignController.php');

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = "You need to log in to view campaigns.";
    header("Location: login.php");
    exit;
}

// Obtén el user_id del usuario logueado
$user_id = $_SESSION['user_id'];

// Crea una instancia del controlador
$userCampaignController = new UserCampaignController();

// Obtén las campañas para este usuario
try {
    $response = $userCampaignController->getCampaignsByUser(['user_id' => $user_id]);
    $campaigns = $response['success'] ? $response['data'] : [];
} catch (Exception $e) {
    $campaigns = [];
    $_SESSION['flash_message'] = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Campaigns</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/table_styles.css"> 
</head>

<body>
    <?php include '../src/includes/header.php'; ?>
    <main>
        <h1>Your Campaigns</h1>
        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar campañas -->
        <?php if (!empty($campaigns)): ?>
            <div class="table-container"> <!-- Added table container -->
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campaigns as $campaign): ?>
                            <tr>
                                <td><?= htmlspecialchars($campaign['name']); ?></td>
                                <td><?= htmlspecialchars($campaign['description']); ?></td>
                                <td><?= htmlspecialchars($campaign['status']); ?></td>
                                <td>
                                    <a href="edit_campaign.php?id=<?= $campaign['id']; ?>">Edit</a>
                                    <a href="delete_campaign.php?id=<?= $campaign['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>You are not part of any campaigns.</p>
        <?php endif; ?>
    </main>
    <?php include '../src/includes/footer.php'; ?>
</body>

</html>
