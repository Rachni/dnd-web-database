<?php
require_once realpath(__DIR__ . '/../../config/Database.php');

class CampaignModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    //===  ADD CAMPAIGN  ===//

    public function addCampaign($name, $description, $startDate, $endDate, $status)
    {
        try {
            $query = "INSERT INTO campaigns (name, description, start_date, end_date, status)
                      VALUES (:name, :description, :start_date, :end_date, :status)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                throw new Exception("Failed to create campaign.");
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to create campaign.");
        }
    }

    //===  GET ALL CAMPAIGNS  ===//

    public function getAllCampaigns($limit = 10, $offset = 0)
    {
        try {
            $query = "SELECT id, name, description, start_date, end_date, status 
                      FROM campaigns LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error while fetching campaigns: " . $e->getMessage());
            throw new Exception("Failed to fetch campaigns.");
        }
    }

    //===  GET A SINGLE CAMPAIGN FILTERING BY ID  ===//

    public function getCampaignById($id)
    {
        try {
            $query = "SELECT * FROM campaigns WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la campaña: " . $e->getMessage());
        }
    }


    //===  UPDATE A CAMPAIGN FILTERING BY ID  ===//

    public function updateCampaign($id, $name, $description, $start_date, $end_date, $status)
    {
        try {
            $query = "UPDATE campaigns 
                      SET name = :name, description = :description, start_date = :start_date, 
                          end_date = :end_date, status = :status 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error while updating campaign: " . $e->getMessage());
            throw new Exception("Failed to update campaign.");
        }
    }

    //===  DELETE A SINGLE CAMPAIGN BY ID  ===//

    public function deleteCampaign($id)
    {
        try {
            $query = "DELETE FROM campaigns WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar la campaña: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la campaña.");
        }
    }

    //===  UPDATE THE STATUS OF A CAMPAIGN ID  ===//

    public function updateCampaignStatus($id, $status)
    {
        try {
            $query = "UPDATE campaigns SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error while updating campaign status: " . $e->getMessage());
            throw new Exception("Failed to update campaign status.");
        }
    }

    //===  ADD A USER ID TO A CAMPAIGN ID WITH A ROLE ===//

    public function addUserToCampaign($campaign_id, $user_id, $role)
    {
        try {
            $query = "INSERT INTO users_campaigns (campaign_id, user_id, role) 
                      VALUES (:campaign_id, :user_id, :role)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':role', $role);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error while adding user to campaign: " . $e->getMessage());
            throw new Exception("Failed to add user to campaign.");
        }
    }

    //===  DELETE USER ID FROM CAMPAIGN ===//
    
    public function removeUserFromCampaign($campaign_id, $user_id)
    {
        try {
            $query = "DELETE FROM users_campaigns WHERE campaign_id = :campaign_id AND user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error while removing user from campaign: " . $e->getMessage());
            throw new Exception("Failed to remove user from campaign.");
        }
    }

    //===  GET ALL USERS BY CAMPAIGN ID  ===//

    public function getUsersByCampaign($campaign_id)
    {
        try {
            $query = "SELECT u.id, u.username, u.email, uc.role 
                      FROM users u 
                      INNER JOIN users_campaigns uc ON u.id = uc.user_id 
                      WHERE uc.campaign_id = :campaign_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':campaign_id', $campaign_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error while fetching users for campaign: " . $e->getMessage());
            throw new Exception("Failed to fetch users for campaign.");
        }
    }

    //=== UPDATE A ROLE FOR A USER IN A CAMPAIGN ===//

    public function assignUserRoleToCampaign($userId, $campaignId, $role)
    {
        try {
            $query = "INSERT INTO users_campaigns (user_id, campaign_id, role)
                      VALUES (:user_id, :campaign_id, :role)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':campaign_id', $campaignId, PDO::PARAM_INT);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Error assigning role: " . $errorInfo[2]);
            }
        } catch (PDOException $e) {
            error_log("Error assigning role to user: " . $e->getMessage());
            throw new Exception("Failed to assign role to user.");
        }
    }
}
