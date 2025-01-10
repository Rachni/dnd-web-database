<?php
require_once realpath(__DIR__ . '/../model/CampaignModel.php');

class CampaignService
{
    private $campaignModel;
    private $userCampaignModel;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->userCampaignModel = new UserCampaignModel();
    }

    // Validate campaign data
    private function validateCampaignData($name, $description, $status)
    {
        if (empty($name) || empty($description)) {
            throw new Exception("Name and Description are required.");
        }

        $allowedStatuses = ['active', 'completed', 'paused'];
        if (!in_array($status, $allowedStatuses)) {
            throw new Exception("Invalid status. Allowed values: active, completed, paused.");
        }
    }

    // Add a campaign
    public function createCampaign($userId, $data)
    {
        try {
            // Validate user and input
            if (empty($userId)) {
                throw new Exception("User ID is required.");
            }

            // Create campaign
            $campaignId = $this->campaignModel->addCampaign(
                $data['name'],
                $data['description'] ?? null,
                $data['start_date'],
                $data['end_date'] ?? null,
                $data['status']
            );

            // Return campaign details
            return [
                "success" => true,
                "data" => [
                    "campaign_id" => $campaignId,
                    "name" => $data['name'],
                    "status" => $data['status']
                ]
            ];
        } catch (Exception $e) {
            error_log("Error creating campaign: " . $e->getMessage());
            throw new Exception("Error creating campaign: " . $e->getMessage());
        }
    }

    // Update a campaign
    public function updateCampaign($id, $name, $description, $start_date, $end_date, $status)
    {
        if (empty($id)) {
            throw new Exception("Campaign ID is required.");
        }
        $this->validateCampaignData($name, $description, $status);
        return $this->campaignModel->updateCampaign($id, $name, $description, $start_date, $end_date, $status);
    }

    // Change campaign status
    public function changeCampaignStatus($id, $status)
    {
        if (empty($id)) {
            throw new Exception("Campaign ID is required.");
        }
        $this->validateCampaignData("dummy", "dummy", $status); // Status-only validation
        return $this->campaignModel->updateCampaignStatus($id, $status);
    }

    // Add a user to a campaign
    public function addUserToCampaign($campaign_id, $user_id, $role)
    {
        if (empty($campaign_id) || empty($user_id) || empty($role)) {
            throw new Exception("Campaign ID, User ID, and Role are required.");
        }
        return $this->campaignModel->addUserToCampaign($campaign_id, $user_id, $role);
    }

    // Remove a user from a campaign
    public function removeUserFromCampaign($campaign_id, $user_id)
    {
        if (empty($campaign_id) || empty($user_id)) {
            throw new Exception("Campaign ID and User ID are required.");
        }
        return $this->campaignModel->removeUserFromCampaign($campaign_id, $user_id);
    }
    public function assignRole($user_id, $campaign_id, $role)
    {
        if (empty($user_id) || empty($campaign_id) || empty($role)) {
            throw new Exception("User ID, Campaign ID, and Role are required.");
        }

        try {
            return $this->userCampaignModel->assignRoleToUser($user_id, $campaign_id, $role);
        } catch (Exception $e) {
            throw new Exception("Failed to assign role: " . $e->getMessage());
        }
    }
    public function getCampaignById($id)
    {
        if (empty($id)) {
            throw new Exception("El ID de la campaña es obligatorio.");
        }

        try {
            return $this->campaignModel->getCampaignById($id); // Implementa esto en CampaignModel
        } catch (Exception $e) {
            throw new Exception("Error al obtener la campaña: " . $e->getMessage());
        }
    }
    public function deleteCampaign($id)
    {
        if (empty($id)) {
            throw new Exception("El ID de la campaña es obligatorio.");
        }

        return $this->campaignModel->deleteCampaign($id);
    }
}
