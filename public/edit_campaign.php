<?php
require_once '../src/includes/auth_helper.php';
checkAuthentication();
require_once '../src/includes/header.php';
require_once '../src/Controller/UserCampaignController.php';
require_once '../src/Controller/CampaignController.php';

$userCampaignController = new UserCampaignController();
$campaignController = new CampaignController();
$campaign = null;

// Obtener las campañas del usuario logueado
$campaigns = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $campaigns = $userCampaignController->getCampaignsByUser(['user_id' => $userId])['data'];
}

// Si el formulario de selección de campaña fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaign_id'])) {
    $campaignId = $_POST['campaign_id'];
    try {
        $campaign = $campaignController->getCampaignById($campaignId);
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
    }
}

// Si el formulario de actualización fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_campaign'])) {
    try {
        $campaignController->updateCampaign($_POST);
        $_SESSION['flash_message'] = "Campaña actualizada con éxito.";
        header("Location: view_campaigns.php");
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
    <title>Edit Campaign</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/form_styles.css"> 
</head>
<body>
    <div class="container">
        <h1>Edit Campaign</h1>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para seleccionar campaña -->
        <div class="form-container">
            <form method="POST" action="">
                <label for="campaign_id">Select the campaign:</label>
                <select name="campaign_id" id="campaign_id" required>
                    <option value="">-- Select a campaign --</option>
                    <?php foreach ($campaigns as $campaignOption): ?>
                        <option value="<?= htmlspecialchars($campaignOption['id']); ?>"
                            <?= isset($campaign) && $campaign['id'] === $campaignOption['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($campaignOption['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Load campaign</button>
            </form>
        </div>

        <!-- Formulario de edición -->
        <?php if (isset($campaign)): ?>
            <div class="form-container">
                <form method="POST" action="">
                    <input type="hidden" name="update_campaign" value="1">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($campaign['id']); ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($campaign['name']); ?>" required>
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" required><?= htmlspecialchars($campaign['description']); ?></textarea>
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" value="<?= $campaign['start_date']; ?>" required>
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" value="<?= $campaign['end_date']; ?>">
                    <label for="status">Status:</label>
                    <select name="status" id="status" required>
                        <option value="active" <?= $campaign['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="completed" <?= $campaign['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="paused" <?= $campaign['status'] === 'paused' ? 'selected' : ''; ?>>Paused</option>
                    </select>
                    <button type="submit">Update Campaign</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
