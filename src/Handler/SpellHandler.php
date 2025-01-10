<?php
require_once realpath(__DIR__ . '/../Controller/SpellController.php');
require_once realpath(__DIR__ . '/../includes/auth_helper.php');

checkAuthentication(); // Ensure the user is authenticated

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $spellController = new SpellController();
    $action = $_POST['action'];

    try {
        $response = null;

        if ($action === 'createSpell') {
            // Create a new spell
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'level' => (int)$_POST['level'],
                'type' => trim($_POST['type'])
            ];
            $response = $spellController->addSpell($data);
        } elseif ($action === 'updateSpell') {
            // Update an existing spell
            if (empty($_POST['id'])) {
                throw new Exception("Spell ID is required for updating.");
            }

            $id = (int)$_POST['id'];
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'level' => (int)$_POST['level'],
                'type' => trim($_POST['type'])
            ];
            $response = $spellController->updateSpell($id, $data);
        } elseif ($action === 'deleteSpell') {
            // Delete a spell
            if (empty($_POST['id'])) {
                throw new Exception("Spell ID is required for deletion.");
            }

            $id = (int)$_POST['id'];
            $response = $spellController->deleteSpell($id);
        } else {
            throw new Exception("Invalid action specified.");
        }

        // Set a success message
        $_SESSION['flash_message'] = $response['message'] ?? "Action completed successfully.";
    } catch (Exception $e) {
        // Handle errors
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
    }

    // Redirect back to the previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
