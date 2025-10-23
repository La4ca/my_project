<?php
// dao/crudDAO.php

class crudDAO {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Creates a new user record.
     *
     * @param string $lastname
     * @param string $firstname
     * @param string $username
     * @param string $password_hash
     * @param string $email
     * @return bool|string true on success, or error message on failure
     */
    public function create($lastname, $firstname, $username, $password_hash, $email) {
        $sql = "INSERT INTO tbsignup (lastname, firstname, username, password_hash, email) 
                VALUES (:lastname, :firstname, :username, :password_hash, :email)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':lastname'      => $lastname,
                ':firstname'     => $firstname,
                ':username'      => $username,
                ':password_hash' => $password_hash,
                ':email'         => $email
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Logs in a user by verifying username/email and password.
     *
     * @param string $identifier Username or Email entered by the user
     * @param string $password Plain password entered by the user
     * @return array|false Returns user data (without password) on success, or false on failure
     */
    public function login($identifier, $password) {
        $sql = "SELECT * FROM tbsignup 
                WHERE username = :identifier OR email = :identifier 
                LIMIT 1";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':identifier' => $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if user found and verify password
            if ($user && password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']); // remove password hash before returning
                return $user;
            }
            return false; // invalid credentials
        } catch (PDOException $e) {
            // You can log the error if needed
            return false;
        }
    }
}
