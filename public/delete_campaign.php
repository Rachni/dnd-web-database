<?php
require_once '../src/includes/auth_helper.php';
checkAuthentication();
require_once '../src/includes/header.php';
require_once '../src/Controller/UserCampaignController.php';
require_once '../src/Controller/CampaignController.php';

$userCampaignController = new UserCampaignController();
$campaignController = new CampaignController();

// Obtener las campañas del usuario logueado
$campaigns = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $campaigns = $userCampaignController->getCampaignsByUser(['user_id' => $userId])['data'];
}

// Si se envió el formulario de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaign_id'])) {
    $campaignId = $_POST['campaign_id'];

    try {
        $campaignController->deleteCampaign($campaignId);
        $_SESSION['flash_message'] = "Campaign deleted successfully.";
        header("Location: view_campaigns.php"); // Redirigir después de eliminar
        exit;
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error al eliminar la campaña: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Campaign</title>
    <link rel="stylesheet" href="./css/styles.css">\
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css"> 
</head>

<body>
    <div class="container">
        <h1>Delete Campaign</h1>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para seleccionar y eliminar campaña -->
        <div class="form-container">
            <form method="POST" action="">
                <label for="campaign_id">Select a campaign to delete:</label>
                <select name="campaign_id" id="campaign_id" required>
                    <option value="">-- Select a campaign --</option>
                    <?php foreach ($campaigns as $campaign): ?>
                        <option value="<?= htmlspecialchars($campaign['id']); ?>">
                            <?= htmlspecialchars($campaign['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="delete-button" onclick="return confirm('¿Estás seguro de eliminar esta campaña?');">Delete campaign</button>
            </form>
        </div>
    </div>
</body>

</html>
