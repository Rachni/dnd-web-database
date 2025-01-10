<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../'));
require_once realpath(__DIR__ . '/../Service/SpellService.php');

class SpellController
{
    private $spellService;

    public function __construct()
    {
        $this->spellService = new SpellService();
    }

    // Add a new spell
    public function addSpell($data)
    {
        try {
            $this->spellService->addSpell($data['name'], $data['description'], $data['level'], $data['type']);
            return ["success" => true, "message" => "Spell added successfully!"];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Update an existing spell
    public function updateSpell($id, $data)
    {
        try {
            $this->spellService->updateSpell($id, $data);
            return ["success" => true, "message" => "Spell updated successfully!"];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Delete a spell
    public function deleteSpell($id)
    {
        try {
            $this->spellService->deleteSpell($id);
            return ["success" => true, "message" => "Spell deleted successfully!"];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Get a single spell by ID
    public function getSpell($id)
    {
        try {
            $spell = $this->spellService->getSpell($id);
            return ["success" => true, "data" => $spell];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function getTotalSpells()
    {
        try {
            $totalSpells = $this->spellService->getTotalSpells();
            return ["success" => true, "data" => $totalSpells];
        } catch (Exception $e) {
            error_log("Error fetching total spells: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    


    public function getAllSpellNames($limit = 10, $offset = 0)
    {
        try {
            $spells = $this->spellService->getAllSpellNames($limit, $offset);
            return ["success" => true, "data" => $spells];
        } catch (Exception $e) {
            error_log("Error in getAllSpellNames: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    public function countSpells()
    {
        try {
            return $this->spellService->countAllSpells();
        } catch (Exception $e) {
            error_log("Error in countSpells: " . $e->getMessage());
            return 0; // Return 0 if there's an error
        }
    }
}
