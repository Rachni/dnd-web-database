<?php
require_once realpath(__DIR__ . '/../Model/UserModel.php');

class UserService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function addUser($username, $password, $email, $role = 'player')
    {
        if (empty($username) || empty($password) || empty($email)) {
            throw new Exception("All fields are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        return $this->userModel->addUser($username, $password, $email);
    }


    public function loginUser($username, $password)
    {
        $username = htmlspecialchars(strip_tags($username));
        return $this->userModel->verifyCredentials($username, $password);
    }
}
