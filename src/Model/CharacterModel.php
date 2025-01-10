<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../');
require_once realpath(__DIR__ . '/../../config/Database.php');
class CharacterModel
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    //=== FUNCIONES ===//

    // SQL_CREATE
    public function addCharacter($name, $race, $class, $level, $experience, $health, $imagePath, $userId, $campaignId)
    {
        try {
            $query = "INSERT INTO characters (name, race, class, level, experience, health, image_path, user_id, campaign_id) 
                      VALUES (:name, :race, :class, :level, :experience, :health, :image_path, :user_id, :campaign_id)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':race', $race, PDO::PARAM_STR);
            $stmt->bindParam(':class', $class, PDO::PARAM_STR);
            $stmt->bindParam(':level', $level, PDO::PARAM_INT);
            $stmt->bindParam(':experience', $experience, PDO::PARAM_INT);
            $stmt->bindParam(':health', $health, PDO::PARAM_INT);
            $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':campaign_id', $campaignId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Database error: " . $errorInfo[2]);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to add character.");
        }
    }

    // SQL_READ

    public function getCharacter($id)
    {
        try {
            // SQL

            $query = "SELECT * FROM characters WHERE id = :id";

            // Preparing statement

            $stmt = $this->db->prepare($query);

            // Param binding

            $stmt->bindParam(':id', $id);

            // Execute query, returns data
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch Character.");
        }
    }


    // SQL_UPDATE

    public function updateCharacter($id, $attributes)
    {
        $fields = [];
        foreach ($attributes as $key => $value) {
            if (!in_array($key, ['id', 'action', 'custom_race', 'custom_class']) && $value !== null) {
                $fields[] = "$key = :$key";
            }
        }

        if (empty($fields)) {
            throw new Exception("No fields to update.");
        }

        $query = "UPDATE characters SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);

        foreach ($attributes as $key => $value) {
            if (!in_array($key, ['id', 'action', 'custom_race', 'custom_class']) && $value !== null) {
                $stmt->bindValue(":$key", $value);
            }
        }
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute update query.");
        }
        return true;
    }


    // SQL_DELETE

    public function deleteCharacter($characterId)
    {
        try {
            $query = "DELETE FROM characters WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $characterId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Failed to delete character. Database error: " . $errorInfo[2]);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to delete character.");
        }
    }


    //GET ALL CHARACTER NAMES
    function getAllCharacters()
    {
        try {
            // SQL
            $query = "SELECT name FROM characters";

            $stmt = $this->db->query($query);

            // returns only the name column
            return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'name');
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to get all characters.");
        }
    }

    // Get all characters for a campaign
    public function getCharactersByCampaign($campaignId)
    {
        try {
            // SQL
            $query = "SELECT * FROM characters WHERE campaign_id = :campaign_id";

            // Prepare the statement
            $stmt = $this->db->prepare($query);

            // Bind the campaign ID parameter
            $stmt->bindParam(':campaign_id', $campaignId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Fetch all characters for the campaign
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch characters for the campaign.");
        }
    }
    // Update Image
    public function updateCharacterImage($characterId, $imagePath)
    {
        try {
            $query = "UPDATE characters SET image_path = :image_path WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':image_path', $imagePath);
            $stmt->bindParam(':id', $characterId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to update character image.");
        }
    }
    public function fetchCharactersByUserWithCampaign($userId)
    {
        try {
            // Consulta para obtener los personajes junto con la campaña a la que pertenecen
            $query = "
                SELECT 
                    c.id AS id, 
                    c.name AS name, 
                    c.race, 
                    c.class, 
                    c.level, 
                    c.health, 
                    c.image_path,
                    cp.name AS campaign_name
                FROM characters c
                LEFT JOIN campaigns cp ON c.campaign_id = cp.id
                WHERE c.user_id = :user_id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch characters with campaign information.");
        }
    }

    public function fetchCharactersByUser($userId)
    {
        try {
            $query = "
                SELECT 
                    id AS character_id, 
                    name AS character_name, 
                    race, 
                    class, 
                    level, 
                    health, 
                    image_path, 
                    campaign_id 
                FROM characters 
                WHERE user_id = :user_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch characters for user.");
        }
    }



    public function fetchCampaignsByUser($userId)
    {
        try {
            $query = "SELECT DISTINCT c.id, c.name 
                  FROM campaigns c
                  INNER JOIN users_campaigns uc ON c.id = uc.campaign_id
                  WHERE uc.user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch campaigns for the user.");
        }
    }
    public function validateForeignKeys($user_id, $campaign_id)
    {
        try {
            // Check if user exists
            $userQuery = "SELECT COUNT(*) FROM users WHERE id = :user_id";
            $stmtUser = $this->db->prepare($userQuery);
            $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtUser->execute();
            $userExists = $stmtUser->fetchColumn();

            // Check if campaign exists
            $campaignQuery = "SELECT COUNT(*) FROM campaigns WHERE id = :campaign_id";
            $stmtCampaign = $this->db->prepare($campaignQuery);
            $stmtCampaign->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);
            $stmtCampaign->execute();
            $campaignExists = $stmtCampaign->fetchColumn();

            if (!$userExists) {
                throw new Exception("User ID $user_id does not exist.");
            }
            if (!$campaignExists) {
                throw new Exception("Campaign ID $campaign_id does not exist.");
            }
        } catch (PDOException $e) {
            error_log("Validation error: " . $e->getMessage());
            throw new Exception("Foreign key validation failed.");
        }
    }
    public function isCharacterOwnedByUser($characterId, $userId)
    {
        try {
            $query = "SELECT COUNT(*) FROM characters WHERE id = :id AND user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $characterId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to verify character ownership.");
        }
    }
}
