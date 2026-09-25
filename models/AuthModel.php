<?php
//import nedded file.
require_once __DIR__ . '/../core/Model.php';
/**
 * class for controlling the database opration for users.
 * has 4 function to creat a user,fetch one data by id and one by email
 * last one checks if the email exist. 
 */
class AuthModel extends Model
{
    //fetching a user by id 
    //Params:$stmt to store the prepared query and excute it and store the result
    //return a user data.
    public function getUserById(int $id):array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * storing new user data 
     * Params:$stmt to store the prepared query and excute it.
     * return the id of the new user.
    */ 
    public function createUser(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Users (first_name, last_name, email, password_hash, location, description, occupation)
             VALUES (:firstName, :lastName, :email, :passwordHash, :location, :description, :occupation)"
        );
        $stmt->execute([
            ':firstName' => $data['first_name'],
            ':lastName' => $data['last_name'],
            ':email' => $data['email'],
            ':passwordHash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':location' => $data['location'],
            ':description' => $data['description'],
            ':occupation' => $data['occupation']
        ]);
        return (int) $this->db->lastInsertId();
    }
    /**
     * retrive user data by email
     * Params:$stmt to store the prepared query and excute it ane store the result.
     * return the a user data.
    */ 
    public function getUserByEmail($email):array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    /**
     * check if a user email exist in the database
     * Params:$stmt to store the prepared query and excute it and store the result.
     * return the bool.
    */ 
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM Users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        // fetch() returns false when no row matches.
        return $stmt->fetch() !== false;
    }

}