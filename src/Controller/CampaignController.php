<?php
require_once realpath(__DIR__ . '/../Service/CampaignService.php');
require_once realpath(__DIR__ . '/../Service/UserCampaignService.php');

class CampaignController
{
    private $campaignService;
    private $userCampaignService;

    public function __construct()
    {
        $this->campaignService = new CampaignService();
        $this->userCampaignService = new UserCampaignService();
    }

    // Create a campaign
    public function createCampaign($data)
    {
        try {
            if (empty($data['name'])) {
                throw new Exception("Campaign name is required.");
            }

            if (empty($data['start_date']) || empty($data['status'])) {
                throw new Exception("Start date and status are required.");
            }

            // Create the campaign
            $response = $this->campaignService->createCampaign($_SESSION['user_id'], $data);

            // After campaign is created, assign the user to the campaign with "admin" role
            $this->userCampaignService->assignRole($_SESSION['user_id'], $response['data']['campaign_id'], 'admin');

            return $response;
        } catch (Exception $e) {
            error_log("Error creating campaign: " . $e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    // Update a campaign
    public function updateCampaign($data)
    {
        if (empty($data['id']) || empty($data['name']) || empty($data['description']) || empty($data['start_date']) || empty($data['status'])) {
            throw new Exception("All fields are required except end date.");
        }

        try {
            $this->campaignService->updateCampaign(
                $data['id'],
                htmlspecialchars(strip_tags($data['name'])),
                htmlspecialchars(strip_tags($data['description'])),
                $data['start_date'],
                $data['end_date'] ?? null,
                $data['status']
            );

            $_SESSION['flash_message'] = "Campaign updated successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Failed to update campaign: " . $e->getMessage();
            throw $e;
        }
    }

    // Change campaign status
    public function changeStatus($id, $status)
    {
        if (empty($id) || empty($status)) {
            throw new Exception("ID and status are required.");
        }

        try {
            $this->campaignService->changeCampaignStatus($id, $status);
            $_SESSION['flash_message'] = "Campaign status updated successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Failed to change campaign status: " . $e->getMessage();
            throw $e;
        }
    }

    // Add a user to a campaign
    public function addUser($campaign_id, $user_id, $role)
    {
        if (empty($campaign_id) || empty($user_id) || empty($role)) {
            throw new Exception("Campaign ID, User ID, and Role are required.");
        }

        try {
            $this->campaignService->addUserToCampaign($campaign_id, $user_id, $role);
            $_SESSION['flash_message'] = "User added to campaign successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Failed to add user to campaign: " . $e->getMessage();
            throw $e;
        }
    }
    public function getCampaignById($id)
    {
        if (empty($id)) {
            throw new Exception("El ID de la campaña es obligatorio.");
        }

        try {
            return $this->campaignService->getCampaignById($id); // Implementa esto en CampaignService
        } catch (Exception $e) {
            throw new Exception("Error al obtener la campaña: " . $e->getMessage());
        }
    }


    // Remove a user from a campaign
    public function removeUser($campaign_id, $user_id)
    {
        if (empty($campaign_id) || empty($user_id)) {
            throw new Exception("Campaign ID and User ID are required.");
        }

        try {
            $this->campaignService->removeUserFromCampaign($campaign_id, $user_id);
            $_SESSION['flash_message'] = "User removed from campaign successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Failed to remove user from campaign: " . $e->getMessage();
            throw $e;
        }
    }
    public function deleteCampaign($id)
    {
        if (empty($id)) {
            throw new Exception("El ID de la campaña es obligatorio.");
        }

        try {
            $this->campaignService->deleteCampaign($id);
            $_SESSION['flash_message'] = "Campaña eliminada exitosamente.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Error al eliminar la campaña: " . $e->getMessage();
            throw $e;
        }
    }
}
