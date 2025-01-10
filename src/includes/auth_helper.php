<?php
function initializeSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Iniciar la sesión si no está ya iniciada
    }
}

function checkAuthentication()
{
    initializeSession();
    if (!isset($_SESSION['user_id'])) {
        // Si el usuario no ha iniciado sesión, redirigir al login
        $_SESSION['flash_message'] = "You must log in to access this page.";
        header("Location: login.php");
        exit;
    }
}
