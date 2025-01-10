<?php
require_once realpath(__DIR__ . '/../Controller/CampaignController.php');
require_once realpath(__DIR__ . '/../Controller/UserCampaignController.php');

// Habilitar el manejo de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Iniciar la sesión para manejar mensajes

// Verificar que se trate de una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $campaignController = new CampaignController();
    $userCampaignController = new UserCampaignController();
    $action = $_POST['action'];

    try {
        // Llamar a los métodos correspondientes según el "action"
        if ($action === 'createCampaign') {
            // Recopilando los datos del formulario
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'status' => $_POST['status'] ?? '',
            ];

            // Crear campaña
            $response = $campaignController->createCampaign($data);

            // Verificar si la campaña fue creada correctamente
            if ($response['success']) {
                $_SESSION['flash_message'] = $response['message'] ?? "Campaign created successfully.";
            } else {
                $_SESSION['flash_message'] = $response['message'] ?? "Error occurred during campaign creation.";
            }
        } else {
            throw new Exception("Invalid action.");
        }
    } catch (Exception $e) {
        // Manejo de excepciones
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
    }

    // Redirigir de vuelta a la página anterior
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    // Si la solicitud no es válida
    $_SESSION['flash_message'] = "Invalid request.";
    header('Location: ../public/create_campaign.php'); // Ruta al formulario
    exit;
}
