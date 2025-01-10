<?php
require_once realpath(__DIR__ . '/../Controller/UserController.php');

session_start();

// POST Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $userController = new UserController();
    $action = $_POST['action'];

    try {
        if ($action === 'registerUser') {
            // Manejo de registro de usuario
            $postData = array_map(fn($value) => htmlspecialchars(trim($value)), $_POST);
            $response = $userController->registerUser($postData);
            $_SESSION['flash_message'] = $response['message'];
            header("Location: ../../public/register.php");
        } elseif ($action === 'loginUser') {
            // Manejo de inicio de sesión
            $postData = array_map(fn($value) => htmlspecialchars(trim($value)), $_POST);
            $response = $userController->loginUser($postData);
            if ($response['success']) {
                $_SESSION['username'] = $response['data']['username'];
                $_SESSION['user_id'] = $response['data']['id'];
                $_SESSION['flash_message'] = $response['message'];
                header("Location: ../../public/index.php");
            } else {
                $_SESSION['flash_message'] = $response['error'];
                header("Location: ../../public/login.php");
            }
        } elseif ($action === 'logout') {
            // Manejo de cierre de sesión
            session_destroy();
            header("Location: ../../public/login.php");
        } else {
            throw new Exception("Invalid action.");
        }
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
        $redirectPage = ($action === 'registerUser') ? "register.php" : "login.php";
        header("Location: ../../public/" . $redirectPage);
    }
    exit;
}

// Fallback for Invalid Requests
http_response_code(400);
echo "Invalid request.";
