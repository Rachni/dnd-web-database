<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../');
require_once realpath(__DIR__ . '/../../config/Database.php');

class UserCampaignModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    //=== FUNCIONES ===//

    // SQL_CREATE: Assign a user to a campaign
    public function addUserToCampaign($user_id, $campaign_id, $role)
    {
        try {
            $query = "INSERT INTO users_campaigns (user_id, campaign_id, role) 
                      VALUES (:user_id, :campaign_id, :role)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Failed to assign user to the campaign.");
        }
    }

    // SQL_READ: Get all users in a campaign
    public function getUsersInCampaign($campaign_id)
    {
        try {
            $query = "SELECT uc.user_id, uc.role, u.username 
                      FROM users_campaigns uc
                      INNER JOIN users u ON uc.user_id = u.id
                      WHERE uc.campaign_id = :campaign_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Failed to retrieve users in the campaign.");
        }
    }

    // SQL_CHECK: Check if a user is an admin in a campaign
    public function isUserAdminInCampaign($user_id, $campaign_id)
    {
        try {
            $query = "SELECT role FROM users_campaigns 
                      WHERE user_id = :user_id AND campaign_id = :campaign_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result && $result['role'] === 'admin';
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Failed to verify user permissions.");
        }
    }

    // SQL_DELETE: Remove a user from a campaign
    public function removeUserFromCampaign($user_id, $campaign_id)
    {
        try {
            $query = "DELETE FROM users_campaigns 
                      WHERE user_id = :user_id AND campaign_id = :campaign_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Failed to remove user from the campaign.");
        }
    }

    public function assignRoleToUser($userId, $campaignId, $role)
    {
        try {
            // SQL query to insert user into the campaign with the role
            $query = "INSERT INTO users_campaigns (user_id, campaign_id, role) 
                      VALUES (:user_id, :campaign_id, :role)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':campaign_id', $campaignId, PDO::PARAM_INT);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Failed to assign role.");
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to assign role to user: " . $e->getMessage());
        }
    }


    public function getRoleInCampaign($user_id, $campaign_id)
    {
        $query = "SELECT role FROM users_campaigns WHERE user_id = :user_id AND campaign_id = :campaign_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':campaign_id', $campaign_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['role'];
    }
    public function fetchCampaignsByUser($userId)
    {
        try {
            $query = "SELECT DISTINCT c.id, c.name, c.description, c.status
                        FROM campaigns c
                        JOIN users_campaigns uc ON c.id = uc.campaign_id
                        WHERE uc.user_id = :user_id;";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch campaigns for user.");
        }
    }
}
