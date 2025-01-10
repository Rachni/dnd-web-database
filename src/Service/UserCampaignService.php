<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../'));
require_once realpath(__DIR__ . '/../Model/UserCampaignModel.php');

class UserCampaignService
{
    private $userCampaignModel;

    public function __construct()
    {
        $this->userCampaignModel = new UserCampaignModel();
    }

    //=== FUNCIONES ===//

    // Validate IDs and roles
    private function validateId($id, $type)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid $type ID.");
        }
    }

    private function validateRole($role)
    {
        $validRoles = ['admin', 'player'];
        if (!in_array($role, $validRoles)) {
            throw new Exception("Invalid role. Allowed values: admin, player.");
        }
    }

    // Check if user has admin permissions
    private function checkAdminPermissions($admin_id, $campaign_id)
    {
        if (!$this->userCampaignModel->isUserAdminInCampaign($admin_id, $campaign_id)) {
            throw new Exception("Insufficient permissions. Only admins can perform this action.");
        }
    }

    // Assign user to a campaign
    public function assignUserToCampaign($admin_id, $user_id, $campaign_id, $role)
    {
        $this->validateId($admin_id, 'admin');
        $this->validateId($user_id, 'user');
        $this->validateId($campaign_id, 'campaign');
        $this->validateRole($role);

        $this->checkAdminPermissions($admin_id, $campaign_id);

        return $this->userCampaignModel->addUserToCampaign($user_id, $campaign_id, $role);
    }

    // Assign the campaign creator as admin automatically
    public function assignAdminToCampaign($user_id, $campaign_id)
    {
        $this->validateId($user_id, 'user');
        $this->validateId($campaign_id, 'campaign');

        return $this->userCampaignModel->assignRoleToUser($user_id, $campaign_id, 'admin');
    }

    // Get users in a campaign
    public function getUsersInCampaign($campaign_id)
    {
        $this->validateId($campaign_id, 'campaign');
        return $this->userCampaignModel->getUsersInCampaign($campaign_id);
    }

    // Remove user from a campaign
    public function removeUserFromCampaign($admin_id, $user_id, $campaign_id)
    {
        $this->validateId($admin_id, 'admin');
        $this->validateId($user_id, 'user');
        $this->validateId($campaign_id, 'campaign');

        $this->checkAdminPermissions($admin_id, $campaign_id);

        return $this->userCampaignModel->removeUserFromCampaign($user_id, $campaign_id);
    }

    // Assign or update a role for a user in a campaign
    public function assignRole($userId, $campaignId, $role)
    {
        try {
            // Llamar al modelo para asignar el rol
            $this->userCampaignModel->assignRoleToUser($userId, $campaignId, $role);
        } catch (Exception $e) {
            throw new Exception("Failed to assign role: " . $e->getMessage());
        }
    }

    // Get the role of a user in a campaign
    public function getUserRole($user_id, $campaign_id)
    {
        $this->validateId($user_id, 'user');
        $this->validateId($campaign_id, 'campaign');

        return $this->userCampaignModel->getRoleInCampaign($user_id, $campaign_id);
    }

    // Allow a user to join a campaign as a player
    public function joinCampaign($user_id, $campaign_id)
    {
        $this->validateId($user_id, 'user');
        $this->validateId($campaign_id, 'campaign');

        return $this->userCampaignModel->assignRoleToUser($user_id, $campaign_id, 'player');
    }

    // Fetch all campaigns associated with a user
    public function getCampaignsByUser($userId)
    {
        if (empty($userId)) {
            throw new Exception("User ID is required.");
        }
    
        try {
            return $this->userCampaignModel->fetchCampaignsByUser($userId);
        } catch (Exception $e) {
            error_log("Error fetching campaigns for user: " . $e->getMessage());
            throw new Exception("Unable to retrieve campaigns for the user.");
        }
    }
    
}
