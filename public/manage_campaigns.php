<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Campaigns</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/header_styles.css">
</head>
<body>
<?php include '../src/includes/header.php'; ?>
<main>
    <h1>Manage Campaigns</h1>
    <ul class="management-list">
        <li class="management-item"><a href="./create_campaign.php">Create Campaign</a></li>
        <li class="management-item"><a href="./view_campaigns.php">View Campaigns</a></li>
        <li class="management-item"><a href="./edit_campaign.php">Update Campaign</a></li>
        <li class="management-item"><a href="./delete_campaign.php">Delete Campaign</a></li>
    </ul>
</main>
<?php include '../src/includes/footer.php'; ?>
</body>
</html>
