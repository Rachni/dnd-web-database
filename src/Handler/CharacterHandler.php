<?php
error_log("POST data: " . print_r($_POST, true)); // Para depuración

require_once realpath(__DIR__ . '/../Controller/CharacterController.php');
require_once realpath(__DIR__ . '/../includes/auth_helper.php');
require_once realpath(__DIR__ . '/../Controller/CharacterSpellController.php');

checkAuthentication();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $characterController = new CharacterController();
    $action = $_POST['action'];

    try {
        $response = null;

        if ($action === 'createCharacter') {
            // Recolectar datos del formulario
            $data = [
                'name' => $_POST['name'] ?? '',
                'race' => $_POST['race'] ?? '',
                'class' => $_POST['class'] ?? '',
                'level' => $_POST['level'] ?? 1,
                'experience' => $_POST['experience'] ?? 0,
                'health' => $_POST['health'] ?? 100,
                'campaign_id' => $_POST['campaign_id'] ?? '',
                'image_path' => null // Inicializar en null
            ];

            // Manejar subida de imagen
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = realpath(__DIR__ . '/../../uploads/characters/') . '/';
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $uploadFile = $uploadDir . $fileName;

                // Validar tipo de archivo
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    throw new Exception("Invalid file type. Only JPG, PNG, or GIF are allowed.");
                }

                // Validar tamaño del archivo
                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    throw new Exception("File size exceeds the 2MB limit.");
                }

                // Intentar mover el archivo
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $data['image_path'] = $fileName;
                    error_log("Image uploaded successfully: " . $fileName);
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            }

            // Llamar al controlador para crear el personaje
            $response = $characterController->createCharacter($data);
        } elseif ($action === 'updateCharacter') {
            // Recolectar datos
            $data = $_POST;
            $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (!$data['id']) {
                throw new Exception("Invalid character ID.");
            }

            // Manejar subida de imagen (si se proporciona)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = realpath(__DIR__ . '/../../uploads/characters/') . '/';
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $uploadFile = $uploadDir . $fileName;

                // Validar tipo de archivo
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    throw new Exception("Invalid file type. Only JPG, PNG, or GIF are allowed.");
                }

                // Validar tamaño del archivo
                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    throw new Exception("File size exceeds the 2MB limit.");
                }

                // Mover archivo
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $data['image_path'] = $fileName;
                    error_log("Image uploaded successfully for update: " . $fileName);
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            }

            // Llamar al controlador para actualizar
            $response = $characterController->updateCharacter($data);
        } elseif ($action === 'deleteCharacter') {
            $characterId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (!$characterId) {
                throw new Exception("Invalid character ID.");
            }

            $response = $characterController->deleteCharacter($characterId);
        }

        $_SESSION['flash_message'] = $response['message'] ?? "Action completed successfully.";
    } catch (Exception $e) {
        error_log("Error processing action: " . $e->getMessage());
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
