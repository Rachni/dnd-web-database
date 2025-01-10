<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../'));
require_once realpath(__DIR__ . '/../Model/CharacterModel.php');

class CharacterService
{
    private $characterModel;

    public function __construct()
    {
        $this->characterModel = new CharacterModel();
    }

    // Get a character by ID
    public function getCharacterById($characterId)
    {
        try {
            $character = $this->characterModel->getCharacter($characterId);
            if (!$character) {
                throw new Exception("Character not found.");
            }
            return $character;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error fetching character: " . $e->getMessage());
        }
    }

    // Create a new character
    public function createCharacter($userId, $campaignId, $name, $race, $class, $level = 1, $experience = 0, $health = 100, $imagePath = null)
    {
        try {
            // Validar nombre
            if (empty($name)) {
                throw new Exception("Name is required.");
            }

            // Validar raza
            if (empty($race)) {
                throw new Exception("Race is required.");
            }

            // Validar clase
            if (empty($class)) {
                throw new Exception("Class is required.");
            }

            // Llamar al modelo para crear el personaje
            return $this->characterModel->addCharacter(
                $name,
                $race,
                $class,
                $level,
                $experience,
                $health,
                $imagePath,
                $userId,
                $campaignId
            );
        } catch (Exception $e) {
            error_log("Error creating character: " . $e->getMessage());
            throw new Exception("Error creating character: " . $e->getMessage());
        }
    }


    // Update an existing character
    public function updateCharacter($characterId, $attributes)
    {
        error_log("Updating character ID $characterId with attributes: " . print_r($attributes, true));
        try {
            return $this->characterModel->updateCharacter($characterId, $attributes);
        } catch (Exception $e) {
            error_log("Error in service layer: " . $e->getMessage());
            throw new Exception("Error updating character.");
        }
    }

    // Delete a character by ID
    public function deleteCharacter($characterId)
    {
        try {
            $deleted = $this->characterModel->deleteCharacter($characterId);
            if (!$deleted) {
                throw new Exception("Failed to delete character.");
            }
            return $deleted;
        } catch (Exception $e) {
            error_log("Error deleting character: " . $e->getMessage());
            throw new Exception("Error deleting character: " . $e->getMessage());
        }
    }

    // Get all characters for a campaign
    public function getCharactersByCampaign($campaignId)
    {
        try {
            return $this->characterModel->getCharactersByCampaign($campaignId);
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error fetching characters: " . $e->getMessage());
        }
    }
    public function getCharactersByUser($userId)
    {
        if (empty($userId)) {
            throw new Exception("User ID is required.");
        }

        try {
            // Asegúrate de que el método de modelo está correctamente configurado
            $characters = $this->characterModel->fetchCharactersByUserWithCampaign($userId);

            // Log para depuración
            error_log("Fetched characters for user ID {$userId}: " . print_r($characters, true));

            return $characters;
        } catch (Exception $e) {
            error_log("Error fetching characters for user: " . $e->getMessage());
            throw new Exception("Unable to retrieve characters for the user.");
        }
    }



    public function getCampaignsByUser($userId)
    {
        try {
            return $this->characterModel->fetchCampaignsByUser($userId);
        } catch (Exception $e) {
            error_log("Error fetching campaigns for user: " . $e->getMessage());
            throw new Exception("Unable to retrieve campaigns for the user.");
        }
    }
    public function validateForeignKeys($userId, $campaignId)
    {
        try {
            $this->characterModel->validateForeignKeys($userId, $campaignId);
        } catch (Exception $e) {
            throw new Exception("Foreign key validation failed: " . $e->getMessage());
        }
    }
    public function isCharacterOwnedByUser($characterId, $userId)
    {
        try {
            return $this->characterModel->isCharacterOwnedByUser($characterId, $userId);
        } catch (Exception $e) {
            error_log("Error checking character ownership: " . $e->getMessage());
            throw new Exception("Unable to verify character ownership.");
        }
    }
}
