<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../');
require_once realpath(__DIR__ . '/../Service/CharacterSpellService.php');

class CharacterSpellController
{
    private $characterSpellService;

    public function __construct()
    {
        $this->characterSpellService = new CharacterSpellService();
    }

    // Add a spell to a character
    public function addSpellToCharacter($data)
    {
        try {
            // Validate inputs
            if (empty($data['character_id']) || empty($data['spell_id']) || empty($data['casting_level'])) {
                throw new Exception("All fields are required.");
            }

            // Pass data to the service
            $this->characterSpellService->addSpellToCharacter(
                (int)$data['character_id'],
                (int)$data['spell_id'],
                (int)$data['casting_level']
            );

            return ["success" => true, "message" => "Spell added to character successfully."];
        } catch (Exception $e) {
            error_log("Error in addSpellToCharacter: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }


    // Get all spells for a character
    public function getSpellsByCharacter($data)
    {
        try {
            $characterId = htmlspecialchars(strip_tags($data['character_id']));
            $spells = $this->characterSpellService->getSpellsByCharacter($characterId);
            return ["success" => true, "data" => $spells];
        } catch (Exception $e) {
            error_log("Error fetching spells for character: " . $e->getMessage());
            throw new Exception("Unable to retrieve spells for character.");
        }
    }


    // Update casting level for a character's spell
    public function updateCastingLevel($data)
    {
        try {
            // Sanitizar entradas
            $character_id = htmlspecialchars(strip_tags($data['character_id']));
            $spell_id = htmlspecialchars(strip_tags($data['spell_id']));
            $casting_level = htmlspecialchars(strip_tags($data['casting_level']));

            // Llamar al servicio para actualizar el nivel de lanzamiento
            $this->characterSpellService->updateCastingLevel($character_id, $spell_id, $casting_level);

            return ["success" => true, "message" => "Nivel de lanzamiento actualizado exitosamente."];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Remove a spell from a character
    public function removeSpellFromCharacter($data)
    {
        try {
            $characterId = (int)$data['character_id'];
            $spellId = (int)$data['spell_id'];

            if ($characterId <= 0 || $spellId <= 0) {
                throw new Exception("Invalid character or spell ID.");
            }

            $this->characterSpellService->removeSpellFromCharacter($characterId, $spellId);
            return ["success" => true, "message" => "Spell unlinked successfully."];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }


    // Remove all spells from a character
    public function removeAllSpellsFromCharacter($characterId)
    {
        try {
            return $this->characterSpellService->removeAllSpellsFromCharacter($characterId);
        } catch (Exception $e) {
            throw new Exception("Error removing spells: " . $e->getMessage());
        }
    }
}
