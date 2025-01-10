<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../');
require_once realpath(__DIR__ . '/../Model/CharacterSpellModel.php');

class CharacterSpellService
{
    private $characterSpellModel;

    public function __construct()
    {
        $this->characterSpellModel = new CharacterSpellModel();
    }

    //=== FUNCIONES ===//

    // Add a spell to a character
    public function addSpellToCharacter($characterId, $spellId, $castingLevel = 1)
    {
        // Validate inputs
        if ($castingLevel < 1 || $castingLevel > 20) {
            throw new Exception("Invalid casting level. Must be between 1 and 20.");
        }

        // Delegate to the model
        $this->characterSpellModel->addSpellToCharacter($characterId, $spellId, $castingLevel);
    }


    // Get all spells for a character
    public function getSpellsByCharacter($characterId)
    {
        if (!is_numeric($characterId) || $characterId <= 0) {
            throw new Exception("Invalid character ID.");
        }
        return $this->characterSpellModel->getSpellsByCharacter($characterId);
    }


    // Update casting level for a character's spell
    public function updateCastingLevel($character_id, $spell_id, $casting_level)
    {
        // Validar entrada
        if (!is_numeric($casting_level) || $casting_level <= 0) {
            throw new Exception("Nivel de lanzamiento inválido.");
        }

        // Actualizar el nivel de lanzamiento
        $this->characterSpellModel->updateCastingLevel($character_id, $spell_id, $casting_level);
    }

    // Remove a spell from a character
    public function removeSpellFromCharacter($characterId, $spellId)
    {
        try {
            return $this->characterSpellModel->removeSpellFromCharacter($characterId, $spellId);
        } catch (Exception $e) {
            error_log("Error removing spell: " . $e->getMessage());
            throw new Exception("Unable to unlink spell.");
        }
    }


    // Remove all spells from a character
    public function removeAllSpellsFromCharacter($character_id)
    {
        // Validar entrada
        if (!is_numeric($character_id) || $character_id <= 0) {
            throw new Exception("ID de personaje inválido.");
        }

        // Eliminar todos los hechizos del personaje
        $this->characterSpellModel->removeAllSpellsFromCharacter($character_id);
    }
}
