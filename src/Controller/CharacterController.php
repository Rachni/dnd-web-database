<?php
require_once realpath(__DIR__ . '/../Service/CharacterService.php');

class CharacterController
{
    private $characterService;

    public function __construct()
    {
        $this->characterService = new CharacterService();
    }

    // Create a new character
    public function createCharacter($data)
    {
        try {
            // Validar campos obligatorios
            if (empty($data['name'])) {
                throw new Exception("Character name is required.");
            }

            if (empty($data['race'])) {
                throw new Exception("Race is required.");
            }

            if (empty($data['class'])) {
                throw new Exception("Class is required.");
            }

            // Llamar al servicio para crear el personaje
            return $this->characterService->createCharacter(
                $_SESSION['user_id'],
                $data['campaign_id'],
                $data['name'],
                $data['race'], // Manejo de raza personalizada
                $data['class'], // Manejo de clase personalizada
                $data['level'] ?? 1,
                $data['experience'] ?? 0,
                $data['health'] ?? 100,
                $data['image_path'] ?? null
            );
        } catch (Exception $e) {
            error_log("Error creating character: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "Error creating character: " . $e->getMessage()
            ];
        }
    }



    // Update character
    public function updateCharacter($data)
    {
        error_log("Updating character with data: " . print_r($data, true));
        try {
            if (empty($data['id']) || !is_numeric($data['id'])) {
                throw new Exception("Invalid character ID.");
            }

            if (empty($data['campaign_id']) || !is_numeric($data['campaign_id'])) {
                throw new Exception("Invalid campaign ID.");
            }

            return $this->characterService->updateCharacter($data['id'], $data);
        } catch (Exception $e) {
            error_log("Error updating character: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "Error updating character: " . $e->getMessage()
            ];
        }
    }




    // Delete character
    public function deleteCharacter($characterId)
    {
        try {
            // Verificar que el personaje pertenece al usuario logueado
            if (!$this->characterService->isCharacterOwnedByUser($characterId, $_SESSION['user_id'])) {
                throw new Exception("You do not have permission to delete this character.");
            }

            // Eliminar el personaje
            $this->characterService->deleteCharacter($characterId);
            return [
                "success" => true,
                "message" => "Character deleted successfully."
            ];
        } catch (Exception $e) {
            error_log("Error deleting character: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "Error deleting character: " . $e->getMessage()
            ];
        }
    }

    // Get a character by ID
    public function getCharacter($id)
    {
        try {
            $this->validateCharacterId($id);

            $character = $this->characterService->getCharacterById($id);
            return ["success" => true, "data" => $character];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Error fetching character: " . $e->getMessage()];
        }
    }

    // Get characters by campaign
    public function getCharactersByCampaign($campaignId)
    {
        try {
            $this->validateCampaignId($campaignId);

            $characters = $this->characterService->getCharactersByCampaign($campaignId);
            return ["success" => true, "data" => $characters];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Error fetching characters for campaign: " . $e->getMessage()];
        }
    }

    // Upload Character Image
    public function uploadCharacterImage($characterId, $file)
    {
        try {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception("Invalid file type. Only JPG and PNG are allowed.");
            }

            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                throw new Exception("File size exceeds the limit of 2MB.");
            }

            // Generate a sanitized filename
            $fileName = uniqid() . "_" . basename($file['name']);
            $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $fileName);

            $uploadPath = __DIR__ . "/uploads/characters/" . $fileName;

            // Move uploaded file to the server
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Update the character's image path in the database
                $this->characterService->updateCharacter($characterId, ['image_path' => $fileName]);
                return ["success" => true, "message" => "Image uploaded successfully."];
            } else {
                throw new Exception("Failed to upload image.");
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => "Error uploading image: " . $e->getMessage()];
        }
    }

    // Private helper methods
    private function validateCharacterData($data)
    {
        if (empty($data['campaign_id']) || empty($data['name']) || empty($data['race']) || empty($data['class'])) {
            throw new Exception("Missing required character fields.");
        }
    }

    private function validateCharacterId($id)
    {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid character ID.");
        }
    }

    private function validateCampaignId($campaignId)
    {
        if (empty($campaignId) || !is_numeric($campaignId)) {
            throw new Exception("Invalid campaign ID.");
        }
    }
    public function getCharactersByUser($userId)
    {
        try {
            $characters = $this->characterService->getCharactersByUser($userId);
            if (empty($characters)) {
                throw new Exception("No characters found for this user.");
            }
            return $characters;
        } catch (Exception $e) {
            error_log("Error fetching characters for user: " . $e->getMessage());
            return []; // Devuelve un array vacío en caso de error
        }
    }
    public function getCampaignsForCurrentUser()
    {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("User is not logged in.");
        }

        $userId = $_SESSION['user_id'];

        try {
            // Obtén las campañas asociadas al usuario
            return $this->characterService->getCampaignsByUser($userId);
        } catch (Exception $e) {
            error_log("Error fetching campaigns: " . $e->getMessage());
            return [];
        }
    }
}
