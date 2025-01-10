<?php
require_once realpath(__DIR__ . '/../Service/UserService.php');

class UserController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function registerUser($data)
    {
        try {
            $this->userService->addUser($data['username'], $data['password'], $data['email']);
            return ["success" => true, "message" => "Usuario registrado con éxito."];
        } catch (Exception $e) {
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function loginUser($data)
    {
        try {
            $user = $this->userService->loginUser($data['username'], $data['password']);
            return ["success" => true, "message" => "Inicio de sesión exitoso.", "data" => $user];
        } catch (Exception $e) {
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
}
