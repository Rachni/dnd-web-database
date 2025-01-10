<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../'));
require_once realpath(__DIR__ . '/../Service/UserCampaignService.php');
class UserCampaignController
{
    private $userCampaignService;

    public function __construct()
    {
        $this->userCampaignService = new UserCampaignService();
    }

    //=== FUNCIONES ===//

    // Assign a user to a campaign
    public function assignUserToCampaign($data)
    {
        try {
            $admin_id = htmlspecialchars(strip_tags($data['admin_id']));
            $user_id = htmlspecialchars(strip_tags($data['user_id']));
            $campaign_id = htmlspecialchars(strip_tags($data['campaign_id']));
            $role = htmlspecialchars(strip_tags($data['role']));

            $this->userCampaignService->assignUserToCampaign($admin_id, $user_id, $campaign_id, $role);

            return ["success" => true, "message" => "User successfully assigned to campaign."];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // List users in a campaign
    public function listUsersInCampaign($data)
    {
        try {
            $campaign_id = htmlspecialchars(strip_tags($data['campaign_id']));
            $users = $this->userCampaignService->getUsersInCampaign($campaign_id);

            return ["success" => true, "data" => $users];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Remove a user from a campaign
    public function removeUserFromCampaign($data)
    {
        try {
            $admin_id = htmlspecialchars(strip_tags($data['admin_id']));
            $user_id = htmlspecialchars(strip_tags($data['user_id']));
            $campaign_id = htmlspecialchars(strip_tags($data['campaign_id']));

            $this->userCampaignService->removeUserFromCampaign($admin_id, $user_id, $campaign_id);

            return ["success" => true, "message" => "User successfully removed from campaign."];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    public function assignRoleToUserInCampaign()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $campaign_id = $_POST['campaign_id'];
            $role = $_POST['role'];

            try {
                // Llamar al servicio para asignar el rol
                $this->userCampaignService->assignRole($user_id, $campaign_id, $role);
                echo "Role assigned successfully.";
            } catch (Exception $e) {
                echo "Error assigning role: " . $e->getMessage();
            }
        }
    }
    public function getCampaignsByUser($data)
    {
        try {
            // Lógica para obtener las campañas
            $campaigns = $this->userCampaignService->getCampaignsByUser($data['user_id']);
    
            if ($campaigns) {
                return ['success' => true, 'data' => $campaigns];
            } else {
                return ['success' => false, 'data' => []];
            }
        } catch (Exception $e) {
            return ['success' => false, 'data' => [], 'error' => $e->getMessage()];
        }
    }

}
