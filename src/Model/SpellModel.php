<?php
require_once realpath(__DIR__ . '/../../config/Database.php');

class SpellModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    //=== METHODS ===//

    // SQL_CREATE
    public function addSpell($name, $description, $level, $type)
    {
        try {
            $query = "INSERT INTO spells (name, description, level, type) VALUES (:name, :description, :level, :type)";
            $stmt = $this->db->prepare($query);

            // Param binding
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':type', $type);

            // Execute query
            $stmt->execute();

            // Return the ID of the newly inserted spell
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to add spell.");
        }
    }

    // SQL_READ
    public function getSpell($id)
    {
        try {
            // SQL
            $query = "SELECT * FROM spells WHERE id = :id";

            // Preparing statement
            $stmt = $this->db->prepare($query);

            // Param binding
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute query, returns data
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch Spell.");
        }
    }

    // SQL_UPDATE
    public function updateSpell($id, $fields)
    {
        try {
            // Dynamically build SET clause
            $setClause = [];
            foreach ($fields as $key => $value) {
                $setClause[] = "$key = :$key";
            }

            // SQL Query
            $query = "UPDATE spells SET " . implode(", ", $setClause) . " WHERE id = :id";

            // Prepare the statement
            $stmt = $this->db->prepare($query);

            // Bind parameters
            foreach ($fields as $key => $value) {
                $stmt->bindParam(":$key", $fields[$key]);
            }

            // Bind the ID
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to update spell.");
        }
    }

    // SQL_DELETE
    public function deleteSpell($id)
    {
        try {
            // SQL
            $query = "DELETE FROM spells WHERE id = :id";

            // Preparing statement
            $stmt = $this->db->prepare($query);

            // Param binding
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute query
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to delete Spell.");
        }
    }

    // GET ALL SPELL NAMES
    public function getAllSpells($limit = 10, $offset = 0)
    {
        try {
            $query = "SELECT id, name, description, level, type FROM spells LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch spells.");
        }
    }




    public function countAllSpells()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM spells";
            $stmt = $this->db->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to count spells.");
        }
    }
    
}
