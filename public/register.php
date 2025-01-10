<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/header_styles.css">
    <link rel="stylesheet" href="./css/login_register_styles.css">
</head>

<body>
    <?php require_once '../src/includes/header.php'; ?>
    <!-- Video Background -->
    <video autoplay muted loop id="bg-video">
        <source src="./assets/Videos/Video fondo taberna.mp4" type="video/mp4">
        Your browser does not support the video format.
    </video>

    <!-- Audio Background -->
    <audio id="background-music" loop>
        <source src="./assets/Musica/Now We Ride.mp3" type="audio/mp3">
        Your browser does not support the audio format.
    </audio>

    <audio id="background-music-2" loop>
        <source src="./assets/Musica/Wintersong.mp3" type="audio/mp3">
        Your browser does not support the audio format.
    </audio>

    <!-- Audio Controls -->
    <div class="audio-controls">
        <button id="play-pause-button">Play</button>
        <button id="mute-button">Mute</button>
        <button id="next-button">Next</button>
        <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1">
    </div>

    <!-- Register Form -->
    <div class="form-container">
        <button id="toggle-btn">
            <img src="./assets/Iconos/pngegg.png" alt="D20 Icon">
        </button>

        <div class="form-card">
            <!-- Front View: Register -->
            <div class="form-card-front">
                <h2>Register</h2>
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="flash-message">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>
                <form action="../src/Handler/UserHandler.php" method="POST">
                    <input type="hidden" name="action" value="registerUser">
                    <input type="text" name="username" placeholder="Username" class="input-field" required>
                    <input type="email" name="email" placeholder="Email" class="input-field" required>
                    <input type="password" name="password" placeholder="Password" class="input-field" required>
                    <button type="submit" class="submit-btn">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/login_register_script.js"></script>
</body>

</html>