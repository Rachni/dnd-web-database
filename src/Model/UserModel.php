<?php
require_once realpath(__DIR__ . '/../../config/Database.php');

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function addUser($username, $password, $email)
    {
        try {
            $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
            $stmt = $this->db->prepare($query);
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashPassword);
            $stmt->bindParam(':email', $email);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            throw new Exception("Failed to add user.");
        }
    }

    public function verifyCredentials($username, $password)
    {
        try {
            $query = "SELECT id, username, email, password FROM users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            } else {
                throw new Exception("Credenciales inválidas.");
            }
        } catch (PDOException $e) {
            error_log("Error verifying credentials: " . $e->getMessage());
            throw new Exception("Error al verificar las credenciales.");
        }
    }
}
