<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../'));
require_once realpath(__DIR__ . '/../Model/SpellModel.php');

class SpellService
{
    private $spellModel;

    public function __construct()
    {
        $this->spellModel = new SpellModel();
    }

    // Helper method to validate spell data
    private function validateSpellData($name, $description, $level, $type)
    {
        // Validation for name
        if (empty($name) || strlen($name) > 255) {
            throw new Exception("Invalid spell name.");
        }

        // Validation for description
        if (empty($description)) {
            throw new Exception("Invalid spell description.");
        }

        // Validation for level
        if (!is_numeric($level) || $level < 1 || $level > 20) {
            throw new Exception("Invalid spell level. Must be between 1 and 20.");
        }

        // Validation for type
        if (empty($type) || strlen($type) > 255) {
            throw new Exception("Invalid spell type.");
        }
    }

    // Add a new spell
    public function addSpell($name, $description, $level, $type)
    {
        // Validate spell data
        $this->validateSpellData($name, $description, $level, $type);

        // Add the spell via the model
        return $this->spellModel->addSpell($name, $description, $level, $type);
    }

    // Get a specific spell by its ID
    public function getSpell($id)
    {
        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid spell ID.");
        }

        // Fetch spell from the model
        return $this->spellModel->getSpell($id);
    }

    // Update an existing spell
    public function updateSpell($id, $fields)
    {
        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid spell ID.");
        }

        // Ensure fields are not empty
        if (empty($fields)) {
            throw new Exception("No fields to update.");
        }

        // Update the spell via the model
        $this->spellModel->updateSpell($id, $fields);
    }

    // Delete a spell by its ID
    public function deleteSpell($id)
    {
        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid spell ID.");
        }

        // Delete the spell via the model
        return $this->spellModel->deleteSpell($id);
    }

    // Get all spell names
    public function getAllSpellNames($limit = 10, $offset = 0)
    {
        try {
            return $this->spellModel->getAllSpells($limit, $offset);
        } catch (Exception $e) {
            error_log("Error fetching spells: " . $e->getMessage());
            throw new Exception("Unable to fetch spell names.");
        }
    }
    public function getTotalSpells()
    {
        try {
            return $this->spellModel->countAllSpells();
        } catch (Exception $e) {
            error_log("Error fetching total spells: " . $e->getMessage());
            throw new Exception("Unable to fetch total spells.");
        }
    }
    

    public function countAllSpells()
    {
        try {
            return $this->spellModel->countAllSpells();
        } catch (Exception $e) {
            error_log("Error counting spells: " . $e->getMessage());
            throw new Exception("Unable to count spells.");
        }
    }
}
