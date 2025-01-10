<?php
require_once realpath(__DIR__ . '/../../config/Database.php');


class CharacterSpellModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    //=== FUNCIONES ===//

    // SQL_CREATE: Add a spell to a character
    public function addSpellToCharacter($characterId, $spellId, $castingLevel = 1)
    {
        try {
            $query = "INSERT INTO character_spells (character_id, spell_id, casting_level)
                      VALUES (:character_id, :spell_id, :casting_level)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':character_id', $characterId, PDO::PARAM_INT);
            $stmt->bindParam(':spell_id', $spellId, PDO::PARAM_INT);
            $stmt->bindParam(':casting_level', $castingLevel, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error in addSpellToCharacter: " . $e->getMessage());
            throw new Exception("Failed to add spell to character.");
        }
    }


    // SQL_READ: Get all spells assigned to a character
    public function getSpellsByCharacter($characterId)
    {
        try {
            $query = "SELECT 
                        s.id AS spell_id, 
                        s.name AS spell_name, 
                        s.description, 
                        s.level AS spell_level, 
                        s.type, 
                        cs.casting_level 
                      FROM spells s
                      INNER JOIN character_spells cs ON s.id = cs.spell_id
                      WHERE cs.character_id = :character_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':character_id', $characterId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch spells for character.");
        }
    }


    // SQL_UPDATE: Update casting level for a character's spell
    public function updateCastingLevel($character_id, $spell_id, $casting_level)
    {
        try {
            // SQL
            $query = "UPDATE character_spells 
                      SET casting_level = :casting_level
                      WHERE character_id = :character_id AND spell_id = :spell_id";

            // Prepare statement
            $stmt = $this->db->prepare($query);

            // Param binding
            $stmt->bindParam(':character_id', $character_id, PDO::PARAM_INT);
            $stmt->bindParam(':spell_id', $spell_id, PDO::PARAM_INT);
            $stmt->bindParam(':casting_level', $casting_level, PDO::PARAM_INT);

            // Execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            throw new Exception("Error al actualizar el nivel de lanzamiento del hechizo.");
        }
    }

    // SQL_CHECK: Check if a spell is already assigned to a character
    public function isSpellAssignedToCharacter($character_id, $spell_id)
    {
        try {
            // SQL
            $query = "SELECT COUNT(*) 
                      FROM character_spells 
                      WHERE character_id = :character_id AND spell_id = :spell_id";

            // Prepare statement
            $stmt = $this->db->prepare($query);

            // Param binding
            $stmt->bindParam(':character_id', $character_id, PDO::PARAM_INT);
            $stmt->bindParam(':spell_id', $spell_id, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();

            // Return result
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            throw new Exception("Error al verificar si el hechizo ya está asignado.");
        }
    }

    // SQL_DELETE: Remove a spell from a character
    public function removeSpellFromCharacter($characterId, $spellId)
    {
        try {
            $query = "DELETE FROM character_spells WHERE character_id = :character_id AND spell_id = :spell_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':character_id', $characterId, PDO::PARAM_INT);
            $stmt->bindParam(':spell_id', $spellId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to unlink spell from character.");
        }
    }

    // SQL_DELETE_ALL: Remove all spells from a character
    public function removeAllSpellsFromCharacter($character_id)
    {
        try {
            // SQL
            $query = "DELETE FROM character_spells WHERE character_id = :character_id";

            // Prepare statement
            $stmt = $this->db->prepare($query);

            // Param binding
            $stmt->bindParam(':character_id', $character_id, PDO::PARAM_INT);

            // Execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            throw new Exception("Error al eliminar todos los hechizos del personaje.");
        }
    }
}
